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

    public function getPlat($id){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('p.id_plat', 'p.libelle_plat')
            ->from('plat', 'p')
            ->where('p.id_plat='.$id);
        return $queryBuilder->execute()->fetch();

    }


    public function deletePlat($donnees){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->delete('plat')
            ->where('id_plat='.$donnees['id_plat']);
        return $queryBuilder->execute();
    }

    public function updatePlat($donnees)
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->update('Plat')
            ->set('libelle_plat' , '?')
            ->where("id_plat = ".$donnees['id_plat'])
            ->setParameter(0, $donnees['libelle_plat'])

        ;
        return $queryBuilder->execute();
    }


    public function autoCompletePlat(){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->select('libelle_plat')
            ->from('plat');
        $result = $queryBuilder->execute()->fetchAll();
        $tab = array_map('current', $result);
        return $tab;
    }

    public function insertPlat($donnees)
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->insert('PLAT')
            ->values(['libelle_plat' => '?'])
            ->setParameter(0, $donnees['libelle_plat']);
        return $queryBuilder->execute();
    }

    public function getLibelle($id){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('m.libelle_menu')
            ->from('menu','m')
            ->innerJoin('m','plat','p','p.id_plat = m.id_plat')
            ->where('p.id_plat='.$id);
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
