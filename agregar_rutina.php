<?php

require_once __DIR__ . '/includes/functions.php';

$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        !empty($_POST['titulo']) && 
        !empty($_POST['descripcion']) && 
        !empty($_POST['dia']) && 
        !empty($_POST['horaInicio']) && 
        !empty($_POST['horaFin'])
    ) {
        $id = crearRutina(
            $_POST['titulo'],
            $_POST['descripcion'],
            $_POST['dia'],
            $_POST['horaInicio'],
            $_POST['horaFin'],
            $_POST['prioridad']
        );
        
        if ($id) {
            header('Location: index.php?dia=' . $_POST['dia'] . '&mensaje=Rutina creada con éxito');
            exit;
        } else {
            $mensaje = "Error al crear la rutina.";
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
    <title>Agregar Nueva Rutina</title>
    <link rel="stylesheet" href="public/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Agregar Nueva Rutina</h1>
        
        <?php if ($mensaje): ?>
            <div class="error"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <form method="POST" class="form-rutina">
            <div class="form-group">
                <label for="titulo">Título:</label>
                <input type="text" id="titulo" name="titulo" required 
                       value="<?php echo isset($_POST['titulo']) ? htmlspecialchars($_POST['titulo']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required><?php 
                    echo isset($_POST['descripcion']) ? htmlspecialchars($_POST['descripcion']) : ''; 
                ?></textarea>
            </div>

            <div class="form-group">
                <label for="dia">Día de la semana:</label>
                <select id="dia" name="dia" required>
                    <?php
                    $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                    foreach ($dias as $dia) {
                        $selected = (isset($_POST['dia']) && $_POST['dia'] === $dia) ? 'selected' : '';
                        echo "<option value=\"$dia\" $selected>$dia</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="horaInicio">Hora de inicio:</label>
                <input type="time" id="horaInicio" name="horaInicio" required
                       value="<?php echo isset($_POST['horaInicio']) ? htmlspecialchars($_POST['horaInicio']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="horaFin">Hora de fin:</label>
                <input type="time" id="horaFin" name="horaFin" required
                       value="<?php echo isset($_POST['horaFin']) ? htmlspecialchars($_POST['horaFin']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="prioridad">Prioridad:</label>
                <select id="prioridad" name="prioridad">
                    <?php
                    $prioridades = ['baja', 'media', 'alta'];
                    foreach ($prioridades as $prioridad) {
                        $selected = (isset($_POST['prioridad']) && $_POST['prioridad'] === $prioridad) ? 'selected' : '';
                        echo "<option value=\"$prioridad\" $selected>" . ucfirst($prioridad) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="button">Guardar Rutina</button>
                <a href="index.php" class="button">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>