#!/bin/bash
RESEAU=$1
MASQUE=$2
DEBUT=$3
FIN=$4
GATEWAY=$5

sudo bash -c "cat > /etc/dhcp/dhcpd.conf <<EOF
subnet $RESEAU netmask $MASQUE {
    range $DEBUT $FIN;
    option routers $GATEWAY;
    option domain-name-servers 192.168.1.1;
}
EOF"

sudo systemctl restart isc-dhcp-server
echo "Nouvelle configuration DHCP appliquée :"
echo "Réseau : $RESEAU / Masque : $MASQUE"
echo "Plage : $DEBUT - $FIN"
echo "Gateway : $GATEWAY"
