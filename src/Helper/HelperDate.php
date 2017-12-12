<?php
/**
 * Created by PhpStorm.
 * User: jrober15
 * Date: 20/10/17
 * Time: 11:24
 */
Namespace App\Helper;
class HelperDate{
    public static function verifDate($date){
        if($date == ""){
            return false;
        }
        $chaines = explode("-",$date);
        if (sizeof($chaines) < 3){
            return false;
        }
        $day = $chaines[0];
        $month = $chaines[1];
        $year = $chaines[2];
        if ((! preg_match("/^[0-9]{1,}/",$day))) $erreurs['date']='nom composé de 2 lettres minimum';
        if ((! preg_match("/^[0-9]{1,}/",$month))) $erreurs['date']='nom composé de 2 lettres minimum';
        if ((! preg_match("/^[0-9]{4,}/",$year))) $erreurs['date']='nom composé de 2 lettres minimum';
        if (empty($erreurs)) {
            return checkdate($month, $day, $year);
        }else return false;
    }
    public static function changeDate($date){
        if($date == ""){
            return false;
        }
        $chaines = explode("-",$date);
        if (sizeof($chaines) < 3){
            return $date;
        }
        $day = $chaines[2];
        $month = $chaines[1];
        $year = $chaines[0];
        $newChaine = array(
            0 => $day,
            1 => $month,
            2 => $year,
        );
        return implode("-",$newChaine);
    }
    public static function changeFormat($date){
        if ($date == ""){
            return false;
        }
        $chaines = explode("-",$date);
        if (sizeof($chaines) < 3){
            return $date;
        }
        $day = $chaines[0];
        $month = $chaines[1];
        $year = $chaines[2];
        $newChaine = array(
            0 => $year,
            1 => $month,
            2 => $day,
        );
        return implode("-",$newChaine);
    }


    public static function convertFRtoUS($date)
    {
        $date = explode("/", $date);
        $newsdate = $date[2] . '-' . $date[1] . '-' . $date[0];
        return $newsdate;
    }
}