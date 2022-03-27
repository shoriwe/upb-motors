SET GLOBAL log_bin_trust_function_creators = 1;
DROP DATABASE upb_motors;
CREATE DATABASE upb_motors;
USE upb_motors;

-- -- Logs -- --
/*
 AUTH
 ERROR
 LOG
 */
CREATE TABLE IF NOT EXISTS niveles_log
(
    id     INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(500),
    CONSTRAINT niveles_log_nombre_unico UNIQUE (nombre)
);

INSERT INTO niveles_log (nombre)
VALUES ('AUTH'),
       ('ERROR'),
       ('LOG');

DELIMITER @@
CREATE FUNCTION get_log_level(nombre VARCHAR(500)) RETURNS INT
    LANGUAGE SQL
BEGIN
    SELECT id INTO @resultado FROM niveles_log WHERE niveles_log.nombre = nombre LIMIT 1;
    RETURN @resultado;
END;
@@

CREATE PROCEDURE log(IN level VARCHAR(500), IN message VARCHAR(5000))
BEGIN
    INSERT INTO app_logs (nivel, mensaje)
    VALUES (get_log_level(level), message);
END;
@@

DELIMITER ;

CREATE TABLE IF NOT EXISTS app_logs
(
    id      INT           NOT NULL PRIMARY KEY AUTO_INCREMENT,
    fecha   DATETIME      NOT NULL DEFAULT NOW(),
    nivel   INT           NOT NULL,
    mensaje VARCHAR(5000) NOT NULL,
    CONSTRAINT fk_logs_niveles_log_nivel FOREIGN KEY (nivel) REFERENCES niveles_log (id)
);

-- -- permisos -- --
CREATE TABLE IF NOT EXISTS permisos
(
    id     INT         NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(45) NOT NULL,
    CONSTRAINT permisos_nombre_unico UNIQUE (nombre)
);

INSERT INTO permisos (nombre)
VALUES ('GERENTE'),
       ('RECURSOS HUMANOS'),
       ('VENTAS'),
       ('INVENTARIO'),
       ('ADMIN');

DELIMITER @@
CREATE FUNCTION get_permisos_id(nombre_permiso VARCHAR(45)) RETURNS INT
BEGIN
    SELECT id INTO @resultado FROM permisos WHERE nombre = nombre_permiso LIMIT 1;
    RETURN @resultado;
END;
@@
DELIMITER ;
-- -- empleados -- --
CREATE TABLE IF NOT EXISTS empleados
(
    id              INT          NOT NULL PRIMARY KEY AUTO_INCREMENT,
    permisos_id     INT          NOT NULL,
    nombre_completo VARCHAR(100) NOT NULL,
    cedula          VARCHAR(45)  NOT NULL,
    direccion       VARCHAR(500) NOT NULL,
    telefono        VARCHAR(20)  NOT NULL,
    correo          VARCHAR(200) NOT NULL,
    hash_contrasena VARCHAR(500) NOT NULL,
    habilitado      BOOLEAN      NOT NULL DEFAULT true,
    CONSTRAINT empleados_cedula_unique UNIQUE (cedula),
    CONSTRAINT empleados_correo_unique UNIQUE (correo),
    CONSTRAINT fk_permisos_empleados_permisos_id FOREIGN KEY (permisos_id) REFERENCES permisos (id)
);

-- -- Dependencias -- --
CREATE TABLE IF NOT EXISTS dependencias
(
    id     INT          NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL,
    CONSTRAINT nombre_unique UNIQUE (nombre)
);

INSERT INTO dependencias (nombre)
VALUES ('Bucaramanaga'),
       ('Bogota'),
       ('Cali');

DELIMITER @@
CREATE FUNCTION get_dependencia_name(v_id_dependencia INT)
    RETURNS VARCHAR(255)
    LANGUAGE SQL
    NOT DETERMINISTIC
BEGIN
    SELECT nombre INTO @result FROM dependencias WHERE id = v_id_dependencia;
    return @result;
END;
@@

CREATE FUNCTION get_dependencia_id(v_nombre_dependencia VARCHAR(255))
    RETURNS INT
    LANGUAGE SQL
    NOT DETERMINISTIC
