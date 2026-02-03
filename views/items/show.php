<?php

use App\Core\Auth;

$item = $item ?? null;

if ($item === null) {
  echo '<div class="kort">Item saknas.</div>';
  return;
}

?>

<div class="kort">
  <div class="h1">
    <?php echo htmlspecialchars($item->namn); ?>
  </div>

  <p>
    <?php echo nl2br(htmlspecialchars($item->beskrivning)); ?>
  </p>

  <p>
    <?php if ($item->tillganglig): ?>
      Tillgänglig
      <br>
      Dostępny
    <?php else: ?>
      Utlånad
      <br>
      Wypożyczony
    <?php endif; ?>
  </p>

  <?php if (Auth::inloggad() && $item->tillganglig): ?>
    <form method="post" action="lan/skapa">

      <input type="hidden"
             name="item_id"
             value="<?php echo (int) $item->id; ?>">
      <button class="knapp" type="submit">
        Låna
        <br>
        Wypożycz
      </button>
    </form>
  <?php endif; ?>

  <p>
    <a class="knapp" href="/items">
      Tillbaka
      <br>
      Powrót
    </a>
  </p>
</div>
