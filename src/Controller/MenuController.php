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

    public function index(Application $app) {
        return $this->showMenu($app);       // appel de la méthode show
    }

    public function showMenu(Application $app)
    {
        $this->menuModel = new MenuModel($app);
        $menu = $this->menuModel->getAllMenu();
        return $app["twig"]->render("menu/v_table_menu.html.twig");
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
        // TODO: Implement connect() method.
    }
}