BEGIN
    SELECT id INTO @result FROM dependencias WHERE nombre = v_nombre_dependencia;
    return @result;
END;
@@

DELIMITER ;

-- -- Inventario -- --
CREATE TABLE IF NOT EXISTS inventario
(
    id             INT            NOT NULL PRIMARY KEY AUTO_INCREMENT,
    cantidad       INT            NOT NULL DEFAULT 0,
    nombre         VARCHAR(255)   NOT NULL,
    descripcion    VARCHAR(10000) NOT NULL,
    precio         DOUBLE         NOT NULL,
    activo         BOOLEAN        NOT NULL DEFAULT true,
    dependencia_id INT            NOT NULL,
    imagen         LONGBLOB,
    CONSTRAINT fk_id_dependencias FOREIGN KEY (dependencia_id) REFERENCES dependencias (id),
    CONSTRAINT unique_producto UNIQUE (nombre)
);

CREATE TABLE IF NOT EXISTS historial_precios
(
    id                INT      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    modification_date DATETIME NOT NULL DEFAULT NOW(),
    inventario_id     INT      NOT NULL,
    precio            DOUBLE   NOT NULL,
    CONSTRAINT fk_inventario_id_inventario FOREIGN KEY (inventario_id) REFERENCES inventario (id)
);

CREATE TRIGGER insertar_producto
    AFTER INSERT
    ON inventario
    FOR EACH ROW
    INSERT INTO historial_precios (inventario_id, precio)
    VALUES (NEW.id, NEW.precio);

DELIMITER @@
CREATE TRIGGER actualizar_product
    AFTER UPDATE
    ON inventario
    FOR EACH ROW
BEGIN
    IF OLD.precio != NEW.precio THEN
        INSERT INTO historial_precios (inventario_id, precio) VALUES (NEW.id, NEW.precio);
    END IF;
END;
@@
DELIMITER ;

-- -- Clientes -- --
CREATE TABLE IF NOT EXISTS clientes
(
    id              INT          NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre_completo VARCHAR(100) NOT NULL,
    cedula          VARCHAR(45)  NOT NULL,
    direccion       VARCHAR(500) NOT NULL,
    telefono        VARCHAR(20)  NOT NULL,
    correo          VARCHAR(200) NOT NULL,
    habilitado      BOOLEAN      NOT NULL DEFAULT true,
    CONSTRAINT empleados_cedula_unique UNIQUE (cedula),
    CONSTRAINT empleados_correo_unique UNIQUE (correo)
);

-- -- ordenes_compra -- --
CREATE TABLE IF NOT EXISTS ordenes_compra
(

    id           INT     NOT NULL PRIMARY KEY AUTO_INCREMENT,
    empleados_id INT     NOT NULL,
    clientes_id  INT     NOT NULL,
    fehca        DATE    NOT NULL,
    decuento     DOUBLE  NOT NULL,
    abierta      BOOLEAN NOT NULL DEFAULT true,

    CONSTRAINT fk_clientes_id FOREIGN KEY (clientes_id) REFERENCES clientes (id),
    CONSTRAINT fk_empleados_id FOREIGN KEY (empleados_id) REFERENCES empleados (id)

);

-- -- tipo pago -- --
CREATE TABLE IF NOT EXISTS tipo_pago_orden
(
    id   INT         NOT NULL PRIMARY KEY AUTO_INCREMENT,
    pago VARCHAR(10) NOT NULL
);

INSERT INTO tipo_pago_orden (pago)
VALUES ('Efectivo'),
       ('Tarjeta'),
       ('Credito');

-- -- detalles_ordenes_compra -- --
CREATE TABLE IF NOT EXISTS detalles_ordenes_compra
(
    id              INT    NOT NULL PRIMARY KEY AUTO_INCREMENT,
    cantidad        INT    NOT NULL,
    valor_total     DOUBLE NOT NULL,
    productos_id    INT    NOT NULL,
    tipo_pago_id    INT    NOT NULL,
    orden_compra_id INT    NOT NULL,
    CONSTRAINT fk_productos_id FOREIGN KEY (productos_id) REFERENCES inventario (id),
    CONSTRAINT fk_orden_compra_id FOREIGN KEY (orden_compra_id) REFERENCES ordenes_compra (id),
    CONSTRAINT fk_tipo_pago_id FOREIGN KEY (tipo_pago_id) REFERENCES tipo_pago_orden (id)

);

