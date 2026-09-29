</main>

<?php if ($site->email()->isNotEmpty()): ?>
<footer class="pie contenedor">
  <p><?= Html::email($site->email()->value()) ?></p>
</footer>
<?php endif ?>

</body>
</html>
