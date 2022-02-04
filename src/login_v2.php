<?php

  session_start();

  require 'connection.php';

  if (!empty($_POST["email"]) && !empty($_POST["password"])) {
    $user = $connection->login($_POST["email"], $_POST["password"]);
    if ($user != null) {
        $_SESSION['cookie'] = $user;
        header("Location:index.html");
    } else {
      ?>
          <h1 class="bad">Lo sentimos, las credenciales no coinciden</h1>
      <?php
    }
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Inicio de sesion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/cabecera.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css">
  </head>
  <body>
    <form action="login_v2.php" method="post">
    <h1 class="animate__animated animate__backInLeft">Iniciar sesion</h1>
    <p>Usuario <input type="text" placeholder="ingrese su nombre" name="email"></p>
    <p>Contraseña <input type="password" placeholder="ingrese su contraseña" name="password"></p>
    <input type="submit" value="Ingresar">
    <br>
    <a href="signup.php">Registrarse</a>
    <span>o <a href="">¿Olvidaste tu contraseña?</a></span>
   </form> 
  </body>
</html>
