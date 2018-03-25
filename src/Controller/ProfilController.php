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
    private $userModel;
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

    public function changeMDP(Application $app){

        return $app["twig"]->render('frontOff/v_change_mdp.html.twig');
    }

    public function validFormChangeMDP(Application $app){
        $donnees = [
            'Amotdepasse' => htmlspecialchars($_POST['Amotdepasse']),
            'Nmotdepasse' => htmlspecialchars($_POST['Nmotdepasse']),
            'Cmotdepasse' => htmlspecialchars($_POST['Cmotdepasse']),

        ];
        $this->userModel = new UserModel($app);
        $grainDeSel = "gsjkstzzeadsfùzrafsdf!sq!fezlkfes";
        $hash = md5($donnees['password'].$grainDeSel);
        var_dump(md5("client".$grainDeSel));
        $donnees['password'] = $hash;
        $data = $this->userModel->verif_mdp_Utilisateur($donnees['password']);
        if($data != null){
            if (strlen($donnees['Nmotdepasse']) < 4) $erreurs['Nmotdepasse']='le mot de passe doit contenir quatre caracteres minimum';
            if($donnees['Nmotdepasse'] != $donnees['Cmotdepasse']) $erreurs['Cmotdepasse'] = 'Les mot de passes sont différents';
            if(! empty($erreurs))
            {
                $this->platModel= new PlatModel($app);
                $plat = $this->platModel->getAllPlat();
                return $app["twig"]->render('backOff/composant/plats/v_form_update_plats.html.twig',['donnees'=>$donnees,'erreurs'=>$erreurs,'plat'=>$plat]);
            }
            else
            {
                $this->platModel = new PlatModel($app);
                $this->platModel->updatePlat($donnees);
                return $app->redirect($app["url_generator"]->generate("composant.index"));
            }

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

        $controllers->get('/', 'App\Controller\ProfilController::index')->bind('profil.index');
        $controllers->get('/show', 'App\Controller\ProfilController::showProfil')->bind('profil.show');
        $controllers->get('/change', 'App\Controller\ProfilController::changeMDP')->bind('profil.changeMDP');




        return $controllers;
    }
}
