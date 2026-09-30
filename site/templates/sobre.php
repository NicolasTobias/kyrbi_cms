<?php
/*
  Quién soy. Título y lead arriba, luego el texto largo y el contacto. Con
  retrato, va fijo a la izquierda mientras se lee (sticky); en móvil pasa entre
  el lead y el texto. La página crece con el texto: no tiene alto fijo.
*/

$retrato = $page->retrato()->toFile();

/* Marcador de texto pendiente (p. ej. la versión EN): se pinta en gris. */
$pendiente = Str::startsWith(trim((string) $page->texto()->value()), '[');
?>
<?php snippet('header') ?>

<div class="sobre<?= $retrato ? ' con-retrato' : '' ?>">
  <div class="sobre-cabeza">
    <h1 class="pagina-titulo"><?= $page->title()->esc() ?></h1>
    <?php if ($page->bio()->isNotEmpty()): ?>
    <p class="sobre-lead"><?= $page->bio()->esc() ?></p>
    <?php endif ?>
  </div>

  <?php if ($retrato): ?>
  <div class="sobre-retrato">
    <div class="sobre-retrato-fijo">
      <?php snippet('figura', [
        'foto'      => $retrato,
        'sizes'     => '(min-width: 801px) 40vw, 100vw',
        'prioridad' => true,
        'pie'       => false,
      ]) ?>
    </div>
  </div>
  <?php endif ?>

  <div class="sobre-cuerpo">
    <?php if ($page->texto()->isNotEmpty()): ?>
    <hr class="regla">
    <div class="sobre-largo<?= $pendiente ? ' pendiente' : '' ?>"><?= $page->texto()->kt() ?></div>
    <?php endif ?>

    <?php if ($page->email()->isNotEmpty()): ?>
    <p class="contacto"><?= t('ui.contacto') ?> <?= Html::email($page->email()->value()) ?></p>
    <?php endif ?>
  </div>
</div>

<?php snippet('footer') ?>
