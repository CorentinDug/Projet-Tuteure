<?php

namespace App\Controller;

use App\Model\AperitifModel;
use App\Model\BoissonModel;
use App\Model\DessertModel;
use App\Model\EntreeModel;
use App\Model\FromageModel;
use App\Model\PlatModel;
use App\Model\SupplementModel;
use App\Model\TypeModel;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use App\Helper\HelperDate;
use App\Model\MenuModel;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
class menuController implements ControllerProviderInterface{

    private $menuModel;
    private $aperitifModel;
    private $boissonModel;
    private $dessertModel;
    private $entreeModel;
    private $fromageModel;
    private $platModel;
    private $typeModel;
    private $supplementModel;
    private $helperDate;

    public function index(Application $app) {
        return $this->showMenu($app);       // appel de la méthode show
    }

    public function showMenu(Application $app)
    {
        $this->menuModel = new MenuModel($app);
        $menu = $this->menuModel->getAllMenu();
        return $app["twig"]->render("menu/v_table_menu.html.twig",['data'=>$menu]);
    }

    public function addMenu(Application $app){
        $this->typeModel = new TypeModel($app);
        $type = $this->typeModel->getAllType();
        $this->aperitifModel = new AperitifModel($app);
        $aperitif = $this->aperitifModel->getAllAperitif();
        $this->boissonModel = new BoissonModel($app);
        $boisson = $this->boissonModel->getAllBoisson();
        $this->dessertModel = new DessertModel($app);
        $dessert = $this->dessertModel->getAllDessert();
        $this->entreeModel = new EntreeModel($app);
        $entree = $this->entreeModel->getAllEntree();
        $this->fromageModel = new FromageModel($app);
        $fromage = $this->fromageModel->getAllFromage();
        $this->platModel = new PlatModel($app);
        $plat = $this->platModel->getAllPlat();
        $this->supplementModel = new SupplementModel($app);
        $supplement = $this->supplementModel->getAllSupplement();
        return $app["twig"]->render('menu/v_form_create_menu.html.twig',
            ['type'=>$type,'aperitif'=>$aperitif,'boisson'=>$boisson,'dessert'=>$dessert,
                'entree'=>$entree,'fromage'=>$fromage,'plat'=>$plat,'supplement'=>$supplement]);
    }

