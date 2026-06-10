<?php
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_use_only_cookies', 1);

    session_start();
}

define('APP_NAME', 'Sistema de Registro de Contactos - PHP');
define('VERSION', '1.1.0');