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
        $sql = "SELECT id,username,password,roles FROM users WHERE username = ? AND password = ?";
        $res=$this->db->executeQuery($sql,[$login,$mdp]);   //md5($mdp);
        if($res->rowCount()==1)
            return $res->fetch();
        else
            return false;
    }

    public function verif_mdp_Utilisateur($mdp){
        $sql = "SELECT password FROM users WHERE password = ?";
        $res=$this->db->executeQuery($sql,[$mdp]);   //md5($mdp);
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
                'password'=>'?' ,

                'motdepasse'=>'?',
                'roles'=>'?'
            ])

            ->where('id= ?')
            ->setParameter(0, $donnees['username'])
            ->setParameter(1, $donnees['email'])
            ->setParameter(2, $donnees['password'])
            ->setParameter(3, $donnees['motdepasse'])
            ->setParameter(4, 'ROLE_CLIENT');
        return $queryBuilder->execute();
    }

    public function updateUser($donnees)
    {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->update('users')
            ->set('username' , '?')
            ->set('email' , '?')
            ->where("id = ".$donnees['id'])
            ->setParameter(0, $donnees['username'])
            ->setParameter(1, $donnees['email'])
        ;
        return $queryBuilder->execute();
    }

}

