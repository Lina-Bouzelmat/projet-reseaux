#!/bin/bash

NOM=$1
DNSP_USER="stud"
DNSP_IP="192.168.1.1"
ZONE_FILE="/etc/bind/db.ceri.com"
SOUSDOMAINE_LIGNE="$NOM IN A 192.168.1.14"

# Dans le fichier zone sur DNSP
ssh $DNSP_USER@$DNSP_IP "echo '$SOUSDOMAINE_LIGNE' | sudo tee -a $ZONE_FILE"

# Redemarrer bind9
ssh $DNSP_USER@$DNSP_IP "sudo systemctl restart bind9"
