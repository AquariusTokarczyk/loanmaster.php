<?php

namespace App\Models;

use App\Core\Database;
use PDO;

abstract class BaseModel
{
  // Konfiguration delas till alla modeller. / Konfiguracja współdzielona dla wszystkich modeli.
  protected static array $konfig = [];

  // Sätter konfig för alla modeller. / Ustawia konfig dla wszystkich modeli.
  public static function sattKonfig(array $konfig): void
  {
    self::$konfig = $konfig;
  }

  // Returnerar PDO-anslutningen. / Zwraca połączenie PDO.
  protected static function pdo(): PDO
  {
    return Database::hamtaInstans(self::$konfig)->pdo();
  }

  // Trimmar sträng säkert. / Bezpiecznie trimuje stringa.
  protected static function trimStr(?string $varde): string
  {
    return trim($varde ?? '');
  }
}
