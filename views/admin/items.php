<?php

use App\Services\FormBuilder;

$items = $items ?? [];
$fel = $fel ?? [];
$gammal = $gammal ?? [];

?>

<div class="kort">
  <div class="h1">Admin - Items</div>

  <div class="kort">
    <div class="h1">Skapa nytt item</div>

    <form method="post" action="/admin/items/skapa">

      <?php
        echo FormBuilder::input(
          'text',
          'namn',
          'Namn',
          $gammal['namn'] ?? '',
          $fel
        );

        echo FormBuilder::textarea(
          'beskrivning',
          'Beskrivning',
          $gammal['beskrivning'] ?? '',
          $fel
        );

        echo FormBuilder::knapp('Skapa');
      ?>

    </form>
  </div>

  <?php if (empty($items)): ?>
    <p>
      Inga items i databasen.
      <br>
      Brak przedmiotów.
    </p>
  <?php else: ?>

    <table class="tabell">
      <thead>
        <tr>
          <th>Namn</th>
          <th>Status</th>
          <th>Redigera</th>
          <th>Ta bort</th>
        </tr>
      </thead>
      <tbody>

      <?php foreach ($items as $item): ?>
        <tr>
          <td>
            <?php echo htmlspecialchars($item->namn); ?>
          </td>
          <td>
            <?php echo $item->tillganglig ? 'Tillgänglig' : 'Utlånad'; ?>
            <br>
            <?php echo $item->tillganglig ? 'Dostępny' : 'Wypożyczony'; ?>
          </td>
          <td>
            <form method="post" action="/admin/items/uppdatera">
              <?php echo FormBuilder::hidden('id', (string) $item->id); ?>

              <?php echo FormBuilder::input(
                'text',
                'namn',
                'Namn',
                $item->namn,
                []
              ); ?>

              <?php echo FormBuilder::textarea(
                'beskrivning',
                'Beskrivning',
                $item->beskrivning,
                []
              ); ?>

              <div class="falt">
                <label>
                  <input type="checkbox"
                         name="tillganglig"
                         <?php echo $item->tillganglig ? 'checked' : ''; ?>>
                  Tillgänglig
                  <br>
                  Dostępny
                </label>
              </div>

              <button class="knapp" type="submit">
                Spara
                <br>
                Zapisz
              </button>
            </form>
          </td>
          <td>
            <form method="post" action="/admin/items/tabort">
              <?php echo FormBuilder::hidden('id', (string) $item->id); ?>
              <button class="knapp"
                      type="submit"
                      data-bekrafta-tabort="1">
                Ta bort
                <br>
                Usuń
              </button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>

      </tbody>
    </table>

  <?php endif; ?>
</div>
