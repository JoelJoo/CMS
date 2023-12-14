
<?php require_once "includes/header_register.php"; ?>
<?php

//difféntes verifications
if(isset($_POST['inscription'])){

    if(empty($_POST['prenom']) || !ctype_alpha($_POST['prenom'])){
        $message = "Votre prénom doit être une chaine de caractères alphabetiques !";

    }elseif(empty($_POST['nom']) || !ctype_alpha( $_POST['nom'])){
        $message = "Votre nom doit être une chaine de caractères alphabetiques !";

    }elseif(empty($_POST['email']) || !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
        $message = " Rentrer une adresse email valide"; 

    }elseif(empty($_POST['password']) || $_POST['password'] != $_POST['confirm_password']){
        $message = "Rentrer un mot de passe valide !";

    }elseif(empty($_POST['username']) || !ctype_alnum( $_POST['username'])){
        $message = "Votre username doit être une chaine de caractères alphanumériques !";
    }else{

        //Connexion à la base de données
        require_once "includes/bdd.php";


        //Seléction de tous les utilisateurs ayant le mmême username saisi
        $req = $bdd->prepare('SELECT * FROM webcms.utilisateurs WHERE username=:username');
        $req->bindvalue(':username', $_POST['username']);
        $req->execute();
        $result = $req->fetch();


        //Seléction de tous les utilisateurs ayant le mmême email saisi
        $req1 = $bdd->prepare('SELECT * FROM webcms.utilisateurs WHERE email_utilisateur = :email');
        $req1->bindvalue(':email', $_POST['email']);
        $req1->execute();
        $result1 = $req1->fetch();

        if($result){
            $message = "Le nom d'utilisateur existe déjà, choisissez un autre nom d'utilisateur";
        }elseif ($result1) {
            $message = "Un compte est déjà attaché à l'adresse email saisie!";
        }else{

            require_once "includes/token.php";

            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            //Insertion des données dans la bdd
            $requete = $bdd->prepare('INSERT INTO webcms.utilisateurs(nom_utilisateur,prenom_utilisateur,username,email_utilisateur,password_utilisateur,token_utilisateur, photo_utilisateur) VALUES (:nom, :prenom,:username, :email,:password,:token, :photo_profil)');

            $requete->bindvalue(':nom', $_POST['nom']);
            $requete->bindvalue(':prenom', $_POST['prenom']);
            $requete->bindvalue(':username', $_POST['username']);
            $requete->bindvalue(':email', $_POST['email']);
            $requete->bindvalue(':password', $password);
            $requete->bindvalue(':token', $token);

            if(empty($_FILES['photo_profil']['name'])){
                $photo_profil = 'avatar_defaut.png';
                $requete->bindvalue(':photo_profil', $photo_profil);
            }else{

                //Processu d'upload la phot de profil
                if(preg_match("#jpeg|png|jpg#", $_FILES['photo_profil']['type'])){
                    $nouveau_nom_photo = $token."_".$_FILES['photo_profil']['name'];

                    $path = "img/photo_profil/";
                    move_uploaded_file($_FILES['photo_profil']['tmp_name'],$path.$nouveau_nom_photo);
                }else{
                    $message = "La photo de profil doit être de type jpeg,jpg ou png";
                }

                $requete->bindvalue(':photo_profil', $nouveau_nom_photo);
            }

            $requete->execute();

            require_once "includes/PHPMailer/sendmail.php";


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

                                    <h3 class="text-center font-weight-light my-4">Créer un compte</h3></div>
                                    <div class="card-body">
                                        <form action = "register.php" method = "post" enctype="multipart/form-data">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3 mb-md-0">
                                                        <input class="form-control" id="inputFirstName" type="text" name = "prenom" />
                                                        <label for="inputFirstName">Prénom</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input class="form-control" id="inputLastName" type="text" name = "nom" />
                                                        <label for="inputLastName">Nom</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputEmail" type="email" name="email"/>
                                                <label for="inputEmail">Adresse Email</label>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3 mb-md-0">
                                                        <input class="form-control" id="inputPassword" type="password" name="password" />
                                                        <label for="inputPassword">Mot de passe</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3 mb-md-0">
                                                        <input class="form-control" id="inputPasswordConfirm" type="password" name = "confirm_password" />
                                                        <label for="inputPasswordConfirm">Confirmation mot de passe</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3 mb-md-0">
                                                        <input class="form-control" id="inputFirstName" type="text" name = "username" />
                                                        <label for="inputFirstName">Nom d'utilisateur</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div>
                                                        <label for="photo">Photo de profil</label>
                                                        <input type="hidden" name="MAX_FILE_SIZE" value = "1000000" />
                                                        <input id = "photo" type = "file" name="photo_profil">

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-4 mb-0">
                                                <div class="d-grid"><input type="submit"  name = "inscription" value="Créer un compte "class="btn btn-warning btn-block"> </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center py-3">
                                        <div class="small"><a href="login.php">Avez-vous un compte? Connectez-vous</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>


            <?php require_once "includes/footer.php"; ?>







