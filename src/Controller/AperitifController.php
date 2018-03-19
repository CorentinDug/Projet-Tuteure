<?php
namespace App\Controller;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use App\Model\AperitifModel;
use App\Helper\HelperDate;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
class AperitifController implements ControllerProviderInterface
{

    private $aperitifModel;

    public function home(Application $app)
    {
        return $app["twig"]->render('aperitif/v_admin.html.twig');
    }

    public function addAperitif(Application $app)
    {

        $this->aperitifModel = new AperitifModel($app);
        $aperitif = $this->aperitifModel->getAllAperitif();
        return $app["twig"]->render('backOff/composant/aperitif/v_form_create_aperitif.html.twig', ['aperitif' => $aperitif]);
    }

    public function deleteAperitif(Application $app, $id)
    {
        $this->aperitifModel = new AperitifModel($app);

        $aperitifModel = $this->aperitifModel->getAperitif($id);
        $libelle = $this->aperitifModel->getLibelle($id);

        return $app["twig"]->render('backOff/composant/aperitif/v_form_delete_aperitif.html.twig', ['donnees' => $aperitifModel,'libelle' => $libelle]);
    }

    public function editAperitif(Application $app, $id)
    {
        $this->aperitifModel = new AperitifModel($app);
        $donnees = $this->aperitifModel->getAperitif($id);
        //var_dump($donnees);

        return $app["twig"]->render('backOff/composant/aperitif/v_form_update_aperitif.html.twig', ['donnees' => $donnees]);
    }

    public function validFormAddAperitif(Application $app)
    {

        if (1 == 1) {
            $donnees = [
                'libelle_aperitif' => htmlspecialchars($_POST['libelle_aperitif']),                    // echapper les entrées
            ];


            if ((!preg_match("/^[A-Za-z ]{2,}/", $donnees['libelle_aperitif']))) $erreurs['libelle_aperitif'] = 'libelle composé de 2 lettres minimum';
            if (!empty($erreurs)) {
                $this->aperitifModel = new AperitifModel($app);
                $aperitif = $this->aperitifModel->getAllaperitif();
                return $app["twig"]->render('backOff/composant/aperitif/v_form_create_aperitif.html.twig', ['donnees' => $donnees, 'erreurs' => $erreurs, 'aperitif' => $aperitif]);
            } else {
                $this->aperitifModel = new AperitifModel($app);
                $this->aperitifModel->insertAperitif($donnees);
                return $app->redirect($app["url_generator"]->generate("composant.index"));
            }
        } else {
            return "probleme";
        }
    }

    public function validFormDeleteAperitif(Application $app, Request $req)
    {

        $donnees = [
            'id_aperitif' => $app->escape($req->get('id')),
        ];

        $this->aperitifModel = new AperitifModel($app);
        $this->aperitifModel->deleteaperitif($donnees);
        return $app->redirect($app["url_generator"]->generate("composant.index"));
    }

    public function validFormEditaperitif(Application $app, Request $req)
    {

        $donnees = [
            'id_aperitif' => htmlspecialchars($_POST['id_aperitif']),
            'libelle_aperitif' => htmlspecialchars($_POST['libelle_aperitif']),                    // echapper les entrées

        ];

        if ((!preg_match("/^[A-Za-z ]{2,}/", $donnees['libelle_aperitif']))) $erreurs['libelle_aperitif'] = 'libelle composé de 2 lettres minimum';
        if (!empty($erreurs)) {
            $this->aperitifModel = new AperitifModel($app);
            $aperitif = $this->aperitifModel->getAllaperitif();
            return $app["twig"]->render('backOff/composant/aperitif/v_form_update_aperitif.html.twig', ['donnees' => $donnees, 'erreurs' => $erreurs, 'aperitif' => $aperitif]);
        } else {
            $this->aperitifModel = new AperitifModel($app);
            $this->aperitifModel->updateAperitif($donnees);
            return $app->redirect($app["url_generator"]->generate("composant.index"));
        }


    }

    public function autoCompleteAperitif(Application $app){
        $this->aperitifModel = new AperitifModel($app);
        $arr = $this->aperitifModel->autoCompleteAperitif();
        return json_encode($arr);
    }

    public function getId(Application $app){
        $this->aperitifModel = new AperitifModel($app);
        return $this->aperitifModel->getId($_POST['aperitif']);
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

        $controllers->get('/show', 'App\Controller\AperitifController::showAperitif')->bind('aperitif.show');

        $controllers->get('/home', 'App\Controller\AperitifController::home')->bind('aperitif.home');

        $controllers->get('/add', 'App\Controller\AperitifController::addAperitif')->bind('aperitif.add');
        $controllers->post('/add', 'App\Controller\AperitifController::validFormAddAperitif')->bind('aperitif.validFormAddAperitif');

        $controllers->get('/delete{id}', 'App\Controller\AperitifController::deleteAperitif')->bind('aperitif.delete');
        $controllers->delete('/delete', 'App\Controller\AperitifController::validFormDeleteAperitif')->bind('aperitif.validFormDeleteAperitif');

        $controllers->get('/edit{id}', 'App\Controller\AperitifController::editAperitif')->bind('aperitif.edit');
        $controllers->put('/edit', 'App\Controller\AperitifController::validFormEditAperitif')->bind('aperitif.validFormEditAperitif');

        $controllers->get('/autoAperitif', 'App\Controller\AperitifController::autoCompleteAperitif')->bind('aperitif.autoComplete');
        $controllers->get('/getId', 'App\Controller\AperitifController::getId')->bind('aperitif.getId');

        return $controllers;
    }

}