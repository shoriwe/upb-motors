# REST API consorcio

## FAQ



## Autenticación

Los usuarios del consorcio deben estar disponibles entre todas las empresas, eso si, empleados de alto rango en la empresa X no necesariamente deben tener altos privilegios en la empresa Y, por lo que se recomienda discreción y correcto uso de los permisos otorgados confirmando además el origen del empleado, mas no solo su cargo.

#### `POST /api/auth/login`

##### Descripción

Iniciar sesión como un usuario del consorcio.

##### Argumentos

- **HEADERS**:
  - `Content-Type: application/json`
  - `API-Key: API_KEY_ENTREGADA_A_LOS_DESARROLLADORES`

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
    "cookie": "COOKIE DEL USUARIO"
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

api_key = "COLOCA TU API KEY AQUI"

headers = {
    "Content-Type": "application/json",
    "API-Key": api_key,
}

credentials = {
    "username": "antonio@mail.com",
    "password": "contrasena",
}

response = requests.post("http://URL:PORT/api/auth/login", headers=headers, json=credentials)
if response.status_code != 200:
    raise Exception("failed to login")

cookie = response.json()["cookie"]
```

#### `PUT /api/auth/password`

##### Descripción

Actualiza la contraseña de la cuenta asociada con la llave.

##### Argumentos

- **HEADERS**:
  - `Content-Type: application/json`
  - `API-Key: API_KEY_ENTREGADA_A_LOS_DESARROLLADORES`
- **BODY**:

```json
{
    "cookie": "COOKIE",
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

api_key = "COLOCA TU API KEY AQUI"

cookie = "USER COOKIE"
headers = {
    "Content-Type": "application/json",
    "API-Key": api_key,
}

payload = {
    "cookie": cookie,
    "old": "contrasena",
    "new": "nueva contrasena",
}

response = requests.put("http://URL:PORT/api/auth/password", headers=headers, json=payload)
if response.status_code != 200:
    raise Exception("failed to update password")
```

#### `POST /api/auth/recover`

##### Descripción

Solicita por correo una llave para actualización de contraseña.

##### Argumentos

- **HEADERS**:
  - `Content-Type: application/json`
  - `API-Key: API_KEY_ENTREGADA_A_LOS_DESARROLLADORES`
- **BODY**:

```json
{
    "username": "USUARIO DE LA CUENTA A RECUPERAR",
}
```

##### Respuestas

- `200`: Se envió (o se intento enviar una llave de recuperación al correo del usuario especificado)

```json
{
    "succeed": true,
}
```

##### Ejemplo

- Python

```python
import requests

api_key = "COLOCA TU API KEY AQUI"

headers = {
    "Content-Type": "application/json",
    "API-Key": api_key,
}

payload = {
    "username": "antonio@mail.com",
}

response = requests.post("http://URL:PORT/api/auth/recover", headers=headers, json=payload)
```

#### `PUT /api/auth/recover`

##### Descripción

Utilizando el ticket recibido en el correo de reinicio de contraseña, el usuario puede actualizar su contraseña.

##### Argumentos

- **HEADERS**:
  - `Content-Type: application/json`
  - `API-Key: API_KEY_ENTREGADA_A_LOS_DESARROLLADORES`
- **BODY**:

```json
{
    "ticket": "COLOCA AQUI EL TICKET RECIVIDO",
    "new": "NUEVA CONTRASENA",
}
```

##### Respuestas

- `200`: La contraseña fue actualizada con éxito.

```json
{
    "succeed": true,
}
```

- `403`: Operación no permitida.

```json
{
    "error": "MENSAJE DE ERROR RETORNADO POR EL API"
}
```

##### Ejemplo

- Python

```python
import requests

api_key = "COLOCA TU API KEY AQUI"

headers = {
    "Content-Type": "application/json",
    "API-Key": api_key,
}

payload = {
    "ticket": "TICKET DE RECUPERACION",
    "new": "nueva contrasena",
}

response = requests.put("http://URL:PORT/api/auth/recover", headers=headers, json=payload)
```

#### `POST /api/auth/whoami`

##### Descripción

Solicita información del usuario actual.

##### Argumentos

- **HEADERS**:
  - `Content-Type: application/json`
  - `API-Key: API_KEY_ENTREGADA_A_LOS_DESARROLLADORES`
- **BODY**:

```json
{
    "cookie": "COLOCA AQUI EL TICKET RECIVIDO",
}
```

##### Respuestas

- `200`: La contraseña fue actualizada con éxito.

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

- Python

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
}

response = requests.put("http://URL:PORT/api/auth/whoami", headers=headers, json=payload)
if response.status_code != 200:
    raise Exception("failed to update password")

user_information = response.json()
```

## Administrativo

Con este conjunto de funcionalidades las empresas pueden realizar operaciones administrativas sobre el personal, inventario, ventas y clientes registrados por ellas mismas. La empresa matriz será la única con un administrador especial capaz de operar en cualquiera de los registros de las otras entidades.

### Clientes

#### `POST /api/admin/client`

##### Descripcion

Registrar un nuevo cliente.

##### Argumentos

##### Respuestas

##### Ejemplo

#### `PUT /api/admin/client/{field}`

##### Descripcion

Modificar informacion del cliente.

##### Argumentos

##### Respuestas

##### Ejemplo

#### `DELETE /api/admin/client`

##### Descripcion

Eliminar cliente.

##### Argumentos

##### Respuestas

##### Ejemplo

### Inventario

#### `POST /api/admin/stock`

##### Descripcion

Agregar nuevo producto al inventario.

##### Argumentos

##### Respuestas

##### Ejemplo

#### `PUT /api/admin/stock/{field}`

##### Descripcion

Actualizar entrada del inventario.

##### Argumentos

##### Respuestas

##### Ejemplo

#### `DELETE /api/admin/stock`

##### Descripcion

Eliminar entrada del inventario.

##### Argumentos

##### Respuestas

##### Ejemplo

### Usuarios (empleados)

#### `POST /api/admin/user`

##### Descripcion

Agregar nuevo empleado.

##### Argumentos

##### Respuestas

##### Ejemplo

#### `PUT /api/admin/user/{field}`

##### Descripcion

Actualizar informacion del empleado.

##### Argumentos

##### Respuestas

##### Ejemplo

#### `DELETE /api/admin/user`

##### Descripcion

Eliminar informacion del empleado.

##### Argumentos

##### Respuestas

##### Ejemplo

## Consulta

### Clientes

#### `POST /api/query/clients/{field}`

##### Descripcion

Buscar clientes por nombre o identificador.

##### Argumentos

##### Respuestas

##### Ejemplo

#### `GET /api/query/clients/{id}`

##### Descripcion

Mostrar informacion del cliente.

##### Argumentos

##### Respuestas

##### Ejemplo

### Inventario

#### `POST /api/query/stock`

##### Descripcion

Buscar items por nombre.

##### Argumentos

##### Respuestas

##### Ejemplo

#### `GET /api/query/stock/{id}`

##### Descripcion

Mostrar los detalles del item.

##### Argumentos

##### Respuestas

##### Ejemplo

### Usuarios (empleados)

#### `POST /api/query/users/{field}`

##### Descripcion

Buscar usuario por nombre o identificador.

##### Argumentos

##### Respuestas

##### Ejemplo

#### `GET /api/query/users/{id}`

##### Descripcion

Mostrar detalles del usuario.

##### Argumentos

##### Respuestas

##### Ejemplo

## Notificacion

Estas funciones son especificas para cada entidad y corresponden a las especificadas en el documento planteado por el profesor.

La comunicacion con estas rutas debe ser repetitiva, cada **minuto** los servidores de la empresa asignada deben hacer un pedido a las rutas para confirmar si an recibido nuevas notificaciones.
