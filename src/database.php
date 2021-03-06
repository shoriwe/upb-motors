<?php

require_once 'tables.php';
require_once 'cache.php';
const Gerente = 1;
const RecursosHumanos = 2;
const Ventas = 3;
const Inventario = 4;
const Admin = 5;
const AUTH = "AUTH";
const ERROR = "ERROR";
const LOG = "LOG";

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

    public function log(string $level, string $message);

    public function get_dependency_id(string $dependency_name): int;

    public function get_dependency_name(int $dependency_id): string;

    public function list_dependencies(): array;

    public function search_employees(string $employee_name, string $employee_personal_id): array;

    public function search_clients(string $client_name, string $employee_personal_id): array;

    public function view_user(int $user_id): ?Employee;

    public function view_client(int $client_id): ?Client;

    public function update_user(int $id, int $permission, string $name, string $personal_id, string $address, string $phone, string $email, string $password, bool $enabled): bool;

    public function update_client(int $id, string $name, string $personal_id, string $address, string $phone, string $email, bool $enabled): bool;

    public function view_product(int $id): ?Product;

    public function update_product(int $id, int $amount, string $name, string $description, float $price, bool $active, int $dependencia): bool;

    public function add_inventory(string $product_name, int $product_amount, string $product_description, int $product_price, string $image_file, int $dependencia): bool;

    public function is_gerente(int $user_id): bool;

    public function is_recursos_humanos(int $user_id): bool;

    public function is_ventas(int $user_id): bool;

    public function is_inventario(int $user_id): bool;

    public function is_admin(int $user_id): bool;

    public function update_password(int $user_id, string $old_password, string $new_password): ?string;

    public function login(string $email, string $password): ?int;

    public function request_password_reset(string $email): ?string;

    public function reset_password(string $code, string $new_password): bool;

    public function register_user(int $permission, string $name, string $personal_id, string $address, string $phone, string $email, string $password): bool;

    public function register_client(string $name, string $personal_id, string $address, string $phone, string $email): bool;

    public function cancel_purchase(int $o_cliente_id, int $o_empleado_id, string $fecha, bool $o_enabled): bool;

    public function get_price_history(int $product_id): ?array;

    public function id_orden(int $empleado, int $cliente): ?int;

    public function lista_pagos(): array;

    public function registrar_orden_producto(int $producto, int $cantidad, int $id_orden, int $pagos): bool;

    public function delete_orden(int $id): bool;

    public function delete_detalles_orden(int $id): bool;

    public function buscar_orden_empleado(string $empleado): array;

    public function get_employee_name(int $id): ?string;

    public function get_client_name(int $id): ?string;

    public function view_orden(int $orden_id): ?Lista_ordenes;

    public function details_view_orden(int $orden_id): array;

    public function get_product(int $id): ?Product;

    public function get_tipo_pago(int $id): ?string;

    public function close_orden(int $id): bool;

    public function registrar_factura(int $empleado, int $cliente, string $hoy, float $descuento): bool;

    public function id_factura(int $empleado, int $cliente): ?int;

    public function registrar_factura_producto(int $producto, int $cantidad, int $id_orden, int $pagos): bool;

    public function delete_factura(int $id): bool;

    public function delete_detalles_factura(int $id): bool;

    public function registrar_orden(int $empleado, int $cliente, string $hoy, float $descuento): bool;

    public function buscar_factura_empleado(string $empleado): array;

    public function view_factura(int $factura_id): ?Lista_facturas;

    public function details_view_factura(int $factura_id): array;

    public function close_factura(int $id): bool;

    public function buscar_factura_cliente(string $cliente): array;

    public function registrar_gasto(int $valor, string $razon): bool;

    public function lista_gastos(): array;

    public function get_ventas_caja(): ?int;

    public function get_ventas_bancos(): ?int;

    public function get_costos_ventas(): ?int;

    public function get_gastos(): ?int;

    public function get_informe_inventario(): array;

    public function get_total_productos(): ?int;

    public function get_total_precio(): ?int;

    public function get_ventas_credito(): ?int;

    public function get_cuentas_cobrar(): array;
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
                $this->log(AUTH, "Login succeed as " . $email);
                return $results["id"];
            }
        }
        $this->log(AUTH, "Login failed as " . $email);
        return null;
    }

    public function log(string $level, string $message)
    {
        if (headers_sent()) {
            $user_id = $_SESSION["user-id"];
            $message = "(Done by user identified by ID: $user_id) $message";
        }
        $records = $this->database->prepare('CALL log(:level, :message)');
        $records->bindParam(':level', $level);
        $records->bindParam(':message', $message);
        $records->execute();
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
                $this->log(AUTH, "Password reset code generated for " . $email);
                return $code;
            }
        }
        $this->log(AUTH, "Could not generate password reset code for " . $email);
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
                    $this->log(AUTH, "Successfully update password identified by $user_id");
                    return null;
                } else {
                    $this->log(AUTH, "Failed to update password for user identified by $user_id cause old password doesn't match");
                    return "contrasena antigua es incorrecta";
                }
            }
        }
        $this->log(AUTH, "Failed to update password for user identified by $user_id cause he is a thief");
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
        $this->log(ERROR, "Failed to request permissions for user identified by $user_id");
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
        $this->log(LOG, "Searching products with name similar to $product_name");
        $product_name = "%" . $product_name . "%";
        $records = $this->database->prepare('SELECT id, cantidad, nombre, descripcion, precio, activo, dependencia_id FROM inventario WHERE LOWER(nombre) LIKE :name;');
        $records->bindParam(':name', $product_name);
        $records->execute();
        $products = array();
        while ($row = $records->fetch(PDO::FETCH_ASSOC)) {
            if (count($row) === 0) {
                break;
            }
            $products[] = new Product($row["id"], $row["cantidad"], $row["nombre"], $row["descripcion"], $row["precio"], $row["activo"], null, $row["dependencia_id"]);
        }
        return $products;
    }

    public function add_inventory(string $product_name, int $product_amount, string $product_description, int $product_price, string $image_file, int $dependencia): bool
    {
        $this->log(LOG, "Adding $product_name to inventory");
        $file = fopen($image_file, 'rb');
        $records = $this->database->prepare('SELECT registrar_producto(:amount, :name, :description, :price, :image, :dependencia) AS exito;');
        $records->bindParam(':amount', $product_amount);
        $records->bindParam(':name', $product_name);
        $records->bindParam(':description', $product_description);
        $records->bindParam(':price', $product_price);
        $records->bindParam(':image', $file, PDO::PARAM_LOB);
        $records->bindParam(':dependencia', $dependencia);
        $records->execute();
        $results = $records->fetch(PDO::FETCH_ASSOC);
        if ($results) {
            if (count($results) > 0) {
                $this->log(LOG, "Successfully added $product_name to inventory");
                return $results["exito"];
            }
        }
        $this->log(LOG, "Failed to add $product_name to inventory");
        return false;
    }

    public function view_product(int $id): ?Product
    {
        $records = $this->database->prepare('SELECT cantidad, nombre, descripcion, precio, activo, imagen, dependencia_id FROM inventario WHERE id = :v_id;');
        $records->bindParam(':v_id', $id);
        $records->execute();
        $result = $records->fetch(PDO::FETCH_ASSOC);
        if (count($result) !== 0) {
            $this->log(LOG, "View product identified by $id");
            return new Product($id, $result["cantidad"], $result["nombre"], $result["descripcion"], $result["precio"], $result["activo"], $result["imagen"], $result["dependencia_id"]);
        }
        $this->log(ERROR, "Failed to view product identified by $id");
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
                if ($result["result"]) {
                    $this->log(LOG, "Successfully registered user $email");
                } else {
                    $this->log(ERROR, "Failed to register user $email");
                }
                return $result["result"];
            }
            $this->log(ERROR, "Failed to register user $email");
            return false;
        } catch (Exception $e) {
            $this->log(ERROR, "Failed to register user $email");
            return false;
        }
    }

    public function view_user(int $user_id): ?Employee
    {
        $records = $this->database->prepare('SELECT permisos_id, nombre_completo, cedula, direccion, telefono, correo, hash_contrasena, habilitado FROM empleados WHERE id = :v_id;');
        $records->bindParam(':v_id', $user_id);
        $records->execute();
        $result = $records->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            if (count($result) !== 0) {
                $this->log(LOG, "Successfully query information for user identified by $user_id");
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
        $this->log(ERROR, "Failed to query information for user identified by $user_id");
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
                if ($result["result"]) {
                    $this->log(LOG, "Successfully updated information for user identified by $id");
                    return $result["result"];
                }
            }
        }
        $this->log(ERROR, "Failed to update information for user identified by $id");
        return false;
    }

    public function update_product(int $id, int $amount, string $name, string $description, float $price, bool $active, int $dependencia): bool
    {
        $records = $this->database->prepare('SELECT update_product(:id, :amount, :name, :description, :price, :active, :dependencia) AS result;');
        $records->bindParam(':id', $id);
        $records->bindParam(':amount', $amount);
        $records->bindParam(':name', $name);
        $records->bindParam(':description', $description);
        $records->bindParam(':price', $price);
        $records->bindParam(':active', $active, PDO::PARAM_BOOL);
        $records->bindParam(':dependencia', $dependencia);
        $records->execute();
        $result = $records->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            if (count($result) !== 0) {
                if ($result["result"]) {
                    $this->log(LOG, "Successfully updated information for product identified by $id");
                    return $result["result"];
                }
            }
        }
        $this->log(ERROR, "Failed to update information for product identified by $id");
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
                if ($result["result"]) {
                    $this->log(LOG, "Successfully registered client $personal_id");
                    return $result["result"];
                }
            }
        } catch (Exception $e) {
        }
        $this->log(ERROR, "Failed to register client $personal_id");
        return false;
    }

    public function view_client(int $client_id): ?Client
    {
        $records = $this->database->prepare('SELECT nombre_completo, cedula, direccion, telefono, correo, habilitado FROM clientes WHERE id = :v_id;');
        $records->bindParam(':v_id', $client_id);
        $records->execute();
        $result = $records->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            if (count($result) !== 0) {
                $this->log(LOG, "Successfully view client $client_id");
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
        $this->log(ERROR, "Failed to view client $client_id");
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
                if ($result["result"]) {
                    $this->log(LOG, "Successfully updated client $id");
                    return $result["result"];
                }
            }
        }
        $this->log(ERROR, "Failed to update client $id");
        return false;
    }

    public function lista_productos(): array
    {
        $records = $this->database->prepare('SELECT id,nombre FROM inventario;');
        $records->execute();
        $products = array();
        while ($row = $records->fetch(PDO::FETCH_ASSOC)) {
            if (count($row) === 0) {
                break;
            }
            $products[] = new Lis_Product($row["id"], $row["nombre"]);
        }
        return $products;
    }

    public function lista_clientes(): array
    {
        $records = $this->database->prepare('SELECT id,nombre_completo FROM clientes;');
        $records->execute();
        $clientes = array();
        while ($row = $records->fetch(PDO::FETCH_ASSOC)) {
            if (count($row) === 0) {
                break;
            }
            $clientes[] = new Lis_Clients($row["id"], $row["nombre_completo"]);
        }
        return $clientes;
    }

    public function lista_empleados(): array
    {
        $records = $this->database->prepare('SELECT id,nombre_completo FROM empleados WHERE permisos_id = 3;');
        $records->execute();
        $empleados = array();
        while ($row = $records->fetch(PDO::FETCH_ASSOC)) {
            if (count($row) === 0) {
                break;
            }
            $empleados[] = new Lis_Empleados($row["id"], $row["nombre_completo"]);
        }
        return $empleados;
    }

    public function registrar_orden(int $empleado, int $cliente, string $hoy, float $descuento): bool
    {
        $records = $this->database->prepare('SELECT registrar_orden(:empleado, :cliente, :hoy,:descuento) AS result');
        $records->bindParam(':empleado', $empleado);
        $records->bindParam(':cliente', $cliente);
        $records->bindParam(':hoy', $hoy);
        $records->bindParam(':descuento', $descuento);
        try {
            $records->execute();
            $result = $records->fetch(PDO::FETCH_ASSOC);
            if (count($result) !== 0) {
                if ($result["result"]) {
                    $this->log(LOG, "Successfully registered order for client $cliente by employee $empleado");
                    return $result["result"];
                }
            }

        } catch (Exception $e) {
            $this->log(ERROR, "System error $e");
        }
        $this->log(ERROR, "Failed to register order for client $cliente by employee $empleado");
        return false;
    }

    public function cancel_purchase(int $o_cliente_id, int $o_empleado_id, string $fecha, bool $o_enabled): bool
    {
        $records = $this->database->prepare('SELECT cancel_purchase(:o_cliente_id, :o_empleado_id, :fecha, :o_enabled)');
        $records->bindParam(':o_cliente_id', $o_cliente_id);
        $records->bindParam(':o_empleado_id', $o_empleado_id);
        $records->bindParam(':fecha', $fecha);
        $records->bindParam(':o_enabled', $o_enabled);

        try {
            $records->execute();
            $result = $records->fetch(PDO::FETCH_ASSOC);
            if (count($result) !== 0) {
                if ($result["result"]) {
                    $this->log(LOG, "Successfully canceled order identified by $o_cliente_id");
                    return $result["result"];
                }
            }

        } catch (Exception $e) {
            $this->log(ERROR, "System error $e");
        }
        $this->log(ERROR, "Failed to cancel order identified by $o_cliente_id");
        return false;
    }

    public function id_orden(int $empleado, int $cliente): int
    {
        $records = $this->database->prepare('SELECT id FROM ordenes_compra WHERE empleados_id = :empleado AND clientes_id = :cliente ORDER BY id DESC LIMIT 1;');
        $records->bindParam(':empleado', $empleado);
        $records->bindParam(':cliente', $cliente);
        $records->execute();
        return $records->fetch(PDO::FETCH_ASSOC)["id"];
    }

    public function lista_pagos(): array
    {
        $records = $this->database->prepare('SELECT id,pago FROM tipo_pago_orden;');
        $records->execute();
        $pago = array();
        while ($row = $records->fetch(PDO::FETCH_ASSOC)) {
            if (count($row) === 0) {
                break;
            }
            $pago[] = new Lis_tipo_pago($row["id"], $row["pago"]);
        }
        return $pago;
    }

    public function registrar_orden_producto(int $producto, int $cantidad, int $id_orden, int $pagos): bool
    {
        $detalles_producto = $this->database->prepare('SELECT cantidad,precio,activo FROM inventario WHERE id = :id_producto;');
        $detalles_producto->bindParam(':id_producto', $producto);
        $detalles_producto->execute();
        $row_producto = $detalles_producto->fetch(PDO::FETCH_ASSOC);
        $cantidad_actual_producto = $row_producto['cantidad'];
        $precio_producto = $row_producto['precio'];
        $activo_producto = $row_producto['activo'];

        if ($activo_producto == 1) {

            if ($cantidad_actual_producto >= $cantidad) {
                $nueva_cantidad = $cantidad_actual_producto - $cantidad;
                $total = $cantidad * $precio_producto;
                $insertar = $this->database->prepare('SELECT registrar_detalles_orden(:cantidad, :total,:producto, :pagos, :orden) AS result');
                $insertar->bindParam(':cantidad', $cantidad);
                $insertar->bindParam(':total', $total);
                $insertar->bindParam(':producto', $producto);
                $insertar->bindParam(':pagos', $pagos);
                $insertar->bindParam(':orden', $id_orden);
                try {
                    $insertar->execute();
                    $result = $insertar->fetch(PDO::FETCH_ASSOC);
                    if (count($result) !== 0) {
                        if ($result["result"]) {
                            $this->log(LOG, "Successfully registered product in order");
                            return $result["result"];
                        }
                    }

                } catch (Exception $e) {
                    $this->log(ERROR, "System error $e");
                }
            }
        }
        $this->log(ERROR, "Failed to register product in order");
        return false;
    }

    public function delete_orden(int $id): bool
    {
        $records = $this->database->prepare('DELETE FROM ordenes_compra WHERE id = :id;');
        $records->bindParam(':id', $id);
        $records->execute();
        try {
            $records->execute();
            $this->log(LOG, "Successfully delete order identified by $id");
            return true;
        } catch (Exception $e) {
            $this->log(ERROR, "System error $e");
        }
        return false;
    }

    public function get_price_history(int $product_id): ?array
    {
        $this->log(LOG, "Get price history for product identified by $product_id");
        $records = $this->database->prepare('SELECT id, modification_date, inventario_id, precio FROM historial_precios WHERE inventario_id = :product_id ORDER BY id DESC;');
        $records->bindParam(':product_id', $product_id);
        $records->execute();
        $precios = array();
        while ($row = $records->fetch(PDO::FETCH_ASSOC)) {
            if (count($row) === 0) {
                break;
            }
            $precios[] = new PriceHistory($row["id"], strtotime($row["modification_date"]), $row["inventario_id"], $row["precio"]);
        }
        return $precios;
    }

    public function delete_detalles_orden(int $id): bool
    {
        $records = $this->database->prepare('DELETE FROM detalles_ordenes_compra WHERE orden_compra_id = :id;');
        $records->bindParam(':id', $id);
        $records->execute();
        try {
            $records->execute();
            $this->log(LOG, "Successfully deleted details for order identified by $id");
            return true;
        } catch (Exception $e) {
            $this->log(ERROR, "System error $e");
        }
        return false;
    }

    public function buscar_orden_empleado(string $empleado): array
    {
        $this->log(LOG, "Searching for orders by employee $empleado");
        $empleados = $this->search_employees($empleado, "");
        $ordenes = array();
        foreach ($empleados as $empleado) {
            $orden = $this->database->prepare('SELECT id,fecha,empleados_id,clientes_id,decuento,abierta FROM ordenes_compra WHERE empleados_id LIKE :empleados_id;');
            $orden->bindParam(':empleados_id', $empleado->id);
            $orden->execute();
            while ($rowOrd = $orden->fetch(PDO::FETCH_ASSOC)) {
                if (count($rowOrd) === 0) {
                    break;
                }
                $ordenes[] = new Lista_ordenes($rowOrd["id"], $rowOrd["fecha"], $rowOrd["empleados_id"], $rowOrd["clientes_id"], $rowOrd["decuento"], $rowOrd["abierta"]);;
            }
        }
        return $ordenes;
    }

    public function search_employees(string $employee_name, string $employee_personal_id): array
    {
        $this->log(LOG, "Searching employees");
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

    public function get_employee_name(int $id): ?string
    {
        $this->log(LOG, "Getting name of employee identified by $id");
        $empleado = $this->database->prepare('SELECT nombre_completo FROM empleados WHERE id = :id;');
        $empleado->bindParam(':id', $id);
        $empleado->execute();
        $row = $empleado->fetch(PDO::FETCH_ASSOC);
        if (count($row) !== 0) {
            $this->log(LOG, "Successfully get name of employee identified by $id");
            return $row["nombre_completo"];
        }
        $this->log(ERROR, "Failed to get name of employee identified by $id");
        return null;
    }

    public function get_client_name(int $id): ?string
    {
        $this->log(LOG, "Getting name of client identified by $id");
        $cliente = $this->database->prepare('SELECT nombre_completo FROM clientes WHERE id = :id;');
        $cliente->bindParam(':id', $id);
        $cliente->execute();
        $row = $cliente->fetch(PDO::FETCH_ASSOC);
        if (count($row) !== 0) {
            $this->log(LOG, "Successfully get name of client identified by $id");
            return $row["nombre_completo"];
        }
        $this->log(ERROR, "Failed to get name of client identified by $id");
        return null;
    }

    public function view_orden(int $orden_id): ?Lista_ordenes
    {
        $records = $this->database->prepare('SELECT fecha,empleados_id,clientes_id,decuento,abierta FROM ordenes_compra WHERE id = :id;');
        $records->bindParam(':id', $orden_id);
        $records->execute();
        $result = $records->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            if (count($result) !== 0) {
                $this->log(LOG, "Successfully view order identified by $orden_id");
                return new Lista_ordenes(
                    $orden_id,
                    $result["fecha"],
                    $result["empleados_id"],
                    $result["clientes_id"],
                    $result["decuento"],
                    $result["abierta"]
                );
            }
        }
        $this->log(ERROR, "Failed to view order identified by $orden_id");
        return null;
    }

    public function details_view_orden(int $orden_id): array
    {
        $this->log(LOG, "Query details for order identified by $orden_id");
        $records = $this->database->prepare('SELECT cantidad,valor_total,productos_id,tipo_pago_id FROM detalles_ordenes_compra WHERE orden_compra_id = :id;');
        $records->bindParam(':id', $orden_id);
        $records->execute();
        $details_view_orden = array();
        while ($row = $records->fetch(PDO::FETCH_ASSOC)) {
            if (count($row) === 0) {
                break;
            }
            $details_view_orden[] = new orden_detalles(
                $row["cantidad"],
                $row["valor_total"],
                $row["productos_id"],
                $row["tipo_pago_id"],
                $orden_id
            );;
        }
        return $details_view_orden;
    }

    public function get_product(int $id): ?Product
    {
        $records = $this->database->prepare('SELECT cantidad, nombre, descripcion, precio, activo, imagen, dependencia_id FROM inventario WHERE id = :id;');
        $records->bindParam(':id', $id);
        $records->execute();
        $result = $records->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            if (count($result) !== 0) {
                $this->log(LOG, "Successfully view product identified by $id");
                return new Product($id,
                    $result["cantidad"],
                    $result["nombre"],
                    $result["descripcion"],
                    $result["precio"],
                    $result["activo"],
                    $result["imagen"],
                    $result["dependencia_id"],
                );
            }
        }
        $this->log(ERROR, "Failed to view product identified by $id");
        return null;
    }

    public function get_tipo_pago(int $id): ?string
    {
        $tipo_pago = $this->database->prepare('SELECT pago FROM tipo_pago_orden WHERE id = :id;');
        $tipo_pago->bindParam(':id', $id);
        $tipo_pago->execute();
        $row = $tipo_pago->fetch(PDO::FETCH_ASSOC);
        $this->log(LOG, "Successfully get payment type name identified by $id");
        if (count($row) !== 0) {
            return $row["pago"];
        }
        $this->log(ERROR, "Failed to get payment type name identified by $id");
        return null;
    }

    public function close_orden(int $id): bool
    {
        $records = $this->database->prepare('SELECT close_orden(:id) AS result');
        $records->bindParam(':id', $id);
        $records->execute();
        $row = $records->fetch(PDO::FETCH_ASSOC);
        if (count($row) > 0) {
            if ($row["result"]) {
                $this->log(LOG, "Successfully closed order identified by $id");
                return $row["result"];
            }
        }
        $this->log(ERROR, "Failed to close order identified by $id");
        return false;
    }

    public function buscar_orden_cliente(string $cliente): array
    {
        $this->log(LOG, "Searching orders by client $cliente");
        $clientes = $this->search_clients($cliente, "");
        $ordenes = array();
        foreach ($clientes as $cliente) {
            $orden = $this->database->prepare('SELECT id,fecha,empleados_id,clientes_id,decuento,abierta FROM ordenes_compra WHERE clientes_id LIKE :clientes_id;');
            $orden->bindParam(':clientes_id', $cliente->id);
            $orden->execute();
            while ($rowOrd = $orden->fetch(PDO::FETCH_ASSOC)) {
                if (count($rowOrd) === 0) {
                    break;
                }
                $ordenes[] = new Lista_ordenes($rowOrd["id"], $rowOrd["fecha"], $rowOrd["empleados_id"], $rowOrd["clientes_id"], $rowOrd["decuento"], $rowOrd["abierta"]);;
            }
        }
        return $ordenes;
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

    public function registrar_factura(int $empleado, int $cliente, string $hoy, float $descuento): bool
    {
        $records = $this->database->prepare('SELECT registrar_factura(:empleado, :cliente, :hoy,:descuento) AS result');
        $records->bindParam(':empleado', $empleado);
        $records->bindParam(':cliente', $cliente);
        $records->bindParam(':hoy', $hoy);
        $records->bindParam(':descuento', $descuento);
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

    public function id_factura(int $empleado, int $cliente): int
    {
        $records = $this->database->prepare('SELECT id FROM facturas WHERE empleados_id = :empleado AND clientes_id = :cliente ORDER BY id DESC LIMIT 1;');
        $records->bindParam(':empleado', $empleado);
        $records->bindParam(':cliente', $cliente);
        $records->execute();
        return $records->fetch(PDO::FETCH_ASSOC)["id"];
    }

    public function registrar_factura_producto(int $producto, int $cantidad, int $id_factura, int $pagos): bool
    {
        $detalles_producto = $this->database->prepare('SELECT cantidad,precio,activo FROM inventario WHERE id = :id_producto;');
        $detalles_producto->bindParam(':id_producto', $producto);
        $detalles_producto->execute();
        $row_producto = $detalles_producto->fetch(PDO::FETCH_ASSOC);
        $cantidad_actual_producto = $row_producto['cantidad'];
        $precio_producto = $row_producto['precio'];
        $activo_producto = $row_producto['activo'];

        if ($activo_producto == 1) {

            if ($cantidad_actual_producto >= $cantidad) {
                $nueva_cantidad = $cantidad_actual_producto - $cantidad;
                $total = $cantidad * $precio_producto;
                $actualizar_producto = $this->database->prepare('SELECT actualizar_cantidad_orden(:producto, :cantidad)');
                $actualizar_producto->bindParam(':producto', $producto);
                $actualizar_producto->bindParam(':cantidad', $nueva_cantidad);
                $actualizar_producto->execute();
                $insertar_factura = $this->database->prepare('SELECT registrar_detalles_factura(:cantidad, :total,:producto, :pagos, :factura) AS result');
                $insertar_factura->bindParam(':cantidad', $cantidad);
                $insertar_factura->bindParam(':total', $total);
                $insertar_factura->bindParam(':producto', $producto);
                $insertar_factura->bindParam(':pagos', $pagos);
                $insertar_factura->bindParam(':factura', $id_factura);
                try {
                    $insertar_factura->execute();
                    $result = $insertar_factura->fetch(PDO::FETCH_ASSOC);
                    if (count($result) !== 0) {
                        if ($result["result"]) {
                            $this->log(LOG, "Successfully registered product in receipt");
                        }
                        return $result["result"];
                    }

                } catch (Exception $e) {
                    $this->log(ERROR, "System error $e");
                }
            }
        }
        $this->log(ERROR, "Failed to register product in receipt");
        return false;
    }

    public function delete_factura(int $id): bool
    {
        $records = $this->database->prepare('DELETE FROM facturas WHERE id = :id;');
        $records->bindParam(':id', $id);
        $records->execute();
        try {
            $records->execute();
            $this->log(LOG, "Successfully deleted receipt identified by $id");
            return true;
        } catch (Exception $e) {
            $this->log(ERROR, "System error $e");
        }
        $this->log(ERROR, "Failed to delete receipt identified by $id");
        return false;
    }

    public function delete_detalles_factura(int $id): bool
    {
        $records = $this->database->prepare('DELETE FROM detalles_facturas WHERE facturas_id = :id;');
        $records->bindParam(':id', $id);
        $records->execute();
        try {
            $records->execute();
            $this->log(LOG, "Successfully deleted receipt details identified by $id");
            return true;
        } catch (Exception $e) {
            $this->log(ERROR, "System error $e");
        }
        $this->log(ERROR, "Failed to delete receipt details identified by $id");
        return false;
    }

    public function buscar_factura_empleado(string $empleado): array
    {
        $this->log(LOG, "Search for receipts by employee $empleado");
        $empleados = $this->search_employees($empleado, "");
        $facturas = array();
        foreach ($empleados as $empleado) {
            $factura = $this->database->prepare('SELECT id,fecha,empleados_id,clientes_id,decuento,abierta FROM facturas WHERE empleados_id LIKE :empleados_id;');
            $factura->bindParam(':empleados_id', $empleado->id);
            $factura->execute();
            while ($rowOrd = $factura->fetch(PDO::FETCH_ASSOC)) {
                if (count($rowOrd) === 0) {
                    break;
                }
                $facturas[] = new Lista_facturas($rowOrd["id"], $rowOrd["fecha"], $rowOrd["empleados_id"], $rowOrd["clientes_id"], $rowOrd["decuento"], $rowOrd["abierta"]);;
            }
        }
        return $facturas;
    }

    public function view_factura(int $factura_id): ?Lista_facturas
    {
        $records = $this->database->prepare('SELECT fecha,empleados_id,clientes_id,decuento,abierta FROM facturas WHERE id = :id;');
        $records->bindParam(':id', $factura_id);
        $records->execute();
        $result = $records->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            if (count($result) !== 0) {
                $this->log(LOG, "Successfully view receipt identified by $factura_id");
                return new Lista_facturas(
                    $factura_id,
                    $result["fecha"],
                    $result["empleados_id"],
                    $result["clientes_id"],
                    $result["decuento"],
                    $result["abierta"]
                );
            }
        }
        $this->log(LOG, "Failed to view receipt identified by $factura_id");
        return null;
    }

    public function details_view_factura(int $factura_id): array
    {
        $this->log(LOG, "Querying details of receipt identified by $factura_id");
        $records = $this->database->prepare('SELECT cantidad,valor_total,productos_id,tipo_pago_id FROM detalles_facturas WHERE facturas_id = :id;');
        $records->bindParam(':id', $factura_id);
        $records->execute();
        $details_view_factura = array();
        while ($row = $records->fetch(PDO::FETCH_ASSOC)) {
            if (count($row) === 0) {
                break;
            }
            $details_view_factura[] = new factura_detalles(
                $row["cantidad"],
                $row["valor_total"],
                $row["productos_id"],
                $row["tipo_pago_id"],
                $factura_id
            );;
        }
        return $details_view_factura;
    }

    public function close_factura(int $id): bool
    {
        $records = $this->database->prepare('SELECT close_factura(:id) AS result');
        $records->bindParam(':id', $id);
        $records->execute();
        $row = $records->fetch(PDO::FETCH_ASSOC);
        if (count($row) !== 0) {
            if ($row["result"]) {
                $this->log(LOG, "Successfully closed receipt identified by $id");
                return $row["result"];
            }
        }
        $this->log(LOG, "Failed to close receipt identified by $id");
        return false;
    }

    public function buscar_factura_cliente(string $cliente): array
    {
        $this->log(LOG, "Searching for receipts by client $cliente");
        $clientes = $this->search_clients($cliente, "");
        $facturas = array();
        foreach ($clientes as $cliente) {
            $factura = $this->database->prepare('SELECT id,fecha,empleados_id,clientes_id,decuento,abierta FROM facturas WHERE clientes_id LIKE :clientes_id;');
            $factura->bindParam(':clientes_id', $cliente->id);
            $factura->execute();
            while ($row = $factura->fetch(PDO::FETCH_ASSOC)) {
                if (count($row) === 0) {
                    break;
                }
                $facturas[] = new Lista_facturas($row["id"], $row["fecha"], $row["empleados_id"], $row["clientes_id"], $row["decuento"], $row["abierta"]);;
            }
        }
        return $facturas;
    }

    public function get_dependency_id(string $dependency_name): int
    {
        $records = $this->database->prepare('SELECT get_dependencia_id(:name) AS name');
        $records->bindParam(':name', $dependency_name);
        $records->execute();
        $result = $records->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return $result["name"];
        }
        return -1;
    }

    public function get_dependency_name(int $dependency_id): string
    {
        $records = $this->database->prepare('SELECT get_dependencia_name(:id) AS id');
        $records->bindParam(':id', $dependency_id);
        $records->execute();
        $result = $records->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return $result["id"];
        }
        return "NOT FOUND";
    }

    public function list_dependencies(): array
    {
        $dependencies = array();
        $factura = $this->database->prepare('SELECT id,nombre FROM dependencias;');
        $factura->execute();
        while ($row = $factura->fetch(PDO::FETCH_ASSOC)) {
            if (count($row) === 0) {
                break;
            }
            $dependencies[] = new Dependency($row["id"], $row["nombre"]);;
        }
        return $dependencies;
    }

    public function registrar_gasto(int $valor, string $razon): bool
    {
        $records = $this->database->prepare('SELECT registrar_gasto(:valor, :razon) AS result');
        $records->bindParam(':valor', $valor);
        $records->bindParam(':razon', $razon);
        try {
            $records->execute();
            $result = $records->fetch(PDO::FETCH_ASSOC);
            if (count($result) !== 0) {
                if ($result["result"]) {
                    $this->log(LOG, "Successfully registered spend $valor");
                    return $result["result"];
                }
            }
        } catch (Exception $e) {
            $this->log(ERROR, "System error $e");
        }
        $this->log(ERROR, "Failed to register spend $valor");
        return false;
    }

    public function lista_gastos(): array
    {
        $this->log(LOG, "List spends");
        $gastos = $this->database->prepare('SELECT id,valor,razon FROM gastos;');
        $gastos->execute();
        $lista = array();
        while ($rowOrd = $gastos->fetch(PDO::FETCH_ASSOC)) {
            if (count($rowOrd) === 0) {
                break;
            }
            $lista[] = new gastos($rowOrd["id"], $rowOrd["valor"], $rowOrd["razon"]);;
        }

        return $lista;
    }

    public function get_ventas_caja(): ?int
    {
        $this->log(LOG, "List sells");
        $records = $this->database->prepare('SELECT SUM(detalles_facturas.valor_total) AS valor
                                                    FROM detalles_facturas,facturas
                                                    WHERE facturas.id = detalles_facturas.facturas_id
                                                    AND detalles_facturas.tipo_pago_id = 1
                                                    AND facturas.abierta = 1;');
        $records->execute();
        $valor = 0;
        while ($row = $records->fetch(PDO::FETCH_ASSOC)) {
            if (count($row) === 0) {
                break;
            }
            $valor = $row["valor"];
        }
        return $valor;
    }

    public function get_ventas_bancos(): ?int
    {
        $this->log(LOG, "List sells in bank");
        $records = $this->database->prepare('SELECT SUM(detalles_facturas.valor_total) AS valor
                                                    FROM detalles_facturas,facturas
                                                    WHERE facturas.id = detalles_facturas.facturas_id
                                                    AND detalles_facturas.tipo_pago_id = 2
                                                    AND facturas.abierta = 1;');
        $records->execute();
        $valor = 0;
        while ($row = $records->fetch(PDO::FETCH_ASSOC)) {
            if (count($row) === 0) {
                break;
            }
            $valor = $row["valor"];
        }
        return $valor;
    }

    public function get_costos_ventas(): ?int
    {
        $this->log(LOG, "Get spends and sells");
        $records = $this->database->prepare('SELECT SUM(costo*cantidad) AS valor FROM costos_inventario;');
        $records->execute();
        $valor = 0;
        while ($row = $records->fetch(PDO::FETCH_ASSOC)) {
            if (count($row) === 0) {
                break;
            }
            $valor = $row["valor"];
        }
        return $valor;
    }

    public function get_gastos(): ?int
    {
        $this->log(LOG, "Get spends");
        $records = $this->database->prepare('SELECT SUM(valor) AS valor FROM gastos;');
        $records->execute();
        $valor = 0;
        while ($row = $records->fetch(PDO::FETCH_ASSOC)) {
            if (count($row) === 0) {
                break;
            }
            $valor = $row["valor"];
        }
        return $valor;
    }

    public function get_informe_inventario(): array
    {
        $this->log(LOG, "Get resume of inventory");
        $informe_inventario = array();
        $records = $this->database->prepare('SELECT inventario.id AS id,inventario.nombre AS nombre,dependencias.nombre AS dependencia,
                                                    inventario.cantidad AS cantidad,costos_inventario.costo AS costo_unitario,
                                                    costos_inventario.costo*inventario.cantidad AS costo_total, 
                                                    inventario.precio AS precio,inventario.precio*inventario.cantidad AS precio_total, inventario.activo AS habilitado
                                                    FROM inventario, costos_inventario,dependencias
                                                    WHERE costos_inventario.productos_id = inventario.id
                                                    AND dependencias.id = inventario.dependencia_id;');
        $records->execute();
        while ($row = $records->fetch(PDO::FETCH_ASSOC)) {
            if (count($row) === 0) {
                break;
            }
            $informe_inventario[] = new informe_inventario($row["id"], $row["nombre"], $row["dependencia"], $row["cantidad"], $row["costo_unitario"],
                $row["costo_total"], $row["precio"], $row["precio_total"], $row["habilitado"]);;
        }
        return $informe_inventario;
    }

    public function get_total_productos(): ?int
    {
        $records = $this->database->prepare('SELECT SUM(cantidad) AS total FROM inventario;');
        $records->execute();
        $total = 0;
        while ($row = $records->fetch(PDO::FETCH_ASSOC)) {
            if (count($row) === 0) {
                break;
            }
            $total = $row["total"];
        }
        return $total;
    }

    public function get_total_precio(): ?int
    {
        $records = $this->database->prepare('SELECT SUM(cantidad*precio) AS total FROM inventario;');
        $records->execute();
        $total = 0;
        while ($row = $records->fetch(PDO::FETCH_ASSOC)) {
            if (count($row) === 0) {
                break;
            }
            $total = $row["total"];
        }
        return $total;
    }

    public function get_ventas_credito(): ?int
    {
        $records = $this->database->prepare('SELECT SUM(detalles_facturas.valor_total) AS valor
                                                    FROM detalles_facturas,facturas
                                                    WHERE facturas.id = detalles_facturas.facturas_id
                                                    AND detalles_facturas.tipo_pago_id = 3
                                                    AND facturas.abierta = 1;');
        $records->execute();
        $valor = 0;
        while ($row = $records->fetch(PDO::FETCH_ASSOC)) {
            if (count($row) === 0) {
                break;
            }
            $valor = $row["valor"];
        }
        return $valor;
    }

    public function get_cuentas_cobrar(): array
    {
        $cuentas_cobrar = array();
        $records = $this->database->prepare('SELECT clientes.nombre_completo AS nombre, clientes.cedula AS cedula , clientes.direccion AS direccion, 
                                                    clientes.telefono AS telefono, clientes.correo AS correo, facturas.id AS numero, facturas.fecha AS fecha,
                                                    SUM(detalles_facturas.valor_total) AS valor
                                                    FROM facturas, clientes, detalles_facturas
                                                    WHERE facturas.clientes_id = clientes.id
                                                    AND detalles_facturas.facturas_id = facturas.id
                                                    AND detalles_facturas.tipo_pago_id = 3
                                                    AND facturas.abierta = 1
                                                    GROUP BY facturas.id;');
        $records->execute();
        while ($row = $records->fetch(PDO::FETCH_ASSOC)) {
            if (count($row) === 0) {
                break;
            }
            $cuentas_cobrar[] = new cuentas_cobrar($row["nombre"], $row["cedula"], $row["direccion"], $row["telefono"], $row["correo"],
                $row["numero"], $row["fecha"], $row["valor"]);;
        }
        return $cuentas_cobrar;
    }
}
