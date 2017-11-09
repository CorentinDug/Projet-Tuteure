<?php
$app->mount("/", new App\Controller\IndexController($app));

$app->mount("/plats", new App\Controller\PlatController($app));
$app->mount("/typePlat", new App\Controller\TypePlatController($app));
$app->mount("/menu", new App\Controller\MenuController($app));
$app->mount("/user", new App\Controller\UserController($app));