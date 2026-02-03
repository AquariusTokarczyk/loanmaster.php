<?php

namespace App\Models;

final class Loan extends BaseModel
{
  // Publika properties. / Publiczne właściwości.
  public int $id;
  public int $anvandare_id;
  public int $item_id;
  public string $lan_datum;
  public ?string $aterlamning_datum;

  // Hämtar lån för en användare. / Pobiera wypożyczenia użytkownika.
  public static function forAnvandare(int $anvandareId): array
  {
    $sql = 'SELECT id, user_id, item_id, loan_date, return_date
            FROM loans
            WHERE user_id = :user_id
            ORDER BY id DESC';

    $stmt = self::pdo()->prepare($sql);
    $stmt->execute(['user_id' => $anvandareId]);

    $lista = [];

    while ($rad = $stmt->fetch()) {
      $lista[] = self::franRad($rad);
    }

    return $lista;
  }

  // Skapar lån och markerar item som utlånat. / Tworzy wypożyczenie i oznacza item jako wypożyczony.
  public static function skapa(int $anvandareId, int $itemId): bool
  {
    $pdo = self::pdo();
    $pdo->beginTransaction();

    try {
      $check = $pdo->prepare(
        'SELECT available
         FROM items
         WHERE id = :id
         LIMIT 1'
      );
      $check->execute(['id' => $itemId]);
      $rad = $check->fetch();

      if (!$rad || ((int) $rad['available']) !== 1) {
        $pdo->rollBack();
        return false;
      }

      $stmt = $pdo->prepare(
        'INSERT INTO loans (user_id, item_id, loan_date, return_date)
         VALUES (:user_id, :item_id, CURDATE(), NULL)'
      );
      $ok = $stmt->execute([
        'user_id' => $anvandareId,
        'item_id' => $itemId,
      ]);

      if (!$ok) {
        $pdo->rollBack();
        return false;
      }

      $upd = $pdo->prepare(
        'UPDATE items
         SET available = 0
         WHERE id = :id'
      );
      $ok2 = $upd->execute(['id' => $itemId]);

      if (!$ok2) {
        $pdo->rollBack();
        return false;
      }

      $pdo->commit();
      return true;

    } catch (\Throwable $e) {
      $pdo->rollBack();
      return false;
    }
  }

  // Returnerar lån och markerar item som tillgängligt. / Zwraca wypożyczenie i oznacza item jako dostępny.
  public static function aterlamna(int $lanId, int $anvandareId): bool
  {
    $pdo = self::pdo();
    $pdo->beginTransaction();

    try {
      $stmt = $pdo->prepare(
        'SELECT item_id, return_date
         FROM loans
         WHERE id = :id
           AND user_id = :user_id
         LIMIT 1'
      );
      $stmt->execute([
        'id' => $lanId,
        'user_id' => $anvandareId,
      ]);

      $rad = $stmt->fetch();
      if (!$rad) {
        $pdo->rollBack();
        return false;
      }

      if (!empty($rad['return_date'])) {
        $pdo->rollBack();
        return false;
      }

      $itemId = (int) $rad['item_id'];

      $updLoan = $pdo->prepare(
        'UPDATE loans
         SET return_date = CURDATE()
         WHERE id = :id'
      );
      $ok = $updLoan->execute(['id' => $lanId]);

      if (!$ok) {
        $pdo->rollBack();
        return false;
      }

      $updItem = $pdo->prepare(
        'UPDATE items
         SET available = 1
         WHERE id = :id'
      );
      $ok2 = $updItem->execute(['id' => $itemId]);

      if (!$ok2) {
        $pdo->rollBack();
        return false;
      }

      $pdo->commit();
      return true;

    } catch (\Throwable $e) {
      $pdo->rollBack();
      return false;
    }
  }

  // Skapar Loan från DB-rad. / Tworzy Loan z wiersza DB.
  private static function franRad(array $rad): Loan
  {
    $l = new Loan();

    $l->id = (int) $rad['id'];
    $l->anvandare_id = (int) $rad['user_id'];
    $l->item_id = (int) $rad['item_id'];
    $l->lan_datum = (string) $rad['loan_date'];

    $l->aterlamning_datum = $rad['return_date'] !== null
      ? (string) $rad['return_date']
      : null;

    return $l;
  }
}
