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

class FromageModel
{
    private $db;

    public function __construct(Application $app)
    {
        $this->db = $app['db'];
    }

    public function getAllFromage(){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('id_fromage','libelle_fromage')
            ->from('fromage')
            ->orderBy('id_fromage');
        return $queryBuilder->execute()->fetchAll();
    }
}
