#!/bin/bash
RESEAU=$1
MASQUE=$2
DEBUT=$3
FIN=$4
GATEWAY=$5

if [ $# -ne 5 ]; then
	echo "erreur dans les 5 argument"
	exit 1
fi

DHCP_CONF="/etc/dhcp/dhcp.conf"

echo ">> generation du fichier dhcp avec nouvelle valeur..."
sudo bash -c "cat > $DHCP_CONF <<EOF
subnet $RESEAU netmask $MASQUE {
    range $DEBUT $FIN;
    option routers $GATEWAY;
    option domain-name-servers 192.168.1.1;
    default-lease-time 600;
    max-lease-time 7200;
}
EOF"

echo ">> redemarrage du service DHCP..."
sudo systemctl restart isc-dhcp-server

if systemctl is-active --quiet isc-dhcp-server; then
	echo "Nouvelle configuration DHCP appliquée :"
#	echo "Réseau : $RESEAU / Masque : $MASQUE"
	echo "Plage : $DEBUT - $FIN"
#	echo "Gateway : $GATEWAY"
else
	echo "le service dhcp ne redemare pas;"
fi
