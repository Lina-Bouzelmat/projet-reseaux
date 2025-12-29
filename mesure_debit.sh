#!/bin/bash

URL="http://192.168.1.10/test.bin"
OUTPUT="/tmp/test.bin"
CSV="/var/www/html/debit.csv"

# Mesure du débit avec wget
SPEED=$(wget -O $OUTPUT $URL 2>&1 | grep -o "[0-9.]\+ MB/s" | tail -1 | awk '{print $1}')

# Date actuelle
DATE=$(date "+%Y-%m-%d %H:%M:%S")

# Si mesure OK → on écrit
if [ ! -z "$SPEED" ]; then
    echo "$DATE,$SPEED" >> $CSV
fi

# Garde seulement les 5 dernières lignes
tail -n 5 $CSV > /tmp/debit.tmp && mv /tmp/debit.tmp $CSV
