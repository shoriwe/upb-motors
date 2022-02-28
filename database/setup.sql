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

CREATE FUNCTION get_log_level(nombre VARCHAR(500)) RETURNS INT
    LANGUAGE SQL
BEGIN
    SELECT id INTO @resultado FROM niveles_log WHERE niveles_log.nombre = nombre LIMIT 1;
    RETURN @resultado;
END;

CREATE TABLE IF NOT EXISTS app_logs
(
    id      INT           NOT NULL PRIMARY KEY AUTO_INCREMENT,
    fecha   DATETIME      NOT NULL,
    nivel   INT           NOT NULL,
    mensaje VARCHAR(5000) NOT NULL,
    CONSTRAINT fk_logs_niveles_log_nivel FOREIGN KEY (nivel) REFERENCES niveles_log (id)
);

CREATE PROCEDURE
    log_login_failed(IN correo VARCHAR(500))
BEGIN
    INSERT INTO app_logs (fecha,
                          nivel,
                          mensaje)
    VALUES (NOW(), get_log_level('AUTH'), CONCAT('COULD NOT LOGIN AS ', correo));
END;

CREATE PROCEDURE
    log_login_succeed(IN correo VARCHAR(500))
BEGIN
    INSERT INTO app_logs (fecha,
                          nivel,
                          mensaje)
    VALUES (NOW(), get_log_level('LOG'), CONCAT('LOGIN SUCCEED ', correo));
END;

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

CREATE FUNCTION get_permisos_id(nombre_permiso VARCHAR(45)) RETURNS INT
BEGIN
    SELECT id INTO @resultado FROM permisos WHERE nombre = nombre_permiso LIMIT 1;
    RETURN @resultado;
END;
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

-- -- Inventario -- --
CREATE TABLE IF NOT EXISTS inventario
(
    id          INT            NOT NULL PRIMARY KEY AUTO_INCREMENT,
    cantidad    INT            NOT NULL DEFAULT 0,
    nombre      VARCHAR(255)   NOT NULL,
    descripcion VARCHAR(10000) NOT NULL,
    precio      DOUBLE         NOT NULL,
    activo      BOOLEAN        NOT NULL DEFAULT true,
    imagen      LONGBLOB,
    CONSTRAINT unique_producto UNIQUE (nombre)
);

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

-- -- Procesos -- --
CREATE PROCEDURE actualizar_contrasena(IN v_id INT, IN hash_nueva_contrasena VARCHAR(500))
BEGIN
    UPDATE
        empleados
    SET empleados.hash_contrasena = hash_nueva_contrasena
    WHERE empleados.id = v_id;
END;

CREATE FUNCTION registrar_producto(
    v_cantidad INT,
    v_nombre VARCHAR(255),
    v_descripcion VARCHAR(10000),
    v_precio FLOAT,
    v_imagen LONGBLOB
)
    RETURNS BOOLEAN
    LANGUAGE SQL
    NOT DETERMINISTIC
BEGIN
    SELECT id,
           cantidad,
           descripcion,
           precio,
           imagen
    INTO @id_producto, @cantidad_producto, @descripcion_producto, @precio_producto, @imagen_producto
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
    ELSE
        INSERT INTO inventario (cantidad,
                                nombre,
                                descripcion,
                                precio,
                                imagen)
        VALUES (v_cantidad,
                v_nombre,
                v_descripcion,
                v_precio,
                v_imagen);
    END IF;
    RETURN true;
END;

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

CREATE FUNCTION update_product(
    v_id INT,
    v_cantidad INT,
    v_nombre VARCHAR(45),
    v_descripcion VARCHAR(10000),
    v_precio FLOAT,
    v_activo BOOLEAN
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
           activo
    INTO @id_producto, @cantidad_producto, @nombre_producto, @descripcion_producto, @precio_producto, @activo_producto
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
    RETURN true;
END;

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