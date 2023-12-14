
<?php

function ajouter_commentaire(){

	global $bdd;
	global $message;

	if(isset($_POST['ajouter_commentaire'])){

            if(isset($_SESSION['id_utilisateur'])){

                $id_utilisateur_commentaire = $_SESSION['id_utilisateur'];
                $id_article_commentaire = $_GET['id_article'];
                $date_commentaire = date("Y-n-d");

                preg_match("/(^[A-Za-z0-9]\s)/",$_POST['contenu_commentaire'], $result);
                if(empty($_POST['contenu_commentaire'])|| !empty($result)){
                    $message = "<center style='color:red;'>Le contenu du commentaire doit une chaîne de caractéres alphanumériques non vide! </center>";
                }else{

                    $requete = $bdd->prepare('INSERT INTO webcms.commentaires(contenu_commentaire, date_commentaire, id_article, id_utilisateur) VALUES(:contenu_commentaire, :date_commentaire, :id_article, :id_utilisateur)');

                    $requete->bindvalue(':contenu_commentaire', $_POST['contenu_commentaire']);
                    $requete->bindvalue(':date_commentaire',$date_commentaire);
                    $requete->bindvalue(':id_article',$id_article_commentaire);
                    $requete->bindvalue(':id_utilisateur',$id_utilisateur_commentaire);

                    $result = $requete->execute();

                    if(!$result){
                        $message = "<br><br><center style = 'color:red;'>Un problème est survenu, le commentaire n'a pas été ajouté! </center>";
                    }else{
                        $message = "<br><br><center style = 'color:blue;'>Le commentaire a bien été enregistré!</center>";
                    }
                }


            }else{
                $message = "<br><br><center style='color:red;'>Vous devez être connécté pour pouvoir laisser un commentaire!</center>";
            }
        }

}



function modifier_commentaire(){
    global $bdd;
    global $message3;

    preg_match("/(^[A-Za-z0-9]\s)/", $_POST['contenu_commentaire_modif'],$result );

    if(empty($_POST['contenu_commentaire_modif']) || !empty($result)){
        $message3 = "Le contenu du commentaire doit être une chaîne de caractéres alphanumérique non vide!";
    }else{
        $req3 = $bdd->prepare('UPDATE webcms.commentaires SET contenu_commentaire=:contenu_commentaire_modif WHERE id_commentaire =:id_commentaire_modif');
        $req3->bindvalue('contenu_commentaire_modif',$_POST['contenu_commentaire_modif']);
        $req3->bindvalue('id_commentaire_modif',$_POST['id_commentaire_modif']);
        $result3 = $req3->execute();
        if(!$result3){
            $message3 = "Un problème est survenu, le commentaire n'a pas été modifé!";
        }else{
            $message3 = "Le commentaire a bien été modifié";
        }
    }
}


