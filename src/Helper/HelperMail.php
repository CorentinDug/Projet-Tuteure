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

    function sendMail($mail){
      /*  $mail = new PHPmailer();
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
*/


        if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.

        {

            $passage_ligne = "\r\n";

        }

        else

        {

            $passage_ligne = "\n";

        }

        //=====Déclaration des messages au format texte et au format HTML.

        $message_txt = "Bonjour, votre reservation a bien été effectué";


        //==========



        //=====Création de la boundary

        $boundary = "-----=".md5(rand());

        //==========



        //=====Définition du sujet.

        $sujet = "Reservation";

        //=========



        //=====Création du header de l'e-mail.

        $header = "From: \"ProjetTut\"<projettut247@gmail.com>".$passage_ligne;

        $header.= "Reply-to: \"ProjetTut\" <projettut247@gmail.com>".$passage_ligne;

        $header.= "MIME-Version: 1.0".$passage_ligne;

        $header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;

        //==========



        //=====Création du message.

        $message = $passage_ligne."--".$boundary.$passage_ligne;

        //=====Ajout du message au format texte.

        $message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;

        $message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;

        $message.= $passage_ligne.$message_txt.$passage_ligne;

        //==========

        $message.= $passage_ligne."--".$boundary.$passage_ligne;

        //=====Ajout du message au format HTML

        $message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;

        $message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;

        $message.= $passage_ligne.$message_txt.$passage_ligne;

        //==========

        $message.= $passage_ligne."--".$boundary."--".$passage_ligne;

        $message.= $passage_ligne."--".$boundary."--".$passage_ligne;

        //==========



        //=====Envoi de l'e-mail.

        mail($mail,$sujet,$message,$header);

        //==========


    }




}