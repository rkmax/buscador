<?php

    $app = include __DIR__ . '/../app/bootstrap.php';

    $app['debug'] = true;

    if($app['debug']) {
        $app->register(new Whoops\Provider\Silex\WhoopsServiceProvider());
    }

    $app->run();
