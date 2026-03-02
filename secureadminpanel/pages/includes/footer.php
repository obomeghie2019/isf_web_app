    </main>
    <footer>
        <p>&copy; <?= date('Y') ?> <?= APP_NAME ?> v<?= APP_VERSION ?></p>
        <?php if (APP_DEBUG): ?>
            <div class="debug-info">
                Page generated in <?= round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 4) ?>s
            </div>
        <?php endif; ?>
    </footer>
    <script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>