-- -- facturas -- --
CREATE TABLE IF NOT EXISTS facturas
(

    id           INT     NOT NULL PRIMARY KEY AUTO_INCREMENT,
    empleados_id INT     NOT NULL,
    clientes_id  INT     NOT NULL,
    fehca        DATE    NOT NULL,
    decuento     DOUBLE  NOT NULL,
    abierta      BOOLEAN NOT NULL DEFAULT true,

    CONSTRAINT fk_facturas_clientes_id FOREIGN KEY (clientes_id) REFERENCES clientes (id),
    CONSTRAINT fk_facturas_empleados_id FOREIGN KEY (empleados_id) REFERENCES empleados (id)

);

-- -- detalles_facturas -- --
CREATE TABLE IF NOT EXISTS detalles_facturas
(
    id           INT    NOT NULL PRIMARY KEY AUTO_INCREMENT,
    cantidad     INT    NOT NULL,
    valor_total  DOUBLE NOT NULL,
    productos_id INT    NOT NULL,
    tipo_pago_id INT    NOT NULL,
    facturas_id  INT    NOT NULL,
    CONSTRAINT FOREIGN KEY (productos_id) REFERENCES inventario (id),
    CONSTRAINT FOREIGN KEY (facturas_id) REFERENCES facturas (id),
    CONSTRAINT FOREIGN KEY (tipo_pago_id) REFERENCES tipo_pago_orden (id)

);

-- -- costos productos -- --
CREATE TABLE IF NOT EXISTS costos_inventario
(
    id           INT    NOT NULL PRIMARY KEY AUTO_INCREMENT,
    productos_id INT    NOT NULL,
    costo        DOUBLE NOT NULL DEFAULT 0,
    cantidad     INT    NOT NULL DEFAULT 0,
    CONSTRAINT FOREIGN KEY (productos_id) REFERENCES inventario (id)

);

-- -- gastos generales -- --
CREATE TABLE IF NOT EXISTS gastos
(
    id    INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    valor INT NOT NULL,
    razon VARCHAR(100)
);

-- -- Procesos -- --
DELIMITER @@
CREATE PROCEDURE actualizar_contrasena(IN v_id INT, IN hash_nueva_contrasena VARCHAR(500))
BEGIN
    UPDATE
        empleados
    SET empleados.hash_contrasena = hash_nueva_contrasena
    WHERE empleados.id = v_id;
END;
@@
CREATE FUNCTION registrar_producto(
    v_cantidad INT,
    v_nombre VARCHAR(255),
    v_descripcion VARCHAR(10000),
    v_precio FLOAT,
    v_imagen LONGBLOB,
    v_dependencia_id INT
)
    RETURNS BOOLEAN
    LANGUAGE SQL
    NOT DETERMINISTIC
BEGIN
    SELECT id,
           cantidad,
           descripcion,
           precio,
           imagen,
           dependencia_id
    INTO @id_producto, @cantidad_producto, @descripcion_producto, @precio_producto, @imagen_producto, @id_dependencia
    FROM inventario
    WHERE nombre = v_nombre
    LIMIT 1;
    IF @id_producto > 0 THEN
        UPDATE
            inventario
        SET cantidad = cantidad + v_cantidad
        WHERE id = @id_producto;
        IF (@descripcion_producto != v_descripcion) AND (v_descripcion IS NOT NULL) THEN
            UPDATE
                inventario
            SET descripcion = v_descripcion
            WHERE id = @id_producto;
        END IF;
        IF @precio_producto != v_precio THEN
            UPDATE
                inventario
            SET precio = v_precio
            WHERE id = @id_producto;
        END IF;
        IF @imagen_producto != v_imagen THEN
            UPDATE
                inventario
            SET imagen = v_imagen
            WHERE id = @id_producto;
        END IF;
        IF @id_dependencia != v_dependencia_id THEN
            UPDATE
                inventario
            SET dependencia_id = v_dependencia_id
            WHERE id = @id_producto;
        END IF;
    ELSE
        INSERT INTO inventario (cantidad,
                                nombre,
                                descripcion,
                                precio,
                                imagen,
                                dependencia_id)
        VALUES (v_cantidad,
                v_nombre,
                v_descripcion,
                v_precio,
                v_imagen,
                v_dependencia_id);
    END IF;
    RETURN true;
