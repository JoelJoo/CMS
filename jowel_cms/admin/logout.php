<?php
session_start();

if($_SESSION['id_utilisateur']){
	session_unset(); //permet de detruire toutes les variables de session courante

	session_destroy();
	header('location:../index.php');
}else{
	echo "Vous n'êtes pas connecté!";
}