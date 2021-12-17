<?php 


include('./config/init.php');


$erreur = "";



if (isset($_POST['login'])) {
    echo "00";

    if (empty($_POST["email"])) {
   
        $erreur .= "<div class='alert alert-danger' role='alert'>email manquant </div>";
    }
    if (empty($_POST["password"])) {
        $erreur .= "<div class='alert alert-danger' role='alert'> Password manquant  </div>";
    }
    if(!$erreur) {
        
    $email = $_POST['email'];
    $password = $_POST['password'];

        echo "1";
            // requette pour recuperer les infos de l'utilisateur dont le pseudo ou le mail correspond a la saisie de l'utilisateur dans le champs login
            $request = $pdo->prepare("SELECT * FROM user WHERE email = ? ");
            $request->execute(array($email));
            $user = $request->fetch();
       
            if (!empty($user)) {
            echo "2";
                
                if (password_verify($password, $user['password'])) {
                   
                    // creer les variables de session
                   
                       echo "3";
                        $_SESSION['user'] = $user['username'];
                       header("Location: index.php");
                } else {
                        // le mot de passe est pas le bon
                        $_SESSION['login'] = "<div class='alert alert-danger '  role='alert'>Password erroné </div>";
                }
            } else {
                    // le login est pas le bon
                    $_SESSION['login'] = "<div class='alert alert-danger '  role='alert'>Email erroné </div>";
            }
       

    
        

}
    }
       
$email = (!empty($_POST['email']) ? $_POST['email'] : "");
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
  

    
        <div class="container login">
            <h1 class="text-center">Login</h1>
            <?php
            if (isset($_SESSION["login"])) {
                echo $_SESSION['login'];
                unset($_SESSION['login']);
            }
         
            ?>
            <?php echo $erreur;  ?>
            <!-- Login form starts here-->

            <form method="post" action="">

                <div class="form-group">
                    <label for="exampleInputEmail1">email</label>
                    <input type="text" value="" name="email" class="form-control" placeholder="Enter email">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                </div>

                <button  name="login"class="btn btn-primary">Submit</button>
            </form>
         


        </div>
    </body>

</html>