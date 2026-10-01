<?php
/*
  Una serie a pantalla: una foto detrás de otra, a ancho completo, con mucho
  aire entre ellas. Sin rejilla, sin carrusel, sin pies de foto: el orden es el
  del panel (ver ProyectoPage::fotos). No se recorta; width/height del original
  reservan el hueco.

  Al final, el lugar y el año de cada foto, solo de las que lo tienen.
*/

$fotos  = $page->fotos();
$lugares = $fotos->filter(fn ($f) => $f->lugar_ano()->isNotEmpty());
$anterior  = $page->prevListed();
$siguiente = $page->nextListed();
?>
<?php snippet('header') ?>

<article class="proyecto">
  <header class="proyecto-cabeza<?= $page->intro()->isEmpty() ? ' sin-intro' : '' ?>">
    <h1 class="proyecto-titulo"><?= $page->title()->esc() ?></h1>
    <?php if ($page->intro()->isNotEmpty()): ?>
    <div class="proyecto-intro"><?= $page->intro()->kt() ?></div>
    <?php endif ?>
  </header>

  <?php if ($fotos->isNotEmpty()): ?>
  <div class="serie">
    <?php foreach ($fotos->values() as $i => $foto): ?>
    <?php snippet('figura', [
      'foto'      => $foto,
      'set'       => 'hero',
      'sizes'     => '(min-width: 1596px) 1500px, (min-width: 801px) calc(100vw - 64px), 100vw',
      'prioridad' => $i === 0,
    ]) ?>
    <?php endforeach ?>
  </div>
  <?php else: ?>
  <p class="serie-vacia"><?= t('ui.sin_fotos') ?></p>
  <?php endif ?>

  <?php if ($lugares->isNotEmpty()): ?>
  <section class="lugares">
    <h2 class="kicker"><?= t('ui.lugar_ano') ?></h2>
    <ol>
      <?php foreach ($fotos->values() as $i => $foto): ?>
      <?php if ($foto->lugar_ano()->isEmpty()) continue ?>
      <li><b><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></b> <?= $foto->lugar_ano()->esc() ?></li>
      <?php endforeach ?>
    </ol>
  </section>
  <?php endif ?>
</article>

<?php if ($anterior || $siguiente): ?>
<nav class="proyecto-nav" aria-label="<?= t('ui.otros_proyectos') ?>">
  <?php if ($anterior): ?>
  <a class="anterior" href="<?= $anterior->url() ?>" rel="prev">&larr; <?= t('ui.anterior') ?><span><?= $anterior->title()->esc() ?></span></a>
  <?php else: ?>
  <span></span>
  <?php endif ?>
  <?php if ($siguiente): ?>
  <a class="siguiente" href="<?= $siguiente->url() ?>" rel="next"><?= t('ui.siguiente') ?> &rarr;<span><?= $siguiente->title()->esc() ?></span></a>
  <?php endif ?>
</nav>
<?php endif ?>

<?php snippet('footer') ?>
