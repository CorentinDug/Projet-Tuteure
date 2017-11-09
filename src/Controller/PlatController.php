<?php
/**
 * Created by PhpStorm.
 * User: julie
 * Date: 19/10/2017
 * Time: 11:02
 */
namespace App\Controller;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use App\Model\PlatModel;
use App\Model\TypePlatModel;
use App\Helper\HelperDate;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
class platController implements ControllerProviderInterface{

    private $platModel;
    private $typePlatModel;
    private $helperDate;



    public function index(Application $app) {
        return $this->showPlat($app);       // appel de la méthode show
    }

    public function showPlat(Application $app) {
        $this->platModel = new PlatModel($app);
        $plats = $this->platModel->getAllPlat();
        return $app["twig"]->render('plats/v_table_menu.html.twig',['data'=>$plats]);
    }
    public function home(Application $app){
        return $app["twig"]->render('plats/v_admin.html.twig');
    }

    public function addPlat(Application $app) {

        $this->typePlatModel = new TypePlatModel($app);
        $typePlat = $this->typePlatModel->getAllTypePlat();
        return $app["twig"]->render('plats/v_form_create_menu.html.twig',['typePlat'=>$typePlat]);
    }

    public function deletePlat(Application $app, $id) {
        $this->platModel = new PlatModel($app);

        $platModel = $this->platModel->getPlat($id);
        return $app["twig"]->render('plats/v_form_delete_menu.html.twig',['donnees'=>$platModel]);
    }

    public function editPlat(Application $app,$id) {
        $this->platModel = new PlatModel($app);
        $donnees = $this->platModel->getPlat($id);
        //var_dump($donnees);

        $this->typePlatModel = new TypePlatModel($app);
        $typePlat = $this->typePlatModel->getAllTypePlat();
        //var_dump($typePlat);

        return $app["twig"]->render('plats/v_form_update_menu.html.twig',['donnees'=>$donnees, 'typePlat'=>$typePlat]);
    }

