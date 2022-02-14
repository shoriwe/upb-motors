<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/autoload.php';

interface iEmail
{
    public function send_reset_code(string $email, string $code);
}

class TestEmail implements iEmail
{

    public function send_reset_code(string $email, string $code)
    {
        file_put_contents("php://stderr", print_r("Hola $email este es tu codigo $code - ", true));
    }
}

class SMTPEmail implements iEmail
{
    public ?string $host = null;
    public ?int $port = null;
    public ?string $username = null;
    public ?string $password = null;

    public function __construct(string $host, int $port, string $username, string $password)
    {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function send_reset_code(string $email, string $code)
    {
        $host = $_SERVER['HTTP_HOST'];
        $url = "http://$host/reset-password.php?code=$code";
        $mail = $this->new_mail();
        $mail->addAddress($email);
        $mail->Body = "Hola, puedes recuperar tu cuenta con desde el siguiente URL: $url";
        $mail->send();
    }

    public function new_mail(): PHPMailer
    {
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->isSMTP();
        $mail->Host = $this->host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->username;
        $mail->Password = $this->password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = $this->port;
        return $mail;
    }
}