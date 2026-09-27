<?php snippet('header') ?>

<article>
  <h1 class="pagina-titulo"><?= $page->title()->esc() ?></h1>
  <div class="texto"><?= $page->text()->kt() ?></div>
</article>

<?php snippet('footer') ?>
