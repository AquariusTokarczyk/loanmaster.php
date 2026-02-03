<?php

namespace App\Models;

final class Item extends BaseModel
{
  // Publika properties. / Publiczne właściwości.
  public int $id;
  public string $namn;
  public string $beskrivning;
  public bool $tillganglig;

  // Hämtar alla items. / Pobiera wszystkie przedmioty.
  public static function alla(): array
  {
    $sql = 'SELECT id, name, description, available
            FROM items
            ORDER BY id DESC';

    $stmt = self::pdo()->query($sql);

    $lista = [];

    while ($rad = $stmt->fetch()) {
      $lista[] = self::franRad($rad);
    }

    return $lista;
  }

  // Hittar item via ID. / Znajduje przedmiot po ID.
  public static function hitta(int $id): ?Item
  {
    $sql = 'SELECT id, name, description, available
            FROM items
            WHERE id = :id
            LIMIT 1';

    $stmt = self::pdo()->prepare($sql);
    $stmt->execute(['id' => $id]);

    $rad = $stmt->fetch();
    if (!$rad) {
      return null;
    }

    return self::franRad($rad);
  }

  // Skapar nytt item. / Tworzy nowy przedmiot.
  public static function skapa(string $namn, string $beskrivning): ?Item
  {
    $sql = 'INSERT INTO items (name, description, available)
            VALUES (:name, :description, 1)';

    $stmt = self::pdo()->prepare($sql);

    $ok = $stmt->execute([
      'name' => $namn,
      'description' => $beskrivning,
    ]);

    if (!$ok) {
      return null;
    }

    $id = (int) self::pdo()->lastInsertId();
    return self::hitta($id);
  }

  // Uppdaterar item. / Aktualizuje przedmiot.
  public static function uppdatera(
    int $id,
    string $namn,
    string $beskrivning,
    bool $tillganglig
  ): bool {
    $sql = 'UPDATE items
            SET name = :name,
                description = :description,
                available = :available
            WHERE id = :id';

    $stmt = self::pdo()->prepare($sql);

    return $stmt->execute([
      'id' => $id,
      'name' => $namn,
      'description' => $beskrivning,
      'available' => $tillganglig ? 1 : 0,
    ]);
  }

  // Tar bort item. / Usuwa przedmiot.
  public static function taBort(int $id): bool
  {
    $sql = 'DELETE FROM items WHERE id = :id';
    $stmt = self::pdo()->prepare($sql);
    return $stmt->execute(['id' => $id]);
  }

  // Skapar Item från DB-rad. / Tworzy Item z wiersza DB.
  private static function franRad(array $rad): Item
  {
    $i = new Item();

    $i->id = (int) $rad['id'];
    $i->namn = (string) $rad['name'];
    $i->beskrivning = (string) ($rad['description'] ?? '');
    $i->tillganglig = ((int) $rad['available']) === 1;

    return $i;
  }
}
