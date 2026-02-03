<?php

use App\Core\Auth;

// Bas-sökväg för appen (t.ex. /loanmaster/public).
// Bazowa ścieżka aplikacji (np. /loanmaster/public).
$bas = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$bas = rtrim($bas, '/');
if ($bas === '' || $bas === '.') {
  $bas = '';
}

// Hämtar inloggad användare.
// Pobiera zalogowanego użytkownika.
$u = Auth::anvandare();

?>
<!doctype html>
<html lang="sv">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LoanMaster</title>
  <link rel="stylesheet" href="<?php echo $bas; ?>/assets/css/app.css">
</head>
<body>
  <div class="container">
    <div class="kort">
      <div class="nav">
        <a href="<?php echo $bas; ?>/">Hem</a>
        <a href="<?php echo $bas; ?>/items">Items</a>

        <?php if (Auth::inloggad()): ?>
          <a href="<?php echo $bas; ?>/lan">Mina lån</a>

          <?php if ($u !== null && $u->roll === 'admin'): ?>
            <a href="<?php echo $bas; ?>/admin/items">Admin</a>
          <?php endif; ?>

          <a href="<?php echo $bas; ?>/logout">Logga ut</a>
        <?php else: ?>
          <a href="<?php echo $bas; ?>/login">Logga in</a>
          <a href="<?php echo $bas; ?>/register">Registrera</a>
        <?php endif; ?>
      </div>
    </div>
