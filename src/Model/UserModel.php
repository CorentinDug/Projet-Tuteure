<?php
namespace App\Model;

use Doctrine\DBAL\Query\QueryBuilder;
use Silex\Application;

class UserModel {

    private $db;

    public function __construct(Application $app) {
        $this->db = $app['db'];
    }

    public function verif_login_mdp_Utilisateur($login,$mdp){
        $sql = "SELECT id,username,motdepasse,roles FROM users WHERE username = ? AND motdepasse = ?";
        $res=$this->db->executeQuery($sql,[$login,$mdp]);   //md5($mdp);
        if($res->rowCount()==1)
            return $res->fetch();
        else
            return false;
    }

    public function getAllUser()
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select ('id','username','email')
            ->from('users')
            ->where('roles = "ROLE_CLIENT"');

        return $queryBuilder->execute()->fetchAll();
    }

    public function addUser($donnees)
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->insert('users')
            ->values([
                'username'=>'?',
                'email'=>'?' ,
                'motdepasse'=>'?',
                'roles'=>'?'
            ])

            ->where('id= ?')
            ->setParameter(0, $donnees['username'])
            ->setParameter(1, $donnees['email'])
            ->setParameter(2, $donnees['motdepasse'])
            ->setParameter(3, 'ROLE_CLIENT');
        return $queryBuilder->execute();
    }
}