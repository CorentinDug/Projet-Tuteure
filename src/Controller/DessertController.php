<?php
namespace App\Controller;

use App\Model\DessertModel;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;

class DessertController implements ControllerProviderInterface
{

    private $DessertModel;

    public function autoCompleteDessert(Application $app){
        $this->dessertModel = new DessertModel($app);
        $arr = $this->dessertModel->autoCompleteDessert();
        return json_encode($arr);
    }

    public function getId(Application $app)
    {

        $this->dessertModel = new DessertModel($app);
        return $this->dessertModel->getId($_POST['dessert']);
    }

    public function index(Application $app)
    {
        return $this->showDessert($app);       // appel de la méthode show
    }

    public function home(Application $app)
    {
        return $app["twig"]->render('dessert/v_admin.html.twig');
    }

    public function addDessert(Application $app)
    {
        $this->DessertModel = new DessertModel($app);
        $dessert = $this->DessertModel->getAllDessert();
        return $app["twig"]->render('backOff/composant/dessert/v_form_create_dessert.html.twig', ['dessert' => $dessert]);
    }

    public function deleteDessert(Application $app, $id)
    {
        $this->DessertModel = new DessertModel($app);

        $DessertModel = $this->DessertModel->getDessert($id);
        return $app["twig"]->render('backOff/composant/dessert/v_form_delete_dessert.html.twig', ['donnees' => $DessertModel]);
    }

    public function editDessert(Application $app, $id)
    {
        $this->DessertModel = new DessertModel($app);
        $donnees = $this->DessertModel->getDessert($id);
        //var_dump($donnees);

        return $app["twig"]->render('backOff/composant/dessert/v_form_update_dessert.html.twig', ['donnees' => $donnees]);
    }

    public function validFormAddDessert(Application $app)
    {
        //var_dump($app['request']->attributes);

        if (1 == 1) {
            $donnees = [
                'libelle_dessert' => htmlspecialchars($_POST['libelle_dessert']),                    // echapper les entrées

            ];


            if ((!preg_match("/^[A-Za-z ]{2,}/", $donnees['libelle_dessert']))) $erreurs['libelle_dessert'] = 'libelle composé de 2 lettres minimum';
            if (!empty($erreurs)) {
                $this->DessertModel = new DessertModel($app);
                $dessert = $this->DessertModel->getAlldessert();
                return $app["twig"]->render('backOff/composant/dessert/v_form_create_dessert.html.twig', ['donnees' => $donnees, 'erreurs' => $erreurs, 'dessert' => $dessert]);
            } else {
                $this->DessertModel = new DessertModel($app);
                $this->DessertModel->insertdessert($donnees);
                return $app->redirect($app["url_generator"]->generate("composant.index"));
            }
        } else {
            return "probleme";
        }
    }

    public function validFormDeleteDessert(Application $app, Request $req)
    {
        //var_dump($app['request']->attributes);

        $id = $app->escape($req->get('id'));

        $this->DessertModel = new DessertModel($app);
        $this->DessertModel->deleteDessert($id);
        return $app->redirect($app["url_generator"]->generate("composant.index"));
    }

    public function validFormEditDessert(Application $app)
    {

        $donnees = [
            'id_dessert' => htmlspecialchars($_POST['id_dessert']),
            'libelle_dessert' => htmlspecialchars($_POST['libelle_dessert']),                    // echapper les entrées

        ];

        if ((!preg_match("/^[A-Za-z ]{2,}/", $donnees['libelle_dessert']))) $erreurs['libelle_dessert'] = 'libelle composé de 2 lettres minimum';
        if (!empty($erreurs)) {
            $this->DessertModel = new DessertModel($app);
            $dessert = $this->DessertModel->getAlldessert();
            return $app["twig"]->render('backOff/composant/dessert/v_form_update_dessert.html.twig', ['donnees' => $donnees, 'erreurs' => $erreurs, 'dessert' => $dessert]);
        } else {
            $this->DessertModel = new DessertModel($app);
            $this->DessertModel->updatedessert($donnees);
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

        $controllers->get('/autoDessert', 'App\Controller\DessertController::autoCompleteDessert')->bind('dessert.autoComplete');
        $controllers->get('/getId', 'App\Controller\DessertController::getId')->bind('dessert.getId');
        $controllers->get('/', 'App\Controller\DessertController::index')->bind('dessert.index');
        $controllers->get('/show', 'App\Controller\DessertController::showDessert')->bind('dessert.show');

        $controllers->get('/home', 'App\Controller\DessertController::home')->bind('dessert.home');

        $controllers->get('/add', 'App\Controller\DessertController::addDessert')->bind('dessert.addDessert');
        $controllers->post('/add', 'App\Controller\DessertController::validFormAddDessert')->bind('dessert.validFormAddDessert');

        $controllers->get('/delete{id}', 'App\Controller\DessertController::deleteDessert')->bind('dessert.delete');
        $controllers->delete('/delete', 'App\Controller\DessertController::validFormDeleteDessert')->bind('dessert.validFormDeleteDessert');

        $controllers->get('/edit{id}', 'App\Controller\DessertController::editDessert')->bind('dessert.edit');
        $controllers->put('/edit', 'App\Controller\DessertController::validFormEditDessert')->bind('dessert.validFormEditDessert');

        return $controllers;
    }

}