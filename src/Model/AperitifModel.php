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

class AperitifModel
{
    private $db;

    public function __construct(Application $app)
    {
        $this->db = $app['db'];
    }

    public function getAllAperitif(){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('id_aperitif','libelle_aperitif')
            ->from('aperitif')
            ->orderBy('id_aperitif');
        return $queryBuilder->execute()->fetchAll();
    }

    public function getAperitif($id){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('a.id_aperitif', 'a.libelle_aperitif')
            ->from('aperitif', 'a')
            ->where('a.id_aperitif='.$id);
        return $queryBuilder->execute()->fetch();

    }
    public function insertAperitif($donnees){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->insert('aperitif')
            ->values(['libelle_aperitif' => '?'])
            ->setParameter(0,$donnees['libelle_aperitif']);
        return $queryBuilder->execute();
    }
    public function updateAperitif($donnees)
    {
        var_dump($donnees);
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->update('aperitif')
            ->set('libelle_aperitif' , '?')
            ->where("id_aperitif = ".$donnees['id_aperitif'])
            ->setParameter(0, $donnees['libelle_aperitif'])

        ;
        return $queryBuilder->execute();
    }
    public function deleteAperitif($donnees){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->delete('aperitif')
            ->where('id_aperitif='.$donnees['id_aperitif']);
        return $queryBuilder->execute();
    }

    public function getLibelle($id){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('m.libelle_menu')
            ->from('menu','m')
            ->innerJoin('m','aperitif','a','a.id_aperitif = m.id_aperitif')
            ->where('a.id_aperitif='.$id);
        return $queryBuilder->execute()->fetchAll();
    }

    public function autoCompleteAperitif()
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->select('libelle_aperitif')
            ->from('aperitif');
        $result = $queryBuilder->execute()->fetchAll();
        $tab = array_map('current', $result);
        return $tab;
    }

    public function getId($aperitif)
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->select('id_aperitif')
            ->from('aperitif')
            ->where('libelle_aperitif = ?')
            ->setParameter(0,$aperitif);
        return $queryBuilder->execute()->fetch();
    }
}
