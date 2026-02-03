<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Validator;
use App\Models\Item;

final class AdminController extends Controller
{
  // Visar admin-panelen för items. / Pokazuje panel admina dla przedmiotów.
  public function items(): void
  {
    Auth::kravAdmin();

    $items = Item::alla();

    $this->render('admin/items', [
      'items' => $items,
      'fel' => [],
      'gammal' => [],
    ]);
  }

  // Skapar nytt item. / Tworzy nowy przedmiot.
  public function skapaItem(): void
  {
    Auth::kravAdmin();

    $namn = trim((string) ($_POST['namn'] ?? ''));
    $beskrivning = trim((string) ($_POST['beskrivning'] ?? ''));

    $v = new Validator();
    $v->krav('namn', $namn, 'Namn krävs.')
      ->minLangd('namn', $namn, 2, 'Minst 2 tecken.');

    if (!$v->ok()) {
      $items = Item::alla();
      $this->render('admin/items', [
        'items' => $items,
        'fel' => $v->fel(),
        'gammal' => [
          'namn' => $namn,
          'beskrivning' => $beskrivning,
        ],
      ]);
      return;
    }

    Item::skapa($namn, $beskrivning);
    $this->redirect('/admin/items');
  }

  // Uppdaterar item. / Aktualizuje przedmiot.
  public function uppdateraItem(): void
  {
    Auth::kravAdmin();

    $id = (int) ($_POST['id'] ?? 0);
    $namn = trim((string) ($_POST['namn'] ?? ''));
    $beskrivning = trim((string) ($_POST['beskrivning'] ?? ''));
    $tillganglig = isset($_POST['tillganglig']);

    if ($id > 0) {
      Item::uppdatera($id, $namn, $beskrivning, $tillganglig);
    }

    $this->redirect('/admin/items');
  }

  // Tar bort item. / Usuwa przedmiot.
  public function taBortItem(): void
  {
    Auth::kravAdmin();

    $id = (int) ($_POST['id'] ?? 0);

    if ($id > 0) {
      Item::taBort($id);
    }

    $this->redirect('/admin/items');
  }
}
