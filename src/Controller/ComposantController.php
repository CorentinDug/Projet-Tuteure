<?php
namespace App\Controller;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use App\Model\AperitifModel;
use App\Model\EntreeModel;
use App\Model\BoissonModel;
use App\Model\DessertModel;
use App\Model\FromageModel;
use App\Model\PlatModel;
use App\Model\SupplementModel;
use App\Helper\HelperDate;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
class ComposantController implements ControllerProviderInterface
{

    private $BoissonModel;
    private $AperitifModel;
    private $EntreeModel;
    private $DessertModel;
    private $FromageModel;
    private $PlatModel;
    private $SupplementModel;
    private $helperDate;


    public function index(Application $app)
    {
        return $this->showComposant($app);       // appel de la méthode show
    }

    public function showComposant(Application $app)
    {
        $this->BoissonModel = new BoissonModel($app);
        $this->AperitifModel = new AperitifModel($app);
        $this->DessertModel = new DessertModel($app);
        $this->FromageModel = new FromageModel($app);
        $this->SupplementModel = new SupplementModel($app);
        $this->EntreeModel = new EntreeModel($app);
        $this->PlatModel = new PlatModel($app);
        $aperitif = $this->AperitifModel->getAllAperitif();
        $dessert = $this->DessertModel->getAllDessert();
        $fromage = $this->FromageModel->getAllFromage();
        $supplement = $this->SupplementModel->getAllSupplement();
        $entree = $this->EntreeModel->getAllEntree();
        $plat = $this->PlatModel->getAllPlat();
        $boisson = $this->BoissonModel->getAllBoisson();
        return $app["twig"]->render('backOff/composant/v_table_composant_menu.html.twig', ['boisson' => $boisson, 'aperitif' => $aperitif, 'dessert' => $dessert, 'fromage' => $fromage, 'plat' => $plat, 'entree' => $entree, 'supplement' => $supplement]);
    }


    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'App\Controller\ComposantController::index')->bind('composant.index');
        $controllers->get('/show', 'App\Controller\ComposantController::showComposant')->bind('composant.show');



        return $controllers;
    }
}