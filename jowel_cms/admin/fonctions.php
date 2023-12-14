<?php

//Gestion des catégories
function enregistrer_categorie(){

	global $bdd;
	global $message;

	preg_match("/(^[A-Za-z0-9]\s)/", $_POST['nom_categorie'],$result );

	if(empty($_POST['nom_categorie']) || !empty($result)){
		$message = "Le nom de la catégorie doit être une chaîne de caractéres alphanumérique non vide!";
	}else{
		$requete = $bdd->prepare('INSERT INTO webcms.categories(nom_categorie) VALUES(:nom_categorie)');
		$requete->bindvalue('nom_categorie',$_POST['nom_categorie']);
		$result = $requete->execute();
		if(!$result){
			$message = "Un problème est survenu, la catégorie n'a pas été enregistrée!";
		}else{
			$message = "La catégorie a bien été enregistrée";
		}
	}
}


function supprimer_categorie(){

	global $bdd;
	global $message2;


	$id_categorie_supp = $_GET['supprimer'];

	$req0 = "SELECT * FROM webcms.articles WHERE id_categorie = '$id_categorie_supp'";
	$result0 = $bdd->query($req0);
	$nb_articles = $result0->rowCount();

	if($nb_articles > 0){
		$req1 = "DELETE webcms.categories, webcms.articles
				FROM webcms.categories
				JOIN webcms.articles
				ON webcms.categories.id_categorie = webcms.articles.id_categorie
				WHERE webcms.categories.id_categorie = '$id_categorie_supp'";

		$result1 = $bdd->exec($req1);

		if(!$result1){
			$message2 = "Un problème est survenu, la catégorie et ses articles n'ont pas été supprimés!";
		}else{
			$message2 = "La catégorie et ses articles ont bien été supprimés!";
		}

	}else{
		$req = "DELETE FROM webcms.categories WHERE id_categorie = '$id_categorie_supp' ";
		$result = $bdd->exec($req);

		if(!$result){
		$message2 = "Un problème est survenu, la catégorie n'a pas été supprimée!";
	} else{
		$message2 = "La catégorie a bien été supprimée!";
		}
	}
	
}


function afficher_categories(){

	global $bdd;

	$requete="SELECT * FROM webcms.categories ORDER BY id_categorie ASC";
	$result = $bdd->query($requete);

	if(!$result){
		$message1 = "La récupération des données a rencontrée un problème!";
		echo '<center style="color:red;">'.$message1. '</centre><br><br>';
	}else{
		while($ligne = $result->fetch(PDO::FETCH_ASSOC)){
			$nom_categorie = $ligne['nom_categorie'];
			$id_categorie = $ligne['id_categorie'];

			echo " <tr>
			<td>$nom_categorie</td>
			<td><a class = 'btn btn-danger' href='categories.php?supprimer=$id_categorie'>Supprimer<a></td>
			<td><a class = 'btn btn-primary' href='categories.php?modifier=$id_categorie'>Modifier<a></td>

			</tr>";

		}
	}
}

function recuperer_categorie_modif(){

	global $id_categorie_modif;
	global $data;
	global $bdd;

	$id_categorie_modif = $_GET['modifier'];
	$req2 = "SELECT * FROM webcms.categories WHERE id_categorie = '$id_categorie_modif' ";
	$result2 = $bdd->query($req2);
	$data = $result2->fetch(PDO::FETCH_ASSOC);

	$nom_categorie_modif = $data['nom_categorie'];
}


