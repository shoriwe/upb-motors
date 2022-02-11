<?php 
// Start session 
session_start(); 
 
// Get data from session 
$sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:''; 
 
// Get status from session 
if(!empty($sessData['status']['msg'])){ 
    $statusMsg = $sessData['status']['msg']; 
    $status = $sessData['status']['type']; 
    unset($_SESSION['sessData']['status']); 
} 
 
$postData = array(); 
if(!empty($sessData['postData'])){ 
    $postData = $sessData['postData']; 
    unset($_SESSION['postData']); 
} 
 
// If the user already logged in 
if(!empty($sessData['userLoggedIn']) && !empty($sessData['userID'])){ 
    include_once 'User.class.php'; 
    $user = new User(); 
    $conditions['where'] = array( 
        'id' => $sessData['userID'] 
    ); 
    $conditions['return_type'] = 'single'; 
    $userData = $user->getRows($conditions); 
} 
 
if(!empty($userData)){ 
    header('location: /Proyecto_integrador/src/sesion/pagina.html');
}

else {
?>
    <h1 class="bad">Lo sentimos, las credenciales no coinciden</h1>
<?php
}
?>
<html>
  <head>
    <title>Inicio de sesion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet" href="/Proyecto_integrador/src/sesion/css/login.css">
    <link rel="stylesheet" href="/Proyecto_integrador/src/sesion/css/cabecera.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css">
  </head>
  <body>
    <?php if(!empty($statusMsg)){ ?>
      <div class="status-msg <?php echo $status; ?>"><?php echo $statusMsg; ?></div>
    <?php } ?>
    <form action="userAccount.php" method="post">
    <h1 class="animate__animated animate__backInLeft">Iniciar sesion</h1>
    <input type="email" name="email" placeholder="EMAIL" required="">
    <input type="password" name="password" placeholder="PASSWORD" required="">
    <div class="send-button">
        <input type="submit" name="loginSubmit" value="LOGIN">
    </div>
    <br>
    <a href="registration.php">Registrarse</a>
    <span>o <a href="forgotPassword.php">¿Olvidaste tu contraseña?</a></span>
   </form> 
  </body>
</html>
