<?php
/**
 * index.php — Página 1: Registro de Estudiantes
 *
 * Recibe los datos del formulario, construye un arreglo de objetos Estudiante
 * y redirige a resultados.php pasando el arreglo a través de sesión.
 *
 * 
 */

session_start();
require_once __DIR__ . '/classes/Estudiante.php';

// ─── Módulo 1 / Módulo 4 — Procesa el formulario POST ─────────────────────
/**
 * Lee los arrays POST, valida las entradas y crea objetos Estudiante.
 * Usa continue para omitir filas vacías o con notas inválidas (Módulo 4).
 * Usa exit después de redirigir (Módulo 4).
 *
 * @param array $post  Contenido de $_POST
 * @return void        Redirige a resultados.php y termina la ejecución
 */
function procesarYRedirigir(array $post): void
{
    $nombres = $post['nombres'] ?? [];
    $notas1  = $post['notas1']  ?? [];
    $notas2  = $post['notas2']  ?? [];
    $notas3  = $post['notas3']  ?? [];

    /** @var Estudiante[] $estudiantes */
    $estudiantes  = [];
    $advertencias = [];

    for ($i = 0; $i < count($nombres); $i++) {

        // Módulo 4 — continue: omitir filas con nombre vacío
        if (trim($nombres[$i]) === '') {
            continue;
        }

        $n1 = $notas1[$i] ?? '';
        $n2 = $notas2[$i] ?? '';
        $n3 = $notas3[$i] ?? '';

        // Módulo 4 — continue: omitir si alguna nota no es numérica o está fuera de rango
        if (
            !is_numeric($n1) || !is_numeric($n2) || !is_numeric($n3) ||
            (float)$n1 < 0 || (float)$n1 > 100 ||
            (float)$n2 < 0 || (float)$n2 > 100 ||
            (float)$n3 < 0 || (float)$n3 > 100
        ) {
            $advertencias[] = "Fila " . ($i + 1) . " («{$nombres[$i]}»): nota inválida — omitida.";
            continue;
        }

        $estudiantes[] = new Estudiante(
            trim($nombres[$i]),
            (float) $n1,
            (float) $n2,
            (float) $n3
        );
    }

    // Módulo 4 — exit/die: error crítico si no se ingresó ningún estudiante válido
    if (empty($estudiantes)) {
        $_SESSION['error_critico'] = 'No se ingresaron estudiantes válidos. Verifique los datos.';
        header('Location: index.php');
        exit;
    }

    // Guardar en sesión y redirigir
    $_SESSION['estudiantes']  = serialize($estudiantes);
    $_SESSION['advertencias'] = $advertencias;
    header('Location: resultados.php');
    exit;
}

// Disparar procesamiento sólo en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    procesarYRedirigir($_POST);
}

