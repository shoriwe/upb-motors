# REST API consorcio

## Funciones

### Sesión

#### `POST /api/login`

##### Descripción

Iniciar sesión y recibir el ticket para poder utilizar el API.

##### Argumentos

- **HEADERS**:
  - `Content-Type: application/json`

- **BODY**:

```json
{
    "username": "NOMBRE DEL USUARIO",
    "password": "CONTRASENA"
}
```

##### Respuestas

- `200`: Inicio de sesión exitoso:

```json
{
    "key": "CREDENCIALES PARA PODER USAR EL API"
}
```

- `403`: Inicio de sesión fallido: 

```json
{
    "error": "MENSAJE DE ERROR RETORNADO POR EL API"
}
```

##### Ejemplo

- Python

```python
import requests

def get_api_key(username: str, password: str) -> str:
    headers = {'Content-type': 'application/json'}

    credenciales = {
        "username": username,
        "password": password
    }

    response = requests.post(
        "http://URL_SERVER/api/login",
        json=credenciales,
        headers=headers
    )
    response_json = response.json()

    if response.status_code == 403: # Credenciales invalidas
        raise Exception(response_json["error"])

    return response_json["key"]

api_key = get_api_key("auto-upb", "contraseña")
```

#### `PUT /api/password`

##### Descripción

Actualiza la contraseña de la cuenta asociada con la llave.

##### Argumentos

- **HEADERS**:
  - `Content-Type: application/json`
- **BODY**:

```json
{
    "key": "COLOCA AQUI LA KEY QUE RETORNO LOGIN",
    "old": "CONTRASENA ACTUAL",
    "new": "NUEVA CONTRASENA",
}
```

##### Respuestas

- `200`: Contraseña actualizada con éxito.

```json
{
    "succeed": true,
}
```

- `403`: Fallo la actualización de la contraseña.

```json
{
    "error": "MENSAJE DE ERROR RETORNADO POR EL API"
}
```

##### Ejemplo

- Python

```python
import requests

api_key = get_api_key(username, password)

headers = {'Content-type': 'application/json'}

payload = {
    "key": api_key,
    "old": "contraseña",
    "new": "otra-contraseña",
}

response = requests.put(
    "http://URL_SERVER/api/password",
    json=payload,
    headers=headers
)
response_json = response.json()

if response.status_code == 403: # Error
    raise Exception(response_json["error"])
```

### Clientes

#### `GET /api/client`

##### Descripción

Buscar clientes de acuerdo a filtro.

##### Argumentos

##### Respuestas

##### Ejemplo

#### `GET /api/client/list/{page}` 

##### Descripción

Listar los clientes ordenados por el identificador único en la pagina especificada.

##### Argumentos

- **Argumentos de URL**:
  - `page`: Valor numérico entero

- **HEADERS**:
  - `Content-Type: application/json`
- **BODY**:

```json
{
    "key": "COLOCA AQUI LA KEY QUE RETORNO LOGIN",
}
```

##### Respuestas

- `200`: La consulta fue un éxito. 

```json
{
    "results": [client_objects...]
}
```

Los elementos en resultados tienen la siguiente estructura:

```
{
	"id": 0000000,
	
}
```



- `403`: La llave utilizada es invalida.
- `400`: El numero de la pagina es invalido.



##### Ejemplo

#### `POST /api/client`

##### Descripción

Añade un nuevo cliente en la base de datos.

##### Argumentos

##### Respuestas

##### Ejemplo

#### `PUT /api/client`

##### Descripción

Actualiza la información de un cliente.

##### Argumentos

##### Respuestas

##### Ejemplo

### Productos

#### `GET /api/product`

##### Descripción



##### Argumentos

##### Respuestas

##### Ejemplo

#### `GET /api/product/list/{page}`

##### Descripción

##### Argumentos

##### Respuestas

##### Ejemplo

#### `POST /api/product`

##### Descripción

Añadir nuevo producto a los disponibles.

##### Argumentos

##### Respuestas

##### Ejemplo

#### `PUT /api/product`

##### Descripción

Actualizar la información de un producto.

##### Argumentos

##### Respuestas

##### Ejemplo



