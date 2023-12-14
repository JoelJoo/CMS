<?php require_once "includes/header_admin.php"; 
require_once "includes/bdd.php";
require_once "fonctions.php";

?>



<?php

//Processus de suppression d'un compte utilisateur par l'admin

if(isset($_POST['supprimer_utilisateur'])){
    $id_utilisateur = $_POST['id_utilisateur'];
    echo '
        <script type="text/javascript">
        if(confirm("Êtes vous sûr de vouloir supprimer cet utilisateur?")){
            window.location.href="utilisateurs.php?supprimer_utilisateur_valid='.$id_utilisateur.'";
        }
        </script>';
}

if(isset($_GET['supprimer_utilisateur_valid'])){
    supprimer_utilisateur();

}


//Processus de modification du role d'un utilisateur
if(isset($_POST['modifier_utilisateur'])){
    modifier_utilisateur();
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
                    <h1 class="h3 mb-4 text-gray-800">Gestion des utilisateurs</h1>

<?php if(isset($message)) echo "<center style = 'color:blue;'>$message</center><br><br>";
if(isset($message_modif)) echo "<center style = 'color:blue;'>$message_modif</center><br><br>";
?>
    
        <!-- DataTales articles -->
        <div class="row justify-content-center">
         <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Utilisateur</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Modifier</th>
                                    <th>Supprimer</th>
                                    

                                </tr>
                            </thead>
                                    <!-- <tfoot>
                                        <tr>
                                            <th>Nom de la catégorie</th>
                                            
                                        </tr>
                                    </tfoot> -->
                                    <tbody>
                                        <?php
                                        afficher_utilisateurs();

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