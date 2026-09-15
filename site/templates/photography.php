<?php snippet('header') ?>

<h1 class="page-title"><?= $page->title()->esc() ?></h1>

<ul class="home-grid">
  <?php foreach ($page->children()->listed() as $project): ?>
  <li>
    <a href="<?= $project->url() ?>">
      <figure class="print">
        <?php if ($cover = $project->cover()): ?>
        <img src="<?= $cover->crop(900, 675)->url() ?>" alt="<?= $cover->alt()->esc() ?>">
        <?php endif ?>
      </figure>
      <figcaption>
        <span><?= $project->title()->esc() ?></span>
        <span class="count"><?= $project->images()->count() ?> fotos</span>
      </figcaption>
    </a>
  </li>
  <?php endforeach ?>
</ul>

<?php snippet('footer') ?>
