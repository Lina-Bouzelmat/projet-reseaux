#!/bin/bash
HOSTNAME=$1
IP=$2
ZONEFILE="/etc/bind/db.ceri.com"

# Vérifie que le nom n'existe pas déjà
if grep -q "$HOSTNAME" "$ZONEFILE"; then
    echo "Le nom $HOSTNAME existe déjà."
    exit 1
fi

# Ajoute l'enregistrement
echo "$HOSTNAME   IN  A   $IP" >> "$ZONEFILE"

# Incrémente le serial dans le fichier de zone
sed -i '/^[ \t]*[0-9]\{10\}/ s/[0-9]\{10\}/'$(date +%Y%m%d%H)'/' "$ZONEFILE"

# Redémarre bind
sudo systemctl restart bind9

echo "Ajouté : $HOSTNAME A $IP"
