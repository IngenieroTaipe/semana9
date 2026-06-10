<?php

require_once 'config.php';

// Acción de limpiar registros de la sesión (Mejora: yendo más allá)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'clear') {
    $_SESSION['registros'] = [];
    header('Location: procesar.php');
    exit;
}

// Inicializar variables
$errores = [];
$success = false;

// 1. Validar Método HTTP (Protección de Controlador)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Si no es una petición POST y no es la visualización directa de registros guardados, dar error 405
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_SESSION['registros'])) {
        // Permitir GET solo para ver registros si ya existen o la sesión está iniciada
    } else {
        http_response_code(405);
        $errores[] = "Método HTTP no permitido. Solo se aceptan peticiones POST desde el formulario.";
    }
} else {
    // 2. Captura y Sanitización de Entradas
    $nombre_raw = trim($_POST['nombre'] ?? '');
    $correo_raw = trim($_POST['correo'] ?? '');

    // 3. Validación avanzada con Expresiones Regulares (Regex)
    // El nombre debe contener solo letras y espacios, longitud de 2 a 50
    if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{2,50}$/", $nombre_raw)) {
        $errores[] = "El nombre ingresado no es válido. Debe tener entre 2 y 50 letras y no incluir caracteres especiales.";
    } else {
        $nombre = htmlspecialchars($nombre_raw, ENT_QUOTES, 'UTF-8');
    }

    // Validación de correo electrónico
    if (!filter_var($correo_raw, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "La dirección de correo electrónico ingresada no es válida.";
    } else {
        $correo = htmlspecialchars($correo_raw, ENT_QUOTES, 'UTF-8');
    }

    // 4. Guardar en Sesión si no hay errores
    if (empty($errores)) {
        if (!isset($_SESSION['registros'])) {
            $_SESSION['registros'] = [];
        }
        
        // Evitar duplicados exactos (Mejora)
        $duplicado = false;
        foreach ($_SESSION['registros'] as $reg) {
            if ($reg['correo'] === $correo) {
                $duplicado = true;
                break;
            }
        }
        
        if ($duplicado) {
            $errores[] = "Este correo electrónico ya se encuentra registrado en esta sesión.";
        } else {
            $_SESSION['registros'][] = [
                'nombre' => $nombre,
                'correo' => $correo,
                'fecha' => date('Y-m-d H:i:s')
            ];
            $success = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Registros (PHP)</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="container">
        <span class="badge">Resultados Backend</span>
        <h2>Panel de Administración de Sesión</h2>
        <p class="subtitle">Visualice la lista de contactos cargados temporalmente en el servidor PHP.</p>

        <!-- Mensajes de Estado -->
        <?php if ($success): ?>
            <div style="background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 16px; padding: 1.5rem; margin-bottom: 2rem; color: #a7f3d0;">
                <strong style="color: var(--accent);">✓ ¡Registro Exitoso!</strong> El contacto se ha almacenado correctamente en $_SESSION.
            </div>
        <?php endif; ?>

        <?php if (!empty($errores)): ?>
            <div class="error-card">
                <div class="error-title">⚠ Error en el procesamiento</div>
                <ul style="padding-left: 1.25rem; margin-top: 0.5rem; font-size: 0.9rem;">
                    <?php foreach ($errores as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

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
                    <?php if (isset($_SESSION['registros']) && count($_SESSION['registros']) > 0): ?>
                        <?php $i = 1; foreach ($_SESSION['registros'] as $registro): ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><strong><?php echo $registro['nombre']; ?></strong></td>
                                <td><?php echo $registro['correo']; ?></td>
                                <td><span style="font-size: 0.8rem; color: #64748b;"><?php echo $registro['fecha'] ?? date('Y-m-d H:i:s'); ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; color: var(--text-secondary); padding: 2rem;">
                                No hay contactos registrados en esta sesión.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Botones de Acción (Mejora) -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem;">
            <a href="index.html" class="back-link">Volver al Registro</a>
            
            <?php if (isset($_SESSION['registros']) && count($_SESSION['registros']) > 0): ?>
                <a href="procesar.php?action=clear" class="btn" style="background: rgba(239, 68, 68, 0.2); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.3); font-size: 0.85rem; padding: 0.5rem 1rem; box-shadow: none;" onclick="return confirm('¿Está seguro de que desea limpiar todos los registros de la sesión?')">
                    Limpiar Sesión
                </a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>