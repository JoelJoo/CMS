<?php require_once "includes/header.php"; ?>

<?php require_once "includes/navigation.php"; 
require_once "includes/bdd.php";
require_once "fonctions.php";
?>

<?php
enregistrer_nouveau_article();

?>
<!-- Page Content -->
<div class="container">

    <div class="row">

        <div class="col-md-12">

            <?php
            if(isset($_SESSION['id_utilisateur']) && $_SESSION['role_utilisateur']=="Auteur"){
                ?>
<div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-header">
                     <?php if(isset($message)) echo $message; ?>
                     <h3 class="text-center font-weight-light my-4">Nouveau article</h3>
                 </div>
                 <div class="card-body">
                     <form action = "" method = "post" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div >
                                    <label for="inputTitre">Titre</label>
                                    <input class="form-control" id="inputTitre" type="text" name = "titre_article" />

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div >
                                    <label for="inputTags">Mots clés</label>
                                    <input class="form-control" id="inputTags" type="text" name = "mots_cles_article" />

                                </div>
                            </div>
                        </div>
                        <div >
                            <label for="tinymceeditor">Contenu de l'article</label>
                            <textarea class="form-control" id="tinymceeditor" name="contenu_article" rows ="3"></textarea>
           
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div >
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

