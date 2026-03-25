#!/bin/bash

FICHIER="/var/www/html/logs_connexions.csv"
TMP="/tmp/logs_analyse.csv"

head -n 1 "$FICHIER" > "$TMP"

tail -n +2 "$FICHIER" | while IFS=";" read date_heure appareil ip mac jour heure etat debit alerte
do
    nouvelle_alerte=""

    if [ "$etat" = "BLOQUE" ]; then
        nouvelle_alerte="Appareil actuellement bloque"
    fi

    if [ "$debit" -gt 15 ]; then
        if [ -n "$nouvelle_alerte" ]; then
            nouvelle_alerte="$nouvelle_alerte | Debit anormal"
        else
            nouvelle_alerte="Debit anormal"
        fi
    fi

    echo "$date_heure;$appareil;$ip;$mac;$jour;$heure;$etat;$debit;$nouvelle_alerte" >> "$TMP"
done

mv "$TMP" "$FICHIER"
