<?php

class Client
{
    public ?int $id = null;
    public ?string $name = null;
    public ?string $personal_id = null;
    public ?string $address = null;
    public ?string $phone = null;
    public ?string $email = null;
    public ?bool $is_enabled = null;

    public function __construct(
        int    $id,
        string $name,
        string $personal_id,
        string $address,
        string $phone,
        string $email,
        bool   $is_enabled
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->personal_id = $personal_id;
        $this->address = $address;
        $this->phone = $phone;
        $this->email = $email;
        $this->is_enabled = $is_enabled;
    }
}

class Employee
{
    public ?int $id = null;
    public ?int $permission = null;
    public ?string $name = null;
    public ?string $personal_id = null;
    public ?string $address = null;
    public ?string $phone = null;
    public ?string $email = null;
    public ?string $password_hash = null;
    public ?bool $is_enabled = null;

    public function __construct(
        int    $id,
        int    $permission,
        string $name,
        string $personal_id,
        string $address,
        string $phone,
        string $email,
        string $password_hash,
        bool   $is_enabled
    )
    {
        $this->id = $id;
        $this->permission = $permission;
        $this->name = $name;
        $this->personal_id = $personal_id;
        $this->address = $address;
        $this->phone = $phone;
        $this->email = $email;
        $this->password_hash = $password_hash;
        $this->is_enabled = $is_enabled;
    }
}

class Product
{
    public ?int $id = null;
    public ?int $cantidad = null;
    public ?string $nombre = null;
    public ?string $descripcion = null;
    public ?float $precio = null;
    public ?bool $activo = null;
    public ?string $imagen = null;

    public function __construct(int $id, int $cantidad, string $nombre, string $descripcion, float $precio, bool $activo, ?string $imagen)
    {
        $this->id = $id;
        $this->cantidad = $cantidad;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->activo = $activo;
        $this->imagen = $imagen;
    }
}

class Lis_Product
{
    public ?int $id = null;
    public ?string $nombre = null;

    public function __construct(int $id, string $nombre)
    {
        $this->id = $id;
        $this->nombre = $nombre;
    }
}

class PriceHistory {
    public int $id;
    public int $modification_date;
    public int $inventario_id;
    public float $precio;

    /**
     * @param int $id
     * @param int $modification_date
     * @param int $inventario_id
     * @param float $precio
     */
    public function __construct(int $id, int $modification_date, int $inventario_id, float $precio)
    {
        $this->id = $id;
        $this->modification_date = $modification_date;
        $this->inventario_id = $inventario_id;
        $this->precio = $precio;
    }

}

class Lis_Clients
{
    public ?int $id = null;
    public ?string $nombre = null;

    public function __construct(int $id, string $nombre)
    {
        $this->id = $id;
        $this->nombre = $nombre;
    }
}

class Lis_Empleados
{
    public ?int $id = null;
    public ?string $nombre = null;

    public function __construct(int $id, string $nombre)
    {
        $this->id = $id;
        $this->nombre = $nombre;
    }
}

class Lis_tipo_pago
{
    public ?int $id = null;
    public ?string $pago = null;

    public function __construct(int $id, string $pago)
    {
        $this->id = $id;
        $this->pago = $pago;
    }
}

class Lista_ordenes
{
    public ?int $id = null;
    public ?string $fecha = null;
    public ?string $empleado = null;
    public ?string $cliente = null;
    public ?float $descuento = null;
    public ?bool $estado = null;

    public function __construct(int $id, string $fecha, string $empleado, string $cliente, float $descuento,bool $estado)
    {
        $this->id = $id;
        $this->fecha = $fecha;
        $this->empleado = $empleado;
        $this->cliente = $cliente;
        $this->descuento = $descuento;
        $this->estado = $estado;
    }
}

class orden_detalles
{
    public ?int $cantidad = null;
    public ?int $valor_total = null;
    public ?int $productos_id = null;
    public ?int $tipo_pago_id = null;
    public ?int $orden_compra_id = null;

    public function __construct(int $cantidad, int $valor_total, int $productos_id, int $tipo_pago_id,int $orden_compra_id)
    {
        $this->cantidad = $cantidad;
        $this->valor_total = $valor_total;
        $this->productos_id = $productos_id;
        $this->tipo_pago_id = $tipo_pago_id;
        $this->orden_compra_id = $orden_compra_id;
    }
}
