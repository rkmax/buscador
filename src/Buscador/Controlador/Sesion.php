<?php

$sesion = $app['controllers_factory'];


$sesion->get('/login', function() {
    return $lugar;
});

$sesion->get('/logout', function() {
    return $id;
});

$sesion->post('/login_check', function() use ($app) {
    return 'ok';
});

$sesion->get('/listar', function () use ($app) {
    $sql =  "SELECT * FROM usuario";
    $usuarios = $app['db']->fetchAll($sql);

    return $app['twig']->render('sesion_listar.twig', array(
        'usuarios' => $usuarios
    ));
});

return $sesion;
