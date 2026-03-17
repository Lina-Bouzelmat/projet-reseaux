#!/bin/bash

arp -a | grep "192.168.1." | while read line
do
    ip=$(echo "$line" | awk '{print $2}' | tr -d '()')
    mac=$(echo "$line" | awk '{print $4}')

    if [ -n "$ip" ] && [ -n "$mac" ] && [ "$mac" != "<incomplete>" ]; then
        mysql -u root -p'TON_MDP_MYSQL' natbox_db -e "
        INSERT INTO appareils(nom, mac, ip)
        VALUES(NULL, '$mac', '$ip')
        ON DUPLICATE KEY UPDATE ip = VALUES(ip);
        " >/dev/null 2>&1
    fi
done
