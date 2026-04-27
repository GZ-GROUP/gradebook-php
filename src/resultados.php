<?php
/**
 * resultados.php — Página 2: Visualización de Resultados
 *
 * Implementa los Módulos 2, 3, 4 y 5 del enunciado:
 *   • Módulo 2 — Condicionales if/elseif/else + switch  (escala y letra)
 *   • Módulo 3 — foreach, for, while, do…while         (recorridos y cálculos)
 *   • Módulo 4 — break, continue, return, exit/die     (control de flujo)
 *   • Módulo 5 — Reporte visual con Tailwind CSS
 *
 * 
 */

session_start();
require_once __DIR__ . '/classes/Estudiante.php';

// ─── Módulo 4 — exit/die: sin datos en sesión ──────────────────────────────
if (empty($_SESSION['estudiantes'])) {
    die('<!DOCTYPE html><html lang="es"><head>
        <meta charset="UTF-8">
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Hammersmith+One&display=swap" rel="stylesheet">
        <style>h1,h2{font-family:"Hammersmith One",sans-serif;}</style>
        </head>
        <body style="background:#0f172a;" class="flex items-center justify-center min-h-screen">
        <div class="text-center px-8">
            <p class="text-6xl mb-6">⚠️</p>
            <h1 class="text-3xl text-white mb-2">Error crítico</h1>
            <p class="text-slate-400 mt-2">No se recibieron datos de estudiantes.</p>
            <a href="index.php"
               class="mt-8 inline-block px-8 py-3 rounded-xl text-white font-bold"
               style="background:linear-gradient(135deg,#22D3EE,#2563EB)">
               ← Volver al formulario
            </a>
        </div></body></html>');
}

/** @var Estudiante[] $estudiantes */
$estudiantes  = unserialize($_SESSION['estudiantes']);
$advertencias = $_SESSION['advertencias'] ?? [];

// Limpiar sesión
unset($_SESSION['estudiantes'], $_SESSION['advertencias']);

// ─── Módulo 4 — return: función que evalúa si el estudiante aprobó ─────────
/**
 * @param  Estudiante $e
 * @return bool  true si promedio >= 61
 */
function aprobo(Estudiante $e): bool
{
    return $e->getPromedio() >= 61;
}

// ─── Módulo 2 — if/elseif/else: escala de calificación ────────────────────
/**
 * Devuelve la escala textual según el promedio.
 *
 * @param  float  $promedio
 * @return string Escala de calificación
 */
function getEscala(float $promedio): string
{
    if ($promedio >= 91) {
        return 'Sobresaliente';
    } elseif ($promedio >= 71) {
        return 'Bueno';
    } elseif ($promedio >= 61) {
        return 'Regular';
    } elseif ($promedio >= 51) {
        return 'Bajo';
    } else {
        return 'Reprobado';
    }
}

// ─── Módulo 2 — switch: letra de calificación A–F ─────────────────────────
/**
 * Asigna la letra de calificación usando switch sobre el rango del promedio.
 *
 * @param  float  $promedio
 * @return string Letra A, B, C, D o F
 */
function getLetra(float $promedio): string
{
    // Convertir el promedio a un rango entero de decenas para el switch
    $rango = (int) floor($promedio / 10);

    switch ($rango) {
        case 10:
        case 9:
            $letra = 'A';   // 91–100 → Sobresaliente
            break;
        case 8:
        case 7:
            $letra = 'B';   // 71–90  → Bueno
            break;
        case 6:
            $letra = 'C';   // 61–70  → Regular
            break;
        case 5:
            $letra = 'D';   // 51–60  → Bajo
            break;
        default:
            $letra = 'F';   // 0–50   → Reprobado
            break;
    }

    return $letra;
}

// ─── Módulo 3 — while: promedio general acumulativo ───────────────────────
$sumaTotal   = 0.0;
$indiceWhile = 0;

while ($indiceWhile < count($estudiantes)) {
    $sumaTotal += $estudiantes[$indiceWhile]->getPromedio();
    $indiceWhile++;
}

$promedioGeneral = count($estudiantes) > 0
    ? round($sumaTotal / count($estudiantes), 2)
    : 0.0;

// ─── Módulo 4 — break: encontrar al estudiante con promedio más alto ───────
$maxPromedio  = -1;
$estudianteStar = null;

foreach ($estudiantes as $e) {
    if ($e->getPromedio() > $maxPromedio) {
        $maxPromedio    = $e->getPromedio();
        $estudianteStar = $e;
    }
    // break cuando ya no puede haber un promedio mayor (100 es el techo)
    if ($maxPromedio === 100.0) {
        break;
    }
}

// Estudiante con la nota más baja
$minPromedio    = 101;
$estudianteMin  = null;

foreach ($estudiantes as $e) {
    if ($e->getPromedio() < $minPromedio) {
        $minPromedio   = $e->getPromedio();
        $estudianteMin = $e;
    }
}

// Contadores para el panel de resumen
$aprobados   = 0;
$reprobados  = 0;

foreach ($estudiantes as $e) {
    if (aprobo($e)) {
        $aprobados++;
    } else {
        $reprobados++;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados — Sistema de Calificaciones</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hammersmith+One&family=Clear+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
</head>
<body class="page-resultados page-body px-4 py-12">
<?php include __DIR__ . '/components/header.php'; ?>

<div class="max-w-5xl mx-auto page-enter">

    <!-- ── Encabezado ─────────────────────────────────────────── -->
    <div class="text-center mb-10">
        <span class="badge uppercase tracking-widest">Reporte Final</span>
        <h1 class="text-4xl md:text-5xl text-white mt-4">
            Resultados del
            <span style="background:linear-gradient(90deg,#22D3EE,#2563EB);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">
                Grupo
            </span>
        </h1>
        <p class="text-slate-400 mt-2 text-sm">Desarrollo de Software VII · Grupo 2GS231</p>
        <div class="accent-line w-24 mx-auto mt-5"></div>
    </div>

    <!-- ── Advertencias (continue aplicado) ────────────────────── -->
    <?php if (!empty($advertencias)): ?>
    <div class="glass rounded-xl p-5 mb-6">
        <h3 class="text-yellow-400 text-sm uppercase tracking-wider mb-3 font-display">
            ⚠ Entradas omitidas
        </h3>
        <div class="space-y-2">
            <?php foreach ($advertencias as $adv): ?>
            <div class="warn-item"><?= htmlspecialchars($adv) ?></div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- ── Módulo 5 — Panel de estadísticas resumen ─────────────── -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

        <!-- Total estudiantes -->
        <div class="stat-card">
            <div class="text-slate-400 text-xs uppercase tracking-wider mb-2">Estudiantes</div>
            <div class="stat-value text-white"><?= count($estudiantes) ?></div>
            <div class="text-slate-500 text-xs mt-1">registrados</div>
        </div>

        <!-- Aprobados -->
        <div class="stat-card" style="border-color:rgba(34,211,238,.25);">
            <div class="text-cyan-400 text-xs uppercase tracking-wider mb-2">Aprobados</div>
            <div class="stat-value" style="color:#22D3EE"><?= $aprobados ?></div>
            <div class="text-slate-500 text-xs mt-1">
                <?= count($estudiantes) > 0 ? round($aprobados/count($estudiantes)*100) : 0 ?>%
            </div>
        </div>

        <!-- Reprobados -->
        <div class="stat-card" style="border-color:rgba(248,113,113,.25);">
            <div class="text-red-400 text-xs uppercase tracking-wider mb-2">Reprobados</div>
            <div class="stat-value text-red-400"><?= $reprobados ?></div>
            <div class="text-slate-500 text-xs mt-1">
                <?= count($estudiantes) > 0 ? round($reprobados/count($estudiantes)*100) : 0 ?>%
            </div>
        </div>

        <!-- Promedio general (while) -->
        <div class="stat-card" style="border-color:rgba(37,99,235,.35);">
            <div class="text-blue-400 text-xs uppercase tracking-wider mb-2">Promedio General</div>
            <div class="stat-value text-blue-400"><?= number_format($promedioGeneral, 1) ?></div>
            <div class="text-slate-500 text-xs mt-1">
                <?= getLetra($promedioGeneral) ?> · <?= getEscala($promedioGeneral) ?>
            </div>
        </div>
    </div>

    <!-- Mejor y peor estudiante -->
    <?php if ($estudianteStar && $estudianteMin): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        <!-- Nota más alta (break) -->
        <div class="glass rounded-xl p-5 flex items-center gap-4"
             style="border-color:rgba(34,211,238,.3);">
            <div class="text-3xl">🏆</div>
            <div>
                <div class="text-xs text-slate-400 uppercase tracking-wider mb-1">Nota más alta (break)</div>
                <div class="text-white font-display text-lg"><?= htmlspecialchars($estudianteStar->nombre) ?></div>
                <div style="color:#22D3EE" class="text-sm">
                    <?= number_format($estudianteStar->getPromedio(), 2) ?> ·
                    <?= getLetra($estudianteStar->getPromedio()) ?> ·
                    <?= getEscala($estudianteStar->getPromedio()) ?>
                </div>
            </div>
        </div>
        <!-- Nota más baja -->
        <div class="glass rounded-xl p-5 flex items-center gap-4"
             style="border-color:rgba(248,113,113,.3);">
            <div class="text-3xl">📉</div>
            <div>
                <div class="text-xs text-slate-400 uppercase tracking-wider mb-1">Nota más baja</div>
                <div class="text-white font-display text-lg"><?= htmlspecialchars($estudianteMin->nombre) ?></div>
                <div class="text-red-400 text-sm">
                    <?= number_format($estudianteMin->getPromedio(), 2) ?> ·
                    <?= getLetra($estudianteMin->getPromedio()) ?> ·
                    <?= getEscala($estudianteMin->getPromedio()) ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- ── Módulo 3/5 — Tabla completa (foreach) ──────────────── -->
    <div class="glass rounded-2xl overflow-hidden mb-8">
        <div class="px-6 py-5 flex items-center justify-between border-b border-white/10">
            <h2 class="text-white text-xl">Tabla de Calificaciones</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="tbl-head">
                        <th>#</th>
                        <th>Nombre</th>
                        <th class="text-center">Nota 1</th>
                        <th class="text-center">Nota 2</th>
                        <th class="text-center">Nota 3</th>
                        <th class="text-center">Promedio</th>
                        <th class="text-center">Letra</th>
                        <th>Escala</th>
                        <th class="text-center">Estado</th>
                        <th>Barra</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // ── Módulo 3 — foreach: recorrer arreglo de estudiantes ──
                    $numFila = 1;
                    foreach ($estudiantes as $est):
                        $promedio = $est->getPromedio();
                        $letra    = getLetra($promedio);
                        $escala   = getEscala($promedio);
                        $ok       = aprobo($est); // Módulo 4 — return
                        $letraClass = 'letra-' . strtolower($letra);
                    ?>
                    <tr class="tbl-row">
                        <td class="text-slate-500 font-mono text-xs"><?= $numFila++ ?></td>
                        <td class="font-medium text-white"><?= htmlspecialchars($est->nombre) ?></td>
                        <td class="text-center text-slate-300"><?= number_format($est->nota1, 1) ?></td>
                        <td class="text-center text-slate-300"><?= number_format($est->nota2, 1) ?></td>
                        <td class="text-center text-slate-300"><?= number_format($est->nota3, 1) ?></td>
                        <td class="text-center font-display text-white"><?= number_format($promedio, 2) ?></td>
                        <td class="text-center">
                            <span class="letra-badge <?= $letraClass ?>"><?= $letra ?></span>
                        </td>
                        <td class="text-slate-300 text-sm"><?= $escala ?></td>
                        <td class="text-center">
                            <span class="pill <?= $ok ? 'pill-ok' : 'pill-fail' ?>">
                                <?= $ok ? 'Aprobado' : 'Reprobado' ?>
                            </span>
                        </td>
                        <td style="min-width:120px; padding-right:1.5rem;">
                            <?php
                            // ── Módulo 3 — for: barra de progreso visual ──
                            $anchoBar = (int) round($promedio); // 0–100
                            ?>
                            <div class="bar-track">
                                <div class="bar-fill" style="width:<?= $anchoBar ?>%;"></div>
                            </div>
                            <div class="text-slate-500 text-xs mt-1 text-right">
                                <?php
                                // El for genera los segmentos de la barra (cada 10 puntos = 1 segmento)
                                $segmentos = 0;
                                for ($seg = 0; $seg < 10; $seg++) {
                                    if (($seg * 10) < $promedio) {
                                        $segmentos++;
                                    }
                                }
                                echo $promedio . '/100';
                                ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ── Módulo 3 — do…while: listado de reprobados ─────────── -->
    <div class="glass rounded-2xl p-6 mb-8">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-white text-xl">Estudiantes Reprobados</h2>
        </div>

        <?php
        // Construir sub-arreglo de reprobados para el do…while
        $reprobadosList = [];
        foreach ($estudiantes as $e) {
            if (!aprobo($e)) {
                $reprobadosList[] = $e;
            }
        }

        // ── Módulo 3 — do…while: al menos una iteración garantizada ──
        $idxDW  = 0;
        $numDW  = 1;
        ?>

        <?php if (empty($reprobadosList)): ?>
            <div class="text-center py-8">
                <div class="text-4xl mb-3">🎉</div>
                <p class="text-cyan-400 font-display text-lg">¡Todos los estudiantes aprobaron!</p>
                <p class="text-slate-400 text-sm mt-1">No hay registros reprobados en este grupo.</p>
            </div>
            <?php
            // do…while ejecuta al menos una vez aunque no haya reprobados
            do {
                // bloque vacío — garantiza la ejecución mínima del loop
                $idxDW++;
            } while ($idxDW < count($reprobadosList));
            ?>
        <?php else: ?>
            <div class="space-y-3">
            <?php
            do {
                $eRep = $reprobadosList[$idxDW];
                ?>
                <div class="fail-item">
                    <span class="fail-num"><?= $numDW++ ?>.</span>
                    <span class="flex-1"><?= htmlspecialchars($eRep->nombre) ?></span>
                    <span class="text-slate-400 text-sm">
                        Promedio: <strong class="text-red-400"><?= number_format($eRep->getPromedio(), 2) ?></strong>
                    </span>
                    <span class="letra-badge letra-f" style="font-size:.8rem;width:1.6rem;height:1.6rem;">F</span>
                </div>
                <?php
                $idxDW++;
            } while ($idxDW < count($reprobadosList));
            ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- ── Volver ──────────────────────────────────────────────── -->
    <div class="text-center">
        <a href="index.php"
           class="inline-block px-8 py-3 rounded-xl text-white font-display tracking-wide"
           style="background:linear-gradient(135deg,#22D3EE,#2563EB);
                  box-shadow:0 4px 24px rgba(37,99,235,.35);">
            ← Nuevo Registro
        </a>
    </div>

</div><!-- /max-w-5xl -->
</body>
</html>