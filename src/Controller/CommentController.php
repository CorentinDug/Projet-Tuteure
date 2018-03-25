<?php
namespace App\Controller;
use App\Model\CommentModel;
use App\Model\EntreeModel;
use App\Model\EtudiantModel;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Created by PhpStorm.
 * User: Ciryion
 * Date: 29/11/2017
 * Time: 21:31
 */
class CommentController implements ControllerProviderInterface
{

    private $commentModel;

    public function ajouterComment(Application $app)
    {
        $id = $_GET['id'];
        var_dump($id);
        return $app["twig"]->render("frontOff/Commentaire/v_form_add_comment.html.twig", ['id' => $id]);

    }

    public function validFormAddComment(Application $app)
    {
        $this->commentModel = new CommentModel($app);
        if (isset($_POST['comment'])) {
            $donnees = [
                'comment' => htmlspecialchars($_POST['comment']),
                'id_reservation' => htmlspecialchars($_POST['id_reservation'])
            ];

            $dateMenu = getDate($donnees['id_reservation']);
            $datejour = date('d-m-Y');

            var_dump($dateMenu);
            $dmenu = explode("-", $dateMenu);

            $djour = explode("-", $datejour);
            $finab = $dmenu[2] . $dmenu[1] . $dmenu[0];
            $auj = $djour[2] . $djour[1] . $djour[0];


            if ($auj < $finab) {

                $erreur['date'] = 'Erreur';

            }


            if (strlen($donnees['comment']) < 2) $erreur['comment'] = 'le commentaire doit contenir 2 caractere minimum';

            if (!empty($erreur)) {

                return $app["twig"]->render("frontOff/Commentaire/v_form_add_comment.html.twig", ['erreur' => $erreur]);


            } else {

                $donnees['id_client'] = $app['session']->get('id');
                $this->commentModel->addComment($donnees);
                return $app->redirect($app["url_generator"]->generate('profil.index'));

            }

        }
    }




    /**
     * Returns routes to connect to the given application.
     *
     * @param \Silex\Application $app An Application instance
     *
     * @return \Silex\ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];



        $controllers->get('/add', 'App\Controller\CommentController::ajouterComment')->bind('comment.addComment');
        $controllers->post('/add', 'App\Controller\CommentController::validFormAddComment')->bind('comment.validFormAddComment');





        return $controllers;


    }
}
