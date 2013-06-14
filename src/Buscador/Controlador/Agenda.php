<?php

$agenda = $app['controllers_factory'];


$agenda->get('/{lugar}', function($lugar) {
    return $lugar;
});

$agenda->get('/cita/cancelar/{id}', function($id) {
    return $id;
});

$agenda->get('/cita/agendar/{agenda}/{intervalo}', function($agenda, $intervalo) {
    return $agenda . " - " . $intervalo;
});

return $agenda;
