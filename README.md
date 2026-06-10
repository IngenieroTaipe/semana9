# Sistema de Registro de Contacto - Semana 9

**Asignatura:** Desarrollo de Aplicaciones Web (IS093A)  
**Unidad II:** Desarrollo Web Fullstack  
**Tema:** Tecnología Web Backend, Arquitectura Cliente-Servidor, Servidores Web, PHP & JSP, Ciclo de Vida y Despliegue  
**Fecha:** 10.06.2026  

## Integrantes del Equipo (6 personas)

A continuación, se detalla la distribución de los roles técnicos para las **6 personas** del equipo, de manera que el trabajo sea equitativo y cubra todos los requerimientos de la consigna:

### 1. Arquitecto de Despliegue e Infraestructura
**Responsabilidades:**
* Instalación y configuración de **XAMPP** (para PHP) y **Apache Tomcat** (para JSP).
* Creación de la estructura base del repositorio y manejo del control de versiones (Git/GitHub).
* Configuración de las rutas en el servidor (`htdocs/` para PHP, `webapps/` para JSP) y puertos.
* **Entregable de su parte:** Tomar las **capturas de los logs** del servidor cuando arranca, y generar los errores 4xx/5xx (ej: buscar una página que no existe o provocar un error 500) y guardar las capturas en la carpeta `docs/`.

### 2. Desarrollador Frontend (Diseño de Interfaces)
**Responsabilidades:**
* Diseñar la estructura HTML/CSS de los formularios para ambas aplicaciones (`index.php` e `index.jsp`).
* Asegurarse de que el diseño sea responsivo y la tabla de resultados sea amigable a la vista.
* **Entregable de su parte:** Los archivos de estilos y las vistas HTML (tanto en el proyecto PHP como en el JSP) listas para que los desarrolladores Backend conecten la lógica.

### 3. Desarrollador PHP Backend
**Responsabilidades:**
* Creación de la lógica en `php-app/registro.php`.
* Recepción de datos mediante `$_POST` y validación de campos.
* Iniciar y manejar almacenamiento temporal usando `session_start()` y la superglobal `$_SESSION`.
* Implementar seguridad previniendo ataques XSS al renderizar los datos usando `htmlspecialchars()`.
* **Entregable de su parte:** Código PHP funcional que guarda múltiples contactos en sesión y los lista.

### 4. Desarrollador JSP Backend
**Responsabilidades:**
* Creación de la lógica en `jsp-app/registro.jsp`.
* Recepción de parámetros usando `request.getParameter()`.
* Uso del objeto implícito `session` para almacenar y recuperar la lista de contactos.
* Renderizar la lista previniendo XSS usando la etiqueta `<c:out>` y recorriendo los datos con `<c:forEach>`.
* **Entregable de su parte:** Código JSP funcional y archivos de configuración como `web.xml`.

### 5. Especialista QA (Pruebas y Seguridad)
**Responsabilidades:**
* Realizar pruebas inyectando código malicioso (`<script>alert(1)</script>`) para verificar que el XSS se prevenga correctamente.
* Analizar el ciclo HTTP completo usando la pestaña **Network (Red)** del navegador.
* Tomar capturas de pantalla de las Peticiones HTTP (Request) y Respuestas HTTP (Response).
* **Entregable de su parte:** Las capturas de red, capturas del sistema funcionando y validar que los errores son manejados.

### 6. Analista Documentador
**Responsabilidades:**
* Redacción final de este archivo `README.md`.
* Elaboración del **Diagrama de Flujo HTTP** (Cliente → DNS → TCP/IP → HTTP Request → Server → Backend → Response → Render) usando una herramienta visual y exportarlo a `docs/diagrama_http.png`.
* Investigación y elaboración de la **Matriz Comparativa** entre PHP y JSP.
* **Entregable de su parte:** Consolidar el repositorio o el archivo ZIP asegurándose de que toda la documentación y diagramas estén presentes.

---

## 2. Matriz Comparativa Técnica (PHP vs JSP)
*(A ser completada por el Documentador)*

| Criterio | PHP (Hypertext Preprocessor) | JSP (JavaServer Pages) |
| :--- | :--- | :--- |
| **Ciclo de vida** | Ejecución por script. Se inicializa y destruye en cada petición HTTP (stateless). | Traducido a un Servlet Java, compilado, cargado en memoria y ejecutado (init, service, destroy). |
| **Rendimiento** | Más ligero en arranque por petición, rápido para scripts cortos, no persiste en memoria (por defecto). | Más pesado en la primera petición (compilación a Servlet), muy rápido en las siguientes porque reside en memoria. |
| **Despliegue** | Muy sencillo (Ej: colocar en `htdocs` de Apache o usar Nginx con PHP-FPM). | Requiere empaquetado (WAR, directorios en `webapps`) y contenedor de Servlets (Apache Tomcat, GlassFish). |
| **Gestión de Estado** | Uso de funciones incorporadas como `session_start()` y `setcookie()`. Archivos o Redis en backend. | Uso de objeto implícito `session` y `request` de la API de Servlets (`HttpSession`). |
| **Madurez del Ecosistema** | Enorme madurez en Web compartida (WordPress, Laravel). Mucha documentación. | Enorme madurez empresarial (Spring Boot, Jakarta EE). Escala muy bien en arquitecturas complejas. |

---

## 3. Diagrama de Flujo HTTP
*(Añadir aquí la imagen cuando el documentador la cree)*

![Diagrama de flujo HTTP](docs/diagrama_http.png)

---

## 4. Capturas y Evidencias
*(Añadir aquí las imágenes solicitadas tomadas por el Tester y Arquitecto)*

* **Logs del servidor Tomcat/XAMPP:** ![Logs](docs/logs.png)
* **Petición HTTP (Network):** ![Red](docs/capturas-red.png)
* **Sistema de Registro en PHP:** ![PHP App](docs/php-app.png)
* **Sistema de Registro en JSP:** ![JSP App](docs/jsp-app.png)
* **Manejo de Errores (404/500):** ![Errores](docs/errores.png)