//gestion des articles
function enregistrer_nouveau_article(){

    global $bdd;
    global $message;

    if(isset($_POST['ajouter_article'])){
        if(empty($_POST['titre_article'])){
            $message = "Le titre de l'article doit être une chaîne de caractéres non vide";
        }elseif(empty($_POST['mots_cles_article'])){
            $message = "Précisez au moins un mot clés de l'article!";

        }elseif(empty($_POST['contenu_article'])){
            $message = "Le contenu de l'article doit être une chaîne de caractéres non vide";
        }elseif(empty($_POST['nom_categorie_article'])){
            $message = "Choisissez une catégorie à votre article";
        }elseif (empty($_FILES['image_article']['name'])) {
            $message = "Veuillez selectionner une image pour votre article de type jpeg,jpg ou png !";
        }else{

            require_once "includes/token.php";

                //Processu d'upload l'image de l'article
            if(preg_match("#jpeg|png|jpg#", $_FILES['image_article']['type'])){
                $nouveau_nom_image = $token."_".$_FILES['image_article']['name'];

                $path = "img/images_articles/";
                move_uploaded_file($_FILES['image_article']['tmp_name'],$path.$nouveau_nom_image);

                $nom_categorie_article = $_POST['nom_categorie_article'];
                $requete_categorie = "SELECT * FROM webcms.categories WHERE nom_categorie ='$nom_categorie_article'";
                $result_categorie = $bdd->query($requete_categorie);
                $data_categorie = $result_categorie->fetch(PDO::FETCH_ASSOC);

                $id_categorie = $data_categorie['id_categorie'];

                $aujourdhui = date("Y-m-d");

                $id_auteur = $_SESSION['id_utilisateur'];

                $requete = $bdd->prepare('INSERT INTO webcms.articles(titre_article,date_article,contenu_article,tags_article,image_article,id_categorie,id_auteur, statut_article) VALUES(:titre_article,:date_article,:contenu_article,:tags_article,:image_article,:id_categorie,:id_auteur, :statut_article)');

                $requete->bindvalue(':titre_article',$_POST['titre_article']);
                $requete->bindvalue(':date_article',$aujourdhui);
                $requete->bindvalue(':contenu_article',$_POST['contenu_article']);
                $requete->bindvalue(':tags_article',$_POST['mots_cles_article']);
                $requete->bindvalue(':image_article', $nouveau_nom_image);
                $requete->bindvalue(':id_categorie',$id_categorie);
                $requete->bindvalue(':id_auteur',$id_auteur);
                $requete->bindvalue(':statut_article',"Brouillant");

                $result = $requete->execute();

                if(!$result){
                    $message = "Un problème est survenu et l'article n'a pas été soumi à la publication!";
                }else{
                    $message = "L'article a bien été soumi pour publication";
                }


            }else{
                $message = "L'image de l'article doit être de type jpeg,jpg ou png et sa taille ne doit pas dépasser 1MO";
            }


            
        }
    }


}


//Gestion des articles
function afficher_mes_articles(){

    global $bdd;
    $id_utilisateur = $_SESSION['id_utilisateur'];

    $requete1="SELECT * FROM webcms.articles WHERE statut_article= 'Brouillant' AND id_auteur=$id_utilisateur";

    $result1 = $bdd->query($requete1);
    $nombre_articles = $result1->rowCount();

    if($nombre_articles == 0){
        $message1 = "Vous n'avez aucun article en état de brouillant!";
        echo '<center style="color:red;">'.$message1. '</centre><br><br>';
    }else{
        while($ligne = $result1->fetch(PDO::FETCH_ASSOC)){
            $titre_article = $ligne['titre_article'];
            $id_article = $ligne['id_article'];
            $id_auteur = $ligne['id_auteur'];
            $date_article = $ligne['date_article'];
            $id_categorie = $ligne['id_categorie'];
            $tags_article = $ligne['tags_article'];
            $statut_article = $ligne['statut_article'];
            $image_article = $ligne['image_article'];

            //Récuperer le nom de la catégorie
            $requete3 = "SELECT * FROM webcms.categories WHERE id_categorie = $id_categorie";
            $result3 = $bdd->query($requete3);
            $ligne_categorie = $result3->fetch(PDO::FETCH_ASSOC);
            $nom_categorie = $ligne_categorie['nom_categorie'];
            $date_article = date('d-m-Y',strtotime($date_article));

            echo " <tr>
            <td>$titre_article</td>";
            echo "
            <td>$date_article</td>
            <td>$nom_categorie</td>
            <td>$tags_article</td>";
            echo "<td><img width='60' src=img/images_articles/$image_article alt='image'></td>";

            echo "<td><a href='mes_articles.php?supprimer_mon_article=$id_article'>Supprimer<a></td>
            <td><a href='modifier_mon_article.php?modifier_mon_article=$id_article'>Modifier<a></td>

            </tr>";

        }
    }
}

function supprimer_mon_article(){

    global $bdd;
    global $message;


    $id_article_supp = $_GET['supprimer_mon_article_valid'];

    $req = "DELETE FROM webcms.articles WHERE id_article = '$id_article_supp' ";
    $result = $bdd->exec($req);

    if(!$result){
        $message = "Un problème est survenu, l'article n'a pas été supprimé!";
    } else{
        $message = "L'article a bien été supprimé!";
    }
}
  


