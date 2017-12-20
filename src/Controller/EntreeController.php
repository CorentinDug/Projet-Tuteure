<?php
namespace App\Controller;
use App\Model\EntreeModel;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Created by PhpStorm.
 * User: Ciryion
 * Date: 29/11/2017
 * Time: 21:31
 */
class EntreeController implements ControllerProviderInterface{

    private $EntreeModel;

    public function autoCompleteEntree(Application $app){
        $this->EntreeModel = new EntreeModel($app);
        $arr = $this->EntreeModel->autoCompleteEntree();
        return json_encode($arr);
    }

    public function getId(Application $app){
        $this->EntreeModel = new EntreeModel($app);
        return $this->EntreeModel->getId($_POST['entree']);
    }

    public function addEntree(Application $app)
    {

        $this->EntreeModel = new EntreeModel($app);
        $entree = $this->EntreeModel->getAllentree();
        return $app["twig"]->render('backOff/composant/entree/v_form_create_entree.html.twig', ['entree' => $entree]);
    }

    public function deleteEntree(Application $app, $id)
    {
        $this->EntreeModel = new EntreeModel($app);

        $EntreeModel = $this->EntreeModel->getentree($id);
        return $app["twig"]->render('backOff/composant/entree/v_form_delete_entree.html.twig', ['donnees' => $EntreeModel]);
    }

    public function editEntree(Application $app, $id)
    {
        $this->EntreeModel = new EntreeModel($app);
        $donnees = $this->EntreeModel->getentree($id);
        //var_dump($donnees);

        return $app["twig"]->render('backOff/composant/entree/v_form_update_entree.html.twig', ['donnees' => $donnees]);
    }

    public function validFormAddEntree(Application $app)
    {

        if (1 == 1) {
            $donnees = [
                'libelle_entree' => htmlspecialchars($_POST['libelle_entree']),                    // echapper les entrées
                'entree' => htmlspecialchars($_POST['entree']),
            ];


            if ((!preg_match("/^[A-Za-z ]{2,}/", $donnees['libelle_entree']))) $erreurs['libelle_entree'] = 'libelle composé de 2 lettres minimum';
            if (!empty($erreurs)) {
                $this->EntreeModel = new EntreeModel($app);
                $entree = $this->EntreeModel->getAllentree();
                return $app["twig"]->render('backOff/composant/entree/v_form_create_entree.html.twig', ['donnees' => $donnees, 'erreurs' => $erreurs, 'entree' => $entree]);
            } else {
                $this->EntreeModel = new EntreeModel($app);
                $this->EntreeModel->insertentree($donnees);
                if (isset($donnees['entree'])) {
                    return $app->redirect($app["url_generator"]->generate("menu.add"));
                }else return $app->redirect($app["url_generator"]->generate("composant.index"));            }
        } else {
            return "probleme";
        }
    }

    public function validFormDeleteEntree(Application $app, Request $req)
    {

        $donnees = [
            'id_entree' => $app->escape($req->get('id')),
        ];

        $this->EntreeModel = new EntreeModel($app);
        $this->EntreeModel->deleteentree($donnees);
        return $app->redirect($app["url_generator"]->generate("composant.index"));
    }

    public function validFormEditEntree(Application $app, Request $req)
    {

        $donnees = [
            'id_entree' => htmlspecialchars($_POST['id_entree']),
            'libelle_entree' => htmlspecialchars($_POST['libelle_entree']),                    // echapper les entrées

        ];

        if ((!preg_match("/^[A-Za-z ]{2,}/", $donnees['libelle_entree']))) $erreurs['libelle_entree'] = 'libelle composé de 2 lettres minimum';
        if (!empty($erreurs)) {
            $this->EntreeModel = new EntreeModel($app);
            $entree = $this->EntreeModel->getAllentree();
            return $app["twig"]->render('backOff/composant/entree/v_form_update_entree.html.twig', ['donnees' => $donnees, 'erreurs' => $erreurs, 'entree' => $entree]);
        } else {
            $this->EntreeModel = new EntreeModel($app);
            $this->EntreeModel->updateentree($donnees);
            return $app->redirect($app["url_generator"]->generate("composant.index"));
        }


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

        $controllers->get('/autoEntree','App\Controller\EntreeController::autoCompleteEntree')->bind('entree.autoComplete');
        $controllers->get('/getId','App\Controller\EntreeController::getId')->bind('entree.getId');

        $controllers->get('/add', 'App\Controller\EntreeController::addEntree')->bind('entree.add');
        $controllers->post('/add', 'App\Controller\EntreeController::validFormAddEntree')->bind('entree.validFormAddEntree');

        $controllers->get('/delete{id}', 'App\Controller\EntreeController::deleteEntree')->bind('entree.delete');
        $controllers->delete('/delete', 'App\Controller\EntreeController::validFormDeleteEntree')->bind('entree.validFormDeleteEntree');

        $controllers->get('/edit{id}', 'App\Controller\EntreeController::editEntree')->bind('entree.edit');
        $controllers->put('/edit', 'App\Controller\EntreeController::validFormEditEntree')->bind('entree.validFormEditEntree');

        return $controllers;


    }
}
