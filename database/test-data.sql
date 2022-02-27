SET @_ = registrar_empleado(
        get_permisos_id('ADMIN'),
        'ADMIN',
        '1234567890',
        '1234567890',
        '1234567890',
        'upbmotorstestmail@gmail.com',
        '$2y$10$WAQuk2pamDhPkUd2Hdxxpe1HoiaGTIEQr6H6GZ9o34rHFbFey5VZ.'
    );

SET @_ = registrar_producto(
        10,
        'Versa 2016',
        'Un carro',
        10000000
    );

SET @_ = registrar_producto(
        10,
        'Versa 2020',
        'Otro carro',
        25000000
    );
