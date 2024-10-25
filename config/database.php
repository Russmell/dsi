<?php
require_once __DIR__ . '/../vendor/autoload.php';

$mongoClient = new MongoDB\Client("mongodb+srv://russmell:djU1BWsExvfbzGbw@horario.vnkel.mongodb.net/?retryWrites=true&w=majority&appName=horario");
$database = $mongoClient->selectDatabase('escuela');
$routinesCollection = $database->rutinas;
?>
