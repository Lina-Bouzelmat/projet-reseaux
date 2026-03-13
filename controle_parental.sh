#!/bin/bash

MAC="08:00:27:f5:74:f3"

heure=$(date +%H)

if [ $heure -ge 0 ] && [ $heure -lt 8 ]; then
    iptables -C FORWARD -m mac --mac-source $MAC -j DROP 2>/dev/null
    if [ $? -ne 0 ]; then
        iptables -I FORWARD -m mac --mac-source $MAC -j DROP
    fi
else
    iptables -D FORWARD -m mac --mac-source $MAC -j DROP 2>/dev/null
fi
