<?php
namespace App\Controller;
use App\Model\EntreeModel;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;

/**
 * Created by PhpStorm.
 * User: Ciryion
 * Date: 29/11/2017
 * Time: 21:31
 */
class EntreeController implements ControllerProviderInterface{

    private $entreeModel;

    public function autoCompleteEntree(Application $app){
        $this->entreeModel = new EntreeModel($app);
        $arr = $this->entreeModel->autoCompleteEntree();
        return json_encode($arr);
    }
    /**
     * Returns routes to connect to the given application.
     *
     * @param \Silex\Application $app An Application instance
     *
     * @return \Silex\ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/autoEntree','App\Controller\EntreeController::autoCompleteEntree')->bind('entree.autoComplete');

        return $controllers;


    }
}
