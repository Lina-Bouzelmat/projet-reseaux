#!/bin/bash

FILE="/home/stud/ams_logs/connexions.csv"

tail -n +2 "$FILE" | while IFS=";" read date mac ip debit volume
do
    if [ "$debit" -gt 15 ]; then
        echo "ALERTE : débit anormal pour $mac"
    fi

    if [ "$volume" -gt 1000 ]; then
        echo "ALERTE : volume anormal pour $mac"
    fi
done
