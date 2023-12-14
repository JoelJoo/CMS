<?php require_once "includes/header.php"; ?>

<!--navigation-->

<?php require_once "includes/navigation.php"; 
require_once "includes/bdd.php"; 
require_once "fonctions.php"; 

?>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-8">

            <h1 class="page-header">
                Articles récents

            </h1>

            <?php

            //Recuperer tous les articles d'une catégorie donnée

            if(isset($_GET['id_categorie']) || isset($_GET['page_cat'])){

                afficher_articles_categorie();
               

            }else{

                //Récuperer tous les articles

             afficher_tous_articles();

               
        }



      ?>  

</div>


<?php require_once "includes/sidebar.php"; ?>

<!--footer-->

<?php require_once "includes/footer.php"; ?>

