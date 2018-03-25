<?php
namespace App\Model;

use Doctrine\DBAL\Query\QueryBuilder;
use Silex\Application;

class EtudiantModel
{

    private $db;

    public function __construct(Application $app)
    {
        $this->db = $app['db'];
    }

    public function addEtu($donnees)
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->insert('etudiant')
            ->values([
                'nom_etu' => '?',
                'prenom_etu' => '?',

            ])
            ->where('id= ?')
            ->setParameter(0, $donnees['nom'])
            ->setParameter(1, $donnees['prenom']);
        return $queryBuilder->execute();
    }

    public function getAllEtu(){

        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('e.nom_etu','e.prenom_etu','e.id_etu')
            ->from('etudiant', 'e')
            ->addOrderBy('e.nom_etu', 'ASC');
        return $queryBuilder->execute()->fetchAll();
    }

    public function getEtu($id)
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('e.nom_etu','e.prenom_etu','e.id_etu')
            ->from('etudiant','e')
            ->where('id_etu='.$id);
        return $queryBuilder->execute()->fetch();
    }

    public function updateEtudiant($donnees){

        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->update('etudiant')
            ->set('nom_etu' , '?')
            ->set('prenom_etu' , '?')
            ->where("id_etu = ".$donnees['id_etu'])
            ->setParameter(0, $donnees['nom_etu'])
            ->setParameter(1, $donnees['prenom_etu']);
        return $queryBuilder->execute();



    }

    public function deleteEtu($donnees){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->delete('etudiant')
            ->where('id_etu='.$donnees['id_etu']);
        return $queryBuilder->execute();
    }
}
