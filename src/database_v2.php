<?php
    require 'tables.php';
    interface iDatabase {
        public function login($email, $password);
    }

    class Test implements iDatabase {
      public function login($email, $password) {
        if ($email === "admin@gamil.com" && $password === "admin") {
          return new User(1, "admin@gamil.com", "admin");
        }
        return null;
      }
    }

    class MySQL implements iDatabase {
        public $connection = null;
        public function __construct($host, $username, $password, $database) {
            try {
                $this->connection = new PDO("mysql:host=$host;dbname=$database;", $username, $password);
              } catch (PDOException $e) {
                die('Connection Failed: ' . $e->getMessage());
              }
        }

        public function login($email, $password) {
          $records = $this->connection->prepare('SELECT id, email, password FROM users WHERE email = :email');
          $records->bindParam(':email', $email);
          $records->execute();
          $results = $records->fetch(PDO::FETCH_ASSOC);
          if ($results) {
            if (count($results) > 0 && password_verify($password, $results['password'])) {
              return new User($results["id"], $results["email"], $results["password"]);
            }
          }
          return null;
        }
    }
?>