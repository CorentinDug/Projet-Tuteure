<?php
namespace App\Controller;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;   // version 2.0 avant sans Api


class IndexController implements ControllerProviderInterface
{
    public function index(Application $app)
    {
        return $app["twig"]->render("v_home.html.twig");
    }

    public function info()
    {
        return phpinfo();
    }

    public function validMonForm(Application $app)
    {
        $data=$_POST['test'];
        return $app["twig"]->render("v_home.html.twig",["data"=>$data]);
    }
    public function errorDroit(Application $app){
        return $app["twig"]->render("v_errorDroit.html.twig");
    }
    public function connect(Application $app)
    {
        // créer un nouveau controleur basé sur la route par défaut
        $index = $app['controllers_factory'];
        $index->match("/", 'App\Controller\IndexController::index')->bind('index.index');
        $index->match("/errorDroit", 'App\Controller\IndexController::errorDroit')->bind('index.errorDroit');
        $index->match("/index", 'App\Controller\IndexController::index');
        $index->match("/info", 'App\Controller\IndexController::info');

        $index->match("/info", 'App\Controller\IndexController::info')->bind('phpinfo');

        //  $index->match("/monForm1", 'App\Controller\IndexController::validMonForm')->bind('validForm')->method('PUT|POST');

        $index->put("/monForm1", 'App\Controller\IndexController::validMonForm')->bind('validForm');

        return $index;
    }
}