<?php session_start(); 
require_once "includes/bdd.php";
require_once "includes/header_login.php"; 

if(isset($_GET['modifier_compte']) AND isset($_SESSION['id_utilisateur']) AND $_GET['modifier_compte'] == $_SESSION['id_utilisateur']){

    $id_utilisateur = $_SESSION['id_utilisateur'];
    $requete = "SELECT * FROM webcms.utilisateurs WHERE id_utilisateur=$id_utilisateur AND role_utilisateur='Admin' ";

    $result = $bdd->query($requete);
    $ligne = $result->fetch(PDO::FETCH_ASSOC);

    $nom_utilisateur = $ligne['nom_utilisateur'];
    $prenom_utilisateur = $ligne['prenom_utilisateur'];
    $username = $ligne['username'];
    $photo_profil = $ligne['photo_utilisateur'];

    if(isset($_POST['modif_profil'])){

        if(empty($_POST['prenom']) || !ctype_alpha($_POST['prenom'])){
        $message = "Votre prénom doit être une chaine de caractères alphabetiques !";

    }elseif(empty($_POST['nom']) || !ctype_alpha( $_POST['nom'])){
        $message = "Votre nom doit être une chaine de caractères alphabetiques !";

    }elseif(empty($_POST['username']) || !ctype_alnum( $_POST['username'])){
        $message = "Votre username doit être une chaine de caractères alphanumériques !";
    }else{

        //Connexion à la base de données
        require_once "includes/bdd.php";


        //Seléction de tous les utilisateurs ayant le mmême username saisi
        $req = $bdd->prepare('SELECT * FROM webcms.utilisateurs WHERE username=:username AND role_utilisateur=:role_utilisateur');
        $req->bindvalue(':username', $_POST['username']);
        $req->bindvalue(':role_utilisateur', 'Admin');
        $req->execute();
        $result = $req->fetch();

        if($result){
            $message = "Le nom d'utilisateur saisi existe déjà, merci de choisir un autre nom d'utilisateur!";
        }else{

            $requete1= $bdd->prepare('UPDATE webcms.utilisateurs SET nom_utilisateur =:nom, prenom_utilisateur=:prenom, username=:username, photo_utilisateur=:photo_profil WHERE id_utilisateur=:id_utilisateur AND role_utilisateur=:role_utilisateur');

            $requete1->bindvalue(':id_utilisateur', $id_utilisateur);
            $requete1->bindvalue(':nom', $_POST['nom']);
            $requete1->bindvalue(':prenom', $_POST['prenom']);
            $requete1->bindvalue(':username', $_POST['username']);
            $requete1->bindvalue(':role_utilisateur', 'Admin');


            if(empty($_FILES['photo_profil']['name'])){

                $requete1->bindvalue(':photo_profil', $photo_profil);

            }else{

                 //Processu d'upload la phot de profil
                if(preg_match("#jpeg|png|jpg#", $_FILES['photo_profil']['type'])){

                    require_once "includes/token.php";
                    $nouveau_nom_photo = $token."_".$_FILES['photo_profil']['name'];

                    $path = "img/photo_profil/";
                    move_uploaded_file($_FILES['photo_profil']['tmp_name'],$path.$nouveau_nom_photo);
                }else{
                    $message = "La photo de profil doit être de type jpeg,jpg ou png";
                }

                $requete1->bindvalue(':photo_profil', $nouveau_nom_photo);
            }

            $result1 = $requete1->execute();

            if($result1){
                header('location:profil.php');
            }else{
                $message = "Votre profil n'a pas été modifé";
            }

        }

    }

 }







?>



<!--Formulaire d'inscription -->
<body class="bg-success">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <?php if(isset($message)) echo $message; ?>

                                    <h3 class="text-center font-weight-light my-4">Modifier mon profil</h3></div>
                                    <div class="card-body">
                                        <form action = "" method = "post" enctype="multipart/form-data">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3 mb-md-0">
                                                        <input class="form-control" id="inputFirstName" type="text" name = "prenom" value="<?=$prenom_utilisateur?>" />
                                                        <label for="inputFirstName">Prénom</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input class="form-control" id="inputLastName" type="text" name = "nom" value="<?=$nom_utilisateur?>" />
                                                        <label for="inputLastName">Nom</label>
                                                    </div>
                                                </div>
                                            </div>
                                        

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3 mb-md-0">
                                                        <input class="form-control" id="inputFirstName" type="text" name = "username" value="<?=$username?>"/>
                                                        <label for="inputFirstName">Nom d'utilisateur</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div>
                                                        <?php echo "<img width=24 class='media-object' src='img/photo_profil/$photo_profil' alt='Photo de profil' >"; ?>

                                                        <label for="photo">Photo de profil</label>
                                                        <input type="hidden" name="MAX_FILE_SIZE" value = "1000000" />
                                                        <input id = "photo" type = "file" name="photo_profil">

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-4 mb-0">
                                                <div class="d-grid"><input type="submit"  name = "modif_profil" value="Modifier mon profil "class="btn btn-warning btn-block"> </div>
                                            </div>
                                        </form>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>

<?php
}

?>
            <?php require_once "includes/footer.php"; ?>




