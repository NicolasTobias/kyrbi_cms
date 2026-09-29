#!/bin/sh
#
# Migra un directorio de contenido de Kirby de "un solo idioma" a multiidioma.
#
# Con `languages => true`, Kirby solo lee los .txt que llevan código de idioma
# (home.es.txt, no home.txt): un contenido sin migrar aparece vacío, sin error.
# Este script:
#
#   1. renombra cada .txt sin código de idioma a .es.txt (el idioma por defecto),
#      incluidos los de metadatos de fichero (foto.jpg.txt -> foto.jpg.es.txt);
#   2. renombra 3_sobre a 3_quien-soy (el slug ES nuevo de "Quién soy").
#
# Es idempotente: se puede ejecutar cuantas veces haga falta. Ejecutarlo ANTES
# de sembrar (`cp -rn seed/...`), para que la semilla solo rellene lo que falte.
#
#   ./scripts/migrar-idiomas.sh ./content
#   ./scripts/migrar-idiomas.sh /mnt/content      # dentro del initContainer
#
set -eu

destino=${1:?uso: migrar-idiomas.sh <directorio-de-contenido>}
[ -d "$destino" ] || { echo "No existe $destino"; exit 1; }

renombrados=0
tmp=$(mktemp)
find "$destino" -type f -name '*.txt' ! -name '*.es.txt' ! -name '*.en.txt' > "$tmp"

while IFS= read -r f; do
  nuevo="${f%.txt}.es.txt"
  if [ -e "$nuevo" ]; then
    echo "  salto $f: ya existe ${nuevo##*/}"
    continue
  fi
  mv "$f" "$nuevo"
  renombrados=$((renombrados + 1))
done < "$tmp"
rm -f "$tmp"

if [ -d "$destino/3_sobre" ] && [ ! -e "$destino/3_quien-soy" ]; then
  mv "$destino/3_sobre" "$destino/3_quien-soy"
  echo "  3_sobre -> 3_quien-soy"
fi

echo "Migración de idiomas: $renombrados fichero(s) renombrado(s) en $destino"
