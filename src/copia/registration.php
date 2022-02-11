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
?>

<!-- Status message -->
<?php if(!empty($statusMsg)){ ?>
    <div class="status-msg <?php echo $status; ?>"><?php echo $statusMsg; ?></div>
<?php } ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Restrablecer contraseña</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet" href="/Proyecto_integrador/src/sesion/css/login.css">
    <link rel="stylesheet" href="/Proyecto_integrador/src/sesion/css/cabecera.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css">
  </head>
<div class="regisFrm">
    <form action="userAccount.php" method="post">
    <h1 class="animate__animated animate__backInLeft">Ingresa los datos</h1>
        <input type="text" name="first_name" placeholder="FIRST NAME" value="<?php echo !empty($postData['first_name'])?$postData['first_name']:''; ?>" required="">
        <input type="text" name="last_name" placeholder="LAST NAME" value="<?php echo !empty($postData['last_name'])?$postData['last_name']:''; ?>" required="">
        <input type="email" name="email" placeholder="EMAIL" value="<?php echo !empty($postData['email'])?$postData['email']:''; ?>" required="">
        <input type="text" name="phone" placeholder="PHONE NUMBER" value="<?php echo !empty($postData['phone'])?$postData['phone']:''; ?>" required="">
        <input type="password" name="password" placeholder="PASSWORD" required="">
        <input type="password" name="confirm_password" placeholder="CONFIRM PASSWORD" required="">
        <div class="send-button">
            <input type="submit" name="signupSubmit" value="CREATE ACCOUNT">
        </div>
        <br>
        <a href="index.php">Iniciar Sesion</a>
        <span>o <a href="forgotPassword.php">¿Olvidaste tu contraseña?</a></span>
    </form>
</div>