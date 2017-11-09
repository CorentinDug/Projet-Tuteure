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

class TypeModel
{
    private $db;

    public function __construct(Application $app)
    {
        $this->db = $app['db'];
    }

    public function getAllType(){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('id_type','libelle_type')
            ->from('type')
            ->orderBy('id_type');
        return $queryBuilder->execute()->fetchAll();
    }
}
