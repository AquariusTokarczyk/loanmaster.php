<?php

namespace App\Core;

use App\Models\User;

final class Auth
{
  // Sessionsnyckel. / Klucz w sesji.
  private const SESSION_USER = 'inloggad_anvandare_id';

  // Startar session. / Uruchamia sesję.
  public static function startaSession(): void
  {
    if (session_status() !== PHP_SESSION_ACTIVE) {
      session_start();
    }
  }

  // Loggar in. / Loguje.
  public static function loggaIn(int $anvandareId): void
  {
    self::startaSession();
    session_regenerate_id(true);
    $_SESSION[self::SESSION_USER] = $anvandareId;
  }

  // Loggar ut. / Wylogowuje.
  public static function loggaUt(): void
  {
    self::startaSession();
    unset($_SESSION[self::SESSION_USER]);
    session_regenerate_id(true);
  }

  // Returnerar user_id eller null. / Zwraca user_id albo null.
  public static function anvandareId(): ?int
  {
    self::startaSession();

    return isset($_SESSION[self::SESSION_USER])
      ? (int) $_SESSION[self::SESSION_USER]
      : null;
  }

  // Är användaren inloggad? / Czy użytkownik jest zalogowany?
  public static function inloggad(): bool
  {
    return self::anvandareId() !== null;
  }

  // Hämtar User-objektet. / Pobiera obiekt User.
  public static function anvandare(): ?User
  {
    $id = self::anvandareId();

    if ($id === null) {
      return null;
    }

    return User::hitta($id);
  }

  // Kräver inloggning. / Wymaga logowania.
  public static function kravInloggning(string $redirectUrl): void
  {
    if (!self::inloggad()) {
      header('Location: ' . $redirectUrl);
      exit;
    }
  }

  // Kräver admin. / Wymaga admina.
  public static function kravAdmin(): void
  {
    self::kravInloggning('/login');

    $u = self::anvandare();

    if ($u === null || $u->roll !== 'admin') {
      http_response_code(403);
      echo '403 - Åtkomst nekad.';
      exit;
    }
  }
}
