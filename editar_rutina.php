<?php
require_once __DIR__ . '/includes/functions.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $dia = $_POST['dia'] ?? '';
    $horaInicio = $_POST['hora_inicio'] ?? '';
    $horaFin = $_POST['hora_fin'] ?? '';
    $prioridad = $_POST['prioridad'] ?? 'media';

    // Validación básica
    if (empty($titulo) || empty($dia) || empty($horaInicio) || empty($horaFin)) {
        $mensaje = "Por favor, complete todos los campos requeridos.";
    } else {
        $resultado = crearRutina($titulo, $descripcion, $dia, $horaInicio, $horaFin, $prioridad);
        if ($resultado) {
            header("Location: index.php?dia=" . urlencode($dia) . "&mensaje=Rutina creada con éxito");
            exit;
        } else {
            $mensaje = "Error al crear la rutina.";
        }
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
            <div class="error">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="agregar_rutina.php" class="form-rutina">
            <div class="form-group">
                <label for="titulo">Título *</label>
                <input type="text" id="titulo" name="titulo" required
                       value="<?php echo isset($_POST['titulo']) ? htmlspecialchars($_POST['titulo']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion"><?php echo isset($_POST['descripcion']) ? htmlspecialchars($_POST['descripcion']) : ''; ?></textarea>
            </div>

            <div class="form-group">
                <label for="dia">Día de la Semana *</label>
                <select id="dia" name="dia" required>
                    <option value="">Seleccione un día</option>
                    <?php
                    $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                    foreach ($dias as $dia) {
                        $selected = (isset($_POST['dia']) && $_POST['dia'] === $dia) ? 'selected' : '';
                        echo "<option value=\"$dia\" $selected>$dia</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group half">
                    <label for="hora_inicio">Hora de Inicio *</label>
                    <input type="time" id="hora_inicio" name="hora_inicio" required
                           value="<?php echo isset($_POST['hora_inicio']) ? htmlspecialchars($_POST['hora_inicio']) : ''; ?>">
                </div>

                <div class="form-group half">
                    <label for="hora_fin">Hora de Fin *</label>
                    <input type="time" id="hora_fin" name="hora_fin" required
                           value="<?php echo isset($_POST['hora_fin']) ? htmlspecialchars($_POST['hora_fin']) : ''; ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="prioridad">Prioridad</label>
                <select id="prioridad" name="prioridad">
                    <?php
                    $prioridades = ['baja', 'media', 'alta'];
                    foreach ($prioridades as $p) {
                        $selected = (isset($_POST['prioridad']) && $_POST['prioridad'] === $p) ? 'selected' : '';
                        echo "<option value=\"$p\" $selected>" . ucfirst($p) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-actions">
                <a href="index.php" class="button">Cancelar</a>
                <button type="submit" class="button primary">Guardar Rutina</button>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Validación de horas
        document.querySelector('form').addEventListener('submit', function(e) {
            const horaInicio = document.getElementById('hora_inicio').value;
            const horaFin = document.getElementById('hora_fin').value;
            
            if (horaInicio && horaFin && horaInicio >= horaFin) {
                e.preventDefault();
                alert('La hora de fin debe ser posterior a la hora de inicio.');
            }
        });
    });
    </script>
</body>
</html>