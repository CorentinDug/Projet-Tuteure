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

    public function getEntree($id){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('e.id_entree', 'e.libelle_entree')
            ->from('entree', 'e')
            ->where('e.id_entree='.$id);
        return $queryBuilder->execute()->fetch();

    }

    public function insertEntree($donnees){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->insert('entree')
            ->values(['libelle_entree' => '?'])
            ->setParameter(0,$donnees['libelle_entree']);
        return $queryBuilder->execute();
    }

    public function updateEntree($donnees)
    {
        var_dump($donnees);
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->update('entree')
            ->set('libelle_entree' , '?')
            ->where("id_entree = ".$donnees['id_entree'])
            ->setParameter(0, $donnees['id_entree'])

        ;
        return $queryBuilder->execute();
    }

    public function deleteEntree($donnees){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->delete('entree')
            ->where('id_entree='.$donnees['id_entree']);
        return $queryBuilder->execute();
    }

    public function autoCompleteEntree()
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->select('libelle_entree')
            ->from('entree');
        $result = $queryBuilder->execute()->fetchAll();
        $tab = array_map('current', $result);
        return $tab;
    }
}
