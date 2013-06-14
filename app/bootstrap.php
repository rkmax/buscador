<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

// Plantillas twig
$app->register(new Silex\Provider\TwigServiceProvider, array(
    'twig.path' => __DIR__ . '/../src/Buscador/Vista'
));

// Base de datos Doctrine (DBAL)
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_sqlite',
        'path' => __DIR__ . '/../cache/app.db'
    )
));

// pagina principal
$app->get('/', function() use ($app) {
    return $app['twig']->render('welcome.twig', array(
        'name' => 'Pollitico'
    ));
});

// Agenda
$app->mount('/agenda', include '../src/Buscador/Controlador/Agenda.php');

// Sesion
$app->mount('/agenda', include '../src/Buscador/Controlador/Sesion.php');

return $app;
