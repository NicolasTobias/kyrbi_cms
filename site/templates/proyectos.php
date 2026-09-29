<?php
/*
  Índice: los proyectos como filas grandes a la izquierda y, a la derecha, la
  foto del proyecto resaltado. Por defecto el primero; con ratón, el que se
  señala (lo cambia sitio.js). En táctil la foto no cambia.
*/

$proyectos = $page->children()->listed()->filterBy('intendedTemplate', 'proyecto');
?>
<?php snippet('header') ?>

<div class="indice" data-indice>
  <h1 class="indice-cabeza kicker"><?= $page->title()->esc() ?></h1>
  <?php foreach ($proyectos->values() as $i => $proyecto): ?>
  <?php $n = $proyecto->fotos()->count() ?>
  <a class="fila<?= $i === 0 ? ' activa' : '' ?>" href="<?= $proyecto->url() ?>" data-fila="<?= $i ?>">
    <span class="fila-titulo"><?= $proyecto->title()->esc() ?></span>
    <span class="fila-meta"><?= $n ?> <?= $n === 1 ? t('ui.foto') : t('ui.fotos') ?><?php if ($proyecto->periodo()->isNotEmpty()): ?> · <?= $proyecto->periodo()->esc() ?><?php endif ?></span>
  </a>
  <?php endforeach ?>
</div>

<div class="indice-visor" aria-hidden="true">
  <?php foreach ($proyectos->values() as $i => $proyecto): ?>
  <?php if ($portada = $proyecto->portada()): ?>
  <img class="<?= $i === 0 ? 'activa' : '' ?>" data-portada="<?= $i ?>"
       src="<?= $portada->thumb(['width' => 1500, 'format' => 'jpg'])->url() ?>"
       srcset="<?= $portada->srcset('hero_jpeg') ?>" sizes="50vw"
       alt="" <?= $i === 0 ? '' : 'loading="lazy"' ?>>
  <?php endif ?>
  <?php endforeach ?>
</div>

<?php snippet('footer') ?>
