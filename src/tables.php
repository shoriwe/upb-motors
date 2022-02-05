<?php

class User
{
    public $id = null;
    public $email = null;
    public $password = null;

    public function __construct($id, $email, $password)
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
    }
}

?>