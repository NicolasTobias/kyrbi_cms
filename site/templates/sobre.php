<?php snippet('header') ?>

<h1 class="pagina-titulo"><?= $page->title()->esc() ?></h1>

<?php if ($page->bio()->isNotEmpty()): ?>
<div class="texto"><?= $page->bio()->kt() ?></div>
<?php endif ?>

<?php if ($retrato = $page->retrato()->toFile()): ?>
<div class="retrato">
  <?php snippet('figura', [
    'foto'  => $retrato,
    'sizes' => '18rem',
    'pie'   => false,
  ]) ?>
</div>
<?php endif ?>

<section class="seccion">
  <h2 class="seccion-titulo">Contacto</h2>
  <?php if ($page->email()->isNotEmpty()): ?>
  <span class="dato"><?= Html::email($page->email()->value()) ?></span>
  <?php endif ?>
  <?php if ($page->instagram()->isNotEmpty()): ?>
  <span class="dato"><a href="https://instagram.com/<?= $page->instagram()->esc('attr') ?>" rel="me">Instagram</a></span>
  <?php endif ?>
</section>

<?php snippet('footer') ?>
