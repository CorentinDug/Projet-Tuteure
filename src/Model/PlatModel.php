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

    public function autoCompletePlat(){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->select('libelle_plat')
            ->from('plat');
        $result = $queryBuilder->execute()->fetchAll();
        $tab = array_map('current', $result);
        return $tab;
    }

    public function getLibelle($id){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('m.libelle_menu')
            ->from('menu','m')
            ->innerJoin('m','plat','p','p.id_plat = m.id_plat')
            ->where('d.id_plat='.$id);
        return $queryBuilder->execute()->fetchAll();
    }

    public function getId($plat)
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->select('id_plat')
            ->from('plat')
            ->where('libelle_plat= ?')
            ->setParameter(0,$plat);
        return $queryBuilder->execute()->fetch();
    }
}
