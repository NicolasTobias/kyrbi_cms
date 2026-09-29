<?php

/*
  Kirby versiona solo, con un hash en la ruta, lo que sirve desde media/.
  Lo de assets/ no: el CSS y el JS se quedan cacheados en Cloudflare
  (max-age=14400) y un cambio tardaría horas en verse.

  Colgamos la fecha de modificación del fichero como query string, así cada
  edición genera una URL nueva y el edge la trata como un recurso distinto.
*/

$sellar = function (string $tipo) {
    return function (Kirby\Cms\App $kirby, string $path, $options) use ($tipo) {
        // El componente original resuelve la ruta a URL; aquí solo la sellamos.
        $url = $kirby->nativeComponent($tipo)($kirby, $path, $options);

        if (is_string($url) === false) {
            return $url;
        }

        $file = $kirby->root('index') . '/' . ltrim($path, '/');

        if (is_file($file) === true) {
            $url .= (str_contains($url, '?') ? '&' : '?') . 'v=' . filemtime($file);
        }

        return $url;
    };
};

Kirby::plugin('nicotobias/assets', [
    'components' => [
        'css' => $sellar('css'),
        'js'  => $sellar('js'),
    ],
]);
