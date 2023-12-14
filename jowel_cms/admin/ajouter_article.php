<?php require_once "includes/header_admin.php"; 
require_once "includes/bdd.php";
require_once "fonctions.php";

?>

<?php


enregistrer_nouveau_article();

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

                    <!-- Formulaire pour enregistrer un nouvel article -->
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-11">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header">
                                       <?php if(isset($message)) echo $message; ?>
                                       <h3 class="text-center font-weight-light my-4">Nouveau article</h3>
                                   </div>
                                   <div class="card-body">
                                   <form action = "" method = "post" enctype="multipart/form-data">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3 mb-md-0">
                                                        <label for="inputTitre">Titre</label>
                                                        <input class="form-control" id="inputTitre" type="text" name = "titre_article" />
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <label for="inputTags">Mots clés</label>
                                                        <input class="form-control" id="inputTags" type="text" name = "mots_cles_article" />
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <label for="summernote">Contenu de l'article</label>
                                                <textarea class="form-control" id="summernote" name="contenu_article" rows ="3"></textarea>
                                                <script>
      $('#summernote').summernote({
        placeholder: 'Hello Bootstrap 4',
        tabsize: 2,
        height: 100
      });
    </script>

                                             
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3 mb-md-0">
                                                        <label for="categorie">Catégorie</label>
                                                        <select class="form-control"  name = "nom_categorie_article" id="categorie">
                                                        <option value="">Choisir une catégorie</option>
<?php
$requete="SELECT * FROM webcms.categories ORDER BY id_categorie ASC";
    $result = $bdd->query($requete);

    if(!$result){
        $message1 = "La récupération des données a rencontrée un problème!";
        echo '<center style="color:red;">'.$message1. '</centre><br><br>';
    }else{
        while($ligne = $result->fetch(PDO::FETCH_ASSOC)){
            $nom_categorie = $ligne['nom_categorie'];
            $id_categorie = $ligne['id_categorie'];

            echo " <option>$nom_categorie</option>";

        }
    }

    ?>
</select>

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div>
                                                        <label for="image_article">Image de l'article</label>
                                                        <input type="hidden" name="MAX_FILE_SIZE" value = "5000000" />
                                                        <input id = "image_article" type = "file" name="image_article">

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-4 mb-0">
                                                <div class="d-grid"><input type="submit"  name = "ajouter_article" value="Soumettre "class="btn btn-primary btn-block"> </div>
                                            </div>
                                        </form>
                                </div>    
                            </div>
                        </div>
                    </div>
                </div>
                <br><br><br>

 

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