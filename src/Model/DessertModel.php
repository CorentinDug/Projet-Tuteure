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

class DessertModel
{
    private $db;

    public function __construct(Application $app)
    {
        $this->db = $app['db'];
    }

    public function getAllDessert(){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('id_dessert','libelle_dessert')
            ->from('dessert')
            ->orderBy('id_dessert');
        return $queryBuilder->execute()->fetchAll();
    }
}
