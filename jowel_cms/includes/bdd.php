<?php
$dsn = 'mysql:dbname = webcms; host=localhost';
$user = 'root';
$password ='';

try{
	$bdd = new PDO($dsn, $user, $password);
	$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


}catch(PDOExeption $e){
	echo "Echec lors de la connexion: ".$e->getMessage();

}
