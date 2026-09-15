<?php snippet('header') ?>

<article class="album">
  <header class="album-head">
    <h1><?= $page->title()->esc() ?></h1>
    <?php if ($page->text()->isNotEmpty()): ?>
    <div class="album-text"><?= $page->text() ?></div>
    <?php endif ?>
  </header>

  <ul class="album-gallery">
    <?php foreach ($gallery as $image): ?>
    <li>
      <a href="<?= $image->url() ?>" data-lightbox>
        <figure class="print">
          <img src="<?= $image->resize(1200)->url() ?>" alt="<?= $image->alt()->esc() ?>" loading="lazy">
        </figure>
      </a>
      <?php if ($image->alt()->isNotEmpty()): ?>
      <figcaption><?= $image->alt()->esc() ?></figcaption>
      <?php endif ?>
    </li>
    <?php endforeach ?>
  </ul>
</article>

<?php snippet('footer') ?>
