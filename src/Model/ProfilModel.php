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

class ProfilModel
{
    private $db;

    public function __construct(Application $app)
    {
        $this->db = $app['db'];
    }

    public function getProfil($id){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('u.username,u.email')
            ->from('users','u')
            ->where('u.id='.$id);
        return $queryBuilder->execute()->fetch();

    }

}