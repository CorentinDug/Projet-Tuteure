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

    public function getDessert($id){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('d.id_dessert', 'd.libelle_dessert')
            ->from('DESSERT', 'd')
            ->where('d.id_dessert='.$id);
        return $queryBuilder->execute()->fetch();

    }
    public function insertDessert($donnees){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->insert('DESSERT')
            ->values(['libelle_dessert' => '?'])
            ->setParameter(0,$donnees['libelle_dessert']);
        return $queryBuilder->execute();
    }
    public function updateDessert($donnees)
    {
        var_dump($donnees);
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->update('DESSERT')
            ->set('libelle_dessert' , '?')
            ->where("id_dessert = ".$donnees['id_dessert'])
            ->setParameter(0, $donnees['libelle_dessert'])

        ;
        return $queryBuilder->execute();
    }
    public function deleteDessert($donnees){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->delete('DESSERT')
            ->where('id_dessert='.$donnees['id_dessert']);
        return $queryBuilder->execute();
    }

    public function autoCompleteDessert()
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->select('libelle_dessert')
            ->from('dessert');
        $result = $queryBuilder->execute()->fetchAll();
        $tab = array_map('current', $result);
        return $tab;
    }

    public function getId($dessert)
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->select('id_dessert')
            ->from('dessert')
            ->where('libelle_dessert= ?')
            ->setParameter(0,$dessert);
        return $queryBuilder->execute()->fetch();
    }


}
