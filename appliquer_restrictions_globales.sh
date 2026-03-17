#!/bin/bash

jour_en=$(date +%A)
heure_actuelle=$(date +%H:%M:%S)

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
SELECT r.actif, r.heure_debut, r.heure_fin
FROM regles_parentales r
JOIN appareils a ON r.appareil_id = a.id
WHERE a.nom = 'TOUS_LES_APPAREILS'
AND r.jour = '$jour_fr'
LIMIT 1;
")

actif=$(echo "$resultat" | awk '{print $1}')
debut=$(echo "$resultat" | awk '{print $2}')
fin=$(echo "$resultat" | awk '{print $3}')

if [ "$actif" = "1" ] && [[ "$heure_actuelle" > "$debut" && "$heure_actuelle" < "$fin" ]]; then
    iptables -C FORWARD -i eth1 -o eth0 -j DROP 2>/dev/null || iptables -I FORWARD -i eth1 -o eth0 -j DROP
else
    iptables -D FORWARD -i eth1 -o eth0 -j DROP 2>/dev/null
fi
