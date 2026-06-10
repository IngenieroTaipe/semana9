<?php
/**
 * Configuración Global de la Aplicación
 * Responsable: Benjamin (Arquitecto de Despliegue) & Sulluchuco (Seguridad)
 */

// Iniciar la sesión de forma segura si no está activa
if (session_status() === PHP_SESSION_NONE) {
    // Configuraciones de seguridad para la cookie de sesión (Protección XSS/Session Hijacking)
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_use_only_cookies', 1);
    
    // Si la conexión es HTTPS, activar secure cookie (comentado por entorno local HTTP)
    // ini_set('session.cookie_secure', 1);
    
    session_start();
}

// Constantes de la aplicación
define('APP_NAME', 'Sistema de Registro de Contactos - PHP');
define('VERSION', '1.1.0');