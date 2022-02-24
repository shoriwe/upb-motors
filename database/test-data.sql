INSERT INTO empleados (nombre_completo,
                       cedula,
                       direccion,
                       telefono,
                       correo,
                       hash_contrasena,
                       permisos_id)
VALUES ('TESTER',
        '1234567890',
        '1234567890',
        '1234567890',
        'upbmotorstestmail@gmail.com',
        '$2y$10$WAQuk2pamDhPkUd2Hdxxpe1HoiaGTIEQr6H6GZ9o34rHFbFey5VZ.',
        get_permisos_id('INVENTARIO'));


INSERT INTO inventario
(cantidad,
 nombre,
 descripcion,
 precio)
VALUES (10,
        'Versa 2016',
        'Un carro',
        10),
       (10,
        'Versa 2020',
        'Otro carro',
        20);