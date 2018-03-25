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

    public function afficherComment(Application $app){
         $this->commentModel = new CommentModel($app);
        $id_reservation = $_GET['id_reservation'];
        $comment = $this->commentModel->getCommentClient($app['session']->get('id'),$id_reservation);
        return $app['twig']->render("frontOff/Commentaire/v_table_comment.html.twig",['comment' => $comment,'id_reservation' => $id_reservation]);

    }

    public function ajouterComment(Application $app)
    {
        $id = $_GET['id_reservation'];
        return $app["twig"]->render("frontOff/Commentaire/v_form_add_comment.html.twig", ['id_reservation' => $id]);

    }

    public function validFormAddComment(Application $app)
    {
        $this->commentModel = new CommentModel($app);
        if (isset($_POST['comment'])) {
            $donnees = [
                'comment' => htmlspecialchars($_POST['comment']),
                'id_reservation' => htmlspecialchars($_POST['id_reservation'])
            ];

            $dateMenu = $this->commentModel->getDate($donnees['id_reservation']);
            $datejour = date('d/m/Y');

            $dmenu = explode("-", $dateMenu[0]['date_menu']);

            $djour = explode("/", $datejour);
            $finab = $dmenu[0] . $dmenu[1] . $dmenu[2];
            $auj = $djour[2] . $djour[1] . $djour[0];

            if ($auj < $finab) {

                $erreur['date'] = 'Vous n\'avez pas assisté au repas';

            }


            if (strlen($donnees['comment']) < 2) $erreur['comment'] = 'le commentaire doit contenir 2 caractere minimum';

            if (!empty($erreur)) {

                return $app["twig"]->render("frontOff/Commentaire/v_form_add_comment.html.twig", ['erreur' => $erreur, 'comment' => $donnees['comment'], 'id_reservation' =>$donnees['id_reservation']]);


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


        $controllers->get('/affiche','App\Controller\CommentController::afficherComment')->bind('comment.afficherComment');
        $controllers->get('/add', 'App\Controller\CommentController::ajouterComment')->bind('comment.addComment');
        $controllers->post('/add', 'App\Controller\CommentController::validFormAddComment')->bind('comment.validFormAddComment');





        return $controllers;


    }
}
