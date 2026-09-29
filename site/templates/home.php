<?php
/*
  La home es una sola pantalla: la foto del proyecto actual a sangre, con su
  título y una línea de presentación. Sin rejilla ni serie debajo: el trabajo
  se ve entrando al proyecto.
*/

$proyectos = page('proyectos')?->children()->listed()->filterBy('intendedTemplate', 'proyecto');
$destacado = $page->destacado()->toPage();

/* El selector del panel podría apuntar a algo que no sea un proyecto. */
if ($destacado === null || $destacado->intendedTemplate()->name() !== 'proyecto') {
    $destacado = $proyectos?->first();
}

$apertura = $page->portada()->toFile() ?? $destacado?->portada();
$lema     = $page->bio_linea()->or($site->descripcion());
?>
<?php snippet('header', ['apertura' => $apertura]) ?>

<section class="hero">
  <?php if ($apertura): ?>
  <?php snippet('figura', [
    'foto'      => $apertura,
    'set'       => 'hero',
    'sizes'     => '100vw',
    'prioridad' => true,
    'pie'       => false,
  ]) ?>
  <?php endif ?>

  <?php if ($destacado): ?>
  <a class="hero-enlace" href="<?= $destacado->url() ?>" aria-label="<?= t('ui.ver_proyecto') ?>: <?= $destacado->title()->esc('attr') ?>"></a>
  <?php endif ?>

  <div class="hero-pie">
    <div>
      <?php if ($destacado): ?>
      <p class="kicker"><?= t('ui.proyecto_actual') ?></p>
      <h1 class="hero-titulo"><a href="<?= $destacado->url() ?>"><?= $destacado->title()->esc() ?></a></h1>
      <?php else: ?>
      <h1 class="hero-titulo"><?= $site->title()->esc() ?></h1>
      <?php endif ?>
    </div>
    <?php if ($lema->isNotEmpty()): ?>
    <p class="hero-lema"><?= $lema->esc() ?></p>
    <?php endif ?>
  </div>
</section>

<?php snippet('footer') ?>
