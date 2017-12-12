<?php
/**
 * Created by PhpStorm.
 * User: Ciryion
 * Date: 09/11/2017
 * Time: 18:50
 */
namespace App\Model;
use Doctrine\DBAL\Query\QueryBuilder;
use Silex\Application;

class ReservationModel
{
    private $db;

    public function __construct(Application $app)
    {
        $this->db = $app['db'];
    }
    public function createReservation($donnees)
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->insert('reservation')
            ->values([
                'nbplaces' => '?',
                'id_menu' => '?',
            ])
            ->setParameter(0,$donnees['nbDispo'])
            ->setParameter(1,$donnees['id_menu']);
        return $queryBuilder->execute();
    }

    public function getAllReserv()
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->select('r.id_reservation,r.nbplaces,r.id_client,r.id_menu,m.libelle_menu')
            ->from('reservation','r')
            ->innerJoin('r','menu','m','m.id_menu = r.id_menu')
            ->orderBy('id_reservation');
       return $queryBuilder->execute()->fetchAll();
    }

    /*
     * Permet de decrementer le nombre de place d'un menu
     *
     */
    public function mnbPlaces($donnees)
    {
        var_dump($donnees['nbDispo']);
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->update('menu')
            ->set('nbDispo',('`nbDispo` - '.$donnees['nbDispo']) )
            ->where('id_menu = '.$donnees['id_menu']);
        return $queryBuilder->execute();

    }
}