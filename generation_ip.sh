#!/bin/bash

# verifier qu'un argument a été fourni
if [ "$#" -ne 1 ]; then
	echo "Usage: $0 numero"
	echo "exemple: $0 3 -> donnnera 192.168.1.13"
	exit 1
fi

NUM=$1
IFACE=eth1
MASK=24
GW=192.168.1.1

#definir la plage dhcp

NET="192.168.1"
START=10
END=50

if ! [[ "$NUM" =~ ^[0-9]+$ ]]; then
	echo "Erreur de saisie, mets un nombre"
	exit 1
fi

IP_NUM=$((START+NUM))

if [ "$IP_NUM" -gt "$END" ]; then
	echo "Erreur: le numeros $NUM depasse le maximum"
	echo "Plage valide: $NET.$START -> $NET.$END"
	exit 1
fi

IP="$NET.$IP_NUM"


#calcul iip final
IP="$NET.$((START+NUM))"

echo ">> Attribution auto de l'addr $IP/$MASK à $IFACE"

#suppression de l'ancien

sudo ip addr flush dev $IFACE

#ajout nouvelle

sudo ip addr add $IP/$MASK dev $IFACE

#ajout/remplacement

sudo ip route replace default via $GW

echo ">> Nouvelle IP appliquée : $IP/$MASK avec gateway $GW"