    public function validFormAdd(Application $app){
        $this->helperDate = new HelperDate();
        if (isset($_POST['_csrf_token'])) {
            $token = $_POST['_csrf_token'];
            $csrf_token = new CsrfToken('token_add_menu', $token);
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
                'libelle_menu' => htmlspecialchars($_POST['libelle_menu']),                    // echapper les entrées
                'nbDispo' => htmlspecialchars($_POST['nbDispo']),
                'prix' => htmlspecialchars($_POST['prix']),
                'date_menu' => htmlspecialchars($_POST['date_menu']),
                'id_type' => htmlspecialchars($_POST['id_type']),
                'id_aperitif' => htmlspecialchars($_POST['id_aperitif']),
                'id_entree' => htmlspecialchars($_POST['id_entree']),
                'id_plat' => htmlspecialchars($_POST['id_plat']),
                'id_fromage' => htmlspecialchars($_POST['id_fromage']),
                'id_dessert' => htmlspecialchars($_POST['id_dessert']),
                'id_boisson' => htmlspecialchars($_POST['id_boisson']),
                'id_supplement' => htmlspecialchars($_POST['id_supplement']),

            ];


            if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['libelle_menu']))) $erreurs['libelle_menu']='libelle composé de 2 lettres minimum';
            if((!is_numeric($donnees['prix'])))$erreurs['prix']='saisir une valeur numérique';
            if((!is_numeric($donnees['nbDispo'])))$erreurs['nbDispo']='saisir une valeur numérique';
            if(!$this->helperDate->verifDate($donnees['date_menu'])) $erreurs['date_menu']='Saisir date au format JJ-MM-AAAA';
            if ($donnees['id_type'] == "") $erreurs['type']='chosir un type';
            if ($donnees['id_aperitif'] == "") $erreurs['aperitif']='chosir un aperitif';
            if ($donnees['id_entree'] == "") $erreurs['entree']='chosir une entree';
            if ($donnees['id_plat'] == "") $erreurs['plat']='chosir un plat';
            if ($donnees['id_fromage'] == "") $erreurs['fromage']='chosir un fromage';
            if ($donnees['id_dessert'] == "") $erreurs['dessert']='chosir un dessert';
            if ($donnees['id_boisson'] == "") $erreurs['boisson']='chosir une boisson';
            if ($donnees['id_supplement'] == "") $erreurs['supplement']='chosir une supplement';
            $donnees['date_menu'] = $this->helperDate->changeFormat($donnees['date_menu']);
            if(! empty($erreurs))
            {
                $this->typeModel = new TypeModel($app);
                $type = $this->typeModel->getAllType();
                $this->aperitifModel = new AperitifModel($app);
                $aperitif = $this->aperitifModel->getAllAperitif();
                $this->boissonModel = new BoissonModel($app);
                $boisson = $this->boissonModel->getAllBoisson();
                $this->dessertModel = new DessertModel($app);
                $dessert = $this->dessertModel->getAllDessert();
                $this->entreeModel = new EntreeModel($app);
                $entree = $this->entreeModel->getAllEntree();
                $this->fromageModel = new FromageModel($app);
                $fromage = $this->fromageModel->getAllFromage();
                $this->platModel = new PlatModel($app);
                $plat = $this->platModel->getAllPlat();
                $this->supplementModel = new SupplementModel($app);
                $supplement = $this->supplementModel->getAllSupplement();
                return $app["twig"]->render('menu/v_form_create_menu.html.twig',
                    ['donnees'=>$donnees,'erreurs'=>$erreurs,'type'=>$type,'aperitif'=>$aperitif,'boisson'=>$boisson,'dessert'=>$dessert,
                        'entree'=>$entree,'fromage'=>$fromage,'plat'=>$plat,'supplement'=>$supplement]);
            }
            else
            {
                $this->menuModel = new MenuModel($app);
                $this->menuModel->insertMenu($donnees);
                return $app->redirect($app["url_generator"]->generate("menu.index"));
            }
        }
        else {
            return "probleme";
        }
    }

    public function updateMenu(Application $app, $id){
        $this->menuModel = new MenuModel($app);
        $donnees = $this->menuModel->getMenu($id);
        //var_dump($donnees);

        $this->typeModel = new TypeModel($app);
        $type = $this->typeModel->getAllType();
        $this->aperitifModel = new AperitifModel($app);
        $aperitif = $this->aperitifModel->getAllAperitif();
        $this->boissonModel = new BoissonModel($app);
        $boisson = $this->boissonModel->getAllBoisson();
        $this->dessertModel = new DessertModel($app);
        $dessert = $this->dessertModel->getAllDessert();
        $this->entreeModel = new EntreeModel($app);
        $entree = $this->entreeModel->getAllEntree();
        $this->fromageModel = new FromageModel($app);
        $fromage = $this->fromageModel->getAllFromage();
        $this->platModel = new PlatModel($app);
        $plat = $this->platModel->getAllPlat();
        $this->supplementModel = new SupplementModel($app);
        $supplement = $this->supplementModel->getAllSupplement();

        return $app["twig"]->render('menu/v_form_update_menu.html.twig',['donnees'=>$donnees, 'plat'=>$plat,'type'=>$type
        ,'aperitif'=>$aperitif,'boisson'=>$boisson,'dessert'=>$dessert,'entree'=>$entree,'fromage'=>$fromage,'supplement'=>$supplement]);

    }

    public function validFormUpdate(Application $app){

        $this->helperDate = new HelperDate();
        if (isset($_POST['_csrf_token'])) {
            $token = $_POST['_csrf_token'];
            $csrf_token = new CsrfToken('token_update_menu', $token);
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
            'id_menu' => htmlspecialchars($_POST['id_menu']),
            'libelle_menu' => htmlspecialchars($_POST['libelle_menu']),                    // echapper les entrées
            'nbDispo' => htmlspecialchars($_POST['nbDispo']),
            'prix' => htmlspecialchars($_POST['prix']),
            'date_menu' => htmlspecialchars($_POST['date_menu']),
            'id_type' => htmlspecialchars($_POST['id_type']),
            'id_aperitif' => htmlspecialchars($_POST['id_aperitif']),
            'id_entree' => htmlspecialchars($_POST['id_entree']),
            'id_plat' => htmlspecialchars($_POST['id_plat']),
            'id_fromage' => htmlspecialchars($_POST['id_fromage']),
            'id_dessert' => htmlspecialchars($_POST['id_dessert']),
            'id_boisson' => htmlspecialchars($_POST['id_boisson']),
            'id_supplement' => htmlspecialchars($_POST['id_supplement']),

        ];

        if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['libelle_menu']))) $erreurs['libelle_menu']='libelle composé de 2 lettres minimum';
        if((!is_numeric($donnees['prix'])))$erreurs['prix']='saisir une valeur numérique';
        if((!is_numeric($donnees['nbDispo'])))$erreurs['nbDispo']='saisir une valeur numérique';
        if(!$this->helperDate->verifDate($donnees['date_menu'])) $erreurs['date_menu']='Saisir date au format JJ-MM-AAAA';
        if ($donnees['id_type'] == "") $erreurs['type']='chosir un type';
        if ($donnees['id_aperitif'] == "") $erreurs['aperitif']='chosir un aperitif';
        if ($donnees['id_entree'] == "") $erreurs['entree']='chosir une entree';
        if ($donnees['id_plat'] == "") $erreurs['plat']='chosir un plat';
        if ($donnees['id_fromage'] == "") $erreurs['fromage']='chosir un fromage';
        if ($donnees['id_dessert'] == "") $erreurs['dessert']='chosir un dessert';
        if ($donnees['id_boisson'] == "") $erreurs['boisson']='chosir une boisson';
        if ($donnees['id_supplement'] == "") $erreurs['supplement']='chosir une supplement';
        $donnees['date_menu'] = $this->helperDate->changeFormat($donnees['date_menu']);

        if(! empty($erreurs))
        {
            $this->typeModel = new TypeModel($app);
            $type = $this->typeModel->getAllType();
            $this->aperitifModel = new AperitifModel($app);
            $aperitif = $this->aperitifModel->getAllAperitif();
            $this->boissonModel = new BoissonModel($app);
            $boisson = $this->boissonModel->getAllBoisson();
            $this->dessertModel = new DessertModel($app);
            $dessert = $this->dessertModel->getAllDessert();
            $this->entreeModel = new EntreeModel($app);
            $entree = $this->entreeModel->getAllEntree();
            $this->fromageModel = new FromageModel($app);
            $fromage = $this->fromageModel->getAllFromage();
            $this->platModel = new PlatModel($app);
            $plat = $this->platModel->getAllPlat();
            $this->supplementModel = new SupplementModel($app);
            $supplement = $this->supplementModel->getAllSupplement();
            return $app["twig"]->render('menu/v_form_update_menu.html.twig',
                ['donnees'=>$donnees,'erreurs'=>$erreurs,'type'=>$type,'aperitif'=>$aperitif,'boisson'=>$boisson,'dessert'=>$dessert,
                    'entree'=>$entree,'fromage'=>$fromage,'plat'=>$plat,'supplement'=>$supplement]);
        }
        else
        {
            $this->menuModel = new MenuModel($app);
            $this->menuModel->updateMenu($donnees);
            return $app->redirect($app["url_generator"]->generate("menu.index"));
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

        $controllers->get('/', 'App\Controller\MenuController::index')->bind('menu.index');
        $controllers->get('/show', 'App\Controller\MenuController::showMenu')->bind('menu.show');

        $controllers->get('/add', 'App\Controller\MenuController::addMenu')->bind('menu.add');
        $controllers->post('/add', 'App\Controller\MenuController::validFormAdd')->bind('menu.validFormAdd');

        $controllers->get('/update{id}', 'App\Controller\MenuController::updateMenu')->bind('menu.update');
        $controllers->put('/update', 'App\Controller\MenuController::validFormUpdate')->bind('menu.validFormUpdate');

        $controllers->get('/edit{id}', 'App\Controller\MenuController::deleteMenu')->bind('menu.delete');
        $controllers->delete('/edit', 'App\Controller\MenuController::validFormDelete')->bind('menu.validFormDelete');

        return $controllers;

        // TODO: Implement connect() method.
    }
}