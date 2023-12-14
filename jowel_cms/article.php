<?php require_once "includes/header.php"; ?>

<?php require_once "includes/navigation.php"; 
require_once "includes/bdd.php";
require_once "fonctions.php";
?>



<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Blog Post Content Column -->
        <div class="col-lg-8">

            <!-- Blog Post -->

            <?php 


//code pour modifier un commentaire par son auteur

    if(isset($_POST['modifier_commentaire'])){
        $id_article = $_POST['id_article'];
            modifier_commentaire();
            header('Location:article.php?id_article='.$id_article.'');
        }


//Recuperer l'article dont l'id est transmis en url (a l'aide du parametre id_article)

            if(isset($_GET['id_article'])){
                $id_article = $_GET['id_article'];
                $requete = "SELECT * FROM webcms.articles WHERE id_article=$id_article";
                $result = $bdd->query($requete);
                $ligne = $result->fetch(PDO::FETCH_ASSOC);

                if(!$result){
                    echo "La récupération le l'article a rencontrée un problème!";
                }else{

//Récuperer le nom et le prénom de l'auteur
                    $id_auteur = $ligne['id_auteur'];
                    $requete1 = "SELECT * FROM webcms.utilisateurs WHERE id_utilisateur=$id_auteur";
                    $result1 = $bdd->query($requete1);
                    $ligne_auteur = $result1->fetch(PDO::FETCH_ASSOC);

                    $nom_auteur = $ligne_auteur['nom_utilisateur'];
                    $prenom_auteur = $ligne_auteur['prenom_utilisateur'];

//Récuperer le nom de la catégorie
                    $id_categorie = $ligne['id_categorie'];
                    $requete2 = "SELECT * FROM webcms.categories WHERE id_categorie=$id_categorie";
                    $result2 = $bdd->query($requete2);
                    $ligne_categorie = $result2->fetch(PDO::FETCH_ASSOC);
                    $nom_categorie = $ligne_categorie['nom_categorie'];

//Récuperer les données de l'article en question

                    $id_article = $ligne['id_article'];
                    $titre_article = $ligne['titre_article'];
                    $date_article = $ligne['date_article'];
                    $tags_article = $ligne['tags_article'];
                    $statut_article = $ligne['statut_article'];
                    $image_article = $ligne['image_article'];
                    $contenu_article = $ligne['contenu_article'];
                    $date_article_fr = date('d-m-Y', strtotime($date_article));

                    ?>

                    <!-- First Blog Post -->
                    <h2>

                        <?php 
                        echo "<a href='article.php?article_id=$id_article'>$titre_article</a>";?>

                    </h2>
                    <p class="lead">
                        Auteur: <a href="index.php"><?php echo $nom_auteur." ".$prenom_auteur;?></a>
                    </p>
                    <p><span class="glyphicon glyphicon-time"></span> Publié le <?php echo $date_article_fr;?> | Catégorie: <?php echo $nom_categorie;?></p>
                    <hr>
                    <?php echo "<img class='img-responsive' src='img/images_articles/$image_article' alt='$titre_article'>";?>
                    <hr>
                    <p><?php 
                    if($contenu_article){
                        echo $contenu_article;
                    }

                ?></p>


                <hr>



                <?php

                
            }



        }

