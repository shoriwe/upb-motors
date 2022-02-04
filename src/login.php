<?php

  session_start();

  require 'database.php';

  if (!empty($_POST['email']) && !empty($_POST['password'])) {
    $records = $conn->prepare('SELECT id, email, password FROM users WHERE email = :email');
    $records->bindParam(':email', $_POST['email']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    $message = '';

    if (count($results) > 0 && password_verify($_POST['password'], $results['password'])) {
      $_SESSION['user_id'] = $results['id'];
      header("Location:index.html");
    } 
    else {
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
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="cabecera.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css">
  </head>
  <body>
    <form action="login.php" method="post">
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
