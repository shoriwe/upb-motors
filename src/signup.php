<?php

  require 'database.php';

  $message = '';

  if (!empty($_POST['email']) && !empty($_POST['password'])) {
    $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $stmt->bindParam(':password', $password);

    if ($stmt->execute()) {
        ?>
            <h1 class="bad">Nuevo usuario creado con éxito</h1>
        <?php
    } 
    else {
        ?>
            <h1 class="bad">Lo sentimos, debe haber habido un problema al crear su cuenta</h1>
        <?php
      
    }
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Registrarse</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/cabecera.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css">
  </head>
  <body>

    <?php if(!empty($message)): ?>
      <p> <?= $message ?></p>
    <?php endif; ?>

    <form action="signup.php" method="POST">
      <h1 class="animate__animated animate__backInLeft">Registrarse</h1>
      <input name="email" type="text" placeholder="Introduce tu correo electrónico">
      <input name="password" type="password" placeholder="Ingresa tu contraseña">
      <input name="confirm_password" type="password" placeholder="Confirmar contraseña">
      <input type="submit" value="Enviar">
      <br>
      <span>o <a href="login.php">Iniciar sesion</a></span>
    </form>

  </body>
</html>