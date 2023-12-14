<?php require_once "includes/header.php"; ?>

<?php require_once "includes/navigation.php"; 
require_once "includes/bdd.php";
require_once "fonctions.php";
?>

<?php
modifier_mon_article();

?>
<!-- Page Content -->
<div class="container">

    <div class="row">

        <div class="col-md-12">

            <?php
            if(isset($_SESSION['id_utilisateur']) && $_SESSION['role_utilisateur']=="Auteur"){

//Récuperer les données de l'article à partir de la bdd
                if(isset($_GET['modifier_mon_article']) && $_GET['modifier_mon_article'] !=""){
                    $id_article_modif = $_GET['modifier_mon_article'];
                    $requete = "SELECT * FROM webcms.articles WHERE id_article = '$id_article_modif' ";
                    $result = $bdd->query($requete);
                    $nombre_articles = $result->rowCount();

                    if($nombre_articles == 0){
                        $message = "Il n'existe aucun article répondant à vos critères!";
                    }else{
                        $data = $result->fetch(PDO::FETCH_ASSOC);

                        $statut_article = $data['statut_article'];

                        $id_categorie_article = $data['id_categorie'];
                        $requete_categorie = "SELECT * FROM webcms.categories WHERE id_categorie='$id_categorie_article'";
                        $result_categorie = $bdd->query($requete_categorie);
                        $data_categorie = $result_categorie->fetch(PDO::FETCH_ASSOC);
                        $nom_categorie_modif = $data_categorie['nom_categorie'];


                    ?>
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                   <?php if(isset($message)) echo $message; ?>
                                   <h3 class="text-center font-weight-light my-4">Modification de mon article</h3>
                               </div>
                               <div class="card-body">
                                   <form action = "" method = "post" enctype="multipart/form-data">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div >
                                                <label for="inputTitre">Titre</label>
                                                <input class="form-control" id="inputTitre" type="text" name = "titre_article" value="<?=$data['titre_article']?>"/>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div >
                                                <label for="inputTags">Mots clés</label>
                                                <input class="form-control" id="inputTags" type="text" name = "mots_cles_article" value="<?=$data['tags_article']?>" />

                                            </div>
                                        </div>
                                    </div>
                                    <div >
                                        <label for="tinymceeditor">Contenu de l'article</label>
                                        <textarea class="form-control" id="tinymceeditor" name="contenu_article" rows ="3"><?=$data['contenu_article']?></textarea>

                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div >
                                                <label for="categorie">Catégorie</label>
                                                <select class="form-control"  name = "nom_categorie_article" id="categorie">
                                                    <?php echo "<option value='$nom_categorie_modif' selected>"; 
                                                    echo $nom_categorie_modif;?>

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
                                    <input type="hidden" name="id_article_modif" value="<?=$id_article_modif?>">
                            

                                    <div class="mt-4 mb-0">
                                        <div class="d-grid"><input type="submit"  name = "modifier_mon_article" value="Modifier "class="btn btn-primary btn-block"> </div>
                                    </div>
                                </form>
                            </div>    
                        </div>
                    </div>
                </div>
                <?php
            }
        }

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

