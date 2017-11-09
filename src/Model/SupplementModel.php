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

class SupplementModel
{
    private $db;

    public function __construct(Application $app)
    {
        $this->db = $app['db'];
    }

    public function getAllSupplement(){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('id_supplement','type_supplement')
            ->from('supplement')
            ->orderBy('id_supplement');
        return $queryBuilder->execute()->fetchAll();
    }
}
