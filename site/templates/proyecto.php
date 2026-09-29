<?php
/*
  Visor: una foto por vista, y cada foto tiene su URL (?foto=N). Funciona sin
  JavaScript, con enlaces; el script solo añade teclado y deslizamiento.
*/

$fotos = $page->fotos();
$total = $fotos->count();
$n     = $total > 0 ? max(1, min($total, (int) get('foto', 1))) : 0;
$foto  = $n > 0 ? $fotos->nth($n - 1) : null;

/* Da la vuelta: tras la última vuelve a la primera. */
$anterior  = $n > 1 ? $n - 1 : $total;
$siguiente = $n < $total ? $n + 1 : 1;
$enlace    = fn (int $i) => $page->url() . ($i > 1 ? '?foto=' . $i : '');

/* Solo el pie de foto. El alt es para lectores de pantalla, no se enseña. */
$leyenda = $foto?->caption();
$precarga = $total > 1 ? $fotos->nth($siguiente - 1) : null;
?>
<?php snippet('header', ['precarga' => $precarga]) ?>

<div class="visor" data-visor>
  <?php if ($foto): ?>
  <?php snippet('figura', [
    'foto'      => $foto,
    'set'       => 'hero',
    'sizes'     => '100vw',
    'prioridad' => true,
    'pie'       => false,
  ]) ?>
  <?php else: ?>
  <p class="visor-vacio"><?= t('ui.sin_fotos') ?></p>
  <?php endif ?>
</div>

<div class="visor-barra">
  <p>
    <b><?= $page->title()->esc() ?></b>
    <?php if ($leyenda && $leyenda->isNotEmpty()): ?><?= Str::ucfirst($leyenda->esc()) ?><?php endif ?>
  </p>
  <?php if ($total > 0): ?>
  <div class="visor-mando">
    <span><?= str_pad((string) $n, 2, '0', STR_PAD_LEFT) ?> / <?= str_pad((string) $total, 2, '0', STR_PAD_LEFT) ?></span>
    <?php if ($total > 1): ?>
    <span class="visor-flechas">
      <a href="<?= $enlace($anterior) ?>" rel="prev" aria-label="<?= t('ui.foto_anterior') ?>" data-anterior>&larr;</a><span></span><a href="<?= $enlace($siguiente) ?>" rel="next" aria-label="<?= t('ui.foto_siguiente') ?>" data-siguiente>&rarr;</a>
    </span>
    <?php endif ?>
  </div>
  <?php endif ?>
</div>

<?php snippet('footer') ?>
