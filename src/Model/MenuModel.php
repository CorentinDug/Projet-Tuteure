<?php
namespace App\Model;
use Silex\Application;
use Doctrine\DBAL\Query\QueryBuilder;

class menuModel{

    public function getAllMenu(){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('p.id_plat','p.nom', 'p.prixPlat', 'p.dureePreparation','p.dateCreation','p.description','p.typePlat_id','t.libelle')
            ->from('plats', 'p')
            ->innerJoin('p', 'typePlats', 't', 'p.typePlat_id=t.idTypePlat')
            ->addOrderBy('p.nom', 'ASC');
        return $queryBuilder->execute()->fetchAll();
    }

}