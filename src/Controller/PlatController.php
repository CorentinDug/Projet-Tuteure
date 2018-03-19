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
        return $app["twig"]->render('backOff/composant/v_table_composant_menu.html.twig',['data'=>$plats]);
    }
    public function home(Application $app){
        return $app["twig"]->render('plats/v_admin.html.twig');
    }

    public function addPlat(Application $app) {

        return $app["twig"]->render('backOff/composant/plats/v_form_create_plats.html.twig');
    }

    public function addPlatNom(Application $app) {
        $donnees['nom'] = $_GET['nom'];
        return $app["twig"]->render('backOff/composant/plats/v_form_create_plats.html.twig',['donnees'=>$donnees]);
    }

    public function deletePlat(Application $app, $id) {
        $this->platModel = new PlatModel($app);

        $platModel = $this->platModel->getPlat($id);
        $libelle = $this->platModel->getLibelle($id);

        return $app["twig"]->render('backOff/composant/plats/v_form_delete_plats.html.twig',['donnees'=>$platModel,'libelle' => $libelle]);
    }

    public function editPlat(Application $app,$id) {
        $this->platModel = new PlatModel($app);
        $donnees = $this->platModel->getPlat($id);



        return $app["twig"]->render('backOff/composant/plats/v_form_update_plats.html.twig',['donnees'=>$donnees]);
    }

    public function validFormAddPlat(Application $app ) {
        //var_dump($app['request']->attributes);

            $donnees = [
                'libelle_plat' => htmlspecialchars($_POST['libelle_plat']),                    // echapper les entrées
            ];


            if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['libelle_plat']))) $erreurs['libelle_plat']='libelle composé de 2 lettres minimum';

            if(! empty($erreurs))
            {
                return $app["twig"]->render('plats/v_form_create_plats.html.twig',['donnees'=>$donnees,'erreurs'=>$erreurs]);
            }
            else {
                $this->platModel = new PlatModel($app);
                $this->platModel->insertPlat($donnees);
                return $app->redirect($app["url_generator"]->generate("composant.index"));
            }

    }

    public function validFormDeletePlat(Application $app,Request $req)
    {

        $donnees = [
            'id_plat' => $app->escape($req->get('id')),
        ];

        $this->platModel = new PlatModel($app);
        $this->platModel->deletePlat($donnees);
        return $app->redirect($app["url_generator"]->generate("composant.index"));
    }

    public function validFormEditPlat(Application $app){
        $donnees = [
            'id_plat' => htmlspecialchars($_POST['id']),
            'libelle_plat' => htmlspecialchars($_POST['libelle_plat']),


        ];

        if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['libelle_plat']))) $erreurs['nom']='nom composé de 2 lettres minimum';
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
    public function autoCompletePlat(Application $app){
        $this->platModel = new PlatModel($app);
        $arr = $this->platModel->autoCompletePlat();
        return json_encode($arr);
    }

    public function getId(Application $app){

        $this->platModel = new PlatModel($app);
        return $this->platModel->getId($_POST['plat']);

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

        $controllers->get('/autoPlat','App\Controller\PlatController::autoCompletePlat')->bind('plat.autoComplete');
        $controllers->get('/getId','App\Controller\PlatController::getId')->bind('plat.getId');

        $controllers->get('/addPlatNom', 'App\Controller\PlatController::addPlatNom')->bind('plat.addPlatNom');



        return $controllers;
    }
}