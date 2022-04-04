# API

## (GET) `/inventory/:page`

- Request:
  - URL Arguments:
    - `page`: int >= 0
- Response:

```json
{
  "succeed": true | false,
  "message": "ERROR MESSAGE IN CASE OF SUCCEED == false",
  "body": [
    {
      "id": 291,              // Inventory ID
      "cantidad": 10,         // Inventory units 
      "nombre": "sedan",      // Product name
      "descripcion": "Mazda", // Product description
      "precio": 1900,         // Product price
      "activo": false,        // Available for sale
      "dependencia": "Giron", // Product location
      "imagen": null          // Product image
    },
    ...
  ] | [] | null
}
```

## (GET) `/private/clients/:page`

- Request:
    - URL Arguments:
      - `page`: int >= 0
    - Headers:
      - `API-Key`: string
- Response:

```json
{
  "succeed": true | false,
  "message": "ERROR MESSAGE IN CASE OF SUCCEED == false",
  "body": [
    {
      "id": 300,                        // Client ID
      "nombre_completo": "Antonio",     // Client name
      "cedula": "123456",               // Client personal ID
      "direccion": "Cerca",             // Client address
      "telefono": "12345",              // Client phone number
      "correo": "antonio@antonio.com",  // Client email
      "habilitado": true                // Client enabled in matriz company
    },
    ...
  ] | [] | null
}
```

## (POST) `/private/clients`

- Request:
  - Headers:
    - `API-Key`: string
  - Body:

```json
{
  "nombre_completo": "Antonio",     // Client name
  "cedula": "123456",               // Client personal id
  "direccion": "Cerca",             // Client address
  "telefono": "12345",              // Client phone number
  "correo": "antonio@antonio.com",  // Client email
  "habilitado": true                // Client enabled
}
```

## (POST) `/private/sales`

- Request:
    - Headers:
        - `API-Key`: string
    - Body:

```json
{
  "id_vehiculo": 1,             // Vehicle ID
  "precio_venta": 1000000,      // Sale price
  "fecha_venta": "2000/03/17",  // Sale date ("YYYY/MM/DD" Format)
}
```
