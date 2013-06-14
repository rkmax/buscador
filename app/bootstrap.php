<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

////////////////////////////////////////////////////////////
//
//  Configuracion
//
////////////////////////////////////////////////////////////

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

// Manejo de sesiones
$app->register(new Silex\Provider\SessionServiceProvider(), array(
    'name' => '__buscador',
    'cookie_path' => __DIR__ . '/../cache/session'
));

// Capade seguridad
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'asegurado' => array(
            'form' => array(
                'login_path' => '/login',
                'check_path' => '/login_check'
            ),
            'logout' => array(
                'lgout_path' => '/logout'
            )
        )
    ),
    'security.access_rules' => array(
        array('^/.*$', 'IS_AUTHENTICATED_ANONYMOUSLY')
    )
));

////////////////////////////////////////////////////////////
//
//  Inicializando definiendo la DB
//
////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////
//
//  Inicia la aplicacion
//
////////////////////////////////////////////////////////////

// pagina principal
$app->get('/', function() use ($app) {
    return $app['twig']->render('welcome.twig', array(
        'name' => 'Pollitico'
    ));
});

// Sesion
$app->mount('/', include '../src/Buscador/Controlador/Sesion.php');

// Agenda
$app->mount('/agenda', include '../src/Buscador/Controlador/Agenda.php');

return $app;
