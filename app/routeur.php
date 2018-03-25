<?php
$app->mount("/", new App\Controller\IndexController($app));

$app->mount("/plats", new App\Controller\PlatController($app));
$app->mount("/composant", new App\Controller\ComposantController($app));
$app->mount("/menu", new App\Controller\MenuController($app));
$app->mount("/user", new App\Controller\UserController($app));
$app->mount("/boisson", new App\Controller\BoissonController($app));
$app->mount("/aperitif", new App\Controller\AperitifController($app));
$app->mount("/entree", new App\Controller\EntreeController($app));
$app->mount("/fromage", new App\Controller\FromageController($app));
$app->mount("/dessert", new App\Controller\DessertController($app));
$app->mount("/supplement", new App\Controller\SupplementController($app));
$app->mount("/reservation", new App\Controller\ReservationController($app));
$app->mount("/profil", new App\Controller\ProfilController($app));
$app->mount("/etudiant", new App\Controller\EtudiantController($app));
$app->mount("/comment", new App\Controller\CommentController($app));
