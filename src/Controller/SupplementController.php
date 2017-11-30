<?php
namespace App\Controller;


use App\Model\SupplementModel;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Silex\ControllerCollection;
class SupplementController implements ControllerProviderInterface
{

    private $supplementModel;

    public function autoCompleteSupplement(Application $app){
        $this->supplementModel = new SupplementModel($app);
        $arr = $this->supplementModel->autoCompleteSupplement();
        return json_encode($arr);
    }

    public function getId(Application $app){


        $this->supplementModel = new SupplementModel($app);
        return $this->supplementModel->getId($_POST['supplement']);

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

        $controllers->get('/autoSupplement', 'App\Controller\SupplementController::autoCompleteSupplement')->bind('supplement.autoComplete');
        $controllers->get('/getId', 'App\Controller\SupplementController::getId')->bind('supplement.getId');

        return $controllers;
    }

}