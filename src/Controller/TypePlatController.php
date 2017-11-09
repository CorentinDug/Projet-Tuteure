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
use App\Model\TypePlatModel;
use App\Helper\HelperDate;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
class TypePlatController implements ControllerProviderInterface{

    private $typePlatModel;
    private $helperDate;



    public function index(Application $app) {
        return $this->showType($app);       // appel de la méthode show
    }

    public function showType(Application $app) {
        $this->typePlatModel = new TypePlatModel($app);
        $type = $this->typePlatModel->getAllTypePlat();
        return $app["twig"]->render('type/v_table_type.html.twig',['data'=>$type]);
    }
    public function home(Application $app){
        return $app["twig"]->render('type/v_admin.html.twig');
    }

    public function addTypePlat(Application $app) {

        $this->typePlatModel = new TypePlatModel($app);
        $typeType = $this->typePlatModel->getAllTypePlat();
        return $app["twig"]->render('type/v_form_create_type.html.twig',['typeType'=>$typeType]);
    }

    public function deleteTypePlat(Application $app, $id) {
        $this->typePlatModel = new TypePlatModel($app);

        $typePlatModel = $this->typePlatModel->getTypePlat($id);
        return $app["twig"]->render('type/v_form_delete_type.html.twig',['donnees'=>$typePlatModel]);
    }

    public function editTypePlat(Application $app,$id) {
        $this->typePlatModel = new TypePlatModel($app);
        $donnees = $this->typePlatModel->getTypePlat($id);
        //var_dump($donnees);

        return $app["twig"]->render('type/v_form_update_type.html.twig',['donnees'=>$donnees]);
    }

    public function validFormAddTypePlat(Application $app ) {
        //var_dump($app['request']->attributes);
        if (isset($_POST['_csrf_token'])) {
            $token = $_POST['_csrf_token'];
            $csrf_token = new CsrfToken('token_add_type', $token);
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
                'libelle' => htmlspecialchars($_POST['libelle']),                    // echapper les entrées

            ];


                if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['libelle']))) $erreurs['libelle']='libelle composé de 2 lettres minimum';
                if(! empty($erreurs))
                {
                    $this->typePlatModel = new TypePlatModel($app);
                    $typePlat = $this->typePlatModel->getAllTypePlat();
                    return $app["twig"]->render('type/v_form_create_type.html.twig',['donnees'=>$donnees,'erreurs'=>$erreurs,'typePlat'=>$typePlat]);
                }
                else
            {
                $this->typePlatModel = new TypePlatModel($app);
                $this->typePlatModel->insertType($donnees);
                return $app->redirect($app["url_generator"]->generate("typePlat.index"));
            }
        }
        else {
            return "probleme";
        }
    }

    public function validFormDeleteTypePlat(Application $app,Request $req)
    {
        //var_dump($app['request']->attributes);
        if (isset($_POST['_csrf_token'])) {
            $token = $_POST['_csrf_token'];
            $csrf_token = new CsrfToken('token_delete_type', $token);
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
            'idTypePlat' => $app->escape($req->get('id')),
        ];

        $this->typePlatModel = new TypePlatModel($app);
        $this->typePlatModel->deleteTypePlat($donnees);
        return $app->redirect($app["url_generator"]->generate("typePlat.index"));
    }

    public function validFormEditTypePlat(Application $app,Request $req){
        $this->helperDate = new HelperDate();
        if (isset($_POST['_csrf_token'])) {
            $token = $_POST['_csrf_token'];
            $csrf_token = new CsrfToken('token_edit_type', $token);
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
            'idTypePlat' => htmlspecialchars($_POST['id']),
            'libelle' => htmlspecialchars($_POST['libelle']),                    // echapper les entrées

        ];

        if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['libelle']))) $erreurs['libelle']='libelle composé de 2 lettres minimum';
        if(! empty($erreurs))
        {
            $this->typePlatModel = new TypePlatModel($app);
            $typePlat = $this->typePlatModel->getAllTypePlat();
            return $app["twig"]->render('type/v_form_update_type.html.twig',['donnees'=>$donnees,'erreurs'=>$erreurs,'typePlat'=>$typePlat]);
        }
        else
        {
            $this->typePlatModel = new TypePlatModel($app);
            $this->typePlatModel->updateTypePlat($donnees);
            return $app->redirect($app["url_generator"]->generate("typePlat.index"));
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

        $controllers->get('/', 'App\Controller\TypePlatController::index')->bind('typePlat.index');
        $controllers->get('/show', 'App\Controller\TypePlatController::showType')->bind('typePlat.show');

        $controllers->get('/home', 'App\Controller\TypePlatController::home')->bind('typePlat.home');

        $controllers->get('/add', 'App\Controller\TypePlatController::addTypePlat')->bind('typePlat.add');
        $controllers->post('/add', 'App\Controller\TypePlatController::validFormAddTypePlat')->bind('typePlat.validFormAddTypePlat');

        $controllers->get('/delete{id}', 'App\Controller\TypePlatController::deleteTypePlat')->bind('typePlat.delete');
        $controllers->delete('/delete', 'App\Controller\TypePlatController::validFormDeleteTypePlat')->bind('typePlat.validFormDeleteTypePlat');

        $controllers->get('/edit{id}', 'App\Controller\TypePlatController::editTypePlat')->bind('typePlat.edit');
        $controllers->put('/edit', 'App\Controller\TypePlatController::validFormEditTypePlat')->bind('typePlat.validFormEditTypePlat');

        return $controllers;
    }
}