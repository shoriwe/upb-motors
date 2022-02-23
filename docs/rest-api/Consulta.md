# Consulta

### Clientes

#### `POST /api/query/clients/{field}`

##### Descripción

Buscar clientes por nombre o identificador.

##### Argumentos

##### Respuestas

##### Ejemplo

#### `GET /api/query/clients/{id}`

##### Descripción

Mostrar información del cliente.

##### Argumentos

##### Respuestas

##### Ejemplo

### Inventario

#### `GET /api/query/stock/name`

##### Descripción

Buscar ítems por nombre.

##### Argumentos

##### Respuestas

##### Ejemplo

#### `GET /api/query/stock`

##### Descripción

Mostrar los detalles del ítem.

##### Argumentos

- **HEADERS**:
    - `Content-Type: application/json`
    - `API-Key: API_KEY_ENTREGADA_A_LOS_DESARROLLADORES`

- **BODY**:

```json
{
    "cookie": "COOKIE",
    "id": 0,
}
```

##### Respuestas

- `200`: La información del producto fue obtenida con éxito.

```json
{
    "id": 0,
    "name": "NOMBRE DEL PRODUCTO",
    "description": "DESCRIPCION",
    "price": 0,
    "taxes": 0,
    "image": [0, 0, 0, 0, 0],
    "stock": [
        {
            "company": "NOMBRE DE LA COMPANIA",
            "quantity": 0,
        },
        ...
    ],
}
```

- `403`: Operación no permitida.

```json
{
    "error": "MENSAJE DE ERROR RETORNADO POR EL API"
}
```

##### Ejemplo

- Python:

```python
import requests

api_key = "COLOCA TU API KEY AQUI"

cookie = "USER COOKIE"
headers = {
    "Content-Type": "application/json",
    "API-Key": api_key,
}

payload = {
    "cookie": cookie,
    "id": 10094,
}

response = requests.get(
    "http://URL:PORT/api/query/stock",
    headers=headers,
    json=payload
)

if response.status_code != 200:
    raise Exception("failed to update password")

product_information = response.json()
```

### Usuarios (empleados)

#### `GET /api/query/users/{field}`

##### Descripción

Buscar usuario por nombre o identificador.

##### Argumentos

- **URL**:
    - `field`: Buscar por `name` o `id`

- **HEADERS**:
    - `Content-Type: application/json`
    - `API-Key: API_KEY_ENTREGADA_A_LOS_DESARROLLADORES`
- **BODY**:

```json
{
    "cookie": "COOKIE",
    "value": ..., // Cuando se utilice name, este valor es un STRING, pero cuando se utilice id, es numerico.
}
```

##### Respuestas

- `200`: Lista de usuario obtenida con éxito.

```json
[
    {
        "id": 0,
        "name": "NOMBRE DEL EMPLEADO",
        "document_id": "CEDULA DEL EMPLEADO",
        "phone": "NUMERO DE TELEFONO DEL EMPLEADO",
        "email": "CORREO DEL EMPLEADO",
    },
    ...
]
```

- `403`: Operación no permitida.

```json
{
    "error": "MENSAJE DE ERROR RETORNADO POR EL API"
}
```

##### Ejemplo

- Python:

```python
import requests

api_key = "COLOCA TU API KEY AQUI"

cookie = "USER COOKIE"
headers = {
    "Content-Type": "application/json",
    "API-Key": api_key,
}

# Buscar por ID
payload = {
    "cookie": cookie,
    "value": 10094,
}

response = requests.get(
    "http://URL:PORT/api/query/users/id",
    headers=headers,
    json=payload
)

if response.status_code != 200:
    raise Exception("failed to update password")

users = response.json()

# Buscar por nombre

payload = {
    "cookie": cookie,
    "value": "Antonio",
}

response = requests.get(
    "http://URL:PORT/api/query/users/name",
    headers=headers,
    json=payload
)

if response.status_code != 200:
    raise Exception("failed to update password")

users = response.json()
```

#### `GET /api/query/users`

##### Descripción

Mostrar detalles del usuario.

##### Argumentos

- **HEADERS**:
    - `Content-Type: application/json`
    - `API-Key: API_KEY_ENTREGADA_A_LOS_DESARROLLADORES`

- **BODY**:

```json
{
    "cookie": "COOKIE",
    "id": 0,
}
```

##### Respuestas

- `200`: La información del usuario fue obtenida con éxito.

```json
{
    "id": 0,
    "name": "NOMBRE DEL EMPLEADO",
    "document_id": "CEDULA DEL EMPLEADO",
    "address": "DOMICILIO DEL EMPLEADO",
    "phone": "NUMERO DE TELEFONO DEL EMPLEADO",
    "email": "CORREO DEL EMPLEADO",
    "positions": [
        {
            "company": "NOMBRE DE LA COMPANIA",
            "position": "CARGO DEL EMPLEADO EN LA COMPANIA",
        },
        ...
    ],
}
```

- `403`: Operación no permitida.

```json
{
    "error": "MENSAJE DE ERROR RETORNADO POR EL API"
}
```

##### Ejemplo

- Python:

```python
import requests

api_key = "COLOCA TU API KEY AQUI"

cookie = "USER COOKIE"
headers = {
    "Content-Type": "application/json",
    "API-Key": api_key,
}

payload = {
    "cookie": cookie,
    "id": 10094,
}

response = requests.get(
    "http://URL:PORT/api/query/users",
    headers=headers,
    json=payload
)

if response.status_code != 200:
    raise Exception("failed to update password")

user_information = response.json()
```
