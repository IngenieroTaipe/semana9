# Sistema de Registro de Contacto - Semana 9

**Asignatura:** Desarrollo de Aplicaciones Web (IS093A)  
**Unidad II:** Desarrollo Web Fullstack  
**Tema:** Tecnología Web Backend, Arquitectura Cliente-Servidor, Servidores Web, PHP & JSP, Ciclo de Vida y Despliegue  
**Fecha:** 10.06.2026  

## Integrantes del Equipo (5 personas)

A continuación, se detalla la distribución de los roles técnicos para las **5 personas** del equipo, de manera que el trabajo sea equitativo y cubra todos los requerimientos de la consigna:

### 1. Arquitecto de Despliegue
**Responsabilidades:**
* Instalación y configuración de **XAMPP** (para PHP) y **Apache Tomcat** (para JSP).
* Creación de la estructura base del repositorio y manejo del control de versiones (Git/GitHub).
* Configuración de las rutas en el servidor (`htdocs/` para PHP, `webapps/` para JSP) y puertos.
* **Entregable de su parte:** Tomar las **capturas de los logs** del servidor cuando arranca, y generar los errores 4xx/5xx (por ejemplo, buscar una página que no existe o provocar un error 500) y guardar las capturas en la carpeta `docs/`.

### 2. Desarrollador PHP
**Responsabilidades:**
* Creación de `php-app/index.php` (Formulario) y `php-app/registro.php` (Lógica Backend).
* Recepción de datos mediante `$_POST` y validación de que el formulario se haya enviado correctamente.
* Iniciar y manejar almacenamiento temporal usando `session_start()` y la superglobal `$_SESSION`.
* Implementar seguridad básica previniendo ataques XSS (Cross-Site Scripting) al renderizar los datos usando la función `htmlspecialchars()`.
* **Entregable de su parte:** Código PHP funcional que guarda múltiples contactos en sesión y los renderiza en una tabla.

### 3. Desarrollador JSP (Java)
**Responsabilidades:**
* Creación de `jsp-app/index.jsp` (Formulario) y `jsp-app/registro.jsp` (Lógica Backend).
* Recepción de parámetros usando `request.getParameter()`.
* Uso del objeto implícito `session` para almacenar y recuperar la lista de contactos.
* Renderizar la lista en pantalla previniendo XSS usando la etiqueta `<c:out>` y recorriendo los datos con `<c:forEach>` de la librería JSTL (o usando Scriplets `<% %>` de forma segura).
* **Entregable de su parte:** Código JSP funcional y configuraciones necesarias como `WEB-INF/web.xml` de ser requerido.

### 4. Especialista QA y Seguridad (Tester)
**Responsabilidades:**
* Realizar pruebas exhaustivas a ambas aplicaciones (PHP y JSP) intentando inyectar código malicioso (`<script>alert(1)</script>`) en los formularios para verificar que XSS se prevenga correctamente.
* Analizar el ciclo HTTP completo usando la pestaña **Network (Red)** de las herramientas de desarrollo del navegador.
* Tomar capturas de pantalla de las Peticiones HTTP (Request Headers) y las Respuestas HTTP (Response Headers).
* **Entregable de su parte:** Las capturas de red, capturas del sistema funcionando correctamente, y verificar que los errores 4xx/5xx son manejados apropiadamente.

### 5. Documentador y Diseñador de Arquitectura
**Responsabilidades:**
* Redacción final de este archivo `README.md`.
* Elaboración del **Diagrama de Flujo HTTP** (Cliente → DNS → TCP/IP → HTTP Request → Server → Backend → Response → Render) usando alguna herramienta visual (Draw.io, Lucidchart, etc.) y exportarlo a `docs/diagrama_http.png`.
* Investigación y elaboración de la **Matriz Comparativa** entre PHP y JSP (ver plantilla abajo).
* Estructurar el `.zip` o `.rar` final con las carpetas organizadas o asegurar que el repositorio de GitHub esté completo y presentable.

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
