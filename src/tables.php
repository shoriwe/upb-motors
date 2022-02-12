<?php

class User
{
    public ?int $id = null;
    public ?string $email = null;
    public ?string $password = null;

    public function __construct($id, $email, $password)
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
    }
}

?>