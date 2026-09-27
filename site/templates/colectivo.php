<?php snippet('header') ?>

<h1 class="pagina-titulo"><?= $page->title()->esc() ?></h1>

<?php if ($page->intro()->isNotEmpty()): ?>
<div class="texto"><?= $page->intro()->kt() ?></div>
<?php endif ?>

<?php if ($page->funcionamiento()->isNotEmpty()): ?>
<section class="seccion">
  <h2 class="seccion-titulo">Cómo funciona</h2>
  <?= $page->funcionamiento()->kt() ?>
</section>
<?php endif ?>

<section class="seccion">
  <h2 class="seccion-titulo">Próxima quedada</h2>
  <?php if ($quedada): ?>
  <dl class="quedada">
    <dt>Cuándo</dt>
    <dd><time datetime="<?= $quedada['iso'] ?>"><?= $quedada['texto'] ?></time></dd>
    <?php if ($page->lugar()->isNotEmpty()): ?>
    <dt>Dónde</dt>
    <dd><?= $page->lugar()->esc() ?></dd>
    <?php endif ?>
    <?php if ($page->punto_encuentro()->isNotEmpty()): ?>
    <dt>Punto de encuentro</dt>
    <dd><?= $page->punto_encuentro()->esc() ?></dd>
    <?php endif ?>
  </dl>
  <?php else: ?>
  <p>No hay ninguna convocada ahora mismo. Escribe y te aviso de la siguiente.</p>
  <?php endif ?>
</section>

<section class="seccion">
  <h2 class="seccion-titulo">Cómo apuntarte</h2>
  <?php if ($page->apuntarse()->isNotEmpty()): ?>
  <?= $page->apuntarse()->kt() ?>
  <?php endif ?>
  <?php if ($page->email()->isNotEmpty()): ?>
  <p><?= Html::email($page->email()->value()) ?></p>
  <?php endif ?>
</section>

<?php snippet('footer') ?>