    public function validFormAddPlat(Application $app ) {
        //var_dump($app['request']->attributes);
        $this->helperDate = new HelperDate();
        if (isset($_POST['_csrf_token'])) {
            $token = $_POST['_csrf_token'];
            $csrf_token = new CsrfToken('token_add_plat', $token);
            $csrf_token_ok = $app['csrf.token_manager']->isTokenValid($csrf_token);
            if(!$csrf_token_ok)
            {
                $erreurs["csrf"] = "Erreur : token : ".$token ;
                return $app["twig"]->render("v_error_csrf.html.twig",['erreurs' => $erreurs]);
            }
        }
        else
            return $app->redirect($app["url_generator"]->generate("index.errorCsrf"));

        if (1==1){
            $donnees = [
                'nom' => htmlspecialchars($_POST['nom']),                    // echapper les entrées
                'typePlat' => htmlspecialchars($_POST['idTypePlat']),
                'prix' => htmlspecialchars($_POST['prix']),
                'dureePreparation' => htmlspecialchars($_POST['dureePreparation']),
                'dateCreation' => htmlspecialchars($_POST['dateCreation']),
                'description' => htmlspecialchars($_POST['description']),

            ];


            if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['nom']))) $erreurs['nom']='nom composé de 2 lettres minimum';
            if((!is_numeric($donnees['prix'])))$erreurs['prix']='saisir une valeur numérique';
            if(!$this->helperDate->verifDate($donnees['dateCreation'])) $erreurs['dateCreation']='Saisir date au format JJ-MM-AAAA';
            if(! is_numeric($donnees['dureePreparation']))$erreurs['dureePreparation']='saisir une valeur numérique';
            $donnees['dateCreation'] = $this->helperDate->changeFormat($donnees['dateCreation']);

            if(! empty($erreurs))
            {
                $this->typePlatModel = new TypePlatModel($app);
                $typePlat = $this->typePlatModel->getAllTypePlat();
                return $app["twig"]->render('plats/v_form_create_menu.html.twig',['donnees'=>$donnees,'erreurs'=>$erreurs,'typePlat'=>$typePlat]);
            }
            else
            {
                $this->platModel = new PlatModel($app);
                $this->platModel->insertPlat($donnees);
                return $app->redirect($app["url_generator"]->generate("plats.index"));
            }
        }
        else {
            return "probleme";
        }
    }

    public function validFormDeletePlat(Application $app,Request $req)
    {
        //var_dump($app['request']->attributes);
        if (isset($_POST['_csrf_token'])) {
            $token = $_POST['_csrf_token'];
            $csrf_token = new CsrfToken('token_delete_plat', $token);
            $csrf_token_ok = $app['csrf.token_manager']->isTokenValid($csrf_token);
            if(!$csrf_token_ok)
            {
                $erreurs["csrf"] = "Erreur : token : ".$token ;
                return $app["twig"]->render("v_error_csrf.html.twig",['erreurs' => $erreurs]);
            }
        }
        else
            return $app->redirect($app["url_generator"]->generate("index.errorCsrf"));

        $donnees = [
            'id' => $app->escape($req->get('id')),
        ];

        $this->platModel = new PlatModel($app);
        $this->platModel->deletePlat($donnees);
        return $app->redirect($app["url_generator"]->generate("plats.index"));
    }

    public function validFormEditPlat(Application $app,Request $req){
        $this->helperDate = new HelperDate();
        if (isset($_POST['_csrf_token'])) {
            $token = $_POST['_csrf_token'];
            $csrf_token = new CsrfToken('token_edit_plat', $token);
            $csrf_token_ok = $app['csrf.token_manager']->isTokenValid($csrf_token);
            if(!$csrf_token_ok)
            {
                $erreurs["csrf"] = "Erreur : token : ".$token ;
                return $app["twig"]->render("v_error_csrf.html.twig",['erreurs' => $erreurs]);
            }
        }
        else
            return $app->redirect($app["url_generator"]->generate("index.errorCsrf"));

        $donnees = [
            'id_plat' => htmlspecialchars($_POST['id']),
            'nom' => htmlspecialchars($_POST['nom']),                    // echapper les entrées
            'typePlat_id' => htmlspecialchars($_POST['idTypePlat']),
            'prixPlat' => htmlspecialchars($_POST['prix']),
            'dureePreparation' => htmlspecialchars($_POST['dureePreparation']),
            'dateCreation' => htmlspecialchars($_POST['dateCreation']),
            'description' => htmlspecialchars($_POST['description']),

        ];

        if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['nom']))) $erreurs['nom']='nom composé de 2 lettres minimum';
        if((!is_numeric($donnees['prixPlat'])))$erreurs['prixPlat']='saisir une valeur numérique';
        if(!$this->helperDate->verifDate($donnees['dateCreation'])) $erreurs['dateCreation']='Saisir date au format JJ-MM-AAAA';
        if(! is_numeric($donnees['dureePreparation']))$erreurs['dureePreparation']='saisir une valeur numérique';
        if($donnees['typePlat_id']==0)$erreurs['typePlat']='choisir un type';
        $donnees['dateCreation'] = $this->helperDate->changeFormat($donnees['dateCreation']);
        if(! empty($erreurs))
        {
            $this->typePlatModel = new TypePlatModel($app);
            $typePlat = $this->typePlatModel->getAllTypePlat();
            return $app["twig"]->render('plats/v_form_update_menu.html.twig',['donnees'=>$donnees,'erreurs'=>$erreurs,'typePlat'=>$typePlat]);
        }
        else
        {
            $this->platModel = new PlatModel($app);
            $this->platModel->updatePlat($donnees);
            return $app->redirect($app["url_generator"]->generate("plats.index"));
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

        $controllers->get('/', 'App\Controller\PlatController::index')->bind('plats.index');
        $controllers->get('/show', 'App\Controller\PlatController::showPlat')->bind('plats.show');

        $controllers->get('/home', 'App\Controller\PlatController::home')->bind('plats.home');

        $controllers->get('/add', 'App\Controller\PlatController::addPlat')->bind('plats.add');
        $controllers->post('/add', 'App\Controller\PlatController::validFormAddPlat')->bind('plats.validFormAdd');

        $controllers->get('/delete{id}', 'App\Controller\PlatController::deletePlat')->bind('plats.delete');
        $controllers->delete('/delete', 'App\Controller\PlatController::validFormDeletePlat')->bind('plats.validFormDelete');

        $controllers->get('/edit{id}', 'App\Controller\PlatController::editPlat')->bind('plats.edit');
        $controllers->put('/edit', 'App\Controller\PlatController::validFormEditPlat')->bind('plats.validFormEdit');

        return $controllers;
    }
}