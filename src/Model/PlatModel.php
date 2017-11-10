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

class PlatModel
{
    private $db;

    public function __construct(Application $app)
    {
        $this->db = $app['db'];
    }

    public function getAllPlat(){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('id_plat','libelle_plat')
            ->from('plat')
            ->orderBy('id_plat');
        return $queryBuilder->execute()->fetchAll();
    }

}
