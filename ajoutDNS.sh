#!/bin/bash
# Script lancé depuis le serveur AMS, ajout d’un sous-domaine dans le DNSP

NOM=$1
DNSP_USER="stud"
DNSP_IP="192.168.1.10"
ZONE_FILE="/etc/bind/db.ceri.com"
SOUSDOMAINE_LIGNE="$NOM IN A 192.168.1.13"

# Étape 1 : Écrire l'entrée dans le fichier zone sur DNSP
ssh $DNSP_USER@$DNSP_IP "echo '$SOUSDOMAINE_LIGNE' | sudo tee -a $ZONE_FILE"

# Étape 2 : Redémarrer bind9
ssh $DNSP_USER@$DNSP_IP "sudo systemctl restart bind9"
