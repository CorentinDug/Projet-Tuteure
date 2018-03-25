<?php
/**
 * Created by PhpStorm.
 * User: julie
 * Date: 19/10/2017
 * Time: 11:02
 */
namespace App\Controller;

use App\Model\ProfilModel;
use App\Model\ReservationModel;
use App\Model\UserModel;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use App\Model\PlatModel;
use App\Model\TypePlatModel;
use App\Helper\HelperMail;
use Silex\Controller;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
class ProfilController implements ControllerProviderInterface
{
    private $profilModel;
    private $reservationModel;


    public function index(Application $app) {
        return $this->showProfil($app);       // appel de la méthode show
    }

    public function showProfil(Application $app) {
        $this->profilModel = new ProfilModel($app);
        $id = $app['session']->get('id');
        $profil = $this->profilModel->getProfil($id);
        $this->reservationModel = new ReservationModel($app);
        $reservation = $this->reservationModel->getAllReservClient($id);
        return $app["twig"]->render('frontOff/profil.html.twig',['data'=>$profil,'reservation'=>$reservation]);
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

        $controllers->get('/', 'App\Controller\ProfilController::index')->bind('profil.index');
        $controllers->get('/show', 'App\Controller\ProfilController::showProfil')->bind('profil.show');
        $controllers->get('/change', 'App\Controller\ProfilController::changeMDP')->bind('profil.changeMDP');




        return $controllers;
    }
}
