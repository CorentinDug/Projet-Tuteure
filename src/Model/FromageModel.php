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

    public function getFromage($id){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('f.id_fromage', 'f.libelle_fromage')
            ->from('fromage', 'f')
            ->where('f.id_fromage='.$id);
        return $queryBuilder->execute()->fetch();

    }

    public function insertFromage($donnees){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->insert('fromage')
            ->values(['libelle_fromage' => '?'])
            ->setParameter(0,$donnees['libelle_fromage']);
        return $queryBuilder->execute();
    }

    public function updateFromage($donnees)
    {
        var_dump($donnees);
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->update('fromage')
            ->set('libelle_fromage' , '?')
            ->where("id_fromage = ".$donnees['id_fromage'])
            ->setParameter(0, $donnees['id_fromage'])

        ;
        return $queryBuilder->execute();
    }

    public function deleteFromage($donnees){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->delete('fromage')
            ->where('id_fromage='.$donnees['id_fromage']);
        return $queryBuilder->execute();
    }

    public function autoCompleteFromage()
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->select('libelle_fromage')
            ->from('fromage');
        $result = $queryBuilder->execute()->fetchAll();
        $tab = array_map('current', $result);
        return $tab;
    }
}
