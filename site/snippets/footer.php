  </main>

  <footer class="footer">
    <p><?= $site->title()->esc() ?> · Fotografía</p>
    <nav>
      <?php foreach ($site->children()->listed() as $item): ?>
      <a href="<?= $item->url() ?>"><?= $item->title()->esc() ?></a>
      <?php endforeach ?>
    </nav>
  </footer>

</div><!-- /.sheet -->

  <?= js([
    'assets/js/lightbox.js',
    'assets/js/index.js',
  ]) ?>

</body>
</html>
