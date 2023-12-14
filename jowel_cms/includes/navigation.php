<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-warning">
        <a class="navbar-brand ps-5" href="index.php"><img src="img/jowel_cms_php.png" width="120" height="30"
                alt=""></a>
       
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-item nav-link active" href="index.php">Accueil <span class="sr-only">(current)</span></a>
            </div>
        </div>


        <!-- Navbar-->
        <!--Right menu -->

        <ul class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">

                    <?php

                    if(isset($_SESSION['photo_utilisateur'])){
                        $photo_utilisateur = $_SESSION['photo_utilisateur'];
                         ?>
                    <img class="img-profile rounded-circle" width= 30 height= 30
                            <?php echo "src=img/photo_profil/".$photo_utilisateur; ?>>
                            <?php
                    }else{
                         ?>
                    <img class="img-profile rounded-circle" width= 30 height= 30
                            <?php echo "src=img/photo_profil/avatar_defaut.png"; ?>>
                            <?php
                    }
                    ?>

                </a>

                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                    <?php if(isset($_SESSION['id_utilisateur']) AND $_SESSION['role_utilisateur'] == "Admin"){
                        echo '<li><a class="dropdown-item" href="admin/index.php">Espace Admin</a></li>';
                    }

                    if(isset($_SESSION['id_utilisateur']) && $_SESSION['role_utilisateur']=="Auteur"){
                        echo'<li><a class="dropdown-item" href="proposer_article.php">Proposer article</a></li> ';
                        echo'<li><a class="dropdown-item" href="mes_articles.php">Mes articles</a></li> ';
                    }

                    if(isset($_SESSION['id_utilisateur'])){
                        echo '<li><a class="dropdown-item" href="profil.php">Mon profil</a></li>';

                        echo '<li><hr class="dropdown-divider" /></li>';

                        echo '<li><a class="dropdown-item" href="logout.php">Déconnexion</a></li>';
                    }else{
                        echo '<li><a class="dropdown-item" href="login.php">Se connecter/Créer compte</a></li>';

                    }

                    ?>
                

                </ul>
            </li>
        </ul>
    </nav>

    <header class="header d-flex justify-content-center align-items-center">
        <div class="container text-white text-center">
            <h1 class="mb-0">Découvrir mes projets réalisés</h1><br><br>
            <button class="btn btn-success">Découvrir mes projets</button>
        </div>
    </header>
    <br><br><br>









    