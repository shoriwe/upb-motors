<?php

require 'tables.php';
require 'cache.php';

// Taken from https://stackoverflow.com/questions/4356289/php-random-string-generator
function generateRandomString(int $length = 10): string
{
    static $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    static $charactersLength = 62;
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

interface iDatabase
{
    public function log_login_succeed(string $email);
    public function log_login_failed(string $email);
    public function login(string $email, string $password): ?User;

    public function request_password_reset(string $email): ?string;

    public function reset_password(string $code, string $new_password): bool;
}

class TestDatabase implements iDatabase
{
    public ?Cache $users = null;
    public ?Cache $cache = null;

    public function __construct()
    {
        $this->users = new Cache("test-users.dump");
        $this->cache = new Cache("cache.dump");
        if ($this->users->get("admin@upb.motors.co") === null) {
            $this->users->set(
                "admin@upb.motors.co",
                new User(
                    1,
                    "admin@upb.motors.co",
                    password_hash("admin", PASSWORD_DEFAULT)
                )
            );
        }
    }

    public function login(string $email, string $password): ?User
    {
        $user = $this->users->get($email);
        if ($user !== null) {
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }
        return null;
    }

    public function request_password_reset(string $email): ?string
    {
        $code = null;
        $user = $this->users->get($email);
        if ($user !== null) {
            $code = generateRandomString(64);
            $this->cache->set($code, $user->email);
        }
        return $code;
    }

    public function reset_password(string $code, string $new_password): bool
    {
        $email = $this->cache->get($code);
        if ($email === null) {
            return false;
        }
        $user = $this->users->get($email);
        if ($user === null) {
            return false;
        }
        $user->password = password_hash($new_password, PASSWORD_DEFAULT);
        $this->users->set($user->email, $user);
        $this->cache->delete($code);
        return true;
    }

    public function log_login_succeed(string $email)
    {
        print_r("Login succeed for " . $email );
    }

    public function log_login_failed(string $email)
    {
        print_r("Login failed for " . $email );
    }
}

class MySQL implements iDatabase
{
    public ?PDO $database = null;
    public ?Cache $cache = null;

    public function __construct($host, $username, $password, $database)
    {
        $this->cache = new Cache("cache.dump");
        try {
            $this->database = new PDO("mysql:host=$host;dbname=$database;", $username, $password);
        } catch (PDOException $e) {
            // Do not log the error to the client
        }
    }

    public function login($email, $password): ?User
    {
        $records = $this->database->prepare('SELECT id, correo, hash_contrasena FROM empleados WHERE correo = :email');
        $records->bindParam(':email', $email);
        $records->execute();
        $results = $records->fetch(PDO::FETCH_ASSOC);
        if ($results) {
            if (count($results) > 0 && password_verify($password, $results['hash_contrasena'])) {
                return new User($results["id"], $results["correo"], $results["hash_contrasena"]);
            }
        }
        return null;
    }

    public function request_password_reset(string $email): ?string
    {
        $records = $this->database->prepare('SELECT id FROM empleados WHERE correo = :email');
        $records->bindParam(':email', $email);
        $records->execute();
        $results = $records->fetch(PDO::FETCH_ASSOC);
        if ($results) {
            if (count($results) > 0) {
                $code = generateRandomString(64);
                $this->cache->set($code, $results["id"]);
                return $code;
            }
        }
        return "";
    }

    public function reset_password(string $code, string $new_password): bool
    {
        $id = $this->cache->get($code);
        if ($id === null) {
            return false;
        }
        $this->cache->delete($code);
        $newPasswordHash = password_hash($new_password, PASSWORD_DEFAULT);
        $records = $this->database->prepare('UPDATE empleados SET hash_contrasena = :newPassword WHERE id = :id');
        $records->bindParam(':newPassword', $newPasswordHash);
        $records->bindParam(':id', $id);
        $records->execute();
        return true;
    }

    public function log_login_succeed(string $email)
    {
        $records = $this->database->prepare('CALL log_login_succeed(:email)');
        $records->bindParam(':email', $email);
        $records->execute();
    }

    public function log_login_failed(string $email)
    {
        $records = $this->database->prepare('CALL log_login_failed(:email)');
        $records->bindParam(':email', $email);
        $records->execute();
    }
}
