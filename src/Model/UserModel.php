<?php
namespace App\Model;

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
}