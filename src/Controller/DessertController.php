<?php
namespace App\Controller;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use App\Model\DessertModel;
use App\Helper\HelperDate;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
class DessertController implements ControllerProviderInterface
{

    private $DessertModel;
    private $helperDate;


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
        return $app["twig"]->render('dessert/v_form_create_dessert.html.twig', ['dessert' => $dessert]);
    }

    public function deleteDessert(Application $app, $id)
    {
        $this->DessertModel = new DessertModel($app);

        $DessertModel = $this->DessertModel->getDessert($id);
        return $app["twig"]->render('dessert/v_form_delete_dessert.html.twig', ['donnees' => $DessertModel]);
    }

    public function editDessert(Application $app, $id)
    {
        $this->DessertModel = new DessertModel($app);
        $donnees = $this->DessertModel->getDessert($id);
        //var_dump($donnees);

        return $app["twig"]->render('dessert/v_form_update_dessert.html.twig', ['donnees' => $donnees]);
    }

    public function validFormAddDessert(Application $app)
    {
        //var_dump($app['request']->attributes);
        if (isset($_POST['_csrf_token'])) {
            $token = $_POST['_csrf_token'];
            $csrf_token = new CsrfToken('token_add_dessert', $token);
            $csrf_token_ok = $app['csrf.token_manager']->isTokenValid($csrf_token);
            if (!$csrf_token_ok) {
                $erreurs["csrf"] = "Erreur : token : " . $token;
                return $app["twig"]->render("v_error_csrf.html.twig", ['erreurs' => $erreurs]);
            }
        } else
            return $app->redirect($app["url_generator"]->generate("index.errorCsrf"));

        if (1 == 1) {
            $donnees = [
                'libelle_dessert' => htmlspecialchars($_POST['libelle_dessert']),                    // echapper les entrées

            ];


            if ((!preg_match("/^[A-Za-z ]{2,}/", $donnees['libelle']))) $erreurs['libelle'] = 'libelle composé de 2 lettres minimum';
            if (!empty($erreurs)) {
                $this->DessertModel = new DessertModel($app);
                $dessert = $this->DessertModel->getAlldessert();
                return $app["twig"]->render('dessert/v_form_create_dessert.html.twig', ['donnees' => $donnees, 'erreurs' => $erreurs, 'dessert' => $dessert]);
            } else {
                $this->DessertModel = new DessertModel($app);
                $this->DessertModel->insertdessert($donnees);
                return $app->redirect($app["url_generator"]->generate("dessert.index"));
            }
        } else {
            return "probleme";
        }
    }

    public function validFormDeleteDessert(Application $app, Request $req)
    {
        //var_dump($app['request']->attributes);
        if (isset($_POST['_csrf_token'])) {
            $token = $_POST['_csrf_token'];
            $csrf_token = new CsrfToken('token_delete_dessert', $token);
            $csrf_token_ok = $app['csrf.token_manager']->isTokenValid($csrf_token);
            if (!$csrf_token_ok) {
                $erreurs["csrf"] = "Erreur : token : " . $token;
                return $app["twig"]->render("v_error_csrf.html.twig", ['erreurs' => $erreurs]);
            }
        } else
            return $app->redirect($app["url_generator"]->generate("index.errorCsrf"));

        $donnees = [
            'idDessert' => $app->escape($req->get('id')),
        ];

        $this->DessertModel = new DessertModel($app);
        $this->DessertModel->deletedessert($donnees);
        return $app->redirect($app["url_generator"]->generate("dessert.index"));
    }

    public function validFormEditDessert(Application $app, Request $req)
    {
        $this->helperDate = new HelperDate();
        if (isset($_POST['_csrf_token'])) {
            $token = $_POST['_csrf_token'];
            $csrf_token = new CsrfToken('token_edit_dessert', $token);
            $csrf_token_ok = $app['csrf.token_manager']->isTokenValid($csrf_token);
            if (!$csrf_token_ok) {
                $erreurs["csrf"] = "Erreur : token : " . $token;
                return $app["twig"]->render("v_error_csrf.html.twig", ['erreurs' => $erreurs]);
            }
        } else
            return $app->redirect($app["url_generator"]->generate("index.errorCsrf"));

        $donnees = [
            'idDessert' => htmlspecialchars($_POST['id']),
            'libelle_dessert' => htmlspecialchars($_POST['libelle']),                    // echapper les entrées

        ];

        if ((!preg_match("/^[A-Za-z ]{2,}/", $donnees['libelle']))) $erreurs['libelle'] = 'libelle composé de 2 lettres minimum';
        if (!empty($erreurs)) {
            $this->DessertModel = new DessertModel($app);
            $dessert = $this->DessertModel->getAlldessert();
            return $app["twig"]->render('dessert/v_form_update_dessert.html.twig', ['donnees' => $donnees, 'erreurs' => $erreurs, 'dessert' => $dessert]);
        } else {
            $this->DessertModel = new DessertModel($app);
            $this->DessertModel->updatedessert($donnees);
            return $app->redirect($app["url_generator"]->generate("dessert.index"));
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