END;
@@
CREATE FUNCTION registrar_empleado(
    v_permiso_id int,
    v_nombre_completo VARCHAR(100),
    v_cedula VARCHAR(45),
    v_direccion VARCHAR(500),
    v_telefono VARCHAR(20),
    v_correo VARCHAR(200),
    v_hash_contrasena VARCHAR(500)
)
    RETURNS BOOLEAN
    LANGUAGE SQL
    NOT DETERMINISTIC
BEGIN
    INSERT INTO empleados (permisos_id,
                           nombre_completo,
                           cedula,
                           direccion,
                           telefono,
                           correo,
                           hash_contrasena)
    VALUES (v_permiso_id,
            v_nombre_completo,
            v_cedula,
            v_direccion,
            v_telefono,
            v_correo,
            v_hash_contrasena);
    SELECT id
    INTO @result
    FROM empleados
    WHERE cedula = v_cedula;
    RETURN @result > 0;
END;
@@
CREATE FUNCTION update_user(
    v_id INT,
    v_permission INT,
    v_name VARCHAR(45),
    v_personal_id VARCHAR(45),
    v_address VARCHAR(10000),
    v_phone VARCHAR(45),
    v_email VARCHAR(200),
    v_password VARCHAR(500),
    v_enabled BOOLEAN
)
    RETURNS BOOLEAN
    LANGUAGE SQL
    NOT DETERMINISTIC
BEGIN
    SELECT id,
           permisos_id,
           nombre_completo,
           cedula,
           direccion,
           telefono,
           correo,
           habilitado
    INTO @id_cliente, @permisos_empleado, @nombre_empleado, @cedula_empleado, @direccion_empleado, @telefono_empleado, @correo_empleado, @habilitado_empleado
    FROM empleados
    WHERE id = v_id;
    IF @id_cliente IS NULL THEN
        RETURN false;
    END IF;
    IF v_permission != @permisos_empleado THEN
        UPDATE
            empleados
        SET permisos_id = v_permission
        WHERE id = v_id;
    END IF;
    IF v_name != @nombre_empleado THEN
        UPDATE
            empleados
        SET nombre_completo = v_name
        WHERE id = v_id;
    END IF;
    IF v_personal_id != @cedula_empleado THEN
        UPDATE
            empleados
        SET cedula = v_personal_id
        WHERE id = v_id;
    END IF;
    IF v_address != @direccion_empleado THEN
        UPDATE
            empleados
        SET direccion = v_address
        WHERE id = v_id;
    END IF;
    IF v_phone != @telefono_empleado THEN
        UPDATE
            empleados
        SET telefono = v_phone
        WHERE id = v_id;
    END IF;
    IF v_email != @correo_empleado THEN
        UPDATE
            empleados
        SET correo = v_email
        WHERE id = v_id;
    END IF;
    IF v_password IS NOT NULL THEN
        UPDATE
            empleados
        SET hash_contrasena = v_password
        WHERE id = v_id;
    END IF;
    UPDATE
        empleados
    SET habilitado = v_enabled
    WHERE id = v_id;
    return true;
END;
@@
CREATE FUNCTION update_product(
    v_id INT,
    v_cantidad INT,
    v_nombre VARCHAR(45),
    v_descripcion VARCHAR(10000),
    v_precio FLOAT,
    v_activo BOOLEAN,
    v_dependencia_id INT
)
    RETURNS BOOLEAN
    LANGUAGE SQL
    NOT DETERMINISTIC
