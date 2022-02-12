<?php

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

class SMTP implements iEmail
{

    public function send_reset_code(string $email, string $code)
    {
        // TODO: Implement send_reset_code() method.
    }
}