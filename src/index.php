<?php
/**
 * index.php — Página 1: Registro de Estudiantes
 * 
 * Recibe los datos del formulario, construye un arreglo de objetos Estudiante
 * y redirige a resultados.php pasando el arreglo a través de sesión.
 * 
 * @author [Tu Nombre] — Módulo 1: Registro de estudiantes y notas
 */

session_start();
require_once __DIR__ . '/classes/Estudiante.php';

// ─── Función: procesa el formulario y redirige a la página 2 ───────────────
/**
 * Toma los datos POST, crea los objetos Estudiante y los guarda en sesión
 * antes de redirigir a resultados.php.
 *
 * @param array $post  Arreglo $_POST del formulario
 * @return void        Redirige al usuario (no retorna)
 */
function sendStudent(array $post): void
{
    $nombres = $post['nombres'] ?? [];
    $notas1  = $post['notas1']  ?? [];
    $notas2  = $post['notas2']  ?? [];
    $notas3  = $post['notas3']  ?? [];

    /** @var Estudiante[] $estudiantes */
    $estudiantes = [];

    for ($i = 0; $i < count($nombres); $i++) {
        // Saltar filas con nombre vacío (Módulo 4 — continue)
        if (trim($nombres[$i]) === '') {
            continue;
        }

        $estudiantes[] = new Estudiante(
            trim($nombres[$i]),
            (float) ($notas1[$i] ?? 0),
            (float) ($notas2[$i] ?? 0),
            (float) ($notas3[$i] ?? 0)
        );
    }

    // Guardar el arreglo de objetos en sesión y redirigir
    $_SESSION['estudiantes'] = serialize($estudiantes);
    header('Location: resultados.php');
    exit;
}

// Procesar cuando el formulario es enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    sendStudent($_POST);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Estudiantes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-6">


</body>
</html>
