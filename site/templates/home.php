<?php
/*
  La home es el momento fuerte: una sola fotografía a pantalla completa y,
  al hacer scroll, la serie completa de un proyecto destacado. No es una
  rejilla de miniaturas: el trabajo funciona en serie y se debilita en fotos
  sueltas.
*/

$proyectos = page('proyectos')?->children()->listed()->filterBy('intendedTemplate', 'proyecto');
$destacado = $page->destacado()->toPage();

/* El selector del panel podría apuntar a algo que no sea un proyecto. */
if ($destacado === null || $destacado->intendedTemplate()->name() !== 'proyecto') {
    $destacado = $proyectos?->first();
}

$apertura  = $page->portada()->toFile() ?? $destacado?->portada();

$serie = $destacado?->fotos();

/*
  Si la foto de apertura pertenece a la serie destacada, no se repite:
  verla de nuevo justo después del primer scroll parece un fallo.
*/
if ($serie && $apertura) {
    $serie = $serie->filter(fn ($foto) => $foto->id() !== $apertura->id());
}
?>
<?php snippet('header', ['apertura' => $apertura]) ?>

<?php if ($destacado && $serie && $serie->isNotEmpty()): ?>
<section>
  <h1 class="home-serie-titulo"><a href="<?= $destacado->url() ?>"><?= $destacado->title()->esc() ?></a></h1>

  <div class="serie">
    <?php foreach ($serie as $foto): ?>
    <?php snippet('figura', ['foto' => $foto]) ?>
    <?php endforeach ?>
  </div>
</section>
<?php endif ?>

<div class="home-cierre">
  <?php if ($page->bio_linea()->isNotEmpty()): ?>
  <p><?= $page->bio_linea()->esc() ?></p>
  <?php endif ?>
  <?php if ($proyectos && $proyectos->count() > 1): ?>
  <p><a href="<?= page('proyectos')->url() ?>">Ver los demás proyectos</a></p>
  <?php endif ?>
</div>

<?php snippet('footer') ?>
