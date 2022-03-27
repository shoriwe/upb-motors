CREATE USER 'upb_motors_client'@'localhost' IDENTIFIED BY 'password';

GRANT EXECUTE ON PROCEDURE upb_motors.actualizar_contrasena TO 'upb_motors_client'@'localhost';

GRANT EXECUTE ON FUNCTION upb_motors.get_log_level TO 'upb_motors_client'@'localhost';
GRANT EXECUTE ON FUNCTION upb_motors.get_permisos_id TO 'upb_motors_client'@'localhost';
GRANT EXECUTE ON FUNCTION upb_motors.registrar_producto TO 'upb_motors_client'@'localhost';
GRANT EXECUTE ON FUNCTION upb_motors.registrar_empleado TO 'upb_motors_client'@'localhost';
GRANT EXECUTE ON FUNCTION upb_motors.update_user TO 'upb_motors_client'@'localhost';
GRANT EXECUTE ON FUNCTION upb_motors.update_product TO 'upb_motors_client'@'localhost';
GRANT EXECUTE ON FUNCTION upb_motors.registrar_cliente TO 'upb_motors_client'@'localhost';
GRANT EXECUTE ON FUNCTION upb_motors.update_client TO 'upb_motors_client'@'localhost';
GRANT EXECUTE ON FUNCTION upb_motors.registrar_orden TO 'upb_motors_client'@'localhost';
GRANT EXECUTE ON FUNCTION upb_motors.actualizar_cantidad_orden TO 'upb_motors_client'@'localhost';
GRANT EXECUTE ON FUNCTION upb_motors.close_factura TO 'upb_motors_client'@'localhost';
GRANT EXECUTE ON FUNCTION upb_motors.close_orden TO 'upb_motors_client'@'localhost';
GRANT EXECUTE ON FUNCTION upb_motors.registrar_factura TO 'upb_motors_client'@'localhost';
GRANT EXECUTE ON FUNCTION upb_motors.registrar_detalles_factura TO 'upb_motors_client'@'localhost';
GRANT EXECUTE ON FUNCTION upb_motors.registrar_gasto TO 'upb_motors_client'@'localhost';
GRANT EXECUTE ON FUNCTION upb_motors.cancel_purchase TO 'upb_motors_client'@'localhost';
GRANT EXECUTE ON FUNCTION upb_motors.get_dependencia_name TO 'upb_motors_client'@'localhost';
GRANT EXECUTE ON FUNCTION upb_motors.get_dependencia_id TO 'upb_motors_client'@'localhost';
GRANT EXECUTE ON FUNCTION upb_motors.registrar_detalles_orden TO 'upb_motors_client'@'localhost';

GRANT SELECT ON upb_motors.* TO 'upb_motors_client'@'localhost';
GRANT INSERT ON upb_motors.* TO 'upb_motors_client'@'localhost';
GRANT UPDATE ON upb_motors.* TO 'upb_motors_client'@'localhost';

FLUSH PRIVILEGES;
