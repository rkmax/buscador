<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

// pagina principal
$app->get('/', function() {
    return 'Hello';
});

// Agenda
$app->mount('/agenda', include '../src/Buscador/Controlador/Agenda.php');

// Sesion
$app->mount('/agenda', include '../src/Buscador/Controlador/Sesion.php');

return $app;
