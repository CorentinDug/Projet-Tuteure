<?php
namespace App\Controller;

use App\Model\UserModel;
use Gregwar\Captcha\CaptchaBuilder;
use Silex\Application;

use Silex\Api\ControllerProviderInterface;   // modif version 2.0

use Symfony\Component\HttpFoundation\Request;   // pour utiliser request

class UserController implements ControllerProviderInterface {

    private $userModel;

    public function index(Application $app) {
        return $this->connexionUser($app);
    }

    public function connexionUser(Application $app)
    {
        return $app["twig"]->render('v_login.html.twig');
    }

    public function validFormConnexionUser(Application $app, Request $req)
    {

        $app['session']->clear();
        $donnees['login']=$app->escape($req->get('login'));
        $donnees['password']=$app->escape($req->get('password'));

        $this->userModel = new UserModel($app);
        $data=$this->userModel->verif_login_mdp_Utilisateur($donnees['login'],$donnees['password']);
        if($data != NULL)
        {
            $app['session']->set('roles', $data['roles']);  //dans twig {{ app.session.get('roles') }}
            $app['session']->set('username', $data['username']);
            $app['session']->set('id', $data['id']);
            $app['session']->set('logged', 1);
            return $app->redirect($app["url_generator"]->generate("index.index"));
        }
        else
        {
            $app['session']->set('erreur','mot de passe ou login incorrect');
            return $app["twig"]->render('v_login.html.twig');
        }
    }
    public function deconnexionSession(Application $app)
    {
        $app['session']->clear();
        $app['session']->getFlashBag()->add('msg', 'vous êtes déconnecté');
        return $app->redirect($app["url_generator"]->generate("index.index"));
    }

    public function ajouterUser(Application $app){
        $builder = new CaptchaBuilder();
        $builder->build();
        $_SESSION['phrase'] = $builder -> getPhrase();
        $phrase = $_SESSION['phrase'];
        return $app["twig"]->render('v_inscription.html.twig', ['phrase' => $phrase, 'image' => $builder -> inline()]);
    }

    public function validFormInscription(Application $app,Request $req){
        $this->userModel = new userModel($app);
        if (isset($_POST['username']) && isset($_POST['motdepasse']) and isset($_POST['email']) and isset($_POST['maPhrase']) and $_POST['verificationmotdepasse']) {
            $donnees = [
                'username' => htmlspecialchars($req->get('username')),
                'motdepasse' => htmlspecialchars($req->get('motdepasse')),
                'verificationmotdepasse' => htmlspecialchars($req->get('verificationmotdepasse')),
                'email' => htmlspecialchars($req->get('email')),
                'captcha' => htmlspecialchars($req->get('maPhrase')),
            ];

            $data = $this->userModel->getAllUser();

            if($donnees['motdepasse'] != $donnees['verificationmotdepasse']) $erreurs['verificationmotdepasse'] = 'Les mots de passes ne correspondent pas';
            if (strlen($donnees['motdepasse']) < 4) $erreurs['motdepasse']='le mot de passe doit contenir quatre caracteres minimum';
            if (strlen($donnees['username']) < 4) $erreurs['username']='Le pseudo doit être composé de 4 caracteres minimum';
            foreach ($data as $value){
                if($donnees['username'] == $value['username']){
                    $erreurs['username']='Cette username est déjà utilisé, veuillez en prendre un autre';
                    break;
                }
            }
            if (!(preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $donnees['email']))) $erreurs['email']='E-Mail : xCaracteres@yCaracteres.zCaracteres';
            if($donnees['captcha'] != $_SESSION['phrase']) $erreurs['phrase']='Le captcha est incorrect';

            if(! empty($erreurs))
            {
                $builder = new CaptchaBuilder();
                $builder->build();
                $_SESSION['phrase'] = $builder -> getPhrase();
                return $app["twig"]->render('v_inscription.html.twig',['donnees'=>$donnees,'erreurs'=>$erreurs,'image' => $builder->inline()]);
            }
            else
            {
                $grainDeSel = "gsjkstzzeadsfùzrafsdf!sq!fezlkfes";
                $hash = md5($donnees['motdepasse'].$grainDeSel);
                $donnees['password'] = $hash;
                $this->userModel = new userModel($app);
                $this->userModel->addUser($donnees);
                if ($app['session']->get('roles') != 'ROLE_ADMIN' || !($app['session']->get('logged') != 1)){
                    return $app->redirect($app["url_generator"]->generate('index.index'));
                }
            }

        }
        else
            return $app->abort(404, 'error Pb data form Add');
    }

    public function connect(Application $app) {
        $controllers = $app['controllers_factory'];
        $controllers->match('/', 'App\Controller\UserController::index')->bind('user.index');
        $controllers->get('/login', 'App\Controller\UserController::connexionUser')->bind('user.login');
        $controllers->get('/signup', 'App\Controller\UserController::ajouterUser')->bind('user.signup');
        $controllers->post('/login', 'App\Controller\UserController::validFormConnexionUser')->bind('user.validFormlogin');
        $controllers->post('/signup', 'App\Controller\UserController::validFormInscription')->bind('user.validFormInscription');
        $controllers->get('/logout', 'App\Controller\UserController::deconnexionSession')->bind('user.logout');
        return $controllers;
    }
}