<?php snippet('header') ?>

<h1 class="pagina-titulo"><?= $page->title()->esc() ?></h1>

<?php $notas = $page->children()->listed()->sortBy('date', 'desc') ?>

<?php if ($notas->isNotEmpty()): ?>
<ul class="notas-lista">
  <?php foreach ($notas as $nota): ?>
  <li>
    <time class="nota-fecha" datetime="<?= $nota->date()->toDate('c') ?>"><?= $nota->publicada() ?></time>
    <h2 class="nota-titulo"><a href="<?= $nota->url() ?>"><?= $nota->title()->esc() ?></a></h2>
  </li>
  <?php endforeach ?>
</ul>
<?php endif ?>

<?php snippet('footer') ?>
