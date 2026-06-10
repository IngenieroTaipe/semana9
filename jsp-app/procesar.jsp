<%@ page contentType="text/html;charset=UTF-8" language="java" pageEncoding="UTF-8" %>
<%@ page import="java.util.*" %>
<%@ page import="java.time.LocalDateTime" %>
<%@ page import="java.time.format.DateTimeFormatter" %>
<%
    // Configurar encoding de request para procesar tildes y eñes correctamente
    request.setCharacterEncoding("UTF-8");

    // Helper para sanitizar strings y prevenir ataques XSS (Seguridad avanzada de Sulluchuco)
    class SecurityHelper {
        public static String escapeHtml(String input) {
            if (input == null) return "";
            return input.replace("&", "&amp;")
                        .replace("<", "&lt;")
                        .replace(">", "&gt;")
                        .replace("\"", "&quot;")
                        .replace("'", "&#x27;")
                        .replace("/", "&#x2F;");
        }
    }

    // Acción para limpiar registros de la sesión (Mejora: yendo más allá)
    if ("GET".equalsIgnoreCase(request.getMethod()) && "clear".equals(request.getParameter("action"))) {
        session.setAttribute("registros", new ArrayList<Map<String, String>>());
        response.sendRedirect("procesar.jsp");
        return;
    }

    List<String> errores = new ArrayList<>();
    boolean success = false;
    List<Map<String, String>> registros = (List<Map<String, String>>) session.getAttribute("registros");
    if (registros == null) {
        registros = new ArrayList<>();
    }

    // 1. Validar Método HTTP (Protección de Controlador)
    if (!"POST".equalsIgnoreCase(request.getMethod())) {
        // Permitir GET solo si es para visualizar el listado ya existente
        if (!"GET".equalsIgnoreCase(request.getMethod())) {
            response.setStatus(405);
            errores.add("Método HTTP no permitido. Solo se aceptan peticiones POST.");
        }
    } else {
        // 2. Captura de Parámetros
        String nombreRaw = request.getParameter("nombre");
        String correoRaw = request.getParameter("correo");
        
        String nombre = (nombreRaw != null) ? nombreRaw.trim() : "";
        String correo = (correoRaw != null) ? correoRaw.trim() : "";

        // 3. Validación avanzada en Servidor (Regex y Lógica)
        if (nombre.isEmpty() || !nombre.matches("^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{2,50}$")) {
            errores.add("El nombre ingresado no es válido. Debe tener entre 2 y 50 letras y espacios, sin números ni caracteres especiales.");
        }

        if (correo.isEmpty() || !correo.matches("^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$")) {
            errores.add("La dirección de correo electrónico ingresada no es válida.");
        }

        // Sanitización anti-XSS
        String nombreSanitizado = SecurityHelper.escapeHtml(nombre);
        String correoSanitizado = SecurityHelper.escapeHtml(correo);

        // 4. Guardar en Sesión si no hay errores
        if (errores.isEmpty()) {
            // Evitar duplicados exactos
            boolean duplicado = false;
            for (Map<String, String> reg : registros) {
                if (correoSanitizado.equalsIgnoreCase(reg.get("correo"))) {
                    duplicado = true;
                    break;
                }
            }

            if (duplicado) {
                errores.add("Este correo electrónico ya se encuentra registrado en esta sesión.");
            } else {
                Map<String, String> nuevoRegistro = new HashMap<>();
                nuevoRegistro.put("nombre", nombreSanitizado);
                nuevoRegistro.put("correo", correoSanitizado);
                
                DateTimeFormatter dtf = DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm:ss");
                nuevoRegistro.put("fecha", dtf.format(LocalDateTime.now()));
                
                registros.add(nuevoRegistro);
                session.setAttribute("registros", registros);
                success = true;
            }
        }
    }
%>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Registros (JSP)</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="container">
        <span class="badge">Resultados Backend JSP</span>
        <h2>Panel de Administración de Sesión</h2>
        <p class="subtitle">Visualice la lista de contactos cargados temporalmente en el servidor Apache Tomcat.</p>

        <!-- Mensajes de Estado -->
        <% if (success) { %>
            <div style="background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 16px; padding: 1.5rem; margin-bottom: 2rem; color: #a7f3d0;">
                <strong style="color: var(--accent);">✓ ¡Registro Exitoso!</strong> El contacto se ha almacenado correctamente en HttpSession de Tomcat.
            </div>
        <% } %>

        <% if (!errores.isEmpty()) { %>
            <div class="error-card">
                <div class="error-title">⚠ Error en el procesamiento</div>
                <ul style="padding-left: 1.25rem; margin-top: 0.5rem; font-size: 0.9rem;">
                    <% for (String error : errores) { %>
                        <li><%= error %></li>
                    <% } %>
                </ul>
            </div>
        <% } %>

        <!-- Tabla de Registros -->
        <h3>Contactos Almacenados (Sesión Activa)</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Nombre Completo</th>
                        <th>Correo Electrónico</th>
                        <th>Fecha de Registro</th>
                    </tr>
                </thead>
                <tbody>
                    <% if (registros != null && !registros.isEmpty()) { 
                        int index = 1;
                        for (Map<String, String> registro : registros) { %>
                            <tr>
                                <td><%= index++ %></td>
                                <td><strong><%= registro.get("nombre") %></strong></td>
                                <td><%= registro.get("correo") %></td>
                                <td><span style="font-size: 0.8rem; color: #64748b;"><%= registro.get("fecha") %></span></td>
                            </tr>
                        <% } 
                    } else { %>
                        <tr>
                            <td colspan="4" style="text-align: center; color: var(--text-secondary); padding: 2rem;">
                                No hay contactos registrados en esta sesión de JSP.
                            </td>
                        </tr>
                    <% } %>
                </tbody>
            </table>
        </div>

        <!-- Botones de Acción -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem;">
            <a href="index.jsp" class="back-link">Volver al Registro</a>
            
            <% if (registros != null && !registros.isEmpty()) { %>
                <a href="procesar.jsp?action=clear" class="btn" style="background: rgba(239, 68, 68, 0.2); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.3); font-size: 0.85rem; padding: 0.5rem 1rem; box-shadow: none;" onclick="return confirm('¿Está seguro de que desea limpiar todos los registros de la sesión de Tomcat?')">
                    Limpiar Sesión
                </a>
            <% } %>
        </div>
    </div>
</body>
</html>