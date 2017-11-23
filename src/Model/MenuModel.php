<?php
namespace App\Model;
use Silex\Application;
use Doctrine\DBAL\Query\QueryBuilder;

class menuModel{

    private $db;

    public function __construct(Application $app) {
        $this->db = $app['db'];
    }

    public function getAllMenu(){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('m.id_menu','m.libelle_menu','m.nbDispo','m.prix','m.date_menu','a.libelle_aperitif'
            ,'b.type_boisson','d.libelle_dessert','e.libelle_entree','f.libelle_fromage','p.libelle_plat','s.type_supplement','t.libelle_type')
            ->from('menu', 'm')
            ->innerJoin('m', 'aperitif', 'a', 'a.id_aperitif=m.id_aperitif')
            ->innerJoin('m', 'boisson', 'b', 'b.id_boisson=m.id_boisson')
            ->innerJoin('m', 'dessert', 'd', 'd.id_dessert=m.id_dessert')
            ->innerJoin('m', 'entree', 'e', 'e.id_entree=m.id_entree')
            ->innerJoin('m', 'fromage', 'f', 'f.id_fromage=m.id_fromage')
            ->innerJoin('m', 'plat', 'p', 'p.id_plat=m.id_plat')
            ->innerJoin('m', 'supplement', 's', 's.id_supplement=m.id_supplement')
            ->innerJoin('m', 'type', 't', 't.id_type=m.id_type')
            ->addOrderBy('m.libelle_menu', 'ASC');
        return $queryBuilder->execute()->fetchAll();
    }

    public function insertMenu($donnees)
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->insert('menu')
            ->values(['libelle_menu' => '?',
                'nbDispo' => '?',
                'prix' => '?',
                'date_menu' => '?',
                'id_type' => '?',
                'id_aperitif' => '?',
                'id_entree' => '?',
                'id_plat' => '?',
                'id_fromage' => '?',
                'id_dessert' => '?',
                'id_boisson' => '?',
                'id_supplement' => '?'
                ])
            ->setParameter(0,$donnees['libelle_menu'])
            ->setParameter(1,$donnees['nbDispo'])
            ->setParameter(2,$donnees['prix'])
            ->setParameter(3,$donnees['date_menu'])
            ->setParameter(4,$donnees['id_type'])
            ->setParameter(5,$donnees['id_aperitif'])
            ->setParameter(6,$donnees['id_entree'])
            ->setParameter(7,$donnees['id_plat'])
            ->setParameter(8,$donnees['id_fromage'])
            ->setParameter(9,$donnees['id_dessert'])
            ->setParameter(10,$donnees['id_boisson'])
            ->setParameter(11,$donnees['id_supplement']);
        return $queryBuilder->execute();
    }

    public function getMenu($id){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('id_menu', 'libelle_menu','nbDispo','prix','date_menu','id_type','id_aperitif','id_entree'
            ,'id_plat','id_plat','id_fromage','id_dessert','id_boisson','id_supplement')
            ->from('menu')
            ->where('id_menu='.$id);
        return $queryBuilder->execute()->fetch();

    }

    public function updateMenu($donnees)
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->update('menu')
            ->set('libelle_menu' , '?')
            ->set('nbDispo' , '?')
            ->set('prix' , '?')
            ->set('date_menu' , '?')
            ->set('id_type' , '?')
            ->set('id_aperitif' , '?')
            ->set('id_entree' , '?')
            ->set('id_plat' , '?')
            ->set('id_fromage' , '?')
            ->set('id_dessert' , '?')
            ->set('id_boisson' , '?')
            ->set('id_supplement' , '?')
            ->where("id_menu = ".$donnees['id_menu'])
            ->setParameter(0, $donnees['libelle_menu'])
            ->setParameter(1, $donnees['nbDispo'])
            ->setParameter(2, $donnees['prix'])
            ->setParameter(3, $donnees['date_menu'])
            ->setParameter(4, $donnees['id_type'])
            ->setParameter(5, $donnees['id_aperitif'])
            ->setParameter(6, $donnees['id_entree'])
            ->setParameter(7, $donnees['id_plat'])
            ->setParameter(8, $donnees['id_fromage'])
            ->setParameter(9, $donnees['id_dessert'])
            ->setParameter(10, $donnees['id_boisson'])
            ->setParameter(11, $donnees['id_supplement'])
        ;
        return $queryBuilder->execute();
    }
    public function deleteTypeMenu($donnees){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->delete('menu')
            ->where('id_menu='.$donnees['id_menu']);
        return $queryBuilder->execute();
    }

    public function autoComplet(){
        $term = $_GET['term'];
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->select('libelle_menu')
            ->from('menu')
            ->where('libelle_menu like'.$term);
        $queryBuilder->execute(array('term'=>'$'.$term.'$'));
    }
}