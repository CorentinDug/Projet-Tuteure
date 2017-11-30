<?php
namespace App\Controller;

use App\Model\FromageModel;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use App\Model\AperitifModel;
use App\Helper\HelperDate;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
class FromageController implements ControllerProviderInterface
{

    private $fromageModel;

    public function autoCompleteFromage(Application $app){
        $this->fromageModel = new FromageModel($app);
        $arr = $this->fromageModel->autoCompleteFromage();
        return json_encode($arr);
    }

    public function getId(Application $app){
        $this->fromageModel = new FromageModel($app);
        return $this->fromageModel->getId($_POST['fromage']);

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

        $controllers->get('/autoFromage', 'App\Controller\FromageController::autoCompleteFromage')->bind('fromage.autoComplete');
        $controllers->get('/getId', 'App\Controller\FromageController::getId')->bind('fromage.getId');

        return $controllers;
    }

}