// Leer mensajes de sesión para mostrar en el formulario
$errorCritico  = $_SESSION['error_critico'] ?? null;
unset($_SESSION['error_critico']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Estudiantes — Sistema de Calificaciones</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hammersmith+One&family=Clear+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="flex items-center justify-center px-4 py-12">

<div class="w-full max-w-4xl page-enter">

    <!-- ── Encabezado ──────────────────────────────────────────── -->
    <div class="text-center mb-10">
        <span class="badge uppercase tracking-widest">Universidad Tecnológica de Panamá</span>
        <h1 class="text-4xl md:text-5xl text-white mt-4 leading-tight">
            Sistema de<br>
            <span style="background:linear-gradient(90deg,#22D3EE,#2563EB);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">
                Calificaciones
            </span>
        </h1>
        <p class="text-slate-400 mt-2 text-sm">Desarrollo de Software VII · Grupo 2GS231</p>
        <div class="accent-line w-24 mx-auto mt-5"></div>
    </div>

    <!-- ── Error crítico (si viene de sesión) ─────────────────── -->
    <?php if ($errorCritico): ?>
    <div class="glass rounded-xl p-4 mb-6 border border-red-500/40 text-red-300 text-sm flex items-center gap-3">
        <span class="text-xl">⚠️</span>
        <span><?= htmlspecialchars($errorCritico) ?></span>
    </div>
    <?php endif; ?>

    <!-- ── Tarjeta principal ──────────────────────────────────── -->
    <div class="glass rounded-2xl p-6 md:p-10">

        <div class="flex items-center justify-between mb-6">
            <h2 class="text-white text-xl">Registro de Estudiantes</h2>
        </div>

        <p class="text-slate-400 text-sm mb-7">
            Ingrese hasta <strong class="text-cyan-400">10 estudiantes</strong> con sus tres notas parciales (0–100).
            Las filas con nombre vacío o notas inválidas serán omitidas automáticamente.
        </p>

        <form method="POST" action="index.php" id="formEstudiantes" novalidate>

            <!-- Encabezados de columnas -->
            <div class="grid grid-cols-12 gap-2 px-3 mb-2">
                <div class="col-span-1 text-slate-500 text-xs uppercase tracking-wider">#</div>
                <div class="col-span-4 text-slate-500 text-xs uppercase tracking-wider">Nombre</div>
                <div class="col-span-2 text-slate-500 text-xs uppercase tracking-wider text-center">Nota 1</div>
                <div class="col-span-2 text-slate-500 text-xs uppercase tracking-wider text-center">Nota 2</div>
                <div class="col-span-2 text-slate-500 text-xs uppercase tracking-wider text-center">Nota 3</div>
                <div class="col-span-1"></div>
            </div>

            <!-- Filas de estudiantes -->
            <div id="filas">
                <?php for ($i = 0; $i < 5; $i++): ?>
                <div class="grid grid-cols-12 gap-2 items-center px-3 py-2 row-stripe rounded-lg row-appear" data-row="<?= $i ?>">
                    <div class="col-span-1 text-slate-500 text-sm font-mono"><?= $i + 1 ?></div>
                    <div class="col-span-4">
                        <input class="inp" type="text" name="nombres[]"
                               placeholder="Nombre del estudiante">
                    </div>
                    <div class="col-span-2">
                        <input class="inp text-center" type="number" name="notas1[]"
                               min="0" max="100" placeholder="—">
                    </div>
                    <div class="col-span-2">
                        <input class="inp text-center" type="number" name="notas2[]"
                               min="0" max="100" placeholder="—">
                    </div>
                    <div class="col-span-2">
                        <input class="inp text-center" type="number" name="notas3[]"
                               min="0" max="100" placeholder="—">
                    </div>
                    <div class="col-span-1 flex justify-center">
                        <button type="button" onclick="limpiarFila(this)"
                                class="text-slate-600 hover:text-red-400 transition-colors text-lg leading-none">×</button>
                    </div>
                </div>
                <?php endfor; ?>
            </div>

            <!-- Controles de filas -->
            <div class="flex items-center gap-3 mt-4 px-3">
                <button type="button" id="btnAgregar"
                        class="text-cyan-400 hover:text-cyan-300 text-sm transition-colors flex items-center gap-1">
                    <span class="text-lg leading-none">+</span> Agregar fila
                </button>
                <span class="text-slate-700">·</span>
                <span id="contadorFilas" class="text-slate-500 text-sm">5 / 10 filas</span>
            </div>

            <!-- Separador -->
            <div class="accent-line my-8 opacity-40"></div>

            <!-- Submit -->
            <div class="flex justify-center">
                <button type="submit" class="btn-primary">
                    Procesar Calificaciones →
                </button>
            </div>

        </form>
    </div>

    <!-- ── Leyenda de escala ───────────────────────────────────── -->
    <div class="glass rounded-xl p-5 mt-6 grid grid-cols-2 md:grid-cols-5 gap-3">
        <?php
        $escala = [
            ['rango'=>'91–100','label'=>'Sobresaliente','letra'=>'A','color'=>'text-cyan-400'],
            ['rango'=>'71–90', 'label'=>'Bueno',        'letra'=>'B','color'=>'text-blue-400'],
            ['rango'=>'61–70', 'label'=>'Regular',      'letra'=>'C','color'=>'text-yellow-400'],
            ['rango'=>'51–60', 'label'=>'Bajo',         'letra'=>'D','color'=>'text-orange-400'],
            ['rango'=>'0–50',  'label'=>'Reprobado',    'letra'=>'F','color'=>'text-red-400'],
        ];
        foreach ($escala as $e):
        ?>
        <div class="text-center">
            <div class="font-display text-2xl <?= $e['color'] ?>"><?= $e['letra'] ?></div>
            <div class="text-white text-xs mt-1"><?= $e['label'] ?></div>
            <div class="text-slate-500 text-xs"><?= $e['rango'] ?></div>
        </div>
        <?php endforeach; ?>
    </div>

</div><!-- /max-w-4xl -->

<script>
// ── Gestión dinámica de filas ────────────────────────────────────────────
let totalFilas = 5;
const MAX = 10;

function actualizarContador() {
    document.getElementById('contadorFilas').textContent = `${totalFilas} / ${MAX} filas`;
    document.getElementById('btnAgregar').disabled = totalFilas >= MAX;
    document.getElementById('btnAgregar').classList.toggle('opacity-30', totalFilas >= MAX);
}

document.getElementById('btnAgregar').addEventListener('click', () => {
    if (totalFilas >= MAX) return;
    const idx = totalFilas;
    totalFilas++;

    const div = document.createElement('div');
    div.className = 'grid grid-cols-12 gap-2 items-center px-3 py-2 row-stripe rounded-lg row-appear';
    div.dataset.row = idx;
    div.innerHTML = `
        <div class="col-span-1 text-slate-500 text-sm font-mono">${totalFilas}</div>
        <div class="col-span-4">
            <input class="inp" type="text" name="nombres[]" placeholder="Nombre del estudiante">
        </div>
        <div class="col-span-2">
            <input class="inp text-center" type="number" name="notas1[]" min="0" max="100" placeholder="—">
        </div>
        <div class="col-span-2">
            <input class="inp text-center" type="number" name="notas2[]" min="0" max="100" placeholder="—">
        </div>
        <div class="col-span-2">
            <input class="inp text-center" type="number" name="notas3[]" min="0" max="100" placeholder="—">
        </div>
        <div class="col-span-1 flex justify-center">
            <button type="button" onclick="limpiarFila(this)"
                    class="text-slate-600 hover:text-red-400 transition-colors text-lg leading-none">×</button>
        </div>`;
    document.getElementById('filas').appendChild(div);
    actualizarContador();
});

function limpiarFila(btn) {
    const row = btn.closest('[data-row]');
    row.querySelectorAll('input').forEach(i => i.value = '');
}

actualizarContador();
</script>
</body>
</html>