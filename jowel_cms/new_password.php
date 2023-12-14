<?php require_once "includes/header_password.php"; ?>

<?php

if(isset($_GET['email']) && !empty($_GET['email']) && isset($_GET['token']) && !empty($_GET['token'])){


    $email = $_GET['email'];
    $token = $_GET['token'];

    require_once "includes/bdd.php";

    $requete = $bdd->prepare('SELECT * FROM webcms.utilisateurs WHERE email_utilisateur=:email AND token_utilisateur =:token');

    $requete->bindvalue(':email', $email);
    $requete->bindvalue(':token', $token);

    $requete->execute();
    $nombre = $requete->rowCount();

    if($nombre!=1){
        header('Location:login.php');
    }else{

        if(isset($_POST['new_password'])){

            if(empty($_POST['password']) || $_POST['password'] != $_POST['confirm_password']){

                $message = "Rentrer un mot de passe valide!";

            }else{

                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

                $req = $bdd->prepare('UPDATE webcms.utilisateurs SET password_utilisateur=:password WHERE email_utilisateur=:email');

                $req->bindvalue(':email', $email);
                $req->bindvalue(':password',$password);

                $result = $req->execute();

                if($result){
                    echo "<script type =\"text/javascript\">
                    alert('Votre mot de passe a bien été réinitialisé!');
                    document.location.href='login.php';
                    </script>";


                }else{
                    header('location:password.php');
                }

            }

        }

    }






}else{
    header('location:password.php');
}


?>


<body class="bg-success">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <?php if(isset($message)) echo $message; ?>
                                    <h3 class="text-center font-weight-light my-4">Réinitialisation du mot de passe</h3>
                                </div>
                                <div class="card-body">
                                    <div class="small mb-3 text-muted">Veuillez choisir un nouveau mot de passe</div>
                                    <form action="" method = "post">

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


                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <a class="small" href="login.php">Connexion</a>
                                                <input type="submit" name="new_password" class="btn btn-primary" value="Valider">
                                            </div>
                                        </form>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            

            <?php require_once "includes/footer.php";










