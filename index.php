<?php
include('./config/init.php');
include('./login-checked.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Dimo diagnostic</title>
    <link rel="styleSheet" href="./css/admin.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

</head>

<body>
    <!-- Menu Section Starts  -->
    <div class="menu">
        <div class="wrapper text-center">
            <ul>
                <li><a href="login.php">Déconnexion</a></li>


            </ul>
        </div>

    </div>
    <?php
    $erreur = "";



    ?>



    <body>
        <div class="container login">
            <h1 class="text-center">Inscription des clients</h1>
            <?php

            if (isset($_POST['Ajouter'])) {

                if (empty($_POST["nom"])) {
                    $erreur .= "<div class='alert alert-danger' role='alert'>nom manquant </div>";
                }
                if (empty($_POST["prenom"])) {
                    $erreur .= "<div class='alert alert-danger' role='alert'> prenom manquant  </div>";
                }
                if (empty($_POST["adresse"])) {
                    $erreur .= "<div class='alert alert-danger' role='alert'>Adresse manquante </div>";
                }
                if (empty($_POST["email"])) {
                    $erreur .= "<div class='alert alert-danger' role='alert'> Email manquant  </div>";
                }
                if (empty($_POST["tel"]) || !is_numeric($_POST["tel"])) {
                    $erreur .= "<div class='alert alert-danger' role='alert'>Telephone manquant </div>";
                }
                if (empty($_POST["annee_construction"])) {
                    $erreur .= "<div class='alert alert-danger' role='alert'> Année de construction manquant  </div>";
                }
                if (empty($_POST["type_bien"])) {
                    $erreur .= "<div class='alert alert-danger' role='alert'>Type manquant </div>";
                }

                if (empty($erreur)) {

                    try {
                        $nom = $_POST['nom'];
                        $email = $_POST['email'];
                        $adresse = $_POST['adresse'];
                        $tel = $_POST['tel'];
                        $prenom = $_POST['prenom'];

                        $annee_construction = $_POST['annee_construction'];
                        $type_bien = $_POST["type_bien"];

                        //calcul du prix et $diagnostic



                        if ($annee_construction < 1949) {
                            $diag = "DPE,AM,TER,PB";
                            if ($type_bien === "A") {
                                $prix = 299;
                            } else if ($type_bien === "B") {
                                $prix = 249;
                            }
                        } else if ($annee_construction >= 1949 && $annee_construction <= 1997) {
                            $diag = "DPE,AM,TER";
                            if ($type_bien === "A") {
                                $prix = 199;
                            } else if ($type_bien === "B") {
                                $prix = 149;
                            }
                        } else {
                            $diag = "DPE,TER";
                            if ($type_bien === "A") {
                                $prix = 99;
                            } else if ($type_bien === "B") {
                                $prix = 49;
                            }
                        }

                      
                        $request = $pdo->prepare("INSERT INTO client (nom, email,prenom,adresse,tel,annee_construction,type_bien,prix_offre,diag) VALUES 
                            (?,?,?,?,?,?,?,?,?)                       
                            ");

                        $request->execute(array($nom, $email, $prenom, $adresse, $tel, $annee_construction, $type_bien, $prix, $diag));
                        $_SESSION['inscription'] = "<div class='alert alert-success'  role='alert'>Ajout réalisé avec succès </div>";



                        // envoi au diagnostic : $dest doit avoir le mail de diagnostic

                        $dest = "wes.ines@gmail.com";
                        $sujet = "Email de test";
                        $corp = "
                                                       
                            <h1> DIMO Diagnostic :</h1>

                            <h4>Nouveau lead !</h4>

                            Source prospect : Utilisateur

                            Son client : 

                            Nom : $nom

                            Prénom : $prenom

                            Adresse : $adresse

                            Téléphone : $tel

                            Email :$email

                            Type de bien : $type_bien

                            Année de construction : $annee_construction

                            Offre :$prix
                            
                            ";
                        $headers = "From: ines.oueslati.attia@gmail.com";


                        if (mail($dest, $sujet, $corp, $headers)) {
                            $_SESSION['mail_diagnostic'] = "<div class='alert alert-success'  role='alert'>Mail envoyé avec succès à Diagnostic </div>";
                        } else {
                            $_SESSION['mail_diagnostic'] = "<div class='alert alert-success'  role='alert'>Echec envoi Mail à Diagnostic </div>";                        }

                        //envoi à l'utilisateur    $dest doit avoir le mail de user connecté       

                        $dest = "wes.ines@gmail.com";
                        $sujet = "Email de test";
                        $corp = "
                                                       
                            <h1> Utilisateur :</h1>

                            Nous avons bien reçu votre demande. Veuillez trouver le récapitulatif ci dessous : 

                            Votre client

                            Nom : $nom

                            Prénom : $prenom

                            Adresse : $adresse

                            Téléphone : $tel

                            Email :$email

                            Type de bien : $type_bien

                            Année de construction :$annee_construction

                            Diag à réaliser :$diag

                            Offre proposée : $prix
                                                        
                            ";
                        $headers = "From: ines.oueslati.attia@gmail.com";


                        if (mail($dest, $sujet, $corp, $headers)) {
                            $_SESSION['mail_user'] = "<div class='alert alert-success'  role='alert'>Mail envoyé avec succès à l'utilisateur </div>";
                        } else {
                            $_SESSION['mail_user'] = "<div class='alert alert-success'  role='alert'>Echec envoi Mail à l'utilisateur </div>";    
                        }



                        //envoi au client final   $dest doit avoir le mail du lcient ajouté        

                        $dest = $dest;
                        $sujet = "Email de test";
                        $corp = "
                                                       
                            Bonjour $nom $prenom,

                        [$_SESSION[user]] nous a sollicité afin vous adresser une offre dans le cadre de votre projet.

                        Pour votre projet immobilier pour le bien situé à cette adresse : $adresse

                        Il vous faudra réaliser :$diag

                        Notre offre pour cette prestation : $prix € TTC

                        Nous restons à votre disposition pour des informations complémentaires. 

                        Cordialement.

                        DIMO Diagnostic
                                                        
                            ";
                        $headers = "From: ines.oueslati.attia@gmail.com";


                        if (mail($dest, $sujet, $corp, $headers)) {
                            $_SESSION['mail_client'] = "<div class='alert alert-success'  role='alert'>Mail envoyé avec succès au client </div>";
                        } else {
                            $_SESSION['mail_client'] = "<div class='alert alert-success'  role='alert'>Echec envoi Mail au client </div>";
                        }

                        //header("location:/admin/index.php");
                    } catch (Exception $e) {


                        $_SESSION['inscription'] = "<div class='alert alert-danger '  role='alert'>Ajout non réalisé </div>";
                    }
                }
            }

            $nom = (!empty($_POST['nom']) ? $_POST['nom'] : "");
            $prenom = (!empty($_POST['prenom']) ? $_POST['prenom'] : "");

            $adresse = (!empty($_POST['adresse']) ? $_POST['adresse'] : "");
            $email = (!empty($_POST['email']) ? $_POST['email'] : "");
            $tel = (!empty($_POST['tel']) ? $_POST['tel'] : "");
            $annee_construction = (!empty($_POST['annee_construction']) ? $_POST['annee_construction'] : "");
            $type_bien = (!empty($_POST['type_bien']) ? $_POST['type_bien'] : "");




            echo $erreur;

            if (isset($_SESSION['inscription'])) {
                echo $_SESSION['inscription'];
                unset($_SESSION['inscription']);
            }
              if (isset($_SESSION['mail_diagnostic'])) {
                echo $_SESSION['mail_diagnostic'];
                unset($_SESSION['mail_diagnostic']);
            }
               if (isset($_SESSION['mail_client'])) {
                echo $_SESSION['mail_client'];
                unset($_SESSION['mail_client']);
            }
              if (isset($_SESSION['mail_user'])) {
                echo $_SESSION['mail_user'];
                unset($_SESSION['mail_user']);
            }
            ?>

            <!-- nscription form starts here-->

            <form method="post" action="">

                <div class="form-group">
                    <label for="exampleInputEmail1">nom</label>
                    <input type="text" value="" name="nom" class="form-control" placeholder=" nom">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">prenom</label>
                    <input type="text" value="" name="prenom" class="form-control" placeholder=" prenom">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Email">
                </div>
                <div class="form-group">
                    <label>Adresse</label>
                    <input type="text" name="adresse" class="form-control" placeholder="Adresse">
                </div>
                <div class="form-group">
                    <label>Tel</label>
                    <input type="text" name="tel" class="form-control" placeholder="tel">
                </div>
                <div class="form-group">
                    <label>Année de consctruction</label>
                    <select name="annee_construction" class="form-control">
                        <option value=""></option>
                        <?php for ($i = 1949; $i <= 2050; $i++) {
                        ?>
                            <option value="<?=$i ?>"><?= $i ?></option>
                        <?php } ?>

                    </select>
                </div>
                <div class="form-group">
                    <label>Type du bien</label>
                    <select name="type_bien" class="form-control">
                        <option value=""></option>
                        <option value="A">A</option>
                        <option value="B">B</option>

                    </select>
                </div>

                <button type="submit" name="Ajouter" class="btn btn-primary mt-3">Ajouter un client</button>

            </form>



        </div>
    </body>

</html>