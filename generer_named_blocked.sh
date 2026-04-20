#!/bin/bash

APPAREIL_ID="$1"

if [ -z "$APPAREIL_ID" ]; then
    echo "Usage: $0 <appareil_id>"
    exit 1
fi

mysql -u root -proot natbox_db -N -e "
SELECT s.domaine
FROM blocages_sites b
JOIN sites_catalogue s ON b.site_id = s.id
WHERE b.appareil_id = $APPAREIL_ID
AND b.actif = 1;
" > /tmp/sites_bloques_appareil.txt

> /tmp/named.conf.blocked

while read domaine
do
    if [ -n "$domaine" ]; then
        echo "zone \"$domaine\" {" >> /tmp/named.conf.blocked
        echo "    type master;" >> /tmp/named.conf.blocked
        echo "    file \"/etc/bind/blocked/db.blocked\";" >> /tmp/named.conf.blocked
        echo "};" >> /tmp/named.conf.blocked
        echo "" >> /tmp/named.conf.blocked

        echo "zone \"www.$domaine\" {" >> /tmp/named.conf.blocked
        echo "    type master;" >> /tmp/named.conf.blocked
        echo "    file \"/etc/bind/blocked/db.blocked\";" >> /tmp/named.conf.blocked
        echo "};" >> /tmp/named.conf.blocked
        echo "" >> /tmp/named.conf.blocked
    fi
done < /tmp/sites_bloques_appareil.txt

echo "Fichier généré : /tmp/named.conf.blocked"
