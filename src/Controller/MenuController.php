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
        if ($app['session']->get('roles') == 'ROLE_ADMIN'){
            return $app["twig"]->render("backOff/menu/v_table_menu.html.twig",['data'=>$menu]);
        }else if ($menu['role'] = $app['session']->get('roles') == 'ROLE_CLIENT') {
            return $app["twig"]->render("frontOff/menu/v_table_menu.html.twig", ['data' => $menu]);
        }else {
            $menu['role'] = $app['session']->get('roles');
            return $app["twig"]->render("frontOff/menu/v_table_menu.html.twig", ['data' => $menu]);
        }
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
        return $app["twig"]->render('backoff/menu/v_form_create_menu.html.twig',
            ['type'=>$type,'aperitif'=>$aperitif,'boisson'=>$boisson,'dessert'=>$dessert,
                'entree'=>$entree,'fromage'=>$fromage,'plat'=>$plat,'supplement'=>$supplement]);
    }

    public function getIds(Application $app){
        $this->aperitifModel = new AperitifModel($app);
        $this->boissonModel = new BoissonModel($app);
        $this->dessertModel = new DessertModel($app);
        $this->entreeModel = new EntreeModel($app);
        $this->fromageModel = new FromageModel($app);
        $this->platModel = new PlatModel($app);
        $this->supplementModel = new SupplementModel($app);
        $id_aperitif = $this->aperitifModel->getId($_POST['aperitif']);
        $id_boisson = $this->boissonModel->getId($_POST['boisson']);
        $id_dessert = $this->dessertModel->getId($_POST['dessert']);
        $id_entree = $this->entreeModel->getId($_POST['entree']);
        $id_fromage = $this->fromageModel->getId($_POST['fromage']);
        $id_plat = $this->platModel->getId($_POST['plat']);
        $id_supplement = $this->supplementModel->getId($_POST['supplement']);
        $donnees = [
            'id_aperitif' => $id_aperitif['id_aperitif'],
            'id_boisson' => $id_boisson['id_boisson'],
            'id_dessert' => $id_dessert['id_dessert'],
            'id_fromage' => $id_fromage['id_fromage'],
            'id_entree' => $id_entree['id_entree'],
            'id_plat' => $id_plat['id_plat'],
            'id_supplement' => $id_supplement['id_supplement'],
        ];
        if (isset ($_POST['creerPlat'])){
            return $this->validFormAdd($app,$donnees);
        }else if (isset($_POST['updatePlat'])){
            return $this->validFormUpdate($app,$donnees);
        }
    }

    public function validFormAdd(Application $app , $donnees){
        $this->helperDate = new HelperDate();

        $donnees += [
            'libelle_menu' => htmlspecialchars($_POST['libelle_menu']),                    // echapper les entrées
            'nbDispo' => htmlspecialchars($_POST['nbDispo']),
            'prix' => htmlspecialchars($_POST['prix']),
            'date_menu' => htmlspecialchars($_POST['date_menu']),
            'id_type' => htmlspecialchars($_POST['id_type']),
            'libelle_aperitif' => htmlspecialchars($_POST['aperitif']),
            'libelle_boisson' => htmlspecialchars($_POST['boisson']),
            'libelle_entree' => htmlspecialchars($_POST['entree']),
            'libelle_dessert' => htmlspecialchars($_POST['dessert']),
            'libelle_plat' => htmlspecialchars($_POST['plat']),
            'libelle_fromage' => htmlspecialchars($_POST['fromage']),
            'libelle_supplement' => htmlspecialchars($_POST['supplement']),

        ];

        if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['libelle_menu']))) $erreurs['libelle_menu']='libelle composé de 2 lettres minimum';
        if((!is_numeric($donnees['prix'])))$erreurs['prix']='saisir une valeur numérique';
        if((!is_numeric($donnees['nbDispo'])))$erreurs['nbDispo']='saisir une valeur numérique';
        if(!$this->helperDate->verifDate($donnees['date_menu'])) $erreurs['date_menu']='Saisir date au format JJ-MM-AAAA';
        if ($donnees['id_type'] == "") $erreurs['type']='choisir un type';
        if ($donnees['id_aperitif'] == null) $erreurs['aperitif']='choisir un aperitif';
        if ($donnees['id_entree'] == null) $erreurs['entree']='choisir une entree';
        if ($donnees['id_plat'] == null) $erreurs['plat']='choisir un plat';
        if ($donnees['id_fromage'] == null) $erreurs['fromage']='chosir un fromage';
        if ($donnees['id_dessert'] == null) $erreurs['dessert']='choisir un dessert';
        if ($donnees['id_boisson'] == null) $erreurs['boisson']='choisir une boisson';
        if ($donnees['id_supplement'] == null) $erreurs['supplement']='choisir une supplement';
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
            return $app["twig"]->render('backoff/menu/v_form_create_menu.html.twig',
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

    public function updateMenu(Application $app, $id){
        $this->menuModel = new MenuModel($app);
        $donnees = $this->menuModel->getMenu($id);
        //var_dump($donnees);

        $this->typeModel = new TypeModel($app);
        $type = $this->typeModel->getAllType();


        return $app["twig"]->render('backoff/menu/v_form_update_menu.html.twig',['donnees'=>$donnees ,'type' => $type]);

    }

    public function validFormUpdate(Application $app , $donnees){

        $this->helperDate = new HelperDate();

        $donnees += [
            'libelle_menu' => htmlspecialchars($_POST['libelle_menu']),                    // echapper les entrées
            'nbDispo' => htmlspecialchars($_POST['nbDispo']),
            'prix' => htmlspecialchars($_POST['prix']),
            'date_menu' => htmlspecialchars($_POST['date_menu']),
            'id_type' => htmlspecialchars($_POST['id_type']),
            'libelle_aperitif' => htmlspecialchars($_POST['aperitif']),
            'type_boisson' => htmlspecialchars($_POST['boisson']),
            'libelle_entree' => htmlspecialchars($_POST['entree']),
            'libelle_dessert' => htmlspecialchars($_POST['dessert']),
            'libelle_plat' => htmlspecialchars($_POST['plat']),
            'libelle_fromage' => htmlspecialchars($_POST['fromage']),
            'type_supplement' => htmlspecialchars($_POST['supplement']),
            'id_menu' => htmlspecialchars($_POST['id_menu']),

        ];
        var_dump($donnees);
        if ((! preg_match("/^[A-Za-z ]{2,}/",$donnees['libelle_menu']))) $erreurs['libelle_menu']='libelle composé de 2 lettres minimum';
        if((!is_numeric($donnees['prix'])))$erreurs['prix']='saisir une valeur numérique';
        if((!is_numeric($donnees['nbDispo'])))$erreurs['nbDispo']='saisir une valeur numérique';
        if(!$this->helperDate->verifDate($donnees['date_menu'])) $erreurs['date_menu']='Saisir date au format JJ-MM-AAAA';
        if ($donnees['id_type'] == "") $erreurs['type']='choisir un type';
        if ($donnees['id_aperitif'] == "") $erreurs['aperitif']='choisir un aperitif';
        if ($donnees['id_entree'] == "") $erreurs['entree']='choisir une entree';
        if ($donnees['id_plat'] == "") $erreurs['plat']='choisir un plat';
        if ($donnees['id_fromage'] == "") $erreurs['fromage']='choisir un fromage';
        if ($donnees['id_dessert'] == "") $erreurs['dessert']='choisir un dessert';
        if ($donnees['id_boisson'] == "") $erreurs['boisson']='choisir une boisson';
        if ($donnees['id_supplement'] == "") $erreurs['supplement']='choisir une supplement';
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
            return $app["twig"]->render('backOff/menu/v_form_update_menu.html.twig',
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

    public function deleteMenu(Application $app, $id){

        $this->menuModel = new MenuModel($app);
        $donnees = $this->menuModel->getMenu($id);
        return $app["twig"]->render('menu/v_form_delete_menu.html.twig',['donnees'=>$donnees]);
    }

    public function validFormDelete(Application $app, Request $req) {
        $id=$app->escape($req->get('id'));
        if (is_numeric($id)) {
            $this->menuModel = new MenuModel($app);
            $this->menuModel->deleteMenu($id);
            return $app->redirect($app["url_generator"]->generate("menu.index"));
        }
        else
            return $app->abort(404, 'error Pb id form Delete');
    }

    public function autoComplete(Application $app){
        $this->menuModel = new MenuModel($app);
        $arr = $this->menuModel->autoComplete();
        return json_encode($arr);
    }

    public function rechercheDateMenu(Application $app){
        $this->menuModel = new MenuModel($app);
        $this->helperDate = new HelperDate();
        $datenull = $_POST['dateMenu'];
        $date = $this->helperDate->convertFRtoUS($datenull);
        $menu = $this->menuModel->rechercheMenuDate($date);
        return $app["twig"]->render("frontOff/menu/v_table_menuDate.html.twig",['data'=>$menu]);

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
        $controllers->post('/add{donnees]', 'App\Controller\MenuController::validFormAdd')->bind('menu.validFormAdd');

        $controllers->get('/update{id}', 'App\Controller\MenuController::updateMenu')->bind('menu.update');
        $controllers->put('/update', 'App\Controller\MenuController::validFormUpdate')->bind('menu.validFormUpdate');

        $controllers->get('/edit{id}', 'App\Controller\MenuController::deleteMenu')->bind('menu.delete');
        $controllers->delete('/edit', 'App\Controller\MenuController::validFormDelete')->bind('menu.validFormDelete');

        $controllers->get('/auto','App\Controller\MenuController::autoComplete')->bind('menu.autoComplete');
        $controllers->post('/getIds','App\Controller\MenuController::getIds')->bind('menu.getIds');

        $controllers->post('/rechercheDateMenu','App\Controller\MenuController::rechercheDateMenu')->bind('menu.rechercheDateMenu');
        return $controllers;

        // TODO: Implement connect() method.
    }
}