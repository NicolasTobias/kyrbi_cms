#!/bin/sh
#
# Copia el contenido semilla (seed/content) a un directorio de contenido, sin
# sobrescribir lo que ya haya. Mismas semánticas que el initContainer del
# Deployment: lo que existe manda, porque la fuente de la verdad es el panel.
#
#   ./scripts/sembrar.sh              # a ./content, para desarrollo local
#   ./scripts/sembrar.sh /ruta/a/pvc  # a un directorio montado del clúster
#
set -eu

raiz=$(CDPATH= cd -- "$(dirname -- "$0")/.." && pwd)
destino=${1:-"$raiz/content"}

mkdir -p "$destino"
cp -Rn "$raiz/seed/content/." "$destino/"

echo "Contenido semilla copiado a $destino (sin sobrescribir)."
