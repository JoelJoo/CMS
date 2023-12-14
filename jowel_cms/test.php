<?php

$nouveau_fichier = 'page1.php' ; 

$header=
'<?php require_once "includes/header.php"; ?>

<?php require_once "includes/navigation.php"; 
require_once "includes/bdd.php";
require_once "fonctions.php";

?>
<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-8">';


$footer = '<?php require_once "includes/footer.php"; ?> ';

file_put_contents($nouveau_fichier, $header, FILE_APPEND);

file_put_contents($nouveau_fichier, $footer, FILE_APPEND);

?>