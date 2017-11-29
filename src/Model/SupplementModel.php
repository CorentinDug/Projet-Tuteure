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

    public function getSupplement($id){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('s.id_supplement', 's.type_supplement')
            ->from('supplement', 's')
            ->where('s.id_supplement='.$id);
        return $queryBuilder->execute()->fetch();

    }
    public function insertSupplement($donnees){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->insert('supplement')
            ->values(['type_supplement' => '?'])
            ->setParameter(0,$donnees['type_supplement']);
        return $queryBuilder->execute();
    }
    public function updateSupplement($donnees)
    {
        var_dump($donnees);
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->update('supplement')
            ->set('type_supplement' , '?')
            ->where("id_supplement = ".$donnees['id_supplement'])
            ->setParameter(0, $donnees['id_supplement'])

        ;
        return $queryBuilder->execute();
    }
    public function deleteSupplement($donnees){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->delete('supplement')
            ->where('id_supplement='.$donnees['id_supplement']);
        return $queryBuilder->execute();
    }

    public function autoCompleteSupplement()
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->select('type_supplement')
            ->from('supplement');
        $result = $queryBuilder->execute()->fetchAll();
        $tab = array_map('current', $result);
        return $tab;
    }
}
