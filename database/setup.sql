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

CREATE TABLE IF NOT EXISTS logs
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
    INSERT INTO logs (fecha,
                      nivel,
                      mensaje)
    VALUES (NOW(), get_log_level('AUTH'), CONCAT('COULD NOT LOGIN AS ', correo));
END;

CREATE PROCEDURE
    log_login_succeed(IN correo VARCHAR(500))
BEGIN
    INSERT INTO logs (fecha,
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
       ('INVENTARIO');

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
    cedula          VARCHAR(10)  NOT NULL,
    direccion       VARCHAR(500) NOT NULL,
    telefono        VARCHAR(20)  NOT NULL,
    correo          VARCHAR(500) NOT NULL,
    hash_contrasena VARCHAR(500) NOT NULL,
    habilitado      BOOLEAN DEFAULT true,
    CONSTRAINT empleados_cedula_unique UNIQUE (cedula),
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
    activo      BOOLEAN        NOT NULL DEFAULT false,
    imagen      LONGBLOB,
    CONSTRAINT unique_producto UNIQUE (nombre)
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
