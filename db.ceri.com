$TTL 604800
@   IN  SOA ns1.ceri.com. root.ceri.com. (
        1        ; Serial
        604800   ; Refresh
        86400    ; Retry
        2419200  ; Expire
        604800 ) ; Negative Cache TTL

; ----- Serveurs de noms -----
@       IN  NS  ns1.ceri.com.
@       IN  NS  ns2.ceri.com.

; ----- Adresses -----
ns1     IN  A   192.168.1.1
ns2     IN  A   192.168.1.2
alice   IN  A   192.168.1.10
bob     IN  A   192.168.1.11

; ----- Délégation de la sous-zone -----
l3      IN  NS  ns2.l3.ceri.com.
ns2.l3  IN  A   192.168.1.2