BEGIN
    SELECT id,
           cantidad,
           nombre,
           descripcion,
           precio,
           activo,
           dependencia_id
    INTO @id_producto, @cantidad_producto, @nombre_producto, @descripcion_producto, @precio_producto, @activo_producto, @id_dependencia
    FROM inventario
    WHERE id = v_id;
    IF @id_producto IS NULL THEN
        RETURN false;
    END IF;
    IF @cantidad_producto != v_cantidad THEN
        UPDATE
            inventario
        SET cantidad = v_cantidad
        WHERE id = v_id;
    END IF;
    IF @nombre_producto != v_nombre THEN
        UPDATE
            inventario
        SET nombre = v_nombre
        WHERE id = v_id;
    END IF;
    IF @descripcion_producto != v_descripcion THEN
        UPDATE
            inventario
        SET descripcion = v_descripcion
        WHERE id = v_id;
    END IF;
    IF @precio_producto != v_precio THEN
        UPDATE
            inventario
        SET precio = v_precio
        WHERE id = v_id;
    END IF;
    IF @activo_producto != v_activo THEN
        UPDATE
            inventario
        SET activo = v_activo
        WHERE id = v_id;
    END IF;
    IF @id_dependencia != v_dependencia_id THEN
        UPDATE
            inventario
        SET dependencia_id = v_dependencia_id
        WHERE id = v_id;
    END IF;
    RETURN true;
END;
@@
CREATE FUNCTION registrar_cliente(
    v_nombre_completo VARCHAR(100),
    v_cedula VARCHAR(45),
    v_direccion VARCHAR(500),
    v_telefono VARCHAR(20),
    v_correo VARCHAR(200)
)
    RETURNS BOOLEAN
    LANGUAGE SQL
    NOT DETERMINISTIC
BEGIN
    INSERT INTO clientes (nombre_completo,
                          cedula,
                          direccion,
                          telefono,
                          correo)
    VALUES (v_nombre_completo,
            v_cedula,
            v_direccion,
            v_telefono,
            v_correo);
    SELECT id
    INTO @result
    FROM clientes
    WHERE cedula = v_cedula;
    RETURN @result > 0;
END;
@@
CREATE FUNCTION update_client(
    v_id INT,
    v_name VARCHAR(45),
    v_personal_id VARCHAR(45),
    v_address VARCHAR(10000),
    v_phone VARCHAR(45),
    v_email VARCHAR(200),
    v_enabled BOOLEAN
)
    RETURNS BOOLEAN
    LANGUAGE SQL
    NOT DETERMINISTIC
BEGIN
    SELECT id,
           nombre_completo,
           cedula,
           direccion,
           telefono,
           correo,
           habilitado
    INTO @id_cliente, @nombre_empleado, @cedula_empleado, @direccion_empleado, @telefono_empleado, @correo_empleado, @habilitado_empleado
    FROM clientes
    WHERE id = v_id;
    IF @id_cliente IS NULL THEN
        RETURN false;
    END IF;
    IF v_name != @nombre_empleado THEN
        UPDATE
            clientes
        SET nombre_completo = v_name
        WHERE id = v_id;
    END IF;
    IF v_personal_id != @cedula_empleado THEN
        UPDATE
            clientes
        SET cedula = v_personal_id
        WHERE id = v_id;
    END IF;
    IF v_address != @direccion_empleado THEN
        UPDATE
            clientes
        SET direccion = v_address
        WHERE id = v_id;
    END IF;
    IF v_phone != @telefono_empleado THEN
        UPDATE
            clientes
        SET telefono = v_phone
        WHERE id = v_id;
    END IF;
    IF v_email != @correo_empleado THEN
        UPDATE
            clientes
        SET correo = v_email
        WHERE id = v_id;
    END IF;
    UPDATE
        clientes
    SET habilitado = v_enabled
    WHERE id = v_id;
    return true;
END;
@@

CREATE FUNCTION registrar_orden(
    v_empleados_id INT,
    v_clientes_id INT,
    v_fehca VARCHAR(10),
    v_descuento DOUBLE
)
    RETURNS BOOLEAN
    LANGUAGE SQL
    NOT DETERMINISTIC
BEGIN
    INSERT INTO ordenes_compra (empleados_id,
                                clientes_id,
                                fehca,
                                decuento)
    VALUES (v_empleados_id,
            v_clientes_id,
            v_fehca,
            v_descuento);
    RETURN TRUE;
END;
@@

CREATE FUNCTION registrar_detalles_orden(
    v_cantidad INT,
    v_total INT,
    v_productos INT,
    v_tipo_pago INT,
    v_orden INT
)
    RETURNS BOOLEAN
    LANGUAGE SQL
    NOT DETERMINISTIC
