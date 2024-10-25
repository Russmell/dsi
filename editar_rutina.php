<?php
require_once __DIR__ . '/includes/functions.php';

$mensaje = '';
$rutina = null;

if (isset($_GET['id'])) {
    $rutina = obtenerRutinaPorId($_GET['id']);
    if (!$rutina) {
        header('Location: index.php?mensaje=Rutina no encontrada');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        !empty($_POST['titulo']) && 
        !empty($_POST['descripcion']) && 
        !empty($_POST['dia']) && 
        !empty($_POST['horaInicio']) && 
        !empty($_POST['horaFin'])
    ) {
        $actualizado = actualizarRutina(
            $_GET['id'],
            $_POST['titulo'],
            $_POST['descripcion'],
            $_POST['dia'],
            $_POST['horaInicio'],
            $_POST['horaFin'],
            $_POST['prioridad']
        );
        
        if ($actualizado) {
            header('Location: index.php?dia=' . $_POST['dia'] . '&mensaje=Rutina actualizada con éxito');
            exit;
        } else {
            $mensaje = "Error al actualizar la rutina.";
        }
    } else {
        $mensaje = "Por favor, complete todos los campos requeridos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Rutina</title>
    <link rel="stylesheet" href="public/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Editar Rutina</h1>
        
        <?php if ($mensaje): ?>
            <div class="error"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <?php if ($rutina): ?>
        <form method="POST" class="form-rutina">
            <div class="form-group">
                <label for="titulo">Título:</label>
                <input type="text" id="titulo" name="titulo" required 
                       value="<?php echo htmlspecialchars($rutina['titulo']); ?>">
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required><?php 
                    echo htmlspecialchars($rutina['descripcion']); 
                ?></textarea>
            </div>

            <div class="form-group">
                <label for="dia">Día de la semana:</label>
                <select id="dia" name="dia" required>
                    <?php
                    $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                    foreach ($dias as $dia) {
                        $selected = $rutina['dia'] === $dia ? 'selected' : '';
                        echo "<option value=\"$dia\" $selected>$dia</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="horaInicio">Hora de inicio:</label>
                <input type="time" id="horaInicio" name="horaInicio" required
                       value="<?php echo htmlspecialchars($rutina['horaInicio']); ?>">
            </div>

            <div class="form-group">
                <label for="horaFin">Hora de fin:</label>
                <input type="time" id="horaFin" name="horaFin" required
                       value="<?php echo htmlspecialchars($rutina['horaFin']); ?>">
            </div>

            <div class="form-group">
                <label for="prioridad">Prioridad:</label>
                <select id="prioridad" name="prioridad">
                    <?php
                    $prioridades = ['baja', 'media', 'alta'];
                    foreach ($prioridades as $prioridad) {
                        $selected = $rutina['prioridad'] === $prioridad ? 'selected' : '';
                        echo "<option value=\"$prioridad\" $selected>" . ucfirst($prioridad) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="button">Actualizar Rutina</button>
                <a href="index.php?dia=<?php echo $rutina['dia']; ?>" class="button">Cancelar</a>
            </div>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>