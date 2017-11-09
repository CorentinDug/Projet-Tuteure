<?php
namespace App\Model;
use Silex\Application;
use Doctrine\DBAL\Query\QueryBuilder;

class PlatModel {

    private $db;

    public function __construct(Application $app) {
        $this->db = $app['db'];
    }

    public function getAllPlat() {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('p.id_plat','p.nom', 'p.prixPlat', 'p.dureePreparation','p.dateCreation','p.description','p.typePlat_id','t.libelle')
            ->from('plats', 'p')
            ->innerJoin('p', 'typePlats', 't', 'p.typePlat_id=t.idTypePlat')
            ->addOrderBy('p.nom', 'ASC');
        return $queryBuilder->execute()->fetchAll();
    }
    public function insertPlat($donnees) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->insert('plats')
            ->values([
                'nom' => '?',
                'prixPlat' => '?',
                'dureePreparation' => '?',
                'dateCreation' => '?',
                'description' => '?',
                'typePlat_id' => '?',
            ])
            ->setParameter(0, $donnees['nom'])
            ->setParameter(1, $donnees['prix'])
            ->setParameter(2, $donnees['dureePreparation'])
            ->setParameter(3, $donnees['dateCreation'])
            ->setParameter(4, $donnees['description'])
            ->setParameter(5, $donnees['typePlat'])
        ;
        return $queryBuilder->execute();
    }

    public function getPlat($id){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('p.id_plat','p.nom', 'p.prixPlat', 'p.dureePreparation','dateCreation','description','p.typePlat_id')
            ->from('plats', 'p')
            ->where('id_plat = '.$id);
        return $queryBuilder->execute()->fetch();
    }

    public function deletePlat($donnees){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->delete('plats')
            ->where('id_plat='.$donnees['id']);
        return $queryBuilder->execute();
    }

    public function updatePlat($donnees)
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->update('plats')
            ->set('nom' , '?')
            ->set('typePlat_id' , '?')
            ->set('dureePreparation' , '?')
            ->set('dateCreation' , '?')
            ->set('description' , '?')
            ->where("id_plat = ".$donnees['id_plat'])
            ->setParameter(0, $donnees['nom'])
            ->setParameter(1, $donnees['typePlat_id'])
            ->setParameter(2, $donnees['dureePreparation'])
            ->setParameter(3, $donnees['dateCreation'])
            ->setParameter(4, $donnees['description'])
        ;
        return $queryBuilder->execute();
    }
}