<?php snippet('header') ?>

<article>
  <header>
    <time class="nota-fecha" datetime="<?= $page->date()->toDate('c') ?>"><?= $page->publicada() ?></time>
    <h1 class="pagina-titulo"><?= $page->title()->esc() ?></h1>
  </header>
  <div class="nota-cuerpo"><?= $page->text()->toBlocks() ?></div>
</article>

<?php snippet('footer') ?>
