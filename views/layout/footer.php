<?php

// Bas-sökväg för appen (t.ex. /loanmaster/public).
// Bazowa ścieżka aplikacji (np. /loanmaster/public).
$bas = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$bas = rtrim($bas, '/');
if ($bas === '' || $bas === '.') {
  $bas = '';
}

?>
  </div>

  <script src="<?php echo $bas; ?>/assets/js/app.js"></script>
</body>
</html>
