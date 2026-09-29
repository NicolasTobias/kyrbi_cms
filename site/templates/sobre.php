<?php snippet('header') ?>

<?php $retrato = $page->retrato()->toFile() ?>

<div class="sobre<?= $retrato ? ' con-retrato' : '' ?>">
  <?php if ($retrato): ?>
  <div class="sobre-retrato">
    <?php snippet('figura', [
      'foto'      => $retrato,
      'sizes'     => '(min-width: 801px) 50vw, 100vw',
      'prioridad' => true,
      'pie'       => false,
    ]) ?>
  </div>
  <?php endif ?>

  <div class="sobre-texto">
    <div>
      <h1 class="pagina-titulo"><?= $page->title()->esc() ?></h1>
      <?php if ($page->bio()->isNotEmpty()): ?>
      <p class="sobre-lead"><?= $page->bio()->esc() ?></p>
      <?php endif ?>
      <?php if ($page->texto()->isNotEmpty()): ?>
      <div class="sobre-largo"><?= $page->texto()->kt() ?></div>
      <?php endif ?>
    </div>

    <?php if ($page->email()->isNotEmpty() || $page->instagram()->isNotEmpty()): ?>
    <p class="contacto">
      <?php if ($page->email()->isNotEmpty()): ?>
      <span class="dato"><?= t('ui.contacto') ?> <b><?= Html::email($page->email()->value()) ?></b></span>
      <?php endif ?>
      <?php if ($page->instagram()->isNotEmpty()): ?>
      <span class="dato"><a href="https://instagram.com/<?= $page->instagram()->esc('attr') ?>" rel="me">Instagram</a></span>
      <?php endif ?>
    </p>
    <?php endif ?>
  </div>
</div>

<?php snippet('footer') ?>
