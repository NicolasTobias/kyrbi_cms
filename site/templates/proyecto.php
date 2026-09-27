<?php snippet('header') ?>

<article>
  <header class="proyecto-cabeza">
    <h1 class="proyecto-titulo"><?= $page->title()->esc() ?></h1>
    <?php if ($page->intro()->isNotEmpty()): ?>
    <div class="proyecto-intro"><?= $page->intro()->kt() ?></div>
    <?php endif ?>
  </header>

  <div class="serie">
    <?php foreach ($page->fotos() as $foto): ?>
    <?php snippet('figura', ['foto' => $foto]) ?>
    <?php endforeach ?>
  </div>
</article>

<?php $anterior = $page->prevListed(); $siguiente = $page->nextListed() ?>
<?php if ($anterior || $siguiente): ?>
<nav class="prevnext" aria-label="Otros proyectos">
  <?php if ($anterior): ?>
  <a href="<?= $anterior->url() ?>"><?= $anterior->title()->esc() ?></a>
  <?php else: ?>
  <span></span>
  <?php endif ?>
  <?php if ($siguiente): ?>
  <a href="<?= $siguiente->url() ?>"><?= $siguiente->title()->esc() ?></a>
  <?php endif ?>
</nav>
<?php endif ?>

<?php snippet('footer') ?>
