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
ip nat inside
ip access-group 102 in
ipv6 address 2801:0:2E0:D:A::1/80
ipv6 nd managed-config-flag
ipv6 dhcp server vlan100ipv6
exit
interface GigabitEthernet0/0/1.200
encapsulation dot1Q 200
ip address 192.168.101.17 255.255.255.240
ip nat inside
ip access-group 102 in
ipv6 address 2801:0:2E0:D:B::1/80
exit
interface Serial0/1/0
ip address 10.10.30.2 255.255.255.252
ip nat outside
ip access-group 101 in
ipv6 address 2801:0:2E0:1:0::10/126
ipv6 ospf 1 area 0
no shutdown
exit
interface Serial0/1/1
ip address 10.10.40.1 255.255.255.252
ip nat outside
ip access-group 101 in
ipv6 address 2801:0:2E0:1:0::5/126
ipv6 ospf 1 area 0
no shutdown
exit
router ospf 1
network 10.10.30.0 0.0.0.3 area 0
network 10.10.40.0 0.0.0.3 area 0
exit
ip route 0.0.0.0 0.0.0.0 10.10.40.2
ipv6 route ::/0 Serial0/1/1
ipv6 router ospf 1
exit
ip nat inside source static tcp 192.168.101.18 80 10.10.30.1 80
ip nat inside source static udp 192.168.101.18 443 192.168.101.18 443
ip nat inside source static udp 192.168.101.19 53 192.168.101.19 53
ip nat inside source static tcp 192.168.101.18 8080 192.168.101.18 8080
ip nat inside source static udp 192.168.101.18 8080 192.168.101.18 8080
access-list 101 permit ospf any any
access-list 101 permit tcp any any eq 80
access-list 101 permit tcp any any eq 443
access-list 101 permit udp any any eq 53
access-list 101 permit tcp any any eq pop3
access-list 101 permit tcp any any eq smtp
access-list 101 permit udp any any eq 110
access-list 101 permit udp any any eq 25
access-list 101 deny icmp any 192.168.101.0 0.0.0.255
access-list 101 permit ip any any 
access-list 102 deny icmp 192.168.101.0 0.0.0.255 192.168.101.32 0.0.0.15
access-list 102 deny icmp 192.168.101.0 0.0.0.255 192.168.101.16 0.0.0.15
access-list 102 permit ip any any
copy running-config startup-config
exit
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