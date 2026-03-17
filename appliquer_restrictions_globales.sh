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

resultat=$(mysql -u root -p'TON_MDP_MYSQL' natbox_db -N -e "
SELECT g.bloque
FROM grille_horaire g
JOIN appareils a ON g.appareil_id = a.id
WHERE a.nom = 'TOUS_LES_APPAREILS'
AND g.jour = '$jour_fr'
AND g.heure = $heure_actuelle
LIMIT 1;
")

if [ "$resultat" = "1" ]; then
    iptables -C FORWARD -i eth1 -o eth0 -j DROP 2>/dev/null || iptables -I FORWARD -i eth1 -o eth0 -j DROP
else
    iptables -D FORWARD -i eth1 -o eth0 -j DROP 2>/dev/null
fi
