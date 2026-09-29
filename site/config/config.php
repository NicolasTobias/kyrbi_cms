<?php

/*
  Config base. NO fijar 'url' aquí: Kirby la detecta sola, y así el mismo
  código sirve en local (localhost:8080) y en producción.

  Ojo: en el clúster, este fichero lo tapa el ConfigMap
  website-photo-kirby-config, que sí fija la URL. Ver deployment.yaml.
*/

/*
  Anchos de thumb. 600–1500 para fotos pequeñas (retrato); 800–2000 para las
  fotos a sangre (apertura de la home, visor de proyecto e índice), que
  ocupan la ventana entera.
*/
$anchosFoto = [600, 900, 1200, 1500];
$anchosHero = [800, 1200, 1500, 2000];

/*
  Construye un srcset de Kirby: mismo ancho en cada entrada, un formato por
  srcset. El <picture> de snippets/figura.php sirve WebP y deja el JPEG como
  respaldo.
*/
$srcset = function (array $anchos, string $formato): array {
    $set = [];
    foreach ($anchos as $ancho) {
        $set[$ancho . 'w'] = [
            'width'  => $ancho,
            'format' => $formato,
        ];
    }
    return $set;
};

/*
  Modo mantenimiento. Un enlace roto es neutro; un enlace a contenido demo
  resta credibilidad. Se enciende con NT_MANTENIMIENTO=true (en el clúster,
  desde el env del Deployment) y no toca el panel: se sigue pudiendo editar.
*/
$mantenimiento = getenv('NT_MANTENIMIENTO') === 'true';

$rutasMantenimiento = [
    [
        'pattern'  => '(:all)',
        'language' => '*',
        'action'   => function ($language, string $path = '') {
            foreach (['panel', 'media', 'api', 'healthz', 'favicon.ico'] as $excepcion) {
                if ($path === $excepcion || str_starts_with($path, $excepcion . '/')) {
                    return $this->next();
                }
            }

            $html = '<!DOCTYPE html><html lang="es"><head><meta charset="utf-8">'
                  . '<meta name="viewport" content="width=device-width,initial-scale=1">'
                  . '<meta name="robots" content="noindex">'
                  . '<title>Nico Tobias — Fotografía</title>'
                  . '<style>html{font-size:112.5%}body{margin:0;min-height:100svh;display:grid;'
                  . 'place-content:center;padding:5vw;background:#fff;color:#000;'
                  . 'font-family:Georgia,"Times New Roman",serif;font-size:1rem;line-height:1.6}'
                  . 'p{max-width:34rem;margin:0}a{color:inherit}</style></head><body>'
                  . '<p>Nico Tobias — fotografía de calle y documental. '
                  . 'La web está en obras. Escribe a '
                  . '<a href="mailto:hola@nicotobias.com">hola@nicotobias.com</a>.</p>'
                  . '</body></html>';

            return new Kirby\Http\Response($html, 'text/html', 503, [
                'Retry-After' => '86400',
            ]);
        },
    ],
];

$rutas = [
    /*
      Salud para las probes de Kubernetes. Tiene que responder 200 también en
      mantenimiento: si las probes apuntan a `/` y `/` devuelve 503, el pod
      nunca pasa a Ready y la liveness lo reinicia en bucle.
    */
    [
        'pattern' => 'healthz',
        'action'  => fn () => new Kirby\Http\Response("ok\n", 'text/plain'),
    ],

    /*
      La sección /photography del starterkit pasa a /proyectos. Los álbumes
      demo no tienen equivalente, así que todo el subárbol cae en el índice.
    */
    [
        'pattern' => ['photography', 'photography/(:all)'],
        'action'  => fn () => go('proyectos', 301),
    ],
    [
        'pattern' => ['about', 'about/(:all)', 'sobre', 'sobre/(:all)'],
        'action'  => fn () => go('quien-soy', 301),
    ],

    /*
      Colectivo está apagado (pasará a ser "Quedadas"). Mientras no exista la
      página nueva, la URL vieja lleva a la home en vez de dar 404.
    */
    [
        'pattern' => ['colectivo', 'colectivo/(:all)'],
        'action'  => fn () => go('/', 302),
    ],
    [
        'pattern' => ['notes', 'notes/(:all)'],
        'action'  => fn () => go('notas', 301),
    ],
    [
        'pattern' => 'robots.txt',
        'action'  => function () {
            $lineas = [
                'User-agent: *',
                'Disallow: /panel',
                'Allow: /',
                '',
                'Sitemap: ' . url('sitemap.xml'),
            ];

            return new Kirby\Http\Response(
                implode("\n", $lineas) . "\n",
                'text/plain'
            );
        },
    ],
    [
        'pattern' => 'sitemap.xml',
        'action'  => function () {
            $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

            $apagadas = ['colectivo', 'quedadas', 'notas'];

            foreach (site()->index()->listed() as $pagina) {
                if ($pagina->isErrorPage() === true
                    || in_array($pagina->intendedTemplate()->name(), $apagadas, true)) {
                    continue;
                }

                foreach (kirby()->languages() as $idioma) {
                    $xml .= '  <url><loc>' . Kirby\Toolkit\Escape::html($pagina->url($idioma->code())) . '</loc>'
                          . '<lastmod>' . $pagina->modified('c') . '</lastmod></url>' . "\n";
                }
            }

            $xml .= '</urlset>' . "\n";

            return new Kirby\Http\Response($xml, 'application/xml');
        },
    ],
];

return [
    'languages' => true,

    'panel' => [
        'install' => false,
    ],

    /*
      Driver ImageMagick, no GD: GD descarta el perfil ICC al redimensionar y
      un sRGB perdido desatura las fotos en Safari. El driver `im` de Kirby
      solo hace -strip en PNG, así que en JPEG/WebP conserva perfil e IPTC.
      Necesita el binario `convert` en la imagen (ver Dockerfile).
    */
    'thumbs' => [
        'driver'  => 'im',
        'quality' => 82,
        'srcsets' => [
            'foto_webp' => $srcset($anchosFoto, 'webp'),
            'foto_jpeg' => $srcset($anchosFoto, 'jpg'),
            'hero_webp' => $srcset($anchosHero, 'webp'),
            'hero_jpeg' => $srcset($anchosHero, 'jpg'),
        ],
    ],

    'routes' => $mantenimiento ? array_merge($rutasMantenimiento, $rutas) : $rutas,
];