function modifier_categorie(){
	global $bdd;
	global $message3;

	preg_match("/([^A-Za-z0-9]\s)/", $_POST['nom_categorie_modif'],$result );

	if(empty($_POST['nom_categorie_modif']) || !empty($result)){
		$message3 = "Le nom de la catégorie doit être une chaîne de caractéres alphanumérique non vide!";
	}else{
		$req3 = $bdd->prepare('UPDATE webcms.categories SET nom_categorie=:nom_categorie_modif WHERE id_categorie =:id_categorie_modif');
		$req3->bindvalue('nom_categorie_modif',$_POST['nom_categorie_modif']);
		$req3->bindvalue('id_categorie_modif',$_POST['id_categorie_modif']);
		$result3 = $req3->execute();
		if(!$result3){
			$message3 = "Un problème est survenu, la catégorie n'a pas été modifée!";
		}else{
			$message3 = "La catégorie a bien été modifiée";
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

				$path = "../img/images_articles/";
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
				$requete->bindvalue(':statut_article',"Publie");

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
function afficher_articles(){

	global $bdd;

	$requete1="SELECT * FROM webcms.articles ORDER BY id_article DESC";
	$result1 = $bdd->query($requete1);

	if(!$result1){
		$message1 = "La récupération des données a rencontrée un problème!";
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



		//Récuperer le nom et prénom de l'auteur
			$requete2 = "SELECT * FROM webcms.utilisateurs WHERE id_utilisateur = $id_auteur";
			$result2 = $bdd->query($requete2);
			$ligne_auteur = $result2->fetch(PDO::FETCH_ASSOC);
			$nom_auteur = $ligne_auteur['nom_utilisateur'];
			$prenom_auteur = $ligne_auteur['prenom_utilisateur'];


			//Récuperer le nom de la catégorie
			$requete3 = "SELECT * FROM webcms.categories WHERE id_categorie = $id_categorie";
			$result3 = $bdd->query($requete3);
			$ligne_categorie = $result3->fetch(PDO::FETCH_ASSOC);
			$nom_categorie = $ligne_categorie['nom_categorie'];
			$date_article = date('d-m-Y',strtotime($date_article));

			?>
			<tr><td><input type="checkbox" name="checkBoxTab[]" value="<?php echo $id_article;?>"></td>

			<?php
			echo "<td>$titre_article</td>
			<td>";
			echo $nom_auteur." ".$prenom_auteur; echo "</td>
			<td>$date_article</td>
			<td>$nom_categorie</td>
			<td>$tags_article</td>
			<td>$statut_article</td>";
			echo "<td><img width='60' src=../img/images_articles/$image_article alt='image'></td>";

			echo "<td><a class='btn btn-danger' href='articles.php?supprimer=$id_article'>Supprimer<a></td>
			<td><a class='btn btn-primary' href='modifier_article.php?modifier_article=$id_article'>Modifier<a></td>

			</tr>";

		}
	}
}

function supprimer_article(){

	global $bdd;
	global $message;


	$id_article_supp = $_GET['supprimer'];

	$req0 = "SELECT * FROM webcms.commentaires WHERE id_article = '$id_article_supp'";
	$result0 = $bdd->query($req0);
	$nb_commentaires = $result0->rowCount();

	if($nb_commentaires > 0){
		$req1 = "DELETE webcms.articles, webcms.commentaires
				 FROM webcms.articles 
				 JOIN webcms.commentaires
				 ON webcms.articles.id_article = webcms.commentaires.id_article
				 WHERE webcms.articles.id_article = '$id_article_supp' ";
		$result1 = $bdd->exec($req1);

		if(!$result1){
			$message = "Un problème est survenu, l'article et ses commentaires n'ont pas été supprimés!";
		}else{
			$message = "L'article et ses commentaires ont bien été supprimés!";
		}

	}else{
		$req = "DELETE FROM webcms.articles WHERE id_article = '$id_article_supp' ";
		$result = $bdd->exec($req);
		if(!$result){
		$message = "Un problème est survenu, l'article n'a pas été supprimé!";
	} else{
		$message = "L'article a bien été supprimé!";
	}
	}


	
}


function modifier_article(){

	global $bdd;
	global $message;

	if(isset($_POST['modifier_article'])){
		if(empty($_POST['titre_article'])){
			$message = "Le titre de l'article doit être une chaîne de caractéres non vide";
		}elseif(empty($_POST['mots_cles_article'])){
			$message = "Précisez au moins un mot clés de l'article!";

		}elseif(empty($_POST['contenu_article'])){
			$message = "Le contenu de l'article doit être une chaîne de caractéres non vide";
		}elseif(empty($_POST['nom_categorie_article'])){
			$message = "Choisissez une catégorie à votre article";
		}elseif(empty($_POST['statut_article'])){
			$message = "Choisissez unstatut pour votre article";
		}else{

			$nom_categorie_article = $_POST['nom_categorie_article'];
			$requete_categorie = "SELECT * FROM webcms.categories WHERE nom_categorie ='$nom_categorie_article'";
			$result_categorie = $bdd->query($requete_categorie);
			$data_categorie = $result_categorie->fetch(PDO::FETCH_ASSOC);

			$id_categorie = $data_categorie['id_categorie'];

			$requete = $bdd->prepare('UPDATE webcms.articles SET titre_article=:titre_article,
				contenu_article=:contenu_article,
				tags_article=:tags_article,
				statut_article=:statut_article,
				image_article=:image_article,
				id_categorie=:id_categorie
				WHERE id_article =:id_article_modif');

			$requete->bindvalue(':titre_article',$_POST['titre_article']);
			$requete->bindvalue(':contenu_article',$_POST['contenu_article']);
			$requete->bindvalue(':tags_article',$_POST['mots_cles_article']);
			$requete->bindvalue(':id_categorie',$id_categorie);
			$requete->bindvalue(':statut_article',$_POST['statut_article']);
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

					$path = "../img/images_articles/";
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


//Gestion des commentaires

function afficher_commentaires(){

	global $bdd;

	$requete1="SELECT * FROM webcms.commentaires ORDER BY id_commentaire ASC";
	$result1 = $bdd->query($requete1);

	if(!$result1){
		$message1 = "La récupération des données a rencontrée un problème!";
		echo '<center style="color:red;">'.$message1. '</centre><br><br>';
	}else{
		while($ligne = $result1->fetch(PDO::FETCH_ASSOC)){
			$id_commentaire = $ligne['id_commentaire'];
			$id_utilisateur = $ligne['id_utilisateur'];
			$id_article = $ligne['id_article'];
			$date_commentaire = $ligne['date_commentaire'];
			$contenu_commentaire = $ligne['contenu_commentaire'];

			$date_commentaire_fr = date('d/m/Y', strtotime($date_commentaire));


		//Récuperer le username et l'email de l'auteur du commentaire
			$requete2 = "SELECT * FROM webcms.utilisateurs WHERE id_utilisateur = $id_utilisateur";
			$result2 = $bdd->query($requete2);
			$ligne_utilisateur = $result2->fetch(PDO::FETCH_ASSOC);
			$username = !empty($ligne_utilisateur['username'])? $ligne_utilisateur['username']:NULL;
			$email_utilisateur = !empty($ligne_utilisateur['email_utilisateur'])? $ligne_utilisateur['email_utilisateur']:NULL;


			//Récuperer le titre de l'article en question
			$requete3 = "SELECT * FROM webcms.articles WHERE id_article = $id_article";
			$result3 = $bdd->query($requete3);
			$ligne_article = $result3->fetch(PDO::FETCH_ASSOC);
			$titre_article = $ligne_article['titre_article'];

			echo "<tr><td>$titre_article</td>";
			echo "<td>$contenu_commentaire</td>";
			echo "<td>$date_commentaire_fr</td>";
			echo "<td>$username</td>";
			echo "<td>$email_utilisateur</td>";

			echo "<td><a class ='btn btn-danger' href='commentaires.php?supprimer_commentaire=$id_commentaire'>Supprimer<a></td>
			

			</tr>";

		}
	}
}

function supprimer_commentaire(){

	global $bdd;
	global $message;


	$id_commentaire_supp = $_GET['supprimer_commentaire'];

	$req = "DELETE FROM webcms.commentaires WHERE id_commentaire = '$id_commentaire_supp' ";
	$result = $bdd->exec($req);

	if(!$result){
		$message = "Un problème est survenu, le commentaire n'a pas été supprimé!";
	} else{
		$message = "Le commentaire a bien été supprimé!";
	}
}

function afficher_utilisateurs(){

	global $bdd;
	global $message;
	$id_utilisateur_connecte = $_SESSION['id_utilisateur'];


	$requete = "SELECT * FROM webcms.utilisateurs WHERE validation_email_utilisateur=1 AND id_utilisateur!=$id_utilisateur_connecte ORDER BY id_utilisateur DESC";

	$result = $bdd->query($requete);

	if(!$result){
		$message = "La récupération des données des utilisateurs a rencontrée un problème!";
	}else{

		while($ligne=$result->fetch(PDO::FETCH_ASSOC)){
			$id_utilisateur = $ligne['id_utilisateur'];
			$nom_utilisateur = $ligne['nom_utilisateur'];
			$prenom_utilisateur = $ligne['prenom_utilisateur'];
			$email_utilisateur = $ligne['email_utilisateur'];
			$role_utilisateur = $ligne['role_utilisateur'];


			echo "<tr>
			<td>$nom_utilisateur</td>";
			echo "<td>$prenom_utilisateur</td>";
			echo "<td>$email_utilisateur</td>";

			//Formulaire de modification du role de l'utilisateur
			echo '<form action = utilisateurs.php method="post">';
			echo "<td><select class='form-control' name='role_utilisateur'>";
			echo "<option value = '$role_utilisateur' selected>";
			echo $role_utilisateur;
			echo "</option>";
			echo "
			<option> Membre</option>
			<option> Admin</option>
			<option> Auteur</option>
			<option> Evaluateur</option>
			</select></td>";

			echo '<td><input type="submit" name="modifier_utilisateur" class="btn btn-primary" value="modifier"></td>';
			echo "<input type='hidden' name='id_utilisateur' value=$id_utilisateur ></form>";

			//Formulaire de suppression d'un utilisateur
			echo '<form action = utilisateurs.php method="post">';
			echo '<td><input type="submit" name="supprimer_utilisateur" class="btn btn-danger" value="Supprimer" ></td>';
			echo "<input type='hidden' name='id_utilisateur' value=$id_utilisateur ></form></tr>";
			
		}

	}

}


function modifier_utilisateur(){

	global $bdd;
	global $message_modif;

	$id_utilisateur = $_POST['id_utilisateur'];
	$role_utilisateur = $_POST['role_utilisateur'];

	if(empty($role_utilisateur)){
		$message_modif = "<center style='color:red;'>Le rôle de l'utilisateur doit être une chaîne de caractères alphabetiques non vide!</center>";
	}else{
		$requete = $bdd->prepare('UPDATE webcms.utilisateurs SET role_utilisateur=:role_utilisateur WHERE id_utilisateur=:id_utilisateur');

		$requete->bindvalue(':role_utilisateur',$role_utilisateur);
		$requete->bindvalue(':id_utilisateur',$id_utilisateur);

		$result = $requete->execute();

		if(!$result){
			$message_modif = "<center style='color:red;'>Un problème est survenu et le rôle de l'utilisateur n'a pas été modifié!</center>";
		}else{
			$message_modif = "<center style='color:green;'>Le rôle de l'utilisateur a bien été modifié!</center>";
		}
	}

}


function supprimer_utilisateur(){

	global $bdd;
	global $message;

	$id_utilisateur_supp = $_GET['supprimer_utilisateur_valid'];

	$requete = $bdd->prepare('UPDATE webcms.utilisateurs SET validation_email_utilisateur =:action_supp WHERE id_utilisateur=:id_utilisateur_supp');

		$requete->bindvalue(':action_supp',2);
		$requete->bindvalue(':id_utilisateur_supp',$id_utilisateur_supp);

		$result = $requete->execute();

	if(!$result){
		$message = "Un problème est survenu, l'utilisateur n'a pas été supprimé!";
	}else{
		$message = "le compte de l'utilisateur a bien été supprimé!";
	}
}


function nombre_articles(){
	global $bdd;
	global $nombre_articles;

	$requete = "SELECT * FROM webcms.articles";
	$result = $bdd->query($requete);
	$nombre_articles = $result->rowCount();
	return $nombre_articles;
}

function nombre_commentaires(){
	global $bdd;
	global $nombre_commentaires;

	$requete = "SELECT * FROM webcms.commentaires";
	$result = $bdd->query($requete);
	$nombre_commentaires = $result->rowCount();
	return $nombre_commentaires;
}

function nombre_utilisateurs(){
	global $bdd;
	global $nombre_utilisateurs;

	$requete = "SELECT * FROM webcms.utilisateurs WHERE validation_email_utilisateur=1";
	$result = $bdd->query($requete);
	$nombre_utilisateurs = $result->rowCount();
	return $nombre_utilisateurs;
}

function nombre_categories(){
	global $bdd;
	global $nombre_categories;

	$requete = "SELECT * FROM webcms.categories";
	$result = $bdd->query($requete);
	$nombre_categories = $result->rowCount();
	return $nombre_categories;
}


function nombre_auteurs(){
	global $bdd;
	global $nombre_auteurs;

	$requete = 'SELECT * FROM webcms.utilisateurs WHERE validation_email_utilisateur=1 AND role_utilisateur="Auteur" ';
	$result = $bdd->query($requete);
	$nombre_auteurs = $result->rowCount();
	return $nombre_auteurs;
}

function nombre_evaluateurs(){
	global $bdd;
	global $nombre_evaluateurs;

	$requete = 'SELECT * FROM webcms.utilisateurs WHERE validation_email_utilisateur=1 AND role_utilisateur="Evaluateur" ';
	$result = $bdd->query($requete);
	$nombre_evaluateurs = $result->rowCount();
	return $nombre_evaluateurs;
}

function nombre_membres(){
	global $bdd;
	global $nombre_membres;

	$requete = 'SELECT * FROM webcms.utilisateurs WHERE validation_email_utilisateur=1 AND role_utilisateur="Membre" ';
	$result = $bdd->query($requete);
	$nombre_membres = $result->rowCount();
	return $nombre_membres;
}

function nombre_admin(){
	global $bdd;
	global $nombre_admin;

	$requete = 'SELECT * FROM webcms.utilisateurs WHERE validation_email_utilisateur=1 AND role_utilisateur="Admin" ';
	$result = $bdd->query($requete);
	$nombre_admin = $result->rowCount();
	return $nombre_admin;
}




?>