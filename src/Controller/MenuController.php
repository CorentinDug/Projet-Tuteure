<?php

namespace App\Controller;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use App\Helper\HelperDate;
use App\Model\MenuModel;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
class menuController implements ControllerProviderInterface{

    private $menuModel;
    private $aperitifModel;
    private $boissonModel;
    private $dessertModel;
    private $entreeModel;
    private $fromageModel;
    private $platModel;
    private $typeModel;
    private $supplementModel;

    public function index(Application $app) {
        return $this->showMenu($app);       // appel de la méthode show
    }

    public function showMenu(Application $app)
    {
        $this->menuModel = new MenuModel($app);
        $menu = $this->menuModel->getAllMenu();
        return $app["twig"]->render("menu/v_table_menu.html.twig",['data'=>$menu]);
    }

    public function addMenu(Application $app){
        $this->typeModel = new TypeModel($app);
        $type = $this->typeModel->getAllType();
        $aperitif = $this->aperitifModel->getAllAperitif();
        return $app["twig"]->render('plats/v_form_create_menu.html.twig');
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

        $controllers->get('/', 'App\Controller\MenuController::index')->bind('menu.index');
        $controllers->get('/show', 'App\Controller\MenuController::showMenu')->bind('menu.show');

        return $controllers;

        // TODO: Implement connect() method.
    }
}