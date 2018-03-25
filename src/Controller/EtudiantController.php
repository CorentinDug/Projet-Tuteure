<?php
namespace App\Controller;
use App\Model\EntreeModel;
use App\Model\EtudiantModel;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Created by PhpStorm.
 * User: Ciryion
 * Date: 29/11/2017
 * Time: 21:31
 */
class EtudiantController implements ControllerProviderInterface{

    private $EtudiantModel;


    public function index(Application $app){
        return $this->afficherEtu($app);       // appel de la méthode show
    }

    public function afficherEtu(Application $app){
        $this->EtudiantModel = new EtudiantModel($app);
        $etu = $this->EtudiantModel->getAllEtu();
        if ($app['session']->get('roles') == 'ROLE_ADMIN'){
                return $app["twig"]->render("backOff/Etudiant/v_table_etu.html.twig",['data'=>$etu]);
        }else{
            return $app["twig"]->render("backOff/menu/v_errorDroit.twig");

        }
    }


    public function creerEtudiant(Application $app){

        return $app["twig"]->render('backOff/Etudiant/v_addEtu.html.twig');

    }

    public function validFormAddEtu(Application $app, Request $req){

        $this->EtudiantModel = new EtudiantModel($app);
        var_dump($_POST);
        if (isset($_POST['nom']) && isset($_POST['prenom'])) {
            $donnees = [
                'prenom' => htmlspecialchars($req->get('prenom')),
                'nom' => htmlspecialchars($req->get('nom')),
            ];


            if (!(preg_match("#^[a-zA-Z]{2,}#",$donnees['prenom']))) $erreurs['prenom'] = 'Le prenom doit contenir au moins 2 caracteres';
            if (!(preg_match("#^[a-zA-Z]{2,}#",$donnees['nom']))) $erreurs['nom'] = 'Le nom doit contenir au moins 2 caracteres';


            if(! empty($erreurs))
            {

                return $app["twig"]->render('backOff/Etudiant/v_addEtu.html.twig',['donnees'=>$donnees,'erreurs'=>$erreurs]);
            }
            else
            {

                $this->EtudiantModel->addEtu($donnees);
                if ($app['session']->get('roles') != 'ROLE_ADMIN' || !($app['session']->get('logged') != 1)){
                    return $app->redirect($app["url_generator"]->generate('etudiant.index'));
                }
            }

        }
        else{
            return $app->abort(404, 'error Pb data form Add');
        }
    }

    public function updateEtu (Application $app, $id){
        $this->EtudiantModel = new EtudiantModel($app);
        $donnees = $this->EtudiantModel->getEtu($id);
        //var_dump($donnees);

        return $app["twig"]->render('backoff/Etudiant/v_form_update_etu.html.twig',['donnees'=>$donnees]);

    }

    public function validFormEditEtu(Application $app)
    {

        $donnees = [
            'nom_etu' => htmlspecialchars($_POST['nom_etu']),
            'prenom_etu' => htmlspecialchars($_POST['prenom_etu']),
            'id_etu' => htmlspecialchars($_POST['id_etu']),
        ];

        if ((!preg_match("/^[A-Za-z ]{2,}/", $donnees['nom_etu']))) $erreurs['nom_etu'] = 'Nom composé de 2 lettres minimum';
        if ((!preg_match("/^[A-Za-z ]{2,}/", $donnees['prenom_etu']))) $erreurs['prenom_etu'] = 'Prenom composé de 2 lettres minimum';
        if (!empty($erreurs)) {
            return $app["twig"]->render('backOff/Etudiant/v_form_update_etu.html.twig', ['donnees' => $donnees, 'erreurs' => $erreurs]);
        } else {
            $this->EtudiantModel = new EtudiantModel($app);
            $this->EtudiantModel->updateEtudiant($donnees);
            return $app->redirect($app["url_generator"]->generate("etudiant.index"));
        }


    }

    public function deleteEtu(Application $app, $id){

        $this->EtudiantModel = new EtudiantModel($app);
        $donnees = $this->EtudiantModel->getEtu($id);
        return $app["twig"]->render('backOff/Etudiant/v_form_delete_etu.html.twig', ['donnees' => $donnees]);

    }

    public function validFormDeleteEtu(Application $app, Request $req)
    {

        $donnees = [
            'id_etu' => $app->escape($req->get('id')),
        ];

        $this->EtudiantModel = new EtudiantModel($app);
        $this->EtudiantModel->deleteEtu($donnees);
        return $app->redirect($app["url_generator"]->generate("etudiant.index"));
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


        $controllers->get('/', 'App\Controller\EtudiantController::index')->bind('etudiant.index');

        $controllers->get('/add','App\Controller\EtudiantController::creerEtudiant')->bind('etudiant.creerEtu');
        $controllers->post('/add','App\Controller\EtudiantController::validFormAddEtu')->bind('etudiant.validFormAddEtu');

        $controllers->get('/update{id}','App\Controller\EtudiantController::updateEtu')->bind('etudiant.updateEtu');
        $controllers->put('/update','App\Controller\EtudiantController::validFormEditEtu')->bind('etudiant.validFormUpdateEtu');

        $controllers->get('/delete{id}','App\Controller\EtudiantController::deleteEtu')->bind('etudiant.deleteEtu');
        $controllers->delete('/delete','App\Controller\EtudiantController::validFormDeleteEtu')->bind('etudiant.validFormDeleteEtu');


        return $controllers;


    }
}
