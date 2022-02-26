# Administrativo

Con este conjunto de funcionalidades las empresas pueden realizar operaciones administrativas sobre el personal,
inventario, ventas y clientes registrados por ellas mismas. La empresa matriz será la única con un administrador
especial capaz de operar en cualquiera de los registros de las otras entidades.

### Clientes

#### `POST /api/admin/client`

##### Descripción

Registrar un nuevo cliente.

##### Argumentos

##### Respuestas

##### Ejemplo

#### `PUT /api/admin/client/{field}`

##### Descripción

Modificar información del cliente.

##### Argumentos

##### Respuestas

##### Ejemplo

#### `DELETE /api/admin/client`

##### Descripción

Deshabilita cliente.

##### Argumentos

##### Respuestas

##### Ejemplo

### Inventario

#### `POST /api/admin/stock`

##### Descripción

Agregar nuevo producto al inventario.

##### Argumentos

##### Respuestas

##### Ejemplo

#### `PUT /api/admin/stock/{field}`

##### Descripción

Actualizar entrada del inventario.

##### Argumentos

##### Respuestas

##### Ejemplo

#### `DELETE /api/admin/stock`

##### Descripción

Deshabilita item del inventario.

##### Argumentos

##### Respuestas

##### Ejemplo

### Usuarios (empleados)

#### `POST /api/admin/user`

##### Descripción

Agregar nuevo empleado.

##### Argumentos

##### Respuestas

##### Ejemplo

#### `PUT /api/admin/user/{field}`

##### Descripción

Actualizar información del empleado.

##### Argumentos

##### Respuestas

##### Ejemplo

#### `DELETE /api/admin/user`

##### Descripción

Deshabilita al empleado.

##### Argumentos

##### Respuestas

##### Ejemplo

##        