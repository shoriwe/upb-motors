USE upb_motors;

SET @_ = registrar_empleado(
        get_permisos_id('ADMIN'),
        'Antonio',
        '1234567891',
        '1234567890',
        '1234567890',
        'admin@upb-motors.co',
        '$2y$10$WAQuk2pamDhPkUd2Hdxxpe1HoiaGTIEQr6H6GZ9o34rHFbFey5VZ.'
    );

SET @_ = registrar_empleado(
        get_permisos_id('RECURSOS HUMANOS'),
        'Miguel',
        '1234567892',
        '1234567890',
        '1234567890',
        'rh@upb-motors.co',
        '$2y$10$WAQuk2pamDhPkUd2Hdxxpe1HoiaGTIEQr6H6GZ9o34rHFbFey5VZ.'
    );

SET @_ = registrar_empleado(
        get_permisos_id('INVENTARIO'),
        'Jean',
        '1234567893',
        '1234567890',
        '1234567890',
        'inventario@upb-motors.co',
        '$2y$10$WAQuk2pamDhPkUd2Hdxxpe1HoiaGTIEQr6H6GZ9o34rHFbFey5VZ.'
    );

SET @_ = registrar_empleado(
        get_permisos_id('GERENTE'),
        'Andres',
        '1234567894',
        '1234567890',
        '1234567890',
        'gerente@upb-motors.co',
        '$2y$10$WAQuk2pamDhPkUd2Hdxxpe1HoiaGTIEQr6H6GZ9o34rHFbFey5VZ.'
    );

SET @_ = registrar_empleado(
        get_permisos_id('VENTAS'),
        'Gleisson',
        '1234567895',
        '1234567890',
        '1234567890',
        'ventas@upb-motors.co',
        '$2y$10$WAQuk2pamDhPkUd2Hdxxpe1HoiaGTIEQr6H6GZ9o34rHFbFey5VZ.'
    );

SET @_ = registrar_producto(
        10,
        'Versa 2016',
        'Un carro',
        10000000,
        NULL
    );

SET @_ = registrar_producto(
        10,
        'Versa 2020',
        'Otro carro',
        25000000,
        NULL
    );
