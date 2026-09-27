# Desarrollo local

El sitio corre en un contenedor con la misma base que producción (php:8.2-apache).
`site/`, `assets/` y `content/` van montados desde el repo: editas y recargas, sin reconstruir.

```sh
export PATH=/opt/podman/bin:$PATH

podman build -t nicotobias-web:dev .

podman run -d --name nicotobias-dev -p 8080:80 \
  -v "$PWD/site":/var/www/html/site \
  -v "$PWD/assets":/var/www/html/assets \
  -v "$PWD/content":/var/www/html/content \
  nicotobias-web:dev
```

→ http://localhost:8080

Parar / arrancar / ver logs:

```sh
podman stop nicotobias-dev
podman start nicotobias-dev
podman logs -f nicotobias-dev
```

Solo hace falta reconstruir la imagen si tocas el `Dockerfile` o `composer.json`.

## Contenido

`content/` está en `.gitignore`: en producción es una PVC y la fuente de la verdad es
el panel. Lo que sí está versionado es `seed/content/`, el contenido semilla: las cuatro
páginas del sitio (inicio, proyectos, colectivo, sobre) con sus textos, más notas como
borrador. No lleva ninguna foto.

Para plantarlo en un `content/` local:

```sh
./scripts/sembrar.sh
```

Copia con `cp -Rn`: planta lo que falte y no pisa nada. La imagen lleva la misma semilla
en `/var/www/html/content`, así que el initContainer del Deployment la planta en la PVC
con las mismas semánticas.

El contenido demo del starterkit (ocho álbumes, las notas, `3_about`, `sandbox`) se
borró de la PVC el 2026-09-27. Ojo con una trampa del `cp -rn`: los ficheros que ya
existían no se pisan, así que `site.txt`, `home/home.txt` y `error/error.txt` siguieron
siendo los del demo hasta que se borraron y se reinició el pod para que el initContainer
plantara los nuestros. Si algún día un fichero de la semilla "no llega", es esto.

```sh
kubectl --context arenero -n websites exec deploy/website-photo -- \
  ls /var/www/html/content
```

## Modo mantenimiento

Con `NT_MANTENIMIENTO=true` en el entorno, todo el sitio responde 503 con una página de
una línea; el panel, `media/` y la API siguen accesibles. Un enlace roto es neutro, un
enlace a contenido demo resta credibilidad.

Se enciende desde el `env` del Deployment. En local:

```sh
podman run -d --name nicotobias-dev -p 8080:80 -e NT_MANTENIMIENTO=true ... 
```

Conviene dejarlo encendido hasta que haya fotos reales en `/proyectos`.

## Thumbs e ImageMagick

`thumbs.driver` es `im`, no GD: **GD descarta el perfil ICC al redimensionar**, y un sRGB
perdido desatura las fotos en Safari. El driver `im` de Kirby solo hace `-strip` en PNG,
así que en JPEG y WebP conserva el perfil y los metadatos IPTC. Por eso el Dockerfile
instala `imagemagick`.

La contrapartida: al conservarlo todo, también conserva el resto del EXIF (cámara, y GPS
si la cámara lo escribe). Kirby no permite un stripping selectivo, así que **el EXIF que
no deba publicarse hay que quitarlo al exportar**, dejando el copyright del IPTC y el
perfil sRGB incrustado.

Anchos de thumb: 600/900/1200/1500 para las fotos en columna, 900/1200/1500/2200 para la
apertura de la home. Calidad 82. WebP con respaldo JPEG vía `<picture>`, en
`site/snippets/figura.php`.

## Notas

- `site/config/config.php` no fija `url`. En producción lo tapa el ConfigMap
  `website-photo-kirby-config`, que sí la fija. Si se añade `NT_MANTENIMIENTO` o cualquier
  otra opción, revisar que el ConfigMap no la borre.
- La web no lleva **nada** de JavaScript. Si aparece un `<script>`, algo se ha colado.
- `/notas` existe como borrador y no está en el menú. Se publica desde el panel el día que
  haya una primera nota.
- Kirby se actualiza tocando `composer.lock`. Si se edita a mano (aquí no hay composer),
  hay que expandir el formato p2 de Packagist, que viene minificado: cada versión solo
  trae los campos que cambian. Perder `type: kirby-cms` instala Kirby en `vendor/` sin
  dar error y tumba el sitio en runtime. El `Dockerfile` lo comprueba desde entonces.
