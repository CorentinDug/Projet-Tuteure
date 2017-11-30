<?php
namespace App\Controller;

use App\Model\DessertModel;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Silex\ControllerCollection;
class DessertController implements ControllerProviderInterface
{

    private $dessertModel;

    public function autoCompleteDessert(Application $app){
        $this->dessertModel = new DessertModel($app);
        $arr = $this->dessertModel->autoCompleteDessert();
        return json_encode($arr);
    }

    public function getId(Application $app){

        $this->dessertModel = new DessertModel($app);
        return $this->dessertModel->getId($_POST['dessert']);
    }

    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/autoDessert', 'App\Controller\DessertController::autoCompleteDessert')->bind('dessert.autoComplete');
        $controllers->get('/getId', 'App\Controller\DessertController::getId')->bind('dessert.getId');

        return $controllers;
    }

}