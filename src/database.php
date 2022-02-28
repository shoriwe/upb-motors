<?php

require_once 'tables.php';
require_once 'cache.php';
const Gerente = 1;
const RecursosHumanos = 2;
const Ventas = 3;
const Inventario = 4;
const Admin = 5;

function get_permission_name(int $permission): string
{
    switch ($permission) {
        case Gerente:
            return "Gerente";
        case RecursosHumanos:
            return "Recursos Humanos";
        case Ventas:
            return "Ventas";
        case Inventario:
            return "Inventario";
        case Admin:
            return "Admin";
    }
    return "Unknown";
}

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

    public function search_employees(string $employee_name, string $employee_personal_id): array;

    public function search_clients(string $client_name, string $employee_personal_id): array;

    public function view_user(int $user_id): ?Employee;

    public function view_client(int $client_id): ?Client;

    public function update_user(int $id, int $permission, string $name, string $personal_id, string $address, string $phone, string $email, string $password, bool $enabled): bool;

    public function update_client(int $id, string $name, string $personal_id, string $address, string $phone, string $email, bool $enabled): bool;

    public function view_product(int $id): ?Product;

    public function update_product(int $id, int $amount, string $name, string $description, float $price, bool $active): bool;

    public function add_inventory(string $product_name, int $product_amount, string $product_description, int $product_price, string $image_file): bool;

    public function is_gerente(int $user_id): bool;

    public function is_recursos_humanos(int $user_id): bool;

    public function is_ventas(int $user_id): bool;

    public function is_inventario(int $user_id): bool;

    public function is_admin(int $user_id): bool;

    public function update_password(int $user_id, string $old_password, string $new_password): ?string;

    public function log_login_succeed(string $email);

    public function log_login_failed(string $email);

    public function login(string $email, string $password): ?int;

    public function request_password_reset(string $email): ?string;

    public function reset_password(string $code, string $new_password): bool;

    public function register_user(int $permission, string $name, string $personal_id, string $address, string $phone, string $email, string $password): bool;

    public function register_client(string $name, string $personal_id, string $address, string $phone, string $email): bool;
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

    public function is_admin(int $user_id): bool
    {
        // TODO: Implement is_admin() method.
        return false;
    }

    public function register_user(int $permission, string $name, string $personal_id, string $address, string $phone, string $email, string $password): bool
    {
        // TODO: Implement register_user() method.
        return false;
    }

    public function search_employees(string $employee_name, string $employee_personal_id): array
    {
        // TODO: Implement search_employees() method.
        return array();
    }

    public function view_user(int $user_id): ?Employee
    {
        // TODO: Implement view_user() method.
        return null;
    }

    public function update_user(int $id, int $permission, string $name, string $personal_id, string $address, string $phone, string $email, string $password, bool $enabled): bool
    {
        // TODO: Implement update_user() method.
        return false;
    }

    public function update_product(int $id, int $amount, string $name, string $description, float $price, bool $active): bool
    {
        // TODO: Implement update_product() method.
        return false;
    }

    public function register_client(string $name, string $personal_id, string $address, string $phone, string $email): bool
    {
        // TODO: Implement register_client() method.
        return false;
    }

    public function search_clients(string $client_name, string $employee_personal_id): array
    {
        // TODO: Implement search_clients() method.
        return array();
    }

    public function view_client(int $client_id): ?Client
    {
        // TODO: Implement view_client() method.
        return null;
    }

    public function update_client(int $id, string $name, string $personal_id, string $address, string $phone, string $email, bool $enabled): bool
    {
        // TODO: Implement update_client() method.
        return false;
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
        $records = $this->database->prepare('SELECT id, hash_contrasena FROM empleados WHERE correo = :email AND habilitado = true LIMIT 1;');
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

    public function is_admin(int $user_id): bool
    {
        return $this->get_user_permissions($user_id) === Admin;
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
        $records->execute();
        $result = $records->fetch(PDO::FETCH_ASSOC);
        if (count($result) !== 0) {
            return new Product($id, $result["cantidad"], $result["nombre"], $result["descripcion"], $result["precio"], $result["activo"], $result["imagen"]);
        }
        return null;
    }

    public function register_user(int $permission, string $name, string $personal_id, string $address, string $phone, string $email, string $password): bool
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $records = $this->database->prepare('SELECT registrar_empleado(:permission, :name, :personal_id, :address, :phone, :email, :password_hash) AS result');
        $records->bindParam(':permission', $permission);
        $records->bindParam(':name', $name);
        $records->bindParam(':personal_id', $personal_id);
        $records->bindParam(':address', $address);
        $records->bindParam(':phone', $phone);
        $records->bindParam(':email', $email);
        $records->bindParam(':password_hash', $hash);
        try {
            $records->execute();
            $result = $records->fetch(PDO::FETCH_ASSOC);
            if (count($result) !== 0) {
                return $result["result"];
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function search_employees(string $employee_name, string $employee_personal_id): array
    {
        $employees = array();
        if (strlen($employee_name) > 0) {
            $employee_name = "%" . $employee_name . "%";
            $records = $this->database->prepare('SELECT id, permisos_id, nombre_completo, cedula, direccion, telefono, correo, hash_contrasena, habilitado FROM empleados WHERE LOWER(nombre_completo) LIKE :name;');
            $records->bindParam(':name', $employee_name);
            $records->execute();
            while ($row = $records->fetch(PDO::FETCH_ASSOC)) {
                if (count($row) === 0) {
                    break;
                }
                $employees[] = new Employee(
                    $row["id"],
                    $row["permisos_id"],
                    $row["nombre_completo"],
                    $row["cedula"],
                    $row["direccion"],
                    $row["telefono"],
                    $row["correo"],
                    $row["hash_contrasena"],
                    $row["habilitado"]
                );
            }
        } else if (strlen($employee_personal_id) > 0) {
            $employee_personal_id = "%" . $employee_personal_id . "%";
            $records = $this->database->prepare('SELECT id, permisos_id, nombre_completo, cedula, direccion, telefono, correo, hash_contrasena, habilitado FROM empleados WHERE LOWER(cedula) LIKE :personal_id;');
            $records->bindParam(':personal_id', $employee_personal_id);
            $records->execute();
            while ($row = $records->fetch(PDO::FETCH_ASSOC)) {
                if (count($row) === 0) {
                    break;
                }
                $employees[] = new Employee(
                    $row["id"],
                    $row["permisos_id"],
                    $row["nombre_completo"],
                    $row["cedula"],
                    $row["direccion"],
                    $row["telefono"],
                    $row["correo"],
                    $row["hash_contrasena"],
                    $row["habilitado"]
                );
            }
        }
        return $employees;
    }

    public function view_user(int $user_id): ?Employee
    {
        $records = $this->database->prepare('SELECT permisos_id, nombre_completo, cedula, direccion, telefono, correo, hash_contrasena, habilitado FROM empleados WHERE id = :v_id;');
        $records->bindParam(':v_id', $user_id);
        $records->execute();
        $result = $records->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            if (count($result) !== 0) {
                return new Employee(
                    $user_id,
                    $result["permisos_id"],
                    $result["nombre_completo"],
                    $result["cedula"],
                    $result["direccion"],
                    $result["telefono"],
                    $result["correo"],
                    $result["hash_contrasena"],
                    $result["habilitado"]
                );
            }
        }
        return null;
    }

    public function update_user(int $id, int $permission, string $name, string $personal_id, string $address, string $phone, string $email, string $password, bool $enabled): bool
    {
        $hash_of_password = null;
        if (strlen($password) !== 0) {
            $hash_of_password = password_hash($password, PASSWORD_DEFAULT);
        }
        $records = $this->database->prepare('SELECT update_user(:id, :permission, :name, :personal_id, :address, :phone, :email, :password, :enabled) AS result;');
        $records->bindParam(':id', $id);
        $records->bindParam(':permission', $permission);
        $records->bindParam(':name', $name);
        $records->bindParam(':personal_id', $personal_id);
        $records->bindParam(':address', $address);
        $records->bindParam(':phone', $phone);
        $records->bindParam(':email', $email);
        $records->bindParam(':password', $hash_of_password);
        $records->bindParam(':enabled', $enabled, PDO::PARAM_BOOL);
        $records->execute();
        $result = $records->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            if (count($result) !== 0) {
                return $result["result"];
            }
        }
        return false;
    }

    public function update_product(int $id, int $amount, string $name, string $description, float $price, bool $active): bool
    {
        $records = $this->database->prepare('SELECT update_product(:id, :amount, :name, :description, :price, :active) AS result;');
        $records->bindParam(':id', $id);
        $records->bindParam(':amount', $amount);
        $records->bindParam(':name', $name);
        $records->bindParam(':description', $description);
        $records->bindParam(':price', $price);
        $records->bindParam(':active', $active, PDO::PARAM_BOOL);
        $records->execute();
        $result = $records->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            if (count($result) !== 0) {
                return $result["result"];
            }
        }
        return false;
    }

    public function register_client(string $name, string $personal_id, string $address, string $phone, string $email): bool
    {
        $records = $this->database->prepare('SELECT registrar_cliente(:name, :personal_id, :address, :phone, :email) AS result');
        $records->bindParam(':name', $name);
        $records->bindParam(':personal_id', $personal_id);
        $records->bindParam(':address', $address);
        $records->bindParam(':phone', $phone);
        $records->bindParam(':email', $email);
        try {
            $records->execute();
            $result = $records->fetch(PDO::FETCH_ASSOC);
            if (count($result) !== 0) {
                return $result["result"];
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function search_clients(string $client_name, string $employee_personal_id): array
    {
        $employees = array();
        if (strlen($client_name) > 0) {
            $client_name = "%" . $client_name . "%";
            $records = $this->database->prepare('SELECT id, nombre_completo, cedula, direccion, telefono, correo, habilitado FROM clientes WHERE LOWER(nombre_completo) LIKE :name;');
            $records->bindParam(':name', $client_name);
            $records->execute();
            while ($row = $records->fetch(PDO::FETCH_ASSOC)) {
                if (count($row) === 0) {
                    break;
                }
                $employees[] = new Client(
                    $row["id"],
                    $row["nombre_completo"],
                    $row["cedula"],
                    $row["direccion"],
                    $row["telefono"],
                    $row["correo"],
                    $row["habilitado"]
                );
            }
        } else if (strlen($employee_personal_id) > 0) {
            $employee_personal_id = "%" . $employee_personal_id . "%";
            $records = $this->database->prepare('SELECT id, nombre_completo, cedula, direccion, telefono, correo, habilitado FROM clientes WHERE LOWER(cedula) LIKE :personal_id;');
            $records->bindParam(':personal_id', $employee_personal_id);
            $records->execute();
            while ($row = $records->fetch(PDO::FETCH_ASSOC)) {
                if (count($row) === 0) {
                    break;
                }
                $employees[] = new Client(
                    $row["id"],
                    $row["nombre_completo"],
                    $row["cedula"],
                    $row["direccion"],
                    $row["telefono"],
                    $row["correo"],
                    $row["habilitado"]
                );
            }
        }
        return $employees;
    }

    public function view_client(int $client_id): ?Client
    {
        $records = $this->database->prepare('SELECT nombre_completo, cedula, direccion, telefono, correo, habilitado FROM clientes WHERE id = :v_id;');
        $records->bindParam(':v_id', $client_id);
        $records->execute();
        $result = $records->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            if (count($result) !== 0) {
                return new Client(
                    $client_id,
                    $result["nombre_completo"],
                    $result["cedula"],
                    $result["direccion"],
                    $result["telefono"],
                    $result["correo"],
                    $result["habilitado"]
                );
            }
        }
        return null;
    }

    public function update_client(int $id, string $name, string $personal_id, string $address, string $phone, string $email, bool $enabled): bool
    {
        $records = $this->database->prepare('SELECT update_client(:id, :name, :personal_id, :address, :phone, :email, :enabled) AS result;');
        $records->bindParam(':id', $id);
        $records->bindParam(':name', $name);
        $records->bindParam(':personal_id', $personal_id);
        $records->bindParam(':address', $address);
        $records->bindParam(':phone', $phone);
        $records->bindParam(':email', $email);
        $records->bindParam(':enabled', $enabled, PDO::PARAM_BOOL);
        $records->execute();
        $result = $records->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            if (count($result) !== 0) {
                return $result["result"];
            }
        }
        return false;
    }
}