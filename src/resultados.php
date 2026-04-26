<?php
/**
 * resultados.php — Página 2: Visualización de Resultados
 * 
 * Recibe el arreglo de objetos Estudiante desde la sesión (enviado por index.php)
 * y muestra el reporte completo de calificaciones.
 * 
 * @author [Tu Nombre] — Módulos 2, 3, 4 y 5
 */

session_start();
require_once __DIR__ . '/classes/Estudiante.php';

// ─── Recibir el arreglo de estudiantes desde la sesión ────────────────────
if (empty($_SESSION['estudiantes'])) {
    // Módulo 4 — exit/die: error crítico si no hay datos
    die('
        <!DOCTYPE html><html lang="es"><head>
        <meta charset="UTF-8">
        <script src="https://cdn.tailwindcss.com"></script>
        </head><body class="bg-red-50 flex items-center justify-center min-h-screen">
        <div class="text-center">
            <p class="text-5xl mb-4">⚠️</p>
            <h1 class="text-2xl font-bold text-red-700">Error crítico</h1>
            <p class="text-red-500 mt-2">No se recibieron datos de estudiantes.</p>
            <a href="index.php" class="mt-6 inline-block bg-red-600 text-white px-6 py-2 rounded-lg">
                Volver al formulario
            </a>
        </div></body></html>
    ');
}

/** @var Estudiante[] $estudiantes */
$estudiantes = unserialize($_SESSION['estudiantes']);

// Limpiar sesión después de leer (evitar datos obsoletos)
unset($_SESSION['estudiantes']);

// ─── Función: determina si el estudiante aprobó (Módulo 4 — return) ───────
/**
 * @param Estudiante $e Objeto estudiante
 * @return bool         true si aprobó (promedio >= 61), false si reprobó
 */
function aprobo(Estudiante $e): bool
{
    return $e->getPromedio() >= 61;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen p-8">

</body>
</html>
