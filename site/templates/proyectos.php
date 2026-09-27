<?php snippet('header') ?>

<h1 class="pagina-titulo"><?= $page->title()->esc() ?></h1>

<?php $proyectos = $page->children()->listed()->filterBy('intendedTemplate', 'proyecto')->limit(3) ?>

<?php if ($proyectos->isNotEmpty()): ?>
<ul class="indice">
  <?php foreach ($proyectos as $proyecto): ?>
  <li>
    <a class="indice-enlace" href="<?= $proyecto->url() ?>">
      <?php if ($portada = $proyecto->portada()): ?>
      <?php snippet('figura', [
        'foto'  => $portada,
        'sizes' => '(min-width: 1660px) 750px, (min-width: 600px) calc((100vw - 8rem) / 2), 90vw',
        'pie'   => false,
      ]) ?>
      <?php endif ?>
      <span class="indice-titulo"><?= $proyecto->title()->esc() ?></span>
      <span class="indice-cuenta"><?= $proyecto->fotos()->count() ?> fotos</span>
    </a>
  </li>
  <?php endforeach ?>
</ul>
<?php endif ?>

<?php snippet('footer') ?>
