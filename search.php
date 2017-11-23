<?php/*
/**
 * Created by PhpStorm.
 * User: Ciryion
 * Date: 23/11/2017
 * Time: 18:43
 *


$bdd = new PDO('mysql:host=localhost;dbname = projet_tut','root','');

$term = $_GET['term'];
var_dump($term);
$requete = $bdd->prepare('SELECT * FROM type WHERE type.libelle_type LIKE :term'); // j'effectue ma requête SQL grâce au mot-clé LIKE
$requete->execute(array('term' => '%'.$term.'%'));
$array = array(); // on créé le tableau
while($donnee = $requete->fetch()) // on effectue une boucle pour obtenir les données
{
    array_push($array, $donnee['libelle_type']); // et on ajoute celles-ci à notre tableau
}
echo json_encode($array); // il n'y a plus qu'à convertir en JSON
*/


    /* Mette le type du document à text/javascript plutôt qu’à text/html */

    header("Content-type: text/javascript");

    /* Notre tableau PHP multidimentionnel permettant de passer à javascript via Ajax ajax */

    $arr = array(

        array(

            "first_name" => "Darian",

            "last_name" => "Brown",

            "age" => "28",

            "email" => "darianbr@example.com"

        ),

        array(

            "first_name" => "John",

            "last_name" => "Doe",

            "age" => "47",

            "email" => "john_doe@example.com"

        )

    );

    /* encode le tableau comme un json. La sortie sera [{"first_name":"Darian","last_name":"Brown","age":"28","email":"darianbr@example.com"},{"first_name":"John","last_name":"Doe","age":"47","email":"john_doe@example.com"}] */

    echo json_encode($arr);