BEGIN
    INSERT INTO detalles_ordenes_compra (cantidad,
                                         valor_total,
                                         productos_id,
                                         tipo_pago_id,
                                         orden_compra_id)
    VALUES (v_cantidad,
            v_total,
            v_productos,
            v_tipo_pago,
            v_orden);
    RETURN TRUE;
END;
@@

CREATE FUNCTION actualizar_cantidad_orden(
    v_producto INT,
    v_cantidad INT
)
    RETURNS BOOLEAN
    LANGUAGE SQL
    NOT DETERMINISTIC
BEGIN
    UPDATE inventario
    SET cantidad = v_cantidad
    WHERE id = v_producto;
    RETURN TRUE;
END;
@@

CREATE FUNCTION cancel_purchase(
    o_id INT,
    o_enabled BOOLEAN
)
    RETURNS BOOLEAN
    LANGUAGE SQL
    NOT DETERMINISTIC
BEGIN
    SELECT id,
           empleados_id,
           clientes_id,
           fehca
    INTO @r_id, @r_empleados_id, @r_clientes_id
    FROM ordenes_compra
    WHERE id = o_id;
    IF @r_id IS NULL THEN
        RETURN false;
    END IF;
    UPDATE
        ordenes_compra
    SET abierta = o_enabled
    WHERE id = o_id;
    return true;
END;
@@

CREATE FUNCTION close_orden(
    v_id INT
)
    RETURNS BOOLEAN
    LANGUAGE SQL
    NOT DETERMINISTIC
BEGIN
    UPDATE ordenes_compra
    SET abierta = 0
    WHERE id = v_id;
    RETURN TRUE;
END;
@@

CREATE FUNCTION registrar_factura(
    v_empleados_id INT,
    v_clientes_id INT,
    v_fehca VARCHAR(10),
    v_descuento DOUBLE
)
    RETURNS BOOLEAN
    LANGUAGE SQL
    NOT DETERMINISTIC
BEGIN
    INSERT INTO facturas (empleados_id,
                          clientes_id,
                          fehca,
                          decuento)
    VALUES (v_empleados_id,
            v_clientes_id,
            v_fehca,
            v_descuento);
    RETURN TRUE;
END;
@@

CREATE FUNCTION registrar_detalles_factura(
    v_cantidad INT,
    v_total INT,
    v_productos INT,
    v_tipo_pago INT,
    v_factura INT
)
    RETURNS BOOLEAN
    LANGUAGE SQL
    NOT DETERMINISTIC
BEGIN
    INSERT INTO detalles_facturas (cantidad,
                                   valor_total,
                                   productos_id,
                                   tipo_pago_id,
                                   facturas_id)
    VALUES (v_cantidad,
            v_total,
            v_productos,
            v_tipo_pago,
            v_factura);
    RETURN TRUE;
END;
@@

CREATE FUNCTION close_factura(
    v_id INT
)
    RETURNS BOOLEAN
    LANGUAGE SQL
    NOT DETERMINISTIC
BEGIN
    UPDATE facturas
    SET abierta = 0
    WHERE id = v_id;
    RETURN TRUE;
END;
@@

CREATE TRIGGER costos_carros
    AFTER INSERT
    ON inventario
    FOR EACH ROW

BEGIN

    SET @id_carro = NEW.id;
    SET @cantidad = NEW.cantidad;
    SET @costo = (NEW.precio * 100) / 125;
    INSERT INTO costos_inventario (productos_id, costo, cantidad) VALUES (@id_carro, @costo, @cantidad);

END;
@@

CREATE TRIGGER actualisar_costos_carros
    AFTER UPDATE
    ON inventario
    FOR EACH ROW

BEGIN

    SET @id_carro = NEW.id;
    SET @costo = (NEW.precio * 100) / 125;
    UPDATE costos_inventario SET costo = @costo WHERE productos_id = @id_carro;

END;
@@

CREATE FUNCTION registrar_gasto(
    v_valor INT,
    v_razon VARCHAR(100)
)
    RETURNS BOOLEAN
    LANGUAGE SQL
    NOT DETERMINISTIC
BEGIN
    INSERT INTO gastos (valor,
                        razon)
    VALUES (v_valor,
            v_razon);
    RETURN TRUE;
END;
@@


DELIMITER ;
