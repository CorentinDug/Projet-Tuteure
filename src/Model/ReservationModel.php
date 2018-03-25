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
                'id_client' => '?',
            ])
            ->setParameter(0,$donnees['nbDispo'])
            ->setParameter(1,$donnees['id_menu'])
            ->setParameter(2,$donnees['id_client']);
        return $queryBuilder->execute();
    }

    public function getAllReserv()
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->select('r.id_reservation,r.nbplaces,r.id_client,r.id_menu,m.libelle_menu,u.username')
            ->from('reservation','r')
            ->innerJoin('r','menu','m','m.id_menu = r.id_menu')
            ->innerJoin('r','users','u','u.id = r.id_client')
            ->orderBy('id_reservation');
       return $queryBuilder->execute()->fetchAll();
    }

    public function getAllReservClient($id_Client)
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->select('r.id_reservation,r.nbplaces,r.id_client,r.id_menu,m.libelle_menu,u.username')
            ->from('reservation','r')
            ->innerJoin('r','menu','m','m.id_menu = r.id_menu')
            ->innerJoin('r','users','u','u.id = r.id_client')
            ->where('r.id_client = '.$id_Client)
            ->orderBy('id_reservation');
        return $queryBuilder->execute()->fetchAll();
    }

    public function getMail($id){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->select('email')
            ->from('users')
            ->where('id='.$id);
        return $queryBuilder->execute()->fetch();
    }
    /*
     * Permet de decrementer le nombre de place d'un menu
     *
     */
    public function mnbPlaces($donnees)
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->update('menu')
            ->set('nbDispo',('`nbDispo` - '.$donnees['nbDispo']) )
            ->where('id_menu = '.$donnees['id_menu']);
        return $queryBuilder->execute();

    }
}