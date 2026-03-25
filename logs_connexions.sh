#!/bin/bash

DATE_HEURE=$(date '+%Y-%m-%d %H:%M:%S')
JOUR_EN=$(date +%A)
HEURE=$(date +%H)

case "$JOUR_EN" in
    Monday) JOUR_FR="lundi" ;;
    Tuesday) JOUR_FR="mardi" ;;
    Wednesday) JOUR_FR="mercredi" ;;
    Thursday) JOUR_FR="jeudi" ;;
    Friday) JOUR_FR="vendredi" ;;
    Saturday) JOUR_FR="samedi" ;;
    Sunday) JOUR_FR="dimanche" ;;
    *) JOUR_FR="inconnu" ;;
esac

MYSQL_CMD="mysql -u root -p'root' natbox_db -N -e"

$MYSQL_CMD "
SELECT a.nom, a.ip, a.mac
FROM appareils a
WHERE a.mac <> '00:00:00:00:00:00';
" | while read nom ip mac
do
    if sudo iptables -L FORWARD -n | grep -q "$mac"; then
        etat="BLOQUE"
    else
        etat="AUTORISE"
    fi

    debit=$((RANDOM % 25 + 1))
    echo "$DATE_HEURE;$nom;$ip;$mac;$JOUR_FR;$HEURE;$etat;$debit;" >> /var/www/html/logs_connexions.csv
done
