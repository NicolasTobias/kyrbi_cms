</main>

<footer class="pie contenedor">
  <p><?= $site->title()->or('Nico Tobias')->esc() ?> — fotografía de calle y documental<?php if ($site->email()->isNotEmpty()): ?>. <?= Html::email($site->email()->value()) ?><?php endif ?></p>
</footer>

</body>
</html>
