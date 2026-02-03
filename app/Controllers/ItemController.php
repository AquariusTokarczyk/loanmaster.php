<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Item;

final class ItemController extends Controller
{
  // Visar lista med items. / Pokazuje listę przedmiotów.
  public function index(): void
  {
    $items = Item::alla();

    $this->render('items/index', [
      'items' => $items,
    ]);
  }

  // Visar ett item via GET ?id=. / Pokazuje jeden przedmiot przez GET ?id=.
  public function show(): void
  {
    $id = (int) ($_GET['id'] ?? 0);

    if ($id <= 0) {
      http_response_code(404);
      echo '404 - Item hittades inte.';
      return;
    }

    $item = Item::hitta($id);

    if ($item === null) {
      http_response_code(404);
      echo '404 - Item hittades inte.';
      return;
    }

    $this->render('items/show', [
      'item' => $item,
    ]);
  }
}
