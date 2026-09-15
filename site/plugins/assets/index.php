<?php

/*
  Kirby versiona solo, con un hash en la ruta, lo que sirve desde media/.
  Lo de assets/ no: el CSS se queda cacheado en Cloudflare (max-age=14400)
  y un cambio de diseño tarda horas en verse.

  Colgamos la fecha de modificación del fichero como query string, así cada
  edición genera una URL nueva y el edge la trata como un recurso distinto.
*/

Kirby::plugin('nicotobias/assets', [
    'components' => [
        'css' => function (Kirby\Cms\App $kirby, string $path, $options) {
            // El componente original resuelve la ruta a URL; aquí solo la sellamos.
            $url = $kirby->nativeComponent('css')($kirby, $path, $options);

            if (is_string($url) === false) {
                return $url;
            }

            $file = $kirby->root('index') . '/' . ltrim($path, '/');

            if (is_file($file) === true) {
                $url .= (str_contains($url, '?') ? '&' : '?') . 'v=' . filemtime($file);
            }

            return $url;
        },
    ],
]);
