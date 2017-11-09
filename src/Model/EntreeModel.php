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

class EntreeModel
{
    private $db;

    public function __construct(Application $app)
    {
        $this->db = $app['db'];
    }

    public function getAllEntree(){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('id_entree','libelle_entree')
            ->from('entree')
            ->orderBy('id_entree');
        return $queryBuilder->execute()->fetchAll();
    }
}
