<?php
/*
  Una fotografía con su srcset, su alt en español y su pie opcional.

  Se sirve WebP con respaldo JPEG vía <picture>. `width` y `height` son los
  del original: el navegador solo necesita la relación de aspecto para
  reservar el hueco y no dar saltos de maquetación. No se recorta a un
  formato común: verticales y horizontales conviven.

  Parámetros:
    foto       File   obligatorio
    set        string 'foto' (columna, hasta 1500px) o 'hero' (apertura)
    sizes      string atributo sizes acorde a la retícula
    prioridad  bool   true solo en la foto de apertura de la home
    pie        bool   pintar el pie de foto si lo tiene
*/

$set       = $set ?? 'foto';
$sizes     = $sizes ?? '(min-width: 1660px) 1500px, (min-width: 900px) calc(100vw - 8rem), 90vw';
$prioridad = $prioridad ?? false;
$pie       = $pie ?? true;

/* Respaldo para navegadores sin srcset: el ancho intermedio. */
$respaldo = $foto->thumb([
    'width'  => $set === 'hero' ? 1500 : 1200,
    'format' => 'jpg',
]);
?>
<figure class="figura">
  <picture>
    <source
      type="image/webp"
      srcset="<?= $foto->srcset($set . '_webp') ?>"
      sizes="<?= esc($sizes, 'attr') ?>">
    <img
      src="<?= $respaldo->url() ?>"
      srcset="<?= $foto->srcset($set . '_jpeg') ?>"
      sizes="<?= esc($sizes, 'attr') ?>"
      alt="<?= $foto->alt()->esc('attr') ?>"
      width="<?= $foto->width() ?>"
      height="<?= $foto->height() ?>"
      <?php if ($prioridad): ?>fetchpriority="high" decoding="async"<?php else: ?>loading="lazy" decoding="async"<?php endif ?>>
  </picture>
  <?php if ($pie && $foto->caption()->isNotEmpty()): ?>
  <figcaption><?= $foto->caption()->esc() ?></figcaption>
  <?php endif ?>
</figure>
