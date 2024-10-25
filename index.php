<?php
require_once __DIR__ . '/includes/functions.php';

if (isset($_GET['accion']) && isset($_GET['id'])) {
    switch ($_GET['accion']) {
        case 'eliminar':
            $count = eliminarRutina($_GET['id']);
            $mensaje = $count > 0 ? "Rutina eliminada con éxito." : "No se pudo eliminar la rutina.";
            break;
        case 'toggleCompletada':
            $nuevoEstado = toggleRutinaCompletada($_GET['id']);
            if ($nuevoEstado !== null) {
                $mensaje = $nuevoEstado ? "Rutina marcada como completada." : "Rutina marcada como pendiente.";
            } else {
                $mensaje = "No se pudo cambiar el estado de la rutina.";
            }
            break;
    }
}

$diaActual = isset($_GET['dia']) ? $_GET['dia'] : obtenerDiaSemana();
$rutinas = obtenerRutinas($diaActual);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Rutinas Semanales</title>
    <link rel="stylesheet" href="public/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Gestión de Rutinas Semanales</h1>

        <?php if (isset($mensaje)): ?>
            <div class="<?php echo strpos($mensaje, 'éxito') !== false ? 'success' : 'error'; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <div class="dias-navegacion">
            <?php
            $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
            foreach ($dias as $dia) {
                $claseActiva = $dia === $diaActual ? 'active' : '';
                echo "<a href='?dia=$dia' class='button $claseActiva'>$dia</a>";
            }
            ?>
        </div>

        <a href="agregar_rutina.php" class="button">Agregar Nueva Rutina</a>

        <h2>Rutinas para <?php echo $diaActual; ?></h2>
        <table>
            <tr>
                <th>Hora</th>
                <th>Título</th>
                <th>Descripción</th>
                <th>Prioridad</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($rutinas as $rutina): ?>
            <tr class="prioridad-<?php echo htmlspecialchars($rutina['prioridad']); ?>">
                <td><?php echo htmlspecialchars($rutina['horaInicio']); ?> - <?php echo htmlspecialchars($rutina['horaFin']); ?></td>
                <td><?php echo htmlspecialchars($rutina['titulo']); ?></td>
                <td><?php echo htmlspecialchars($rutina['descripcion']); ?></td>
                <td><?php echo htmlspecialchars($rutina['prioridad']); ?></td>
                <td>
                    <a href="index.php?accion=toggleCompletada&id=<?php echo $rutina['_id']; ?>&dia=<?php echo $diaActual; ?>"
                       class="button <?php echo $rutina['completada'] ? 'completada' : 'pendiente'; ?>">
                        <?php echo $rutina['completada'] ? 'Completada' : 'Pendiente'; ?>
                    </a>
                </td>
                <td class="actions">
                    <a href="editar_rutina.php?id=<?php echo $rutina['_id']; ?>" class="button">Editar</a>
                    <a href="index.php?accion=eliminar&id=<?php echo $rutina['_id']; ?>&dia=<?php echo $diaActual; ?>" 
                       class="button"
                       onclick="return confirm('¿Estás seguro de que quieres eliminar esta rutina?');">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>