#!/bin/bash

# Vérifier que l'utilisateur a donné une IP
if [ "$#" -ne 1 ]; then
	echo "Usage: $0 nouvelle_ip"
	exit 1
fi

IP=$1
IFACE=eth1
MASK=24
GW=192.168.1.1

echo ">> changement IP en cours pour $IFACE..."

# suppression ancienne adresse

sudo ip addr flush dev $IFACE

# ajout de la nouvelle IP

sudo ip addr add $IP/$MASK dev $IFACE

# ajout de la route par default

sudo ip route replace default via $GW

echo ">> nouvelle IP: $IP/$MASK, Passerelle: $GW"
