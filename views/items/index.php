<?php

$items = $items ?? [];

?>

<div class="kort">
  <div class="h1">Items</div>

  <?php if (empty($items)): ?>
    <p>
      Inga items hittades.
      <br>
      Brak przedmiotów.
    </p>
  <?php else: ?>

    <table class="tabell">
      <thead>
        <tr>
          <th>Namn</th>
          <th>Status</th>
          <th>Visa</th>
        </tr>
      </thead>
      <tbody>

      <?php foreach ($items as $item): ?>
        <tr>
          <td>
            <?php echo htmlspecialchars($item->namn); ?>
          </td>
          <td>
            <?php if ($item->tillganglig): ?>
              Tillgänglig
              <br>
              Dostępny
            <?php else: ?>
              Utlånad
              <br>
              Wypożyczony
            <?php endif; ?>
          </td>
          <td>
            <a class="knapp"
               href="/items/show?id=<?php echo (int) $item->id; ?>">
              Visa
            </a>
          </td>
        </tr>
      <?php endforeach; ?>

      </tbody>
    </table>

  <?php endif; ?>
</div>
