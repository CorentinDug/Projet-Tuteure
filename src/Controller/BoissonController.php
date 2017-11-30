<?php
namespace App\Controller;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use App\Model\BoissonModel;
use App\Helper\HelperDate;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
class BoissonController implements ControllerProviderInterface
{

    private $BoissonModel;
    private $helperDate;


    public function index(Application $app)
    {
        return $this->showboisson($app);       // appel de la méthode show
    }

    public function showBoisson(Application $app)
    {
        $this->BoissonModel = new BoissonModel($app);
        $boisson = $this->BoissonModel->getAllBoisson();
        return $app["twig"]->render('backOff/composant/boisson/v_table_boisson_menu.html.twig', ['boisson' => $boisson]);
    }

    public function home(Application $app)
    {
        return $app["twig"]->render('boisson/v_admin.html.twig');
    }

    public function addBoisson(Application $app)
    {

        $this->BoissonModel = new BoissonModel($app);
        $boisson = $this->BoissonModel->getAllBoisson();
        return $app["twig"]->render('backOff/composant/boisson/v_form_create_boisson.html.twig', ['boisson' => $boisson]);
    }

    public function deleteBoisson(Application $app, $id)
    {
        $this->BoissonModel = new BoissonModel($app);

        $boissonModel = $this->BoissonModel->getBoisson($id);
        return $app["twig"]->render('backOff/composant/boisson/v_form_delete_boisson.html.twig', ['donnees' => $boissonModel]);
    }

    public function editBoisson(Application $app, $id)
    {
        $this->BoissonModel = new BoissonModel($app);
        $donnees = $this->BoissonModel->getBoisson($id);
        //var_dump($donnees);

        return $app["twig"]->render('backOff/composant/boisson/v_form_update_boisson.html.twig', ['donnees' => $donnees]);
    }

    public function validFormAddBoisson(Application $app)
    {

        if (1 == 1) {
            $donnees = [
                'type_boisson' => htmlspecialchars($_POST['type_boisson']),                    // echapper les entrées

            ];


            if ((!preg_match("/^[A-Za-z ]{2,}/", $donnees['type_boisson']))) $erreurs['type_boisson'] = 'libelle composé de 2 lettres minimum';
            if (!empty($erreurs)) {
                $this->BoissonModel = new BoissonModel($app);
                $Boisson = $this->BoissonModel->getAllBoisson();
                return $app["twig"]->render('backOff/composant/boisson/v_form_create_boisson.html.twig', ['donnees' => $donnees, 'erreurs' => $erreurs, 'Boisson' => $Boisson]);
            } else {
                $this->BoissonModel = new BoissonModel($app);
                $this->BoissonModel->insertboisson($donnees);
                return $app->redirect($app["url_generator"]->generate("composant.index"));
            }
        } else {
            return "probleme";
        }
    }

    public function validFormDeleteBoisson(Application $app, Request $req)
    {
        $donnees = [
            'id_boisson' => $app->escape($req->get('id')),
        ];

        $this->BoissonModel = new BoissonModel($app);
        $this->BoissonModel->deleteBoisson($donnees);
        return $app->redirect($app["url_generator"]->generate("composant.index"));
    }

    public function validFormEditBoisson(Application $app, Request $req)
    {

        $donnees = [
            'id_boisson' => htmlspecialchars($_POST['id_boisson']),
            'type_boisson' => htmlspecialchars($_POST['type_boisson']),                    // echapper les entrées

        ];

        if ((!preg_match("/^[A-Za-z ]{2,}/", $donnees['type_boisson']))) $erreurs['type_boisson'] = 'libelle composé de 2 lettres minimum';
        if (!empty($erreurs)) {
            $this->BoissonModel = new BoissonModel($app);
            $Boisson = $this->BoissonModel->getAllBoisson();
            return $app["twig"]->render('backOff/composant/boisson/v_form_update_boisson.html.twig', ['donnees' => $donnees, 'erreurs' => $erreurs, 'Boisson' => $Boisson]);
        } else {
            $this->BoissonModel = new BoissonModel($app);
            $this->BoissonModel->updateBoisson($donnees);
            return $app->redirect($app["url_generator"]->generate("composant.index"));
        }
    }

    public function autoCompleteBoisson(Application $app){
        $this->BoissonModel = new BoissonModel($app);
        $arr = $this->BoissonModel->autoCompleteBoisson();
        return json_encode($arr);
    }

    public function getId(Application $app){
        $this->BoissonModel = new BoissonModel($app);
        return $this->BoissonModel->getId($_POST['boisson']);
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

        $controllers->get('/', 'App\Controller\BoissonController::index')->bind('boisson.index');
        $controllers->get('/show', 'App\Controller\BoissonController::showBoisson')->bind('boisson.show');

        $controllers->get('/home', 'App\Controller\BoissonController::home')->bind('boisson.home');

        $controllers->get('/add', 'App\Controller\BoissonController::addBoisson')->bind('boisson.add');
        $controllers->post('/add', 'App\Controller\BoissonController::validFormAddBoisson')->bind('boisson.validFormAddBoisson');

        $controllers->get('/delete{id}', 'App\Controller\BoissonController::deleteBoisson')->bind('boisson.delete');
        $controllers->delete('/delete', 'App\Controller\BoissonController::validFormDeleteBoisson')->bind('boisson.validFormDeleteBoisson');

        $controllers->get('/edit{id}', 'App\Controller\BoissonController::editBoisson')->bind('boisson.edit');
        $controllers->put('/edit', 'App\Controller\BoissonController::validFormEditBoisson')->bind('boisson.validFormEditBoisson');

        $controllers->get('/autoBoisson', 'App\Controller\BoissonController::autoCompleteBoisson')->bind('boisson.autoComplete');
        $controllers->get('/getId', 'App\Controller\BoissonController::getId')->bind('boisson.getId');
        return $controllers;
    }

}