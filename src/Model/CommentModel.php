<?php
namespace App\Model;

use Doctrine\DBAL\Query\QueryBuilder;
use Silex\Application;

class CommentModel
{

    private $db;

    public function __construct(Application $app)
    {
        $this->db = $app['db'];
    }

    public function addComment($donnees)
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->insert('commentaires')
            ->values([
                'Commentaire' => '?',
                'id_client' =>'?',
                'id_reservation' =>'?',
                'date' =>'curdate()',
            ])
            ->where('id= ?')
            ->setParameter(0, $donnees['comment'])
            ->setParameter(1, $donnees['id_client'])
            ->setParameter(2, $donnees['id_reservation']);
        return $queryBuilder->execute();
    }

    public function getCommentClient($id_client,$id_reservation){

        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->select('Commentaire')
            ->from('commentaires')
            ->where('id_client='.$id_client,'id_reservation='.$id_reservation);
        return $queryBuilder->execute()->fetchAll();

    }

    public function getDate($id_reserv){

        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('m.date_menu')
            ->from('reservation', 'r')
            ->innerJoin('r', 'menu', 'm', 'r.id_menu=m.id_menu')
            ->where('r.id_reservation = '.$id_reserv);
        return $queryBuilder->execute()->fetchAll();

    }

}
