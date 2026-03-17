#!/bin/bash

jour_en=$(date +%A)
heure_actuelle=$(date +%H)

case "$jour_en" in
    Monday) jour_fr="lundi" ;;
    Tuesday) jour_fr="mardi" ;;
    Wednesday) jour_fr="mercredi" ;;
    Thursday) jour_fr="jeudi" ;;
    Friday) jour_fr="vendredi" ;;
    Saturday) jour_fr="samedi" ;;
    Sunday) jour_fr="dimanche" ;;
    *) exit 0 ;;
esac

MYSQL_CMD="mysql -u root -p'TON_MDP_MYSQL' natbox_db -N -e"

macs_a_bloquer=$($MYSQL_CMD "
SELECT a.mac
FROM grille_horaire g
JOIN appareils a ON g.appareil_id = a.id
WHERE g.jour = '$jour_fr'
AND g.heure = $heure_actuelle
AND g.bloque = 1;
")

toutes_macs=$($MYSQL_CMD "
SELECT mac
FROM appareils
WHERE mac <> '00:00:00:00:00:00';
")

for mac in $macs_a_bloquer
do
    iptables -C FORWARD -m mac --mac-source "$mac" -j DROP 2>/dev/null || \
    iptables -I FORWARD -m mac --mac-source "$mac" -j DROP
done

for mac in $toutes_macs
do
    echo "$macs_a_bloquer" | grep -q "$mac"
    if [ $? -ne 0 ]; then
        iptables -D FORWARD -m mac --mac-source "$mac" -j DROP 2>/dev/null
    fi
done
