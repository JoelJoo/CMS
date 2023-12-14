<?php require_once "includes/header_admin.php"; 
require_once "includes/bdd.php";
require_once "fonctions.php";

?>



<?php

if (isset($_GET['supprimer']) && $_GET['supprimer']!="") {
    supprimer_article();
}

if(isset($_POST['executer_action'])){

    if(!empty($_POST['checkBoxTab'])){

        foreach($_POST['checkBoxTab'] as $valeur_id_article){

            $action_choisie = $_POST['action_choisie'];

            switch ($action_choisie){

                case 'Publie':
                $requete1 = $bdd->prepare('UPDATE webcms.articles SET statut_article=:statut_article WHERE id_article=:id_article');
                $requete1->bindvalue(':id_article',$valeur_id_article);
                $requete1->bindvalue(':statut_article',$action_choisie);
                $result1 = $requete1->execute();
                break;

                case 'Brouillant':
                $requete1 = $bdd->prepare('UPDATE webcms.articles SET statut_article=:statut_article WHERE id_article=:id_article');
                $requete1->bindvalue(':id_article',$valeur_id_article);
                $requete1->bindvalue(':statut_article',$action_choisie);
                $result1 = $requete1->execute();
                break;

                case "Supprimer":
                $requete3 = $bdd->prepare("DELETE FROM webcms.articles WHERE id_article=:id_article");
                $requete3->bindvalue(':id_article', $valeur_id_article);
                $result3 = $requete3->execute();
                break;

            }

        }

    }
}



?>
<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php require_once "includes/sidebar_left_admin.php"; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php require_once "includes/topbar_admin.php"; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Articles</h1>

                    <?php if(isset($message)) echo "<center style = 'color:blue;'>$message</center><br><br>";
                    if(isset($message1)) echo "<center style = 'color:blue;'>$message1</center><br><br>";
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
                                    <form action="" method="post">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <div class="form-floating">
                                                        <select class="form-control" name="action_choisie">
                                                            <option value="">Sélectionner une action</option>
                                                            <option value="Publie">Publier</option>
                                                            <option value="Brouillant">Ne pas publier</option>
                                                            <option value="Supprimer">Supprimer</option>

                                                        </select>

                                                    </div>

                                                </div>

                                                <div class="col-md-4">

                                                   <input type="submit" name="executer_action" class="btn btn-primary" value="Exécuter">

                                               </div> 

                                           </div>




                                           <thead>
                                            <tr>
                                                <th>Choix</th>
                                                <th>Titre</th>
                                                <th>Auteur</th>
                                                <th>Date</th>
                                                <th>Catégorie</th>
                                                <th>Mots clés</th>
                                                <th>Statut</th>
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
                                        afficher_articles();

                                        ?>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- End of Main Content -->

    <!-- Footer -->
    <?php require_once "includes/footer_admin.php"; ?>
    <!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<?php require_once "includes/mode_deconnexion.php"; ?>
<!-- Bootstrap core JavaScript-->
<?php require_once "includes/bootstrap_js.php"; ?>

</body>

</html>