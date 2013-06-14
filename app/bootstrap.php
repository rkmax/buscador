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
        'assets' => array(
            'pattern' =>  '^/css|images|js)/',
            'security' => false,
        ),
        'buscador' => array(
            'pattern' => '^/agenda/.*',
            'anonymous' => true,
            'form' => array(
                'login_path' => '/login',
                'check_path' => '/login_check'
            ),
            'logout' => array(
                'logout_path' => '/logout'
            ),
            'users' => $app->share(function () use ($app) {
                return new Buscador\Model\ProveedorUsuarios($app['db']);
            }),
        )
    ),
    'security.access_rules' => array(
        array('^/.*$', 'IS_AUTHENTICATED_ANONYMOUSLY'),
        array('^/login', 'IS_AUTHENTICATED_ANONYMOUSLY'),
        array('^/agenda/.*$', 'ROLE_USER')
    )
));

////////////////////////////////////////////////////////////
//
//  Inicializando definiendo la DB
//
////////////////////////////////////////////////////////////

$schema = $app['db']->getSchemaManager();

// Usuario
$tableName = "usuario";
if (!$schema->tablesExist($tableName)) {
    $usuario = new Doctrine\DBAL\Schema\Table($tableName);
    $usuario->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
    $usuario->setPrimaryKey(array('id'));
    $usuario->addColumn('username', 'string', array('length' => 32));
    $usuario->addUniqueIndex(array('username'));
    $usuario->addColumn('password', 'string', array('length' => 255));
    $usuario->addColumn('roles', 'string', array('length' => 255));

    $schema->createTable($usuario);

    // Fixture usuario de ejemplo
    $app['db']->insert($tableName, array(
        'username' => 'pollo',
        'password' => '123', // la clasica,
        'roles' => 'ROLE_USER'
    ));
}

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
