
 <div class="col-md-4">
    <br><br>
                <!-- Blog Search Well -->
                <div class="well">
                    <h4>Recherche</h4>
                    <div class="input-group">
                        <input type="text" class="form-control">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button">
                                <span class="glyphicon glyphicon-search"></span>
                            </button>
                        </span>
                    </div>
                    <!-- /.input-group -->
                </div>

                <!-- Blog Categories Well -->
                <div class="well">
                    <h4>Catégories</h4>
                    <div class="row">
                        <div class="col-lg-10">
                            <ul class="list-unstyled">
                                <?php
                                $requete = "SELECT * FROM webcms.categories ORDER BY id_categorie ASC";
                                $result = $bdd->query($requete);

                                if(!$result){
                                    echo "La récupération des catégories a rencontrée un problème!";
                                }else{
                                    while ($ligne = $result->fetch(PDO::FETCH_ASSOC)) {
                                        $nom_categorie = $ligne['nom_categorie'];
                                        $id_categorie = $ligne['id_categorie'];

                                        echo "<li><a href = 'index.php?id_categorie=$id_categorie'> $nom_categorie</a></li>";
                                    }
                                }

                                ?>
                            </ul>
                        </div>
                       
                        <!-- /.col-lg-6 -->
                    </div>
                    <!-- /.row -->
                </div>

              

            </div>

        </div>
        <!-- /.row -->

        <hr>






        