#!/bin/bash

CSV="/var/www/html/logs_sites.csv"
RAPPORT="/var/www/html/rapport_sites.csv"
DATE_JOUR=$(date "+%Y-%m-%d")

if [ ! -f "$CSV" ]; then
    echo "Fichier logs_sites.csv introuvable"
    exit 1
fi

echo "site;nb_connexions;debit_total_mbps;debit_moyen_mbps" > "$RAPPORT"

awk -F';' -v jour="$DATE_JOUR" '
NR > 1 {
    if(substr($1,1,10) == jour){
        site = $4
        debit = $5 + 0
        nb[site]++
        total[site] += debit
    }
}
END {
    for(site in nb){
        moyenne = total[site] / nb[site]
        printf "%s;%d;%.2f;%.2f\n", site, nb[site], total[site], moyenne
    }
}
' "$CSV" | sort -t';' -k3 -nr >> "$RAPPORT"
