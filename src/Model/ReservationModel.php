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
        $queryBuilder->execute();
    }
}