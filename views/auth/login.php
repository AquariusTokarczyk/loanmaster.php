<?php

use App\Services\FormBuilder;

// Fel och gamla värden. / Błędy i stare wartości.
$fel = $fel ?? [];
$gammal = $gammal ?? [];

?>

<div class="kort">
  <div class="h1">Logga in</div>

  <?php if (isset($fel['global'])): ?>
    <div class="fel">
      <?php echo htmlspecialchars($fel['global'][0]); ?>
    </div>
  <?php endif; ?>

  <form method="post" action="login">


    <?php
      echo FormBuilder::input(
        'email',
        'epost',
        'E-post',
        $gammal['epost'] ?? '',
        $fel
      );

      echo FormBuilder::input(
        'password',
        'losenord',
        'Lösenord',
        '',
        $fel
      );

      echo FormBuilder::knapp('Logga in');
    ?>

  </form>
</div>
