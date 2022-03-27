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
        20,
        'Versa 2016',
        'Un carro',
        10000000,
        NULL,
        2
    );

SET @_ = registrar_producto(
        20,
        'Versa 2020',
        'Otro carro',
        25000000,
        NULL,
        1
    );

INSERT INTO clientes(nombre_completo,cedula,direccion,telefono,correo) VALUES('pepito',100453,'calle 48 # 47 - 22',36455,'pepito@gmail.com');

INSERT INTO facturas(empleados_id,clientes_id,fehca,decuento,abierta) VALUES(5,1,CURDATE(),0,1);
INSERT INTO facturas(empleados_id,clientes_id,fehca,decuento,abierta) VALUES(5,1,CURDATE(),0,1);
INSERT INTO facturas(empleados_id,clientes_id,fehca,decuento,abierta) VALUES(5,1,CURDATE(),0,1);
INSERT INTO facturas(empleados_id,clientes_id,fehca,decuento,abierta) VALUES(5,1,CURDATE(),0,1);

INSERT INTO detalles_facturas(cantidad, valor_total, productos_id, tipo_pago_id, facturas_id) VALUES(2,20000000,1,1,1);
INSERT INTO detalles_facturas(cantidad, valor_total, productos_id, tipo_pago_id, facturas_id) VALUES(1,25000000,2,1,1);
INSERT INTO detalles_facturas(cantidad, valor_total, productos_id, tipo_pago_id, facturas_id) VALUES(1,25000000,2,2,2);
INSERT INTO detalles_facturas(cantidad, valor_total, productos_id, tipo_pago_id, facturas_id) VALUES(1,25000000,2,3,3);
INSERT INTO detalles_facturas(cantidad, valor_total, productos_id, tipo_pago_id, facturas_id) VALUES(1,25000000,2,3,4);
INSERT INTO detalles_facturas(cantidad, valor_total, productos_id, tipo_pago_id, facturas_id) VALUES(1,10000000,1,3,4);

INSERT INTO gastos(valor, razon) VALUES(200000,'Factura luz');
INSERT INTO gastos(valor, razon) VALUES(1000,'Razon x');

INSERT INTO ordenes_compra(empleados_id,clientes_id,fehca,decuento,abierta) VALUES(5,1,CURDATE(),0,1);
INSERT INTO ordenes_compra(empleados_id,clientes_id,fehca,decuento,abierta) VALUES(5,1,CURDATE(),0,1);

INSERT INTO detalles_ordenes_compra(cantidad, valor_total, productos_id, tipo_pago_id, orden_compra_id) VALUES(2,20000000,1,1,1);
INSERT INTO detalles_ordenes_compra(cantidad, valor_total, productos_id, tipo_pago_id, orden_compra_id) VALUES(1,25000000,2,1,1);
INSERT INTO detalles_ordenes_compra(cantidad, valor_total, productos_id, tipo_pago_id, orden_compra_id) VALUES(1,25000000,2,2,2);
