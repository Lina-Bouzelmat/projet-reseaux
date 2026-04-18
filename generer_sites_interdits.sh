#!/bin/bash

mysql -u root -proot natbox_db -N -e "
SELECT domaine
FROM sites_interdits
WHERE actif = 1;
" > /tmp/sites_interdits.txt
