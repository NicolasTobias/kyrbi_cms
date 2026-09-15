<?php snippet('header') ?>

<?php
/*
  La foto destacada. Si no se ha elegido ninguna en el panel,
  cae en la portada del primer proyecto para que la home nunca
  aparezca vacía.
*/
$projects = page('photography')?->children()->listed();
$featured = $page->featured()->toFile() ?? $projects?->first()?->cover();
?>

<section class="masthead">
<?php if ($featured): ?>
<div class="hero">
  <figure class="print">
    <img src="<?= $featured->resize(1800)->url() ?>"
         alt="<?= $featured->alt()->esc() ?>"
         width="<?= $featured->width() ?>" height="<?= $featured->height() ?>">
  </figure>
  <figcaption class="hero-caption">
    <?php if ($featured->alt()->isNotEmpty()): ?>
      <span><?= $featured->alt()->esc() ?></span>
    <?php endif ?>
    <?php if ($parent = $featured->parent()): ?>
      <span class="sep">/</span>
      <span><a href="<?= $parent->url() ?>"><?= $parent->title()->esc() ?></a></span>
    <?php endif ?>
  </figcaption>
</div>
<?php endif ?>

<?php if ($page->headline()->isNotEmpty() || $page->subheadline()->isNotEmpty()): ?>
<div class="intro">
  <?php if ($page->headline()->isNotEmpty()): ?>
  <h1><?= $page->headline()->esc() ?></h1>
  <?php endif ?>
  <?php if ($page->subheadline()->isNotEmpty()): ?>
  <p><?= $page->subheadline()->esc() ?></p>
  <?php endif ?>
</div>
<?php endif ?>
</section>

<?php if ($projects && $projects->isNotEmpty()): ?>
<section>
  <h2 class="section-label eyebrow">Proyectos</h2>
  <ul class="home-grid">
    <?php foreach ($projects as $album): ?>
    <li>
      <a href="<?= $album->url() ?>">
        <figure class="print">
          <?php if ($cover = $album->cover()): ?>
          <img src="<?= $cover->resize(900, 900)->url() ?>" alt="<?= $cover->alt()->esc() ?>">
          <?php endif ?>
        </figure>
        <figcaption>
          <span><?= $album->title()->esc() ?></span>
          <span class="count"><?= $album->images()->count() ?> fotos</span>
        </figcaption>
      </a>
    </li>
    <?php endforeach ?>
  </ul>
</section>
<?php endif ?>

<?php
/* Sobre mí: resumen editable en la propia página "Sobre mí", no duplicado aquí. */
$about = page('about');
?>
<?php if ($about && $about->excerpt()->isNotEmpty()): ?>
<section class="about-teaser">
  <h2 class="section-label eyebrow"><?= $about->title()->esc() ?></h2>
  <div class="about-teaser-body">
    <?php if ($portrait = $about->portrait()->toFile()): ?>
    <figure class="print about-portrait">
      <img src="<?= $portrait->crop(600, 750)->url() ?>" alt="<?= $portrait->alt()->esc() ?>">
    </figure>
    <?php endif ?>
    <div class="about-teaser-text">
      <p><?= $about->excerpt()->esc() ?></p>
      <p><a href="<?= $about->url() ?>">Seguir leyendo &rarr;</a></p>
    </div>
  </div>
</section>
<?php endif ?>

<?php snippet('footer') ?>