function modifier_mon_article(){

    global $bdd;
    global $message;


    if(isset($_POST['modifier_mon_article'])){

        preg_match("/(^[A-Za-z0-9]\s)/",$_POST['titre_article'], $result1);
        preg_match("/(^[A-Za-z0-9]\s)/",$_POST['mots_cles_article'], $result2);
        preg_match("/(^[A-Za-z0-9]\s)/",$_POST['contenu_article'], $result3);

        if(empty($_POST['titre_article'])||!empty($result1)){
            $message = "Le titre de l'article doit être une chaîne de caractéres alphanumérique non vide";
        }elseif(empty($_POST['mots_cles_article']) || !empty($result2)){
            $message = "Précisez au moins un mot clés de l'article!";

        }elseif(empty($_POST['contenu_article']) || !empty($result3)){
            $message = "Le contenu de l'article doit être une chaîne de caractéres alphanumérique non vide";
        }elseif(empty($_POST['nom_categorie_article'])){
            $message = "Choisissez une catégorie à votre article";
        }else{

            $nom_categorie_article = $_POST['nom_categorie_article'];
            $requete_categorie = "SELECT * FROM webcms.categories WHERE nom_categorie ='$nom_categorie_article'";
            $result_categorie = $bdd->query($requete_categorie);
            $data_categorie = $result_categorie->fetch(PDO::FETCH_ASSOC);

            $id_categorie = $data_categorie['id_categorie'];

            $requete = $bdd->prepare('UPDATE webcms.articles SET titre_article=:titre_article,
                contenu_article=:contenu_article,
                tags_article=:tags_article,
                image_article=:image_article,
                id_categorie=:id_categorie
                WHERE id_article =:id_article_modif');

            $requete->bindvalue(':titre_article',$_POST['titre_article']);
            $requete->bindvalue(':contenu_article',$_POST['contenu_article']);
            $requete->bindvalue(':tags_article',$_POST['mots_cles_article']);
            $requete->bindvalue(':id_categorie',$id_categorie);
            $requete->bindvalue(':id_article_modif',$_POST['id_article_modif']);

            if(empty($_FILES['image_article']['name'])){

                $id_article = $_POST['id_article_modif'];
                $requete1 = "SELECT * FROM webcms.articles WHERE id_article=$id_article";
                $result1 = $bdd->query($requete1);
                $data = $result1->fetch(PDO::FETCH_ASSOC);

                $requete->bindvalue(':image_article', $data['image_article']);


            }else{

                require_once "includes/token.php";

                //Processus d'upload l'image de l'article
                if(preg_match("#jpeg|png|jpg#", $_FILES['image_article']['type'])){
                    $nouveau_nom_image = $token."_".$_FILES['image_article']['name'];

                    $path = "img/images_articles/";
                    move_uploaded_file($_FILES['image_article']['tmp_name'],$path.$nouveau_nom_image);

                    $requete->bindvalue(':image_article', $nouveau_nom_image);

                }else{
                $message = "L'image de l'article doit être de type jpeg,jpg ou png et sa taille ne doit pas dépasser 5MO";
            }



            }

            $result = $requete->execute();

            if(!$result){
                $message = "Un problème est survenu et l'article n'a pas été mis à jour!";
            }else{
                $message = "L'article a bien été modifié";
            }
            
        }
    }


}



function afficher_tous_articles(){

    global $bdd;

     //Récuperer tous les articles

               $req = "SELECT * FROM webcms.articles WHERE statut_article ='Publie' ORDER BY id_article DESC";
               $result1 = $bdd->query($req);

               $nombre_total_articles = $result1->rowCount();
               $articles_par_page = 5;

               $nombre_total_de_page = ceil($nombre_total_articles/$articles_par_page);

               if(isset($_GET['page'])){
                $page_actuelle = $_GET['page'];
            }else{
                $page_actuelle = 1;
            }

            $indice_debut = ($page_actuelle - 1)*$articles_par_page;


            $requete = "SELECT * FROM webcms.articles WHERE statut_article ='Publie' ORDER BY id_article DESC LIMIT $indice_debut,5";
            $result = $bdd->query($requete);

            $nombre_articles = $result->rowCount();

            if($nombre_articles == 0){
                echo "<center style = 'color:red;'>Il n'y a aucun article!<center><br> ";
            }else{

                if(!$result){
            echo "La récupération des articles a rencontrée un problème!";
        }else{

            while($ligne = $result->fetch(PDO::FETCH_ASSOC)){

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

                if(strlen($contenu_article)>400){
                    $contenu_article = substr($contenu_article, 0, 400);
                }else{
                    $contenu_article = $contenu_article;
                }

                ?>

                <!-- First Blog Post -->
                <h2>

                    <?php 
                    echo "<a href='article.php?id_article=$id_article'>$titre_article</a>";?>

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
            <?php echo "<a class='btn btn-primary' 
            href='article.php?id_article=$id_article'>Lire la suite <span
            class='glyphicon glyphicon-chevron-right'></span></a>"; ?>

            <hr>



            <?php

        }
    }

    ?>



    <!-- Pager -->
    <center><ul class="pagination">
        <li class="previous">
            <?php
            if ($page_actuelle > 1) {
            echo '<a href="?page='.($page_actuelle - 1).'">Précédent</a>';
            }
            ?>

        </li>
        <?php 
        for($i=1;$i<=$nombre_total_de_page; $i++){
            echo "<li><a href='index.php?page=$i'>$i</a></li>";
        }

        ?>
        <li class="next">
             <?php
            if ($page_actuelle < $nombre_total_de_page) {
            echo '<a href="?page='.($page_actuelle + 1).'">Suivant</a>';
            }
            ?>
        </li>
    </ul>
</center>
<?php
            }


}


function afficher_articles_categorie(){

    global $bdd;

     //Récuperer tous les articles
    $id_categorie = $_GET['id_categorie'];

               $req = "SELECT * FROM webcms.articles WHERE statut_article = 'Publie' AND id_categorie = $id_categorie ORDER BY id_article DESC ";
               $result1 = $bdd->query($req);

               $nombre_total_articles_cat = $result1->rowCount();
               $articles_par_page_cat = 5;

               $nombre_total_de_page_cat = ceil($nombre_total_articles_cat/$articles_par_page_cat);

               if(isset($_GET['page_cat'])){
                $page_actuelle_cat = $_GET['page_cat'];
            }else{
                $page_actuelle_cat = 1;
            }

            $indice_debut_cat = ($page_actuelle_cat - 1)*$articles_par_page_cat;


            $requete = "SELECT * FROM webcms.articles WHERE statut_article ='Publie' AND id_categorie = $id_categorie ORDER BY id_article DESC LIMIT $indice_debut_cat,5";
            $result = $bdd->query($requete);

            $nombre_articles_cat = $result->rowCount();

            if($nombre_articles_cat == 0){
                echo "<center style = 'color:red;'>Il n'y a aucun article!<center><br> ";
            }else{

                if(!$result){
            echo "La récupération des articles a rencontrée un problème!";
        }else{

            while($ligne = $result->fetch(PDO::FETCH_ASSOC)){

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

                if(strlen($contenu_article)>400){
                    $contenu_article = substr($contenu_article, 0, 400);
                }else{
                    $contenu_article = $contenu_article;
                }

                ?>

                <!-- First Blog Post -->
                <h2>

                    <?php 
                    echo "<a href='article.php?id_article=$id_article'>$titre_article</a>";?>

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
            <?php echo "<a class='btn btn-primary' 
            href='article.php?id_article=$id_article'>Lire la suite <span
            class='glyphicon glyphicon-chevron-right'></span></a>"; ?>

            <hr>



            <?php

        }
    }

    ?>



    <!-- Pager -->
    <center><ul class="pagination">
        <li class="previous">
            <?php
            if ($page_actuelle_cat > 1) {
            echo '<a href="?page_cat='.($page_actuelle_cat - 1).'&id_categorie='.$id_categorie.'">Précédent</a>';
            }
            ?>

        </li>
        <?php 
        for($i=1;$i<=$nombre_total_de_page_cat; $i++){
            echo "<li><a href='index.php?page_cat=$i&id_categorie=$id_categorie'>$i</a></li>";
        }

        ?>
        <li class="next">
             <?php
            if ($page_actuelle_cat < $nombre_total_de_page_cat) {
            echo '<a href="?page_cat='.($page_actuelle_cat + 1).'&id_categorie='.$id_categorie.'">Suivant</a>';
            }
            ?>
        </li>
    </ul>
</center>
<?php
            }


}







?>