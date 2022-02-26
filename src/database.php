<?php

require_once 'tables.php';
require_once 'cache.php';
const Gerente = 1;
const RecursosHumanos = 2;
const Ventas = 3;
const Inventario = 4;

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
    /**
     * @param string $product_name
     * @return array|null
     */
    public function search_products(string $product_name): array;

    public function view_product(int $id): ?Product;

    public function add_inventory(string $product_name, int $product_amount, string $product_description, int $product_price, string $image_file): bool;

    public function is_gerente(int $user_id): bool;

    public function is_recursos_humanos(int $user_id): bool;

    public function is_ventas(int $user_id): bool;

    public function is_inventario(int $user_id): bool;

    public function update_password(int $user_id, string $old_password, string $new_password): ?string;

    public function log_login_succeed(string $email);

    public function log_login_failed(string $email);

    public function login(string $email, string $password): ?int;

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
        // TODO: FIX ME
    }

    public function login(string $email, string $password): ?int
    {
        $user = $this->users->get($email);
        if ($user !== null) {
            if (password_verify($password, $user->password)) {
                return $user->id;
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
        print_r("Login succeed for " . $email);
    }

    public function log_login_failed(string $email)
    {
        print_r("Login failed for " . $email);
    }

    public function update_password(int $user_id, string $old_password, string $new_password): ?string
    {
        // TODO: Implement update_password() method.
        return "IMPLEMENT ME";
    }

    public function is_gerente(int $user_id): bool
    {
        // TODO: Implement is_gerente() method.
        return false;
    }

    public function is_recursos_humanos(int $user_id): bool
    {
        // TODO: Implement is_recursos_humanos() method.
        return false;
    }

    public function is_ventas(int $user_id): bool
    {
        // TODO: Implement is_ventas() method.
        return false;
    }

    public function is_inventario(int $user_id): bool
    {
        // TODO: Implement is_inventario() method.
        return false;
    }

    public function search_products(string $product_name): array
    {
        // TODO: Implement search_products() method.
        return array();
    }

    public function add_inventory(string $product_name, int $product_amount, string $product_description, int $product_price, string $image_file): bool
    {
        // TODO: Implement add_inventory() method.
        return false;
    }

    public function view_product(int $id): ?Product
    {
        // TODO: Implement view_product() method.
        return null;
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
            echo $e;
            // Do not log the error to the client
        }
    }

    public function login($email, $password): ?int
    {
        $records = $this->database->prepare('SELECT id, hash_contrasena FROM empleados WHERE correo = :email LIMIT 1;');
        $records->bindParam(':email', $email);
        $records->execute();
        $results = $records->fetch(PDO::FETCH_ASSOC);
        if ($results) {
            if (count($results) > 0 && password_verify($password, $results['hash_contrasena'])) {
                return $results["id"];
            }
        }
        return null;
    }

    public function request_password_reset(string $email): ?string
    {
        $records = $this->database->prepare('SELECT id FROM empleados WHERE correo = :email LIMIT 1;');
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
        $records = $this->database->prepare('UPDATE empleados SET hash_contrasena = :newPassword WHERE id = :id;');
        $records->bindParam(':newPassword', $newPasswordHash);
        $records->bindParam(':id', $id);
        $records->execute();
        return true;
    }

    public function log_login_succeed(string $email)
    {
        $records = $this->database->prepare('CALL log_login_succeed(:email);');
        $records->bindParam(':email', $email);
        $records->execute();
    }

    public function log_login_failed(string $email)
    {
        $records = $this->database->prepare('CALL log_login_failed(:email);');
        $records->bindParam(':email', $email);
        $records->execute();
    }

    public function update_password(int $user_id, string $old_password, string $new_password): ?string
    {
        $records = $this->database->prepare('SELECT hash_contrasena FROM empleados WHERE id = :id LIMIT 1;');
        $records->bindParam(':id', $user_id);
        $records->execute();
        $results = $records->fetch(PDO::FETCH_ASSOC);
        if ($results) {
            if (count($results) > 0) {
                if (password_verify($old_password, $results["hash_contrasena"])) {
                    $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                    $records = $this->database->prepare('CALL actualizar_contrasena(:id, :new_password_hash);');
                    $records->bindParam(':id', $user_id);
                    $records->bindParam(':new_password_hash', $new_password_hash);
                    $records->execute();
                    return null;
                } else {
                    return "contrasena antigua es incorrecta";
                }
            }
        }
        return "COMO LLEGASTE AQUI LADRON?";
    }

    public function is_gerente(int $user_id): bool
    {
        return $this->get_user_permissions($user_id) === Gerente;
    }

    public function get_user_permissions(int $user_id): int
    {
        $records = $this->database->prepare('SELECT permisos_id FROM empleados WHERE id = :id LIMIT 1;');
        $records->bindParam(':id', $user_id);
        $records->execute();
        $results = $records->fetch(PDO::FETCH_ASSOC);
        if ($results) {
            if (count($results) > 0) {
                return $results["permisos_id"];
            }
        }
        return 0;
    }

    public function is_recursos_humanos(int $user_id): bool
    {
        return $this->get_user_permissions($user_id) === RecursosHumanos;
    }

    public function is_ventas(int $user_id): bool
    {
        return $this->get_user_permissions($user_id) === Ventas;
    }

    public function is_inventario(int $user_id): bool
    {
        return $this->get_user_permissions($user_id) === Inventario;
    }

    public function search_products(string $product_name): array
    {
        $product_name = "%" . $product_name . "%";
        $records = $this->database->prepare('SELECT id, cantidad, nombre, descripcion, precio, activo FROM inventario WHERE LOWER(nombre) LIKE :name;');
        $records->bindParam(':name', $product_name);
        $records->execute();
        $products = array();
        while ($row = $records->fetch(PDO::FETCH_ASSOC)) {
            if (count($row) === 0) {
                break;
            }
            $products[] = new Product($row["id"], $row["cantidad"], $row["nombre"], $row["descripcion"], $row["precio"], $row["activo"], null);
        }
        return $products;
    }

    public function add_inventory(string $product_name, int $product_amount, string $product_description, int $product_price, string $image_file): bool
    {
        $file = fopen($image_file, 'rb');
        $records = $this->database->prepare('SELECT registrar_producto(:amount, :name, :description, :price, :image) AS exito;');
        $records->bindParam(':amount', $product_amount);
        $records->bindParam(':name', $product_name);
        $records->bindParam(':description', $product_description);
        $records->bindParam(':price', $product_price);
        $records->bindParam(':image', $file, PDO::PARAM_LOB);
        $records->execute();
        $results = $records->fetch(PDO::FETCH_ASSOC);
        if ($results) {
            if (count($results) > 0) {
                return $results["exito"];
            }
        }
        return false;
    }

    public function view_product(int $id): ?Product
    {
        $records = $this->database->prepare('SELECT cantidad, nombre, descripcion, precio, activo, imagen FROM inventario WHERE id = :v_id;');
        $records->bindParam(':v_id', $id);
        // $records->bindColumn(6, $lob, PDO::PARAM_STR);
        $records->execute();
        $result = $records->fetch(PDO::FETCH_ASSOC);
        if (count($result) !== 0) {
            return new Product($id, $result["cantidad"], $result["nombre"], $result["descripcion"], $result["precio"], $result["activo"], $result["imagen"]);
        }
        return null;
    }
}