<?php

use App\Models\Item;

$lan = $lan ?? [];

?>

<div class="kort">
  <div class="h1">Mina lån</div>

  <?php if (empty($lan)): ?>
    <p>
      Du har inga lån.
      <br>
      Nie masz wypożyczeń.
    </p>
  <?php else: ?>

    <table class="tabell">
      <thead>
        <tr>
          <th>Item</th>
          <th>Lånedatum</th>
          <th>Returdatum</th>
          <th>Åtgärd</th>
        </tr>
      </thead>
      <tbody>

      <?php foreach ($lan as $l): ?>
        <?php
          $item = Item::hitta((int) $l->item_id);
          $itemNamn = $item ? $item->namn : 'Okänt item';
        ?>
        <tr>
          <td>
            <?php echo htmlspecialchars($itemNamn); ?>
          </td>
          <td>
            <?php echo htmlspecialchars($l->lan_datum); ?>
          </td>
          <td>
            <?php echo $l->aterlamning_datum
              ? htmlspecialchars($l->aterlamning_datum)
              : '-'; ?>
          </td>
          <td>
            <?php if ($l->aterlamning_datum === null): ?>
              <form method="post" action="lan/aterlamna">

                <input type="hidden"
                       name="lan_id"
                       value="<?php echo (int) $l->id; ?>">
                <button class="knapp" type="submit">
                  Återlämna
                  <br>
                  Zwróć
                </button>
              </form>
            <?php else: ?>
              -
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>

      </tbody>
    </table>

  <?php endif; ?>
</div>
