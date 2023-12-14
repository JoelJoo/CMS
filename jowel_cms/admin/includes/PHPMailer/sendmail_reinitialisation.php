<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';

$mail = new PHPMailer(true);

$mail->isSMTP(); //Specifier que PHPMailer utilise le protcole SMTP.
$mail->Host = 'smtp.gmail.com'; // Specifier le serveur gmail
$mail->SMTPAuth = true; //Pour activer l'authentifiaction
$mail->Username = 'webcms2up@gmail.com'; 
$mail->Password = 'erskyvwwcrsfkdka';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;
$mail->CharSet = "utf-8";
$mail->setFrom('webcms2up@gmail.com', 'Webcms');
$mail->addAddress($_POST['email'], 'Webcms');
$mail->isHTML(true); //Pour activer l'envoi de mail sous forme html

$mail->Subject = 'Réinitialisation du mot de passe';
$mail->Body = 'Afin de réinitailiser votre mot de passe, merci de cliquer sur le lien suivant: <a href = "localhost/webcms/admin/new_password.php?token='.$token.'&email='.$_POST['email'].' ">Reinitialiser votre mot de passe</a>';

$mail->SMTPDebug = 0; //Pour desactiver le debug

if(!$mail->send()){
	$message = "Mail non envoyé";
	echo 'Erreurs:' .$mail->ErrorInfo;
}else{
	$message = "Un mail vient d'être envoyé à votre adresse email pour réinitialiser votre mot de passe!";
	
}










