<?php require_once "includes/header.php"; ?>

<?php require_once "includes/navigation.php"; 
require_once "includes/bdd.php";
require_once "fonctions.php";
?>

<?php
enregistrer_nouveau_article();

//Processus de suppression de mon artcile
if(isset($_GET['supprimer_mon_article']) && $_GET['supprimer_mon_article']!=""){

    $id_article_supp = $_GET['supprimer_mon_article'];
    echo'

        <script type="text/javascript">
        if(confirm("Êtes vous sûr de vouloir supprimer votre article?"))
        {
            window.location.href = "mes_articles.php?supprimer_mon_article_valid='.$id_article_supp.'";
        }
        </script>';

}

if(isset($_GET['supprimer_mon_article_valid'])){
    supprimer_mon_article();
}

?>
<!-- Page Content -->
<div class="container">

    <div class="row">

        <div class="col-md-12">

            <?php
            if(isset($_SESSION['id_utilisateur']) && $_SESSION['role_utilisateur']=="Auteur"){
                ?>


                <!-- DataTales articles -->
                <div class="row justify-content-center">
                   <div class="col-lg-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Articles</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Titre</th>
                                            <th>Date</th>
                                            <th>Catégorie</th>
                                            <th>Mots clés</th>
                                            <th>Image</th>
                                            <th>Supprimer</th>
                                            <th>Modifier</th>

                                        </tr>
                                    </thead>
                                    <!-- <tfoot>
                                        <tr>
                                            <th>Nom de la catégorie</th>
                                            
                                        </tr>
                                    </tfoot> -->
                                    <tbody>
                                        <?php
                                        afficher_mes_articles();

                                        ?>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>







            <?php

        }else{
            header('location:index.php');

        }

        ?>

    </div>
</div>
<!-- Blog Sidebar Widgets Column -->


<!-- /.row -->

<!-- Footer -->
<?php require_once "includes/footer.php"; ?> 


<!-- /.container -->

