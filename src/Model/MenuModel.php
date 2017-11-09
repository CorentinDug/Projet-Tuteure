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
            ->select('m.id_menu','m.libelle_menu','m.nbDispo','m.prix','m.date_menu','m.pres_boisson','a.libelle_aperitif'
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
}