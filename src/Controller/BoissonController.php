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

    public function showboisson(Application $app)
    {
        $this->BoissonModel = new BoissonModel($app);
        $boisson = $this->BoissonModel->getAllBoisson();
        return $app["twig"]->render('boisson/v_table_boisson.html.twig', ['data' => $boisson]);
    }

    public function home(Application $app)
    {
        return $app["twig"]->render('boisson/v_admin.html.twig');
    }

    public function addBoisson(Application $app)
    {

        $this->BoissonModel = new BoissonModel($app);
        $boisson = $this->BoissonModel->getAllBoisson();
        return $app["twig"]->render('boisson/v_form_create_boisson.html.twig', ['boisson' => $boisson]);
    }

    public function deleteBoisson(Application $app, $id)
    {
        $this->BoissonModel = new BoissonModel($app);

        $BoissonModel = $this->BoissonModel->getBoisson($id);
        return $app["twig"]->render('boisson/v_form_delete_boisson.html.twig', ['donnees' => $BoissonModel]);
    }

    public function editBoisson(Application $app, $id)
    {
        $this->BoissonModel = new BoissonModel($app);
        $donnees = $this->BoissonModel->getBoisson($id);
        //var_dump($donnees);

        return $app["twig"]->render('boisson/v_form_update_boisson.html.twig', ['donnees' => $donnees]);
    }

    public function validFormAddBoisson(Application $app)
    {
        //var_dump($app['request']->attributes);
        if (isset($_POST['_csrf_token'])) {
            $token = $_POST['_csrf_token'];
            $csrf_token = new CsrfToken('token_add_boisson', $token);
            $csrf_token_ok = $app['csrf.token_manager']->isTokenValid($csrf_token);
            if (!$csrf_token_ok) {
                $erreurs["csrf"] = "Erreur : token : " . $token;
                return $app["twig"]->render("v_error_csrf.html.twig", ['erreurs' => $erreurs]);
            }
        } else
            return $app->redirect($app["url_generator"]->generate("index.errorCsrf"));

        if (1 == 1) {
            $donnees = [
                'libelle_boisson' => htmlspecialchars($_POST['libelle_boisson']),                    // echapper les entrées

            ];


            if ((!preg_match("/^[A-Za-z ]{2,}/", $donnees['libelle']))) $erreurs['libelle'] = 'libelle composé de 2 lettres minimum';
            if (!empty($erreurs)) {
                $this->BoissonModel = new BoissonModel($app);
                $Boisson = $this->BoissonModel->getAllBoisson();
                return $app["twig"]->render('boisson/v_form_create_boisson.html.twig', ['donnees' => $donnees, 'erreurs' => $erreurs, 'Boisson' => $Boisson]);
            } else {
                $this->BoissonModel = new BoissonModel($app);
                $this->BoissonModel->insertboisson($donnees);
                return $app->redirect($app["url_generator"]->generate("Boisson.index"));
            }
        } else {
            return "probleme";
        }
    }

    public function validFormDeleteBoisson(Application $app, Request $req)
    {
        //var_dump($app['request']->attributes);
        if (isset($_POST['_csrf_token'])) {
            $token = $_POST['_csrf_token'];
            $csrf_token = new CsrfToken('token_delete_boisson', $token);
            $csrf_token_ok = $app['csrf.token_manager']->isTokenValid($csrf_token);
            if (!$csrf_token_ok) {
                $erreurs["csrf"] = "Erreur : token : " . $token;
                return $app["twig"]->render("v_error_csrf.html.twig", ['erreurs' => $erreurs]);
            }
        } else
            return $app->redirect($app["url_generator"]->generate("index.errorCsrf"));

        $donnees = [
            'idBoisson' => $app->escape($req->get('id')),
        ];

        $this->BoissonModel = new BoissonModel($app);
        $this->BoissonModel->deleteBoisson($donnees);
        return $app->redirect($app["url_generator"]->generate("Boisson.index"));
    }

    public function validFormEditBoisson(Application $app, Request $req)
    {
        $this->helperDate = new HelperDate();
        if (isset($_POST['_csrf_token'])) {
            $token = $_POST['_csrf_token'];
            $csrf_token = new CsrfToken('token_edit_boisson', $token);
            $csrf_token_ok = $app['csrf.token_manager']->isTokenValid($csrf_token);
            if (!$csrf_token_ok) {
                $erreurs["csrf"] = "Erreur : token : " . $token;
                return $app["twig"]->render("v_error_csrf.html.twig", ['erreurs' => $erreurs]);
            }
        } else
            return $app->redirect($app["url_generator"]->generate("index.errorCsrf"));

        $donnees = [
            'idBoisson' => htmlspecialchars($_POST['id']),
            'libelle' => htmlspecialchars($_POST['libelle']),                    // echapper les entrées

        ];

        if ((!preg_match("/^[A-Za-z ]{2,}/", $donnees['libelle']))) $erreurs['libelle'] = 'libelle composé de 2 lettres minimum';
        if (!empty($erreurs)) {
            $this->BoissonModel = new BoissonModel($app);
            $Boisson = $this->BoissonModel->getAllBoisson();
            return $app["twig"]->render('boisson/v_form_update_boisson.html.twig', ['donnees' => $donnees, 'erreurs' => $erreurs, 'Boisson' => $Boisson]);
        } else {
            $this->BoissonModel = new BoissonModel($app);
            $this->BoissonModel->updateBoisson($donnees);
            return $app->redirect($app["url_generator"]->generate("Boisson.index"));
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

        $controllers->get('/', 'App\Controller\BoissonController::index')->bind('Boisson.index');
        $controllers->get('/show', 'App\Controller\BoissonController::showBoisson')->bind('Boisson.show');

        $controllers->get('/home', 'App\Controller\BoissonController::home')->bind('Boisson.home');

        $controllers->get('/add', 'App\Controller\BoissonController::addBoisson')->bind('Boisson.add');
        $controllers->post('/add', 'App\Controller\BoissonController::validFormAddBoisson')->bind('Boisson.validFormAddBoisson');

        $controllers->get('/delete{id}', 'App\Controller\BoissonController::deleteBoisson')->bind('Boisson.delete');
        $controllers->delete('/delete', 'App\Controller\BoissonController::validFormDeleteBoisson')->bind('Boisson.validFormDeleteBoisson');

        $controllers->get('/edit{id}', 'App\Controller\BoissonController::editBoisson')->bind('Boisson.edit');
        $controllers->put('/edit', 'App\Controller\BoissonController::validFormEditBoisson')->bind('Boisson.validFormEditBoisson');

        return $controllers;
    }

}