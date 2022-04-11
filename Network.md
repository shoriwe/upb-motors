# Network

## Primary zone domains

| Domain                  | Type       | IPv4           | IPv6              |
| ----------------------- | ---------- |----------------|-------------------|
| www.matriz.autoupb.com  | A and AAAA | 192.168.101.19 | 2801:0:2E0:D:B::3 |
| ftp.matriz.autoupb.com  | A and AAAA | 192.168.101.19 | 2801:0:2E0:D:B::3 |
| mail.matriz.autoupb.com | A and AAAA | 192.168.101.18 | 2801:0:2E0:D:B::2 |
| mail.matriz.autoupb.com | MX         | 192.168.101.18 | 2801:0:2E0:D:B::2 |

## Secondary zone

| Company           | Domain             | DNS server IP  |
| ----------------- | ------------------ | -------------- |
| Matriz            | matriz.autoupb.com | 192.168.101.19 |
| Concesionario - 1 | ?                  | ?              |
| Concesionario - 2 | ?                  | ?              |
| Taller            | ?                  | ?              |
| Partes            | ?                  | ?              |

## Topology

| Computer         | Services                      | VLAN  | IPv6              | IPv6 mask | IPv6 Default Gateway     | DNS server IPV6   | IPv4           | IPv4 mask | IPv4 Default Gateway | DNS server IPV4 |
| ---------------- |-------------------------------|-------|-------------------|-----------|--------------------------|-------------------|----------------| --------- | -------------------- |-----------------|
| Debian           | HTTP, HTTPS, POP3, IMAP, SMTP | 200   | 2801:0:2E0:D:B::2 | 80        | 2801:0:2E0:D:B::1        | 2801:0:2E0:D:B::3 | 192.168.101.18 | 28        | 192.168.101.17       | 192.168.101.19  |
| Windows server 1 | FTP, FTPS, Primary DNS        | 200   | 2801:0:2E0:D:B::3 | 80        | 2801:0:2E0:D:B::1        | 2801:0:2E0:D:B::3 | 192.168.101.19 | 28        | 192.168.101.17       | 192.168.101.19  |
| Mint             | Proxy and IPTables            | 200   | 2801:0:2E0:D:B::4 | 80        | 2801:0:2E0:D:B::1        | 2801:0:2E0:D:B::3 | 192.168.101.20 | 28        | 192.168.101.17       | 192.168.101.19  |
| Employees        | Internet                      | 100   | DHCP              | 80        | FE80::260:47FF:FE51:2702 | 2801:0:2E0:D:B::3 | DHCP           | 28        | 192.168.101.33       | 192.168.101.19  |


## Switch ports

- vlan 100 from fastEthernet 10 to fastEthernet 17
- vlan 200 from fastEthernet 1 to fastEthernet 3
- GigabitEthernet 0/1 connects to router GigabitEthernet 0/0/1

## Router ports

- serial 0/1/0 connects to serial 0/1/0 of COMPANY 
- serial 0/1/1 connects to serial 0/1/1 of COMPANY
- GigabitEthernet 0/0/1 connects to switch GigabitEthernet 0/1

## Scripts

### Router

```
enable
configure terminal
ip dhcp pool vlan100ipv4
network 192.168.101.32 255.255.255.240
default-router 192.168.101.33
dns-server 192.168.101.19
exit
ipv6 unicast-routing
ipv6 dhcp pool vlan100ipv6
address prefix 2801:0:2E0:D:A::2/80
dns-server 2801:0:2E0:D:B::3
exit
interface GigabitEthernet0/0/1
ip address 192.168.101.1 255.255.255.240
ip nat inside
no shutdown
exit
interface GigabitEthernet0/0/1.100
encapsulation dot1Q 100
ip address 192.168.101.33 255.255.255.240
ipv6 address 2801:0:2E0:D:A::1/80
ipv6 nd managed-config-flag
ipv6 dhcp server vlan100ipv6
exit
interface GigabitEthernet0/0/1.200
encapsulation dot1Q 200
ip address 192.168.101.17 255.255.255.240
ipv6 address 2801:0:2E0:D:B::1/80
exit
interface Serial0/1/0
ip address 10.10.30.2 255.255.255.0
ip access-group 101 in
ipv6 address 2801:0:2E0:1::5/126
ipv6 ospf 1 area 0
no shutdown
exit
interface Serial0/1/1
ip address 10.10.40.1 255.255.255.0
ip nat outside
ipv6 address 2801:0:2E0:1::9/126
ipv6 ospf 1 area 0
no shutdown
exit
router ospf 1
network 10.10.30.0 0.0.0.255 area 0
network 10.10.40.0 0.0.0.255 area 0
exit
ipv6 router ospf 1
router-id 1.1.1.1
exit
ip nat pool NAT_POOL_UPBMOTORS 10.10.30.3 10.10.30.254 netmask 255.255.255.0
ip nat inside source list 1 pool NAT_POOL_UPBMOTORS
access-list 1 permit 192.168.101.32 0.0.0.15
access-list 1 permit 192.168.101.16 0.0.0.15
access-list 101 permit tcp any host 192.168.101.18 eq 80
access-list 101 permit tcp any host 192.168.101.18 eq 433
access-list 101 permit tcp any host 192.168.101.19 eq 53
access-list 101 permit icmp any 10.10.30.0 0.0.0.255
exit
copy running-config startup-config
```

### Switch

```
enable 
configure terminal 
vlan 100
name empleados
exit
vlan 200
name servidores
exit
interface range fastEthernet 0/10-17
switchport mode access 
switchport access vlan 100
exit
interface range fastEthernet 0/1-3
switchport mode access
switchport access vlan 200
exit
interface gigabitEthernet 0/1
switchport mode trunk 
switchport trunk allowed vlan 100,200
exit
copy running-config startup-config
```