
<?php
    //Authorization Access control
    // Check wether the user is logged in or not
  
    if(!isset($_SESSION['user'])){
    //user is not loggin in
    $_SESSION['no-login-message']= "<div class='alert alert-danger' role='alert'>Veuillez vous authentifier svp!! </div>";
    header("location:login.php");
    }

 
?>