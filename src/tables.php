<?php

class Product
{
    public ?int $id = null;
    public ?int $cantidad = null;
    public ?string $nombre = null;
    public ?string $descripcion = null;
    public ?float $precio = null;

    public function __construct(int $id, int $cantidad, string $nombre, string $descripcion, float $precio)
    {
        $this->id = $id;
        $this->cantidad = $cantidad;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
    }
}
