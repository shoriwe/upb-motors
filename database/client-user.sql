CREATE USER 'upb_motors_client'@'localhost' IDENTIFIED BY 'password';

GRANT SELECT ON upb_motors.* TO 'upb_motors_client'@'localhost';
GRANT INSERT ON upb_motors.* TO 'upb_motors_client'@'localhost';
GRANT UPDATE ON upb_motors.* TO 'upb_motors_client'@'localhost';

FLUSH PRIVILEGES;
