<?php

namespace App\Core;

use PDO;
use PDOException;

final class Database
{
  // Den enda instansen (Singleton). / Jedyna instancja (Singleton).
  private static ?Database $instans = null;

  // PDO-anslutningen. / Połączenie PDO.
  private PDO $anslutning;

  // Privat konstruktor: ingen new utanför klassen. / Prywatny konstruktor: brak new poza klasą.
  private function __construct(array $konfig)
  {
    // Bygger DSN för MySQL. / Buduje DSN dla MySQL.
    $dsn = 'mysql:host=' . $konfig['db_host']
      . ';dbname=' . $konfig['db_name']
      . ';charset=' . $konfig['db_charset'];

    try {
      // Skapar PDO-objektet. / Tworzy obiekt PDO.
      $this->anslutning = new PDO(
        $dsn,
        $konfig['db_user'],
        $konfig['db_pass'],
        [
          // Exceptions vid fel. / Wyjątki przy błędach.
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

          // Standard: assoc-array. / Domyślnie: tablica asocjacyjna.
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

          // Riktiga prepares (säkerhet). / Prawdziwe prepares (bezpieczeństwo).
          PDO::ATTR_EMULATE_PREPARES => false,
        ]
      );
    } catch (PDOException $e) {
      // Stoppar om DB inte fungerar. / Zatrzymuje, jeśli DB nie działa.
      die('Databasfel: ' . $e->getMessage());
    }
  }

  // Hämtar instansen (skapar om saknas). / Pobiera instancję (tworzy, jeśli brak).
  public static function hamtaInstans(array $konfig): Database
  {
    if (self::$instans === null) {
      self::$instans = new Database($konfig);
    }
    return self::$instans;
  }

  // Returnerar PDO. / Zwraca PDO.
  public function pdo(): PDO
  {
    return $this->anslutning;
  }
}
