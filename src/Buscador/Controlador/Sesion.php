<?php

$sesion = $app['controllers_factory'];


$sesion->get('/login', function() {
    return $lugar;
});

$sesion->get('/logout', function() {
    return $id;
});

$sesion->post('/login', function() use ($sesion) {
    return 'ok';
});

return $sesion;
