<?php

    $app = include __DIR__ . '/../app/bootstrap.php';

    $app['debug'] = true;

    $app->run();
