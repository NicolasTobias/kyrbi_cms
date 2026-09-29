<?php
/*
  Cabecera común: <head> con metadatos y la barra de navegación con el
  selector de idioma.

  En la home la barra va superpuesta a la foto (lo resuelve el CSS con la clase
  de plantilla del body); la home pasa su foto en `$apertura` solo para la
  imagen social. `$precarga` es la siguiente foto del visor de proyecto.
*/

$codigo = $kirby->language()->code();
$nombre = $site->title()->or('Nico Tobias');

$titulo = $page->isHomePage()
    ? $nombre . ' — ' . t('ui.fotografia')
    : $page->title() . ' — ' . $nombre;

$descripcion = $page->descripcion()->or($site->descripcion());

/* Imagen social: 1200×630 recortado. Específica del proyecto si la hay. */
$social = $page->content()->get('portada')->toFile()
       ?? ($apertura ?? null)
       ?? $page->images()->first()
       ?? $site->content()->get('portada')->toFile();

/* Idiomas en orden fijo: el de por defecto primero (ES / EN), no alfabético. */
$idiomas = array_merge(
    [$kirby->defaultLanguage()],
    array_values(array_filter($kirby->languages()->values(), fn ($l) => !$l->isDefault()))
);

/* Páginas apagadas: existen en el panel pero no se enseñan en el menú. */
$apagadas = ['colectivo', 'quedadas', 'notas'];
$secciones = $site->children()->listed()->filter(
    fn ($p) => !in_array($p->intendedTemplate()->name(), $apagadas, true)
);
?>
<!DOCTYPE html>
<html lang="<?= $codigo ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">

  <title><?= esc($titulo) ?></title>
  <?php if ($descripcion->isNotEmpty()): ?>
  <meta name="description" content="<?= $descripcion->esc('attr') ?>">
  <?php endif ?>
  <link rel="canonical" href="<?= $page->url() ?>">
  <?php foreach ($kirby->languages() as $l): ?>
  <link rel="alternate" hreflang="<?= $l->code() ?>" href="<?= $page->url($l->code()) ?>">
  <?php endforeach ?>
  <link rel="alternate" hreflang="x-default" href="<?= $page->url($kirby->defaultLanguage()->code()) ?>">

  <meta property="og:type" content="website">
  <meta property="og:locale" content="<?= t('ui.og_locale') ?>">
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

  <link rel="preload" href="<?= url('assets/fonts/archivo-latin-400.woff2') ?>" as="font" type="font/woff2" crossorigin>
  <link rel="preload" href="<?= url('assets/fonts/archivo-latin-800.woff2') ?>" as="font" type="font/woff2" crossorigin>
  <?php if ($precarga ?? null): ?>
  <link rel="preload" as="image" type="image/webp" imagesrcset="<?= $precarga->srcset('hero_webp') ?>" imagesizes="100vw">
  <?php endif ?>

  <?= css('assets/css/site.css') ?>

  <link rel="shortcut icon" type="image/x-icon" href="<?= url('favicon.ico') ?>">
</head>
<body class="pagina-<?= $page->intendedTemplate()->name() ?>">

<header class="cabecera">
  <a class="cabecera-nombre" href="<?= $site->url() ?>"><?= $nombre->esc() ?></a>
  <input type="checkbox" id="menu-abierto" class="menu-check" aria-label="<?= t('ui.menu') ?>">
  <label for="menu-abierto" class="menu-boton"><span class="t-abrir"><?= t('ui.menu') ?></span><span class="t-cerrar"><?= t('ui.cerrar') ?></span></label>
  <nav class="menu" aria-label="<?= t('ui.navegacion') ?>">
    <?php foreach ($secciones as $item): ?>
    <a <?php e($item->isOpen(), 'aria-current="page"') ?> href="<?= $item->url() ?>"><?= $item->title()->esc() ?></a>
    <?php endforeach ?>
    <span class="idiomas" aria-label="<?= t('ui.idioma') ?>">
      <?php foreach ($idiomas as $n => $l): ?>
      <?php if ($n > 0): ?> / <?php endif ?>
      <?php if ($l->code() === $codigo): ?>
      <span class="idioma activo" aria-current="true"><?= strtoupper($l->code()) ?></span>
      <?php else: ?>
      <a class="idioma" href="<?= $page->url($l->code()) ?>" hreflang="<?= $l->code() ?>" lang="<?= $l->code() ?>"><?= strtoupper($l->code()) ?></a>
      <?php endif ?>
      <?php endforeach ?>
    </span>
    <?php if ($site->email()->isNotEmpty()): ?>
    <a class="menu-correo" href="mailto:<?= $site->email()->esc('attr') ?>"><?= $site->email()->esc() ?></a>
    <?php endif ?>
  </nav>
</header>

<main class="main">
