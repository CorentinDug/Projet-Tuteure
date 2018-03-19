<?php
namespace App\Model;
use Doctrine\DBAL\Query\QueryBuilder;
use Silex\Application;

class TypePlatModel {
    private $db;
    public function __construct(Application $app) {
        $this->db = $app['db'];
    }

    public function getAllTypePlat() {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('t.idTypePlat', 't.libelle')
            ->from('type', 't')
            ->addOrderBy('t.idTypePlat', 'ASC');
        return $queryBuilder->execute()->fetchAll();
    }
    public function getTypePlat($id){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('t.idTypePlat', 't.libelle')
            ->from('type', 't')
            ->where('t.idTypePlat='.$id);
        return $queryBuilder->execute()->fetch();

    }
    public function insertType($donnees){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->insert('type')
            ->values(['libelle' => '?'])
            ->setParameter(0,$donnees['libelle']);
        return $queryBuilder->execute();
    }
    public function updateTypePlat($donnees)
    {
        var_dump($donnees);
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->update('typeplats')
            ->set('libelle' , '?')
            ->where("idTypePlat = ".$donnees['idTypePlat'])
            ->setParameter(0, $donnees['libelle'])

        ;
        return $queryBuilder->execute();
    }
    public function deleteTypePlat($donnees){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->delete('typeplats')
            ->where('idTypePlat='.$donnees['idTypePlat']);
        return $queryBuilder->execute();
    }


}