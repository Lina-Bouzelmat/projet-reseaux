#!/bin/bash

SITE="$1"
APPAREIL="$2"
IP_APPAREIL="$3"

URL="http://192.168.1.10/test.bin"
OUTPUT="/tmp/test.bin"
CSV="/var/www/html/logs_sites.csv"

if [ -z "$SITE" ] || [ -z "$APPAREIL" ] || [ -z "$IP_APPAREIL" ]; then
    echo "Usage: $0 <site> <appareil> <ip>"
    exit 1
fi

if [ ! -f "$CSV" ]; then
    echo "date_heure;appareil;ip;site;debit_mbps" > "$CSV"
fi

SPEED_MB=$(wget -O "$OUTPUT" "$URL" 2>&1 | grep -o "[0-9.]\+ MB/s" | tail -1 | awk '{print $1}')
DATE=$(date "+%Y-%m-%d %H:%M:%S")

if [ ! -z "$SPEED_MB" ]; then
    DEBIT_MBPS=$(awk "BEGIN {printf \"%.2f\", $SPEED_MB * 8}")
    echo "$DATE;$APPAREIL;$IP_APPAREIL;$SITE;$DEBIT_MBPS" >> "$CSV"
fi
