<?php
session_start();
$sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:'';
if(!empty($sessData['status']['msg'])){
    $statusMsg = $sessData['status']['msg'];
    $statusMsgType = $sessData['status']['type'];
    unset($_SESSION['sessData']['status']);
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Restrablecer contraseña</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/containers.css">
    <link rel="stylesheet" href="css/text.css">
    <link rel="stylesheet" href="css/buttons.css">
  </head>
<?php echo !empty($statusMsg)?'<p class="'.$statusMsgType.'">'.$statusMsg.'</p>':''; ?>
<div class="centered-container">
    <div class="login-container">
        <h1 class="purple-text" style="margin-top: 10%;">Enviar correo</h1>
        <form action="userAccount.php" method="post">
            <label>
                <input class="basic-text-input" type="email" placeholder="Correo electronico" name="email" required="" style="width: 75%;">
            </label>
            <br>
            <button class="blue-button" type="submit" name="forgotSubmit"  value="CONTINUE" style="margin-top: 5%; width: 75%;">Recuperar</button>
        </form>
        <br>
        <a href="login.php">Iniciar Sesion</a>
    </div>
</div>

