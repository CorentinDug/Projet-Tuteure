<?php
/**
 * Created by PhpStorm.
 * User: jrober15
 * Date: 20/10/17
 * Time: 11:24
 */


Namespace App\Helper;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class HelperMail{

    function sendMail(){
        $mail = new PHPmailer();
        $mail->IsSMTP();
        $mail->Host='smtp.orange.fr';
        $mail->From='julien.robert1998@gmail.com';
        $mail->AddAddress('marieleininger67@gmail.com');
        $mail->AddReplyTo('julien.robert1998@gmail.com');
        $mail->Subject='Test';
        $mail->Body='Je t\'aime mon chaton :3 <3 <3 <3 ';
        var_dump($mail);
        if(!$mail->Send()){ //Teste le return code de la fonction
            echo $mail->ErrorInfo; //Affiche le message d'erreur (ATTENTION:voir section 7)
        }
        else{
            echo 'Mail envoyé avec succès';
        }
        $mail->SmtpClose();
        unset($mail);

    }




}