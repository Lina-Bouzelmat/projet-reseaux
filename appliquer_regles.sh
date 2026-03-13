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

mysql -u root -p'root' natbox_db -N -e "
SELECT a.mac, r.heure_debut, r.heure_fin
FROM regles_parentales r
JOIN appareils a ON r.appareil_id = a.id
WHERE r.actif = 1 AND r.jour = '$jour_fr';
" | while read mac debut fin
do
    if [[ "$heure_actuelle" > "$debut" && "$heure_actuelle" < "$fin" ]]; then
        iptables -C FORWARD -m mac --mac-source "$mac" -j DROP 2>/dev/null
        if [ $? -ne 0 ]; then
            iptables -I FORWARD -m mac --mac-source "$mac" -j DROP
        fi
    else
        iptables -D FORWARD -m mac --mac-source "$mac" -j DROP 2>/dev/null
    fi
done
