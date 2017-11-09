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

class BoissonModel
{
    private $db;

    public function __construct(Application $app)
    {
        $this->db = $app['db'];
    }

    public function getAllBoisson(){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('id_boisson','type_boisson')
            ->from('boisson')
            ->orderBy('id_boisson');
        return $queryBuilder->execute()->fetchAll();
    }
}
