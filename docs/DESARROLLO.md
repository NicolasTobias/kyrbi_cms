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

## Notas

- `content/` está en `.gitignore`: es una copia del contenido demo que hay hoy en la
  PVC, traída con `kubectl exec ... tar`. No es la fuente de la verdad.
- `site/config/config.php` no fija `url`. En producción lo tapa el ConfigMap
  `website-photo-kirby-config`, que sí la fija.
- Nada de esto se ha desplegado. Producción sigue con la imagen anterior.
