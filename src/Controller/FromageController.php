<?php
namespace App\Controller;

use App\Model\FromageModel;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
class FromageController implements ControllerProviderInterface
{

    private $FromageModel;

    public function autoCompleteFromage(Application $app){
        $this->FromageModel = new FromageModel($app);
        $arr = $this->FromageModel->autoCompleteFromage();
        return json_encode($arr);
    }

    public function getId(Application $app){
        $this->FromageModel = new FromageModel($app);
        return $this->FromageModel->getId($_POST['fromage']);

    }

    public function addFromage(Application $app)
    {

        $this->FromageModel = new FromageModel($app);
        $fromage = $this->FromageModel->getAllfromage();
        return $app["twig"]->render('backOff/composant/fromage/v_form_create_fromage.html.twig', ['fromage' => $fromage]);
    }

    public function deleteFromage(Application $app, $id)
    {
        $this->FromageModel = new FromageModel($app);

        $FromageModel = $this->FromageModel->getfromage($id);
        return $app["twig"]->render('backOff/composant/fromage/v_form_delete_fromage.html.twig', ['donnees' => $FromageModel]);
    }

    public function editFromage(Application $app, $id)
    {
        $this->FromageModel = new FromageModel($app);
        $donnees = $this->FromageModel->getfromage($id);
        //var_dump($donnees);

        return $app["twig"]->render('backOff/composant/fromage/v_form_update_fromage.html.twig', ['donnees' => $donnees]);
    }

    public function validFormAddFromage(Application $app)
    {

        if (1 == 1) {
            $donnees = [
                'libelle_fromage' => htmlspecialchars($_POST['libelle_fromage']),                    // echapper les entrées

            ];


            if ((!preg_match("/^[A-Za-z ]{2,}/", $donnees['libelle_fromage']))) $erreurs['libelle_fromage'] = 'libelle composé de 2 lettres minimum';
            if (!empty($erreurs)) {
                $this->FromageModel = new FromageModel($app);
                $fromage = $this->FromageModel->getAllfromage();
                return $app["twig"]->render('backOff/composant/fromage/v_form_create_fromage.html.twig', ['donnees' => $donnees, 'erreurs' => $erreurs, 'fromage' => $fromage]);
            } else {
                $this->FromageModel = new FromageModel($app);
                $this->FromageModel->insertfromage($donnees);
                return $app->redirect($app["url_generator"]->generate("composant.index"));
            }
        } else {
            return "probleme";
        }
    }

    public function validFormDeleteFromage(Application $app, Request $req)
    {

        $donnees = [
            'id_fromage' => $app->escape($req->get('id')),
        ];

        $this->FromageModel = new FromageModel($app);
        $this->FromageModel->deleteFromage($donnees);
        return $app->redirect($app["url_generator"]->generate("composant.index"));
    }

    public function validFormEditFromage(Application $app, Request $req)
    {

        $donnees = [
            'id_fromage' => htmlspecialchars($_POST['id_fromage']),
            'libelle_fromage' => htmlspecialchars($_POST['libelle_fromage']),                    // echapper les entrées

        ];

        if ((!preg_match("/^[A-Za-z ]{2,}/", $donnees['libelle_fromage']))) $erreurs['libelle_fromage'] = 'libelle composé de 2 lettres minimum';
        if (!empty($erreurs)) {
            $this->FromageModel = new FromageModel($app);
            $fromage = $this->FromageModel->getAllfromage();
            return $app["twig"]->render('backOff/composant/fromage/v_form_update_fromage.html.twig', ['donnees' => $donnees, 'erreurs' => $erreurs, 'fromage' => $fromage]);
        } else {
            $this->FromageModel = new FromageModel($app);
            $this->FromageModel->updateFromage($donnees);
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

        $controllers->get('/autoFromage', 'App\Controller\FromageController::autoCompleteFromage')->bind('fromage.autoComplete');
        $controllers->get('/getId', 'App\Controller\FromageController::getId')->bind('fromage.getId');

        $controllers->get('/add', 'App\Controller\FromageController::addFromage')->bind('fromage.add');
        $controllers->post('/add', 'App\Controller\FromageController::validFormAddFromage')->bind('fromage.validFormAddFromage');

        $controllers->get('/delete{id}', 'App\Controller\FromageController::deleteFromage')->bind('fromage.delete');
        $controllers->delete('/delete', 'App\Controller\FromageController::validFormDeleteFromage')->bind('fromage.validFormDeleteFromage');

        $controllers->get('/edit{id}', 'App\Controller\FromageController::editFromage')->bind('fromage.edit');
        $controllers->put('/edit', 'App\Controller\FromageController::validFormEditFromage')->bind('fromage.validFormEditFromage');

        return $controllers;
    }

}