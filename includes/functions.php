<?php
// functions.php
require_once __DIR__ . '/../config/database.php';

function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

function formatDate($date) {
    if ($date instanceof MongoDB\BSON\UTCDateTime) {
        return $date->toDateTime()->format('Y-m-d');
    }
    return $date;
}

function crearRutina($titulo, $descripcion, $dia, $horaInicio, $horaFin, $prioridad = 'media') {
    global $routinesCollection;
    $resultado = $routinesCollection->insertOne([
        'titulo' => sanitizeInput($titulo),
        'descripcion' => sanitizeInput($descripcion),
        'dia' => sanitizeInput($dia),
        'horaInicio' => sanitizeInput($horaInicio),
        'horaFin' => sanitizeInput($horaFin),
        'prioridad' => sanitizeInput($prioridad),
        'completada' => false,
        'fechaCreacion' => new MongoDB\BSON\UTCDateTime()
    ]);
    return $resultado->getInsertedId();
}

function obtenerRutinas($dia = null) {
    global $routinesCollection;
    $filtro = [];
    if ($dia !== null) {
        $filtro['dia'] = $dia;
    }
    return $routinesCollection->find($filtro, [
        'sort' => ['horaInicio' => 1]
    ]);
}

function obtenerRutinaPorId($id) {
    global $routinesCollection;
    return $routinesCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
}

function actualizarRutina($id, $titulo, $descripcion, $dia, $horaInicio, $horaFin, $prioridad) {
    global $routinesCollection;
    $resultado = $routinesCollection->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($id)],
        ['$set' => [
            'titulo' => sanitizeInput($titulo),
            'descripcion' => sanitizeInput($descripcion),
            'dia' => sanitizeInput($dia),
            'horaInicio' => sanitizeInput($horaInicio),
            'horaFin' => sanitizeInput($horaFin),
            'prioridad' => sanitizeInput($prioridad)
        ]]
    );
    return $resultado->getModifiedCount();
}

function eliminarRutina($id) {
    global $routinesCollection;
    $resultado = $routinesCollection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
    return $resultado->getDeletedCount();
}

function toggleRutinaCompletada($id) {
    global $routinesCollection;
    $rutina = $routinesCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
    if ($rutina) {
        $nuevoEstado = !$rutina['completada'];
        $resultado = $routinesCollection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($id)],
            ['$set' => ['completada' => $nuevoEstado]]
        );
        return $resultado->getModifiedCount() > 0 ? $nuevoEstado : null;
    }
    return null;
}

function obtenerDiaSemana() {
    $dias = [
        'Domingo', 'Lunes', 'Martes', 'Miércoles',
        'Jueves', 'Viernes', 'Sábado'
    ];
    return $dias[date('w')];
}