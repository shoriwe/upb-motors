<?php 
// Start session 
session_start(); 
 
// Load and initialize user class 
include 'User.class.php'; 
$user = new User(); 
 
$postData = $statusMsg = $valErr = ''; 
$status = 'error'; 
$redirectURL = 'index.php'; 
if(isset($_POST['signupSubmit'])){ 
    $redirectURL = 'registration.php'; 
     
    // Get user's input 
    $postData = $_POST; 
    $first_name = trim($_POST['first_name']); 
    $last_name = trim($_POST['last_name']); 
    $email = trim($_POST['email']); 
    $phone = trim($_POST['phone']); 
    $password = trim($_POST['password']); 
    $confirm_password = trim($_POST['confirm_password']); 
     
    // Validate form fields 
    if(empty($first_name)){ 
        $valErr .= 'Please enter your first name.<br/>'; 
    } 
    if(empty($last_name)){ 
        $valErr .= 'Please enter your last name.<br/>'; 
    } 
    if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){ 
        $valErr .= 'Please enter a valid email.<br/>'; 
    } 
    if(empty($phone)){ 
        $valErr .= 'Please enter your phone no.<br/>'; 
    } 
    if(empty($password)){ 
        $valErr .= 'Please enter login password.<br/>'; 
    } 
    if(empty($confirm_password)){ 
        $valErr .= 'Please confirm your password.<br/>'; 
    } 
    if($password !== $confirm_password){ 
        $valErr .= 'Confirm password should be matched with the password.<br/>'; 
    } 
     
    // Check whether user inputs are empty 
    if(empty($valErr)){ 
        // Check whether the user already exists with the same email in the database 
        $prevCon['where'] = array( 
            'email'=>$_POST['email'] 
        ); 
        $prevCon['return_type'] = 'count'; 
        $prevUser = $user->getRows($prevCon); 
        if($prevUser > 0){ 
            ?>
                 <h1 class="bad">Lo sentimos, las credenciales no coinciden</h1>
            <?php
        }else{ 
            // Insert user data in the database 
            $password_hash = md5($password); 
            $userData = array( 
                'first_name' => $first_name, 
                'last_name' => $last_name, 
                'email' => $email, 
                'password' => $password_hash, 
                'phone' => $phone 
            ); 
            $insert = $user->insert($userData); 
             
            if($insert){ 
                $status = 'success'; 
                $statusMsg = 'Your account has been registered successfully, login to the account.'; 
                $postData = ''; 
                 
                $redirectURL = 'index.php'; 
            }else{ 
                $statusMsg = 'Something went wrong, please try again after some time.'; 
            } 
        } 
    }else{ 
        $statusMsg = '<p>Please fill all the mandatory fields:</p>'.trim($valErr, '<br/>'); 
    } 
     
    // Store registration status into the SESSION 
    $sessData['postData'] = $postData; 
    $sessData['status']['type'] = $status; 
    $sessData['status']['msg'] = $statusMsg; 
    $_SESSION['sessData'] = $sessData; 
     
    // Redirect to the home/registration page 
    header("Location: $redirectURL"); 
}elseif(isset($_POST['loginSubmit'])){ 
    // Get user's input 
    $postData = $_POST; 
    $email = trim($_POST['email']); 
    $password = trim($_POST['password']); 
     
    // Validate form fields 
    if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){ 
        $valErr .= 'Please enter a valid email.<br/>'; 
    } 
    if(empty($password)){ 
        $valErr .= 'Please enter your password.<br/>'; 
    } 
     
    // Check whether user inputs are empty 
    if(empty($valErr)){ 
        // Check whether the user account exists with active status in the database 
        $password_hash = md5($password); 
        $conditions['where'] = array( 
            'email' => $email, 
            'password' => $password_hash, 
            'status' => 1 
        ); 
        $conditions['return_type'] = 'single'; 
        $userData = $user->getRows($conditions); 
         
        if(!empty($userData)){ 
            $status = 'success'; 
            $statusMsg = 'Welcome '.$userData['first_name'].'!'; 
            $postData = ''; 
             
            $sessData['userLoggedIn'] = TRUE; 
            $sessData['userID'] = $userData['id']; 
        }else{ 
            $statusMsg = 'Wrong email or password, please try again!'; 
        } 
    }else{ 
        $statusMsg = '<p>Please fill all the mandatory fields:</p>'.trim($valErr, '<br/>'); 
    } 
     
    // Store login status into the SESSION 
    $sessData['postData'] = $postData; 
    $sessData['status']['type'] = $status; 
    $sessData['status']['msg'] = $statusMsg; 
    $_SESSION['sessData'] = $sessData; 
     
    // Redirect to the home page 
    header("Location: $redirectURL"); 
}elseif(!empty($_REQUEST['logoutSubmit'])){ 
    // Remove session data 
    unset($_SESSION['sessData']); 
    session_destroy(); 
     
    // Store logout status into the SESSION 
    $sessData['status']['type'] = 'success'; 
    $sessData['status']['msg'] = 'You have logout successfully!'; 
    $_SESSION['sessData'] = $sessData; 
     
    // Redirect to the home page 
    header("Location: $redirectURL"); 
}elseif(isset($_POST['forgotSubmit'])){
    //check whether email is empty
    if(!empty($_POST['email'])){
        //check whether user exists in the database
        $prevCon['where'] = array('email'=>$_POST['email']);
        $prevCon['return_type'] = 'count';
        $prevUser = $user->getRows($prevCon);
        if($prevUser > 0){
            //generat unique string
            $uniqidStr = md5(uniqid(mt_rand()));;
            
            //update data with forgot pass code
            $conditions = array(
                'email' => $_POST['email']
            );
            $data = array(
                'forgot_pass_identity' => $uniqidStr
            );
            $update = $user->update($data, $conditions);
            
            if($update){
                $resetPassLink = 'http://localhost/Proyecto_integrador/src/sesion/copia/resetPassword.php?fp_code='.$uniqidStr;
                
                //get user details
                $con['where'] = array('email'=>$_POST['email']);
                $con['return_type'] = 'single';
                $userDetails = $user->getRows($con);
                
                //send reset password email
                $to = $userDetails['email'];
                $subject = "Password Update Request";
                $mailContent = 'Dear '.$userDetails['first_name'].', 
                <br/>Recently a request was submitted to reset a password for your account. If this was a mistake, just ignore this email and nothing will happen.
                <br/>To reset your password, visit the following link: <a href="'.$resetPassLink.'">'.$resetPassLink.'</a>
                <br/><br/>Regards,
                <br/>CodexWorld';
                //set content-type header for sending HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                //additional headers
                $headers .= 'From: Empresa Matriz<empresa.matriz.2022@gmail.com>' . "\r\n";
                //send email
                mail($to,$subject,$mailContent,$headers);
                
                $sessData['status']['type'] = 'success';
                $sessData['status']['msg'] = 'Please check your e-mail, we have sent a password reset link to your registered email.';
            }else{
                $sessData['status']['type'] = 'error';
                $sessData['status']['msg'] = 'Some problem occurred, please try again.';
            }
        }else{
            $sessData['status']['type'] = 'error';
            $sessData['status']['msg'] = 'Given email is not associated with any account.'; 
        }
        
    }else{
        $sessData['status']['type'] = 'error';
        $sessData['status']['msg'] = 'Enter email to create a new password for your account.'; 
    }
    //store reset password status into the session
    $_SESSION['sessData'] = $sessData;
    //redirect to the forgot pasword page
    header("Location:forgotPassword.php");
}elseif(isset($_POST['resetSubmit'])){
    $fp_code = '';
    if(!empty($_POST['password']) && !empty($_POST['confirm_password']) && !empty($_POST['fp_code'])){
        $fp_code = $_POST['fp_code'];
        //password and confirm password comparison
        if($_POST['password'] !== $_POST['confirm_password']){
            $sessData['status']['type'] = 'error';
            $sessData['status']['msg'] = 'Confirm password must match with the password.'; 
        }else{
            //check whether identity code exists in the database
            $prevCon['where'] = array('forgot_pass_identity' => $fp_code);
            $prevCon['return_type'] = 'single';
            $prevUser = $user->getRows($prevCon);
            if(!empty($prevUser)){
                //update data with new password
                $conditions = array(
                    'forgot_pass_identity' => $fp_code
                );
                $data = array(
                    'password' => md5($_POST['password'])
                );
                $update = $user->update($data, $conditions);
                if($update){
                    $sessData['status']['type'] = 'success';
                    $sessData['status']['msg'] = 'Your account password has been reset successfully. Please login with your new password.';
                }else{
                    $sessData['status']['type'] = 'error';
                    $sessData['status']['msg'] = 'Some problem occurred, please try again.';
                }
            }else{
                $sessData['status']['type'] = 'error';
                $sessData['status']['msg'] = 'You does not authorized to reset new password of this account.';
            }
        }
    }else{
        $sessData['status']['type'] = 'error';
        $sessData['status']['msg'] = 'All fields are mandatory, please fill all the fields.'; 
    }
    //store reset password status into the session
    $_SESSION['sessData'] = $sessData;
    $redirectURL = ($sessData['status']['type'] == 'success')?'index.php':'resetPassword.php?fp_code='.$fp_code;
    //redirect to the login/reset pasword page
    header("Location:".$redirectURL);
}
else{ 
    // Redirect to the home page 
    header("Location: $redirectURL"); 
}