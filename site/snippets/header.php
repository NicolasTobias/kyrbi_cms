<?php
/*
  Cabecera común: <head> con metadatos, y la cabecera visible del sitio.

  En la home la cabecera visible NO va arriba: primero se ve una sola
  fotografía a pantalla completa, sin nada encima, y el nombre y el menú
  aparecen al hacer scroll. Para eso la home pasa su foto de apertura en
  `$apertura` y este snippet la pinta antes de la cabecera.
*/

$nombre = $site->title()->or('Nico Tobias');

$titulo = $page->isHomePage()
    ? $nombre . ' — Fotografía'
    : $page->title() . ' — ' . $nombre;

$descripcion = $page->descripcion()->or($site->descripcion());

/* Imagen social: 1200×630 recortado. Específica del proyecto si la hay. */
$social = $page->content()->get('portada')->toFile()
       ?? ($apertura ?? null)
       ?? $page->images()->first()
       ?? $site->content()->get('portada')->toFile();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">

  <title><?= esc($titulo) ?></title>
  <?php if ($descripcion->isNotEmpty()): ?>
  <meta name="description" content="<?= $descripcion->esc('attr') ?>">
  <?php endif ?>
  <link rel="canonical" href="<?= $page->url() ?>">

  <meta property="og:type" content="website">
  <meta property="og:locale" content="es_ES">
  <meta property="og:site_name" content="<?= $nombre->esc('attr') ?>">
  <meta property="og:title" content="<?= esc($titulo, 'attr') ?>">
  <meta property="og:url" content="<?= $page->url() ?>">
  <?php if ($descripcion->isNotEmpty()): ?>
  <meta property="og:description" content="<?= $descripcion->esc('attr') ?>">
  <?php endif ?>
  <?php if ($social): ?>
  <?php $og = $social->crop(1200, 630) ?>
  <meta property="og:image" content="<?= $og->url() ?>">
  <meta property="og:image:width" content="<?= $og->width() ?>">
  <meta property="og:image:height" content="<?= $og->height() ?>">
  <?php if ($social->alt()->isNotEmpty()): ?>
  <meta property="og:image:alt" content="<?= $social->alt()->esc('attr') ?>">
  <?php endif ?>
  <meta name="twitter:card" content="summary_large_image">
  <?php else: ?>
  <meta name="twitter:card" content="summary">
  <?php endif ?>

  <link rel="preload" href="<?= url('assets/fonts/newsreader-latin.woff2') ?>" as="font" type="font/woff2" crossorigin>

  <?= css('assets/css/site.css') ?>

  <link rel="shortcut icon" type="image/x-icon" href="<?= url('favicon.ico') ?>">
</head>
<body>

<?php if ($apertura ?? null): ?>
<div class="apertura">
  <?php snippet('figura', [
    'foto'       => $apertura,
    'set'        => 'hero',
    'sizes'      => '100vw',
    'prioridad'  => true,
    'pie'        => false,
  ]) ?>
</div>
<?php endif ?>

<header class="cabecera contenedor">
  <a class="cabecera-nombre" href="<?= $site->url() ?>"><?= $nombre->esc() ?></a>
  <nav class="menu" aria-label="Navegación principal">
    <?php foreach ($site->children()->listed() as $item): ?>
    <a <?php e($item->isOpen(), 'aria-current="page"') ?> href="<?= $item->url() ?>"><?= $item->title()->esc() ?></a>
    <?php endforeach ?>
  </nav>
</header>

<main class="main contenedor">
