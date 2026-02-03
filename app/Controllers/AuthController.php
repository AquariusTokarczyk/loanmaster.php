<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Validator;
use App\Models\User;

final class AuthController extends Controller
{
  // Visar login-form. / Pokazuje formularz logowania.
  public function visaLogin(): void
  {
    $this->render('auth/login', [
      'fel' => [],
      'gammal' => [],
    ]);
  }

  // Hanterar login (POST). / Obsługuje logowanie (POST).
  public function loggaIn(): void
  {
    Auth::startaSession();

    $epost = trim((string) ($_POST['epost'] ?? ''));
    $losenord = (string) ($_POST['losenord'] ?? '');

    $v = new Validator();
    $v->krav('epost', $epost, 'E-post krävs.')
      ->epost('epost', $epost, 'Ogiltig e-postadress.')
      ->krav('losenord', $losenord, 'Lösenord krävs.');

    if (!$v->ok()) {
      $this->render('auth/login', [
        'fel' => $v->fel(),
        'gammal' => ['epost' => $epost],
      ]);
      return;
    }

    $u = User::hittaViaEpost($epost);

    if ($u === null || !$u->verifieraLosenord($losenord)) {
      $this->render('auth/login', [
        'fel' => ['global' => ['Fel e-post eller lösenord.']],
        'gammal' => ['epost' => $epost],
      ]);
      return;
    }

    Auth::loggaIn($u->id);
    $this->redirect('/');
  }

  // Visar register-form. / Pokazuje formularz rejestracji.
  public function visaRegister(): void
  {
    $this->render('auth/register', [
      'fel' => [],
      'gammal' => [],
    ]);
  }

  // Hanterar registrering (POST). / Obsługuje rejestrację (POST).
  public function registrera(): void
  {
    Auth::startaSession();

    $anvandarnamn = trim((string) ($_POST['anvandarnamn'] ?? ''));
    $epost = trim((string) ($_POST['epost'] ?? ''));
    $losenord = (string) ($_POST['losenord'] ?? '');

    $v = new Validator();
    $v->krav('anvandarnamn', $anvandarnamn, 'Användarnamn krävs.')
      ->minLangd(
        'anvandarnamn',
        $anvandarnamn,
        3,
        'Minst 3 tecken i användarnamn.'
      )
      ->krav('epost', $epost, 'E-post krävs.')
      ->epost('epost', $epost, 'Ogiltig e-postadress.')
      ->krav('losenord', $losenord, 'Lösenord krävs.')
      ->minLangd(
        'losenord',
        $losenord,
        6,
        'Minst 6 tecken i lösenord.'
      );

    if (!$v->ok()) {
      $this->render('auth/register', [
        'fel' => $v->fel(),
        'gammal' => [
          'anvandarnamn' => $anvandarnamn,
          'epost' => $epost,
        ],
      ]);
      return;
    }

    if (User::hittaViaEpost($epost) !== null) {
      $this->render('auth/register', [
        'fel' => ['epost' => ['E-post används redan.']],
        'gammal' => [
          'anvandarnamn' => $anvandarnamn,
          'epost' => $epost,
        ],
      ]);
      return;
    }

    $u = User::skapa($anvandarnamn, $epost, $losenord, 'user');

    if ($u === null) {
      $this->render('auth/register', [
        'fel' => ['global' => ['Kunde inte skapa konto.']],
        'gammal' => [
          'anvandarnamn' => $anvandarnamn,
          'epost' => $epost,
        ],
      ]);
      return;
    }

    Auth::loggaIn($u->id);
    $this->redirect('/');
  }

  // Loggar ut. / Wylogowuje.
  public function loggaUt(): void
  {
    Auth::loggaUt();
    $this->redirect('/login');
  }
}
