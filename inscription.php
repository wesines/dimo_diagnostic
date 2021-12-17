<?php include('../config/init.php');
$erreur = "";



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
                <li><a href="login.php">Login</a></li>
                <li> <a href="inscription.php">Inscription</a> </li>

            </ul>
        </div>

    </div>

    <body>
        <div class="container login">
            <h1 class="text-center">Inscription</h1>
            <?php

            if (isset($_POST['inscription'])) {

                if (empty($_POST["username"])) {
                    $erreur .= "<div class='alert alert-danger' role='alert'>Username manquant </div>";
                }
                if (empty($_POST["password"])) {
                    $erreur .= "<div class='alert alert-danger' role='alert'> Password manquant  </div>";
                }
                if (empty($_POST["adresse"])) {
                    $erreur .= "<div class='alert alert-danger' role='alert'>Username manquant </div>";
                }
                if (empty($_POST["email"])) {
                    $erreur .= "<div class='alert alert-danger' role='alert'> Email manquant  </div>";
                }
                if (empty($_POST["tel"]) || !is_numeric($_POST["tel"])) {
                    $erreur .= "<div class='alert alert-danger' role='alert'>Telephone manquant </div>";
                }
                if (empty($_POST["annee_construction"]) || !is_numeric($_POST["annee_construction"]) || strlen($_POST["annee_construction"]) > 4 || strlen($_POST["annee_construction"]) < 4) {
                    $erreur .= "<div class='alert alert-danger' role='alert'> Année de construction manquant  </div>";
                }
                if (empty($_POST["type_bien"])) {
                    $erreur .= "<div class='alert alert-danger' role='alert'>Type manquant </div>";
                }

                if (empty($erreur)) {

                    try {
                        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                        $username = $_POST['username'];
                        $email = $_POST['email'];
                        $adresse = $_POST['adresse'];
                        $tel = $_POST['tel'];

                        $annee_construction = $_POST['annee_construction'];
                        $type_bien = $_POST["type_bien"];

                        $request = $pdo->prepare("INSERT INTO user (nom, email,password,adresse,tel,annee_construction,type_bien) VALUES (?,?,?,?,?,?,?)");

                        $request->execute(array($username, $email, $password, $adresse, $tel, $annee_construction, $type_bien));
                        $_SESSION['inscription'] = "<div class='alert alert-success'  role='alert'>Inscription achieved successfully </div>";
                        //header("location:/admin/index.php");
                    } catch (Exception $e) {


                        $_SESSION['inscription'] = "<div class='alert alert-danger '  role='alert'>Inscription failed </div>";
                    }
                }
            }

            $username = (!empty($_POST['username']) ? $_POST['username'] : "");
            $adresse = (!empty($_POST['adresse']) ? $_POST['adresse'] : "");
            $email = (!empty($_POST['email']) ? $_POST['email'] : "");
            $tel = (!empty($_POST['tel']) ? $_POST['tel'] : "");
            $annee_construction = (!empty($_POST['annee_construction']) ? $_POST['annee_construction'] : "");
            $type_bien = (!empty($_POST['type_bien']) ? $_POST['type_bien'] : "");
            ?>



            <?php echo $erreur;

            if (isset($_SESSION["inscription"])) {
                echo $_SESSION['inscription'];
                unset($_SESSION['inscription']);
            }
            ?>

            <!-- nscription form starts here-->

            <form method="post" action="">

                <div class="form-group">
                    <label for="exampleInputEmail1">Username</label>
                    <input type="text" value="" name="username" class="form-control" placeholder="Enter username">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Password">
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
                    <input type="text" name="annee_construction" class="form-control" placeholder="Année de construction">
                </div>
                <div class="form-group">
                    <label>Type du bien</label>
                    <select name="type_bien" class="form-control">
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                    </select>
                </div>
                <button type="submit" name="inscription" class="btn btn-primary">Inscription</button>

            </form>
            <!-- Login form ends here-->


        </div>
    </body>

</html>