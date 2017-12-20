<?php
namespace App\Controller;


use App\Model\SupplementModel;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;

class SupplementController implements ControllerProviderInterface
{

    private $SupplementModel;

    public function autoCompleteSupplement(Application $app){
        $this->SupplementModel = new SupplementModel($app);
        $arr = $this->SupplementModel->autoCompleteSupplement();
        return json_encode($arr);
    }

    public function getId(Application $app){


        $this->SupplementModel = new SupplementModel($app);
        return $this->SupplementModel->getId($_POST['supplement']);

    }

    public function addSupplement(Application $app)
    {

        $this->SupplementModel = new SupplementModel($app);
        $supplement = $this->SupplementModel->getAllsupplement();
        return $app["twig"]->render('backOff/composant/supplement/v_form_create_supplement.html.twig', ['supplement' => $supplement]);
    }

    public function deleteSupplement(Application $app, $id)
    {
        $this->SupplementModel = new SupplementModel($app);

        $SupplementModel = $this->SupplementModel->getsupplement($id);
        return $app["twig"]->render('backOff/composant/supplement/v_form_delete_supplement.html.twig', ['donnees' => $SupplementModel]);
    }

    public function editSupplement(Application $app, $id)
    {
        $this->SupplementModel = new SupplementModel($app);
        $donnees = $this->SupplementModel->getSupplement($id);
        //var_dump($donnees);

        return $app["twig"]->render('backOff/composant/supplement/v_form_update_supplement.html.twig', ['donnees' => $donnees]);
    }

    public function validFormAddSupplement(Application $app)
    {

        if (1 == 1) {
            $donnees = [
                'type_supplement' => htmlspecialchars($_POST['type_supplement']),                    // echapper les entrées
                'supplement' => htmlspecialchars($_POST['supplement']),
            ];


            if ((!preg_match("/^[A-Za-z ]{2,}/", $donnees['type_supplement']))) $erreurs['type_supplement'] = 'libelle composé de 2 lettres minimum';
            if (!empty($erreurs)) {
                $this->SupplementModel = new SupplementModel($app);
                $supplement = $this->SupplementModel->getAllsupplement();
                return $app["twig"]->render('backOff/composant/supplement/v_form_create_supplement.html.twig', ['donnees' => $donnees, 'erreurs' => $erreurs, 'supplement' => $supplement]);
            } else {
                $this->SupplementModel = new SupplementModel($app);
                $this->SupplementModel->insertsupplement($donnees);
                if (isset($donnees['supplement'])) {
                    return $app->redirect($app["url_generator"]->generate("menu.add"));
                }else return $app->redirect($app["url_generator"]->generate("composant.index"));            }
        } else {
            return "probleme";
        }
    }

    public function validFormDeleteSupplement(Application $app, Request $req)
    {
        $donnees = [
            'id_supplement' => $app->escape($req->get('id')),
        ];

        $this->SupplementModel = new SupplementModel($app);
        $this->SupplementModel->deleteSupplement($donnees);
        return $app->redirect($app["url_generator"]->generate("composant.index"));
    }

    public function validFormEditSupplement(Application $app, Request $req)
    {

        $donnees = [
            'id_supplement' => htmlspecialchars($_POST['id_supplement']),
            'type_supplement' => htmlspecialchars($_POST['type_supplement']),                    // echapper les entrées

        ];

        if ((!preg_match("/^[A-Za-z ]{2,}/", $donnees['type_supplement']))) $erreurs['type_supplement'] = 'libelle composé de 2 lettres minimum';
        if (!empty($erreurs)) {
            $this->SupplementModel = new SupplementModel($app);
            $supplement = $this->SupplementModel->getAllSupplement();
            return $app["twig"]->render('backOff/composant/supplement/v_form_update_supplement.html.twig', ['donnees' => $donnees, 'erreurs' => $erreurs, 'supplement' => $supplement]);
        } else {
            $this->SupplementModel = new SupplementModel($app);
            $this->SupplementModel->updateSupplement($donnees);
            return $app->redirect($app["url_generator"]->generate("composant.index"));
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

        $controllers->get('/autoSupplement', 'App\Controller\SupplementController::autoCompleteSupplement')->bind('supplement.autoComplete');
        $controllers->get('/getId', 'App\Controller\SupplementController::getId')->bind('supplement.getId');

        $controllers->get('/add', 'App\Controller\SupplementController::addSupplement')->bind('supplement.add');
        $controllers->post('/add', 'App\Controller\SupplementController::validFormAddSupplement')->bind('supplement.validFormAddSupplement');

        $controllers->get('/delete{id}', 'App\Controller\SupplementController::deleteSupplement')->bind('supplement.delete');
        $controllers->delete('/delete', 'App\Controller\SupplementController::validFormDeleteSupplement')->bind('supplement.validFormDeleteSupplement');

        $controllers->get('/edit{id}', 'App\Controller\SupplementController::editSupplement')->bind('supplement.edit');
        $controllers->put('/edit', 'App\Controller\SupplementController::validFormEditSupplement')->bind('supplement.validFormEditSupplement');

        return $controllers;
    }

}