# Acceso a internet

###### Antonio Jose Donis Hung - 000408397 (Product owner)

## Razones por su ausencia

Tanto **IPTables** como el **Proxy** no se lograron debido a la inestabilidad del router al momento de su configuración. La inestabilidad fue un problema debido a que retraso tareas con mayor prioridad como **La configuración de las VLANs**, **configuración del NAT entre empresas** y **servicio de DHCP para la VLAN de empleados**.

El **Proxy** no logro implementarse en su totalidad debido a que si no hay acceso a internet no hay razón para que el proxy este presente debido a que su razón de implementación era hacerle proxy al internet al trafico de sus clientes.

## Consejos para su implementación de manera independiente

1. Utilizar el servidor en el que esta la implementación parcial de **IPTables** o reconfigurar un servidor con una completa configuración de el mismo.
2. Instalar en el mismo servidor el proxy **SOCKS5** y **HTTP**.
3. En el router configurar que el trafico que no se pueda resolver con el consorcio, se intente resolver a través del servidor con las **IPTables**.
4. En el router configurar en el **NAT** la redirección del puerto `9050` (**SOCKS5**) y `8081` (**HTTP**) para que pueda ser utilizado por los miembros del consorcio.