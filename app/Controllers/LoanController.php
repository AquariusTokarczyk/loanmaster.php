<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Loan;

final class LoanController extends Controller
{
  // Visar användarens lån. / Pokazuje wypożyczenia użytkownika.
  public function index(): void
  {
    Auth::kravInloggning('/login');

    $anvandareId = (int) Auth::anvandareId();
    $lan = Loan::forAnvandare($anvandareId);

    $this->render('loans/index', [
      'lan' => $lan,
    ]);
  }

  // Skapar lån. / Tworzy wypożyczenie.
  public function skapa(): void
  {
    Auth::kravInloggning('/login');

    $anvandareId = (int) Auth::anvandareId();
    $itemId = (int) ($_POST['item_id'] ?? 0);

    if ($itemId > 0) {
      Loan::skapa($anvandareId, $itemId);
    }

    $this->redirect('/items/show?id=' . $itemId);
  }

  // Återlämnar lån. / Zwraca wypożyczenie.
  public function aterlamna(): void
  {
    Auth::kravInloggning('/login');

    $anvandareId = (int) Auth::anvandareId();
    $lanId = (int) ($_POST['lan_id'] ?? 0);

    if ($lanId > 0) {
      Loan::aterlamna($lanId, $anvandareId);
    }

    $this->redirect('/lan');
  }
}
