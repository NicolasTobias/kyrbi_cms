<?php
/** @var \Kirby\Cms\Block $block */

/*
  El bloque de imagen dentro de una nota. Sin lightbox y sin enlace: si se
  hace clic en una foto, no pasa nada.
*/

$foto = $block->image()->toFile();
?>
<?php if ($foto): ?>
<?php snippet('figura', [
  'foto'  => $foto,
  'sizes' => '(min-width: 900px) 34rem, 90vw',
  'pie'   => false,
]) ?>
<?php if ($block->caption()->isNotEmpty()): ?>
<p class="nota-fecha"><?= $block->caption() ?></p>
<?php endif ?>
<?php endif ?>