//Ajout d'un commentaire


        ajouter_commentaire();

        ?>

        <!-- Blog Comments -->

        <!-- Comments Form -->

        <?php
        if(isset($_GET['id_article']) && $_GET['id_article']!=""){
            ?>
            <div class="well">
                <?php if(isset($message)) echo $message; ?>
                <h4>Laisser un commentaire:</h4>
                <form role="form" action = "" method="post">
                    <div class="form-group">
                        <textarea class="form-control" rows="3" name="contenu_commentaire"></textarea>
                    </div>
                    <button type="submit" name="ajouter_commentaire" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
            <?php

        }
        ?>


        <!-- Posted Comments -->

        <!-- Comment -->

        <?php
        if(isset($_GET['id_article']) && $_GET['id_article']!=""){

            $id_article = $_GET['id_article'];
            $requete = "SELECT * FROM webcms.commentaires WHERE id_article = $id_article";
            $result = $bdd->query($requete);

            if(!$result){
                $message_commentaire = "La récupération des commentaires a rencontrée un problème";
            }else{

                while ($ligne = $result->fetch(PDO::FETCH_ASSOC)) {

            //récuperer le username et la photo du profil de l'utilisateur qui a laissé le commentaire

                    $id_utilisateur = $ligne['id_utilisateur'];
                    $requete1 = "SELECT * FROM webcms.utilisateurs WHERE id_utilisateur = $id_utilisateur";
                    $result1 = $bdd->query($requete1);

                    $ligne_utilisateur = $result1->fetch(PDO::FETCH_ASSOC);
                    $username = $ligne_utilisateur['username'];
                    $photo_utilisateur = $ligne_utilisateur['photo_utilisateur'];


                    $id_commentaire = $ligne['id_commentaire'];
                    $contenu_commentaire = $ligne['contenu_commentaire'];
                    $date_commentaire = $ligne['date_commentaire'];
                    $date_commentaire_fr = date('d/m/Y', strtotime($date_commentaire));


                    ?>
                    <div class="media">
                        <?php if (isset($message_commentaire)) echo $message_commentaire; ?>
                        <a class="pull-left" href="#">
                            <img class="img-profile rounded-circle" width= 35 height= 35
                            <?php echo "src=img/photo_profil/".$photo_utilisateur; ?>>
                        </a>
                        <div class="media-body">
                            <h4 class="media-heading"><?php echo $username; ?>
                            <small><?php  echo $date_commentaire_fr; 
                            if(isset($_SESSION['id_utilisateur']) &&
                                $_SESSION['id_utilisateur']== $ligne_utilisateur['id_utilisateur']){
                                echo " |<a href='article.php?modifier_mon_commentaire=$id_commentaire&id_article=$id_article'> Modifier </a> | <a href='article.php?supprimer_mon_commentaire=$id_commentaire&id_article=$id_article'>Supprimer</a> ";
                        }


                        ?>
                        
                    </small>
                </h4>
                <?php echo $contenu_commentaire; ?>
                <hr>
            </div>
        </div>

        <?php

    }



    if(isset($_GET['modifier_mon_commentaire']) && $_GET['modifier_mon_commentaire']!=""){

        $id_commentaire_modif = $_GET['modifier_mon_commentaire'];
        $requete3 = "SELECT * FROM webcms.commentaires WHERE id_commentaire=$id_commentaire_modif";
        $result3 = $bdd->query($requete3);

        if(!$result3){
            $message3 = "La récupération du commentaire a échouée!";
        }else{
            $ligne3 = $result3->fetch(PDO::FETCH_ASSOC);

              echo '<script type="text/javascript">
              window.onload=function(){
                document.documentElement.scrollTop+=document.documentElement.offsetHeight*100000;
              };
              </script>';




            ?>
            <!--Formulaire de modification de commentaire -->


            <div class="well">
                <?php if(isset($message3)) echo $message3; ?>
                <h4>Modifier votre commentaire:</h4>
                <form role="form" action = "" method="post">
                    <div class="form-group">
                        <textarea class="form-control" rows="3" name="contenu_commentaire_modif"><?php echo $ligne3['contenu_commentaire']; ?></textarea>
                        <input type="hidden" name="id_commentaire_modif" value="<?=$id_commentaire_modif?>">
                        <input type="hidden" name="id_article" value="<?=$id_article?>">
                    </div>
                    <button type="submit" name="modifier_commentaire" class="btn btn-primary">Modifier</button>
                </form>
            </div>

            <?php

        }

        ?>

        
        <?php

    }

}

}


//Suppression de mon commentaire


if(isset($_GET['supprimer_mon_commentaire']) && $_GET['supprimer_mon_commentaire']!="" ){

    $id_commentaire_supp = $_GET['supprimer_mon_commentaire'];

    echo '
    <script type="text/javascript">
    if(confirm("Etes-vous sûr de vouloir supprimer votre commentaire?")){
        window.location.href = "article.php?supprimer_mon_commentaire_valid='.$id_commentaire_supp.'";
    } 
    </script>';
}


if(isset($_GET['supprimer_mon_commentaire_valid']) && $_GET['supprimer_mon_commentaire_valid']!="" ){
    $id_commentaire_supp = $_GET['supprimer_mon_commentaire_valid'];
    $req = "DELETE FROM webcms.commentaires WHERE id_commentaire =$id_commentaire_supp";
    $result = $bdd->exec($req);

    if(!$result){
        echo "<center style='color:red;'>Un problème est survenu, le commentaire n'a pas été supprimé!";
    }else{
       header('Location:index.php');
   }

}


?>




<!-- Comment -->

</div>

<!-- Blog Sidebar Widgets Column -->
<?php require_once "includes/sidebar.php"; ?>

</div>
<!-- /.row -->

<!-- Footer -->
<?php require_once "includes/footer.php"; ?> 

</div>
<!-- /.container -->


</body>

</html>
