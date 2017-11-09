<?php
include('config.php');




//On initialise le timeZone
ini_set('date.timezone', 'Europe/Paris');

//On ajoute l'autoloader (compatible winwin)
$loader = require_once join(DIRECTORY_SEPARATOR,[dirname(__DIR__), 'vendor', 'autoload.php']);

//dans l'autoloader nous ajoutons notre répertoire applicatif
$loader->addPsr4('App\\',join(DIRECTORY_SEPARATOR,[dirname(__DIR__), 'src']));

//Nous instancions un objet Silex\Application
$app = new Silex\Application();

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'dbhost' => hostname,
        'host' => hostname,
        'dbname' => database,
        'user' => username,
        'password' => password,
        'charset'   => 'utf8mb4',
    ),
));



//utilisation de twig
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => join(DIRECTORY_SEPARATOR, [dirname(__DIR__), 'src', 'View'])
));

// utilisation des sessions
$app->register(new Silex\Provider\SessionServiceProvider());

//en dev, nous voulons voir les erreurs
$app['debug'] = true;

$app->register(new Silex\Provider\AssetServiceProvider(), array(
    'assets.named_packages' => array(
        'css' => array(
            'version' => 'css2',
            'base_path' => __DIR__.'/../web/'
        )
    ),
));

//  namespace App\Helper;  // ne pas oublier le namespace ; le nom de la classe = le nom du fichier
$app->extend('twig', function($twig, $app) {

    $function = new Twig_SimpleFunction('viewTVA', function ($prix) {
        return App\Helper\HelperViewPrix::viewTVA($prix);
    });
    $twig->addFunction($function);


    return $twig;
});
$app->extend('twig', function($twig, $app) {
    $function = new Twig_SimpleFunction('changeDate', function ($date) {
        return App\Helper\HelperDate::changeDate($date);
    });
    $twig->addFunction($function);

    return $twig;
});

$app->before(function (\Symfony\Component\HttpFoundation\Request $request) use ($app) {
    $nomRoute=$request->get("_route");
    if ($app['session']->get('roles') != 'ROLE_ADMIN') {
        if($nomRoute=="plats.add"|| $nomRoute=="plats.delete"||$nomRoute=="plats.edit"
            ||$nomRoute=="typePlat.add"||$nomRoute=="typePlat.delete"||$nomRoute=="typePlat.edit"
            ||$nomRoute=="plats.validFormAdd"||$nomRoute=="plats.validFormeDelete"||$nomRoute=="plats.validFormEdit"
            ||$nomRoute=="typePlat.validFormAddTypePlat"||$nomRoute=="typePlat.validFormDeleteTypePlat"||$nomRoute=="typePlat.validFormEditTypePlat") {
            return $app->redirect($app["url_generator"]->generate("index.errorDroit"));
        }
    }

});

use Silex\Provider\CsrfServiceProvider;
$app->register(new CsrfServiceProvider());

use Silex\Provider\FormServiceProvider;
$app->register(new FormServiceProvider());

// par défaut les méthodes DELETE PUT ne sont pas prises en compte
use Symfony\Component\HttpFoundation\Request;
Request::enableHttpMethodParameterOverride();

//***************************************
// Montage des contrôleurs sur le routeur
$app->mount("/", new App\Controller\IndexController($app));

$app->mount("/plats", new App\Controller\PlatController($app));
$app->mount("/typePlat", new App\Controller\TypePlatController($app));
$app->mount("/user", new App\Controller\UserController($app));


//On lance l'application
$app->run();