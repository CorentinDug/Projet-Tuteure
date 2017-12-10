<?php
/**
 * Created by PhpStorm.
 * User: julie
 * Date: 19/10/2017
 * Time: 11:02
 */
namespace App\Controller;

use App\Model\ReservationModel;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use App\Model\PlatModel;
use App\Model\TypePlatModel;
use App\Helper\HelperMail;
use Silex\Controller;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
class ReservationController implements ControllerProviderInterface
{

    private $reservationModel;
    private $helperMail;


    public function reserver(Application $app){
        $menu = $_GET['menu'];
        return $app['twig']->render('frontOff/menu/reservation.html.twig',['menu'=>$menu]);
    }

    public function validFormReserv(Application $app){
        $donnees = [
            'id_menu' => htmlspecialchars($_POST['id_menu']),
            'nbDispo' => htmlspecialchars($_POST['nbDispo']),
            'email' => htmlspecialchars($_POST['email'])
        ];
        if (!preg_match("/[A-Za-z0-9]{2,}.(@).[A-Za-z0-9]{2,}.(fr|com|de)/", $donnees['email'])) $erreurs['email'] = 'mail faux (exemple.exemple@exemple.fr ou com)';
        if (empty($erreurs)){
            $this->helperMail = new HelperMail();
            $this->reservationModel = new ReservationModel($app);
            $this->reservationModel->createReservation($donnees);
            $this->helperMail->sendMail();

            return $app->redirect($app["url_generator"]->generate('menu.index'));
        }else{
            return $app['twig']->render('reservation.html.twig',['donnees'=>$donnees,'erreurs'=>$erreurs]);
        }
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
        $controllers->get('/reserver', 'App\Controller\ReservationController::reserver')->bind('reservation.reserver');
        $controllers->post('/reserver', 'App\Controller\ReservationController::validFormReserv')->bind('reservation.validFormReserv');

        return $controllers;

    }
}