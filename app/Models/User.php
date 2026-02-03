<?php

namespace App\Models;

final class User extends BaseModel
{
  // Publika properties för enkelhet i skolprojekt. / Publiczne właściwości dla prostoty w projekcie szkolnym.
  public int $id;
  public string $anvandarnamn;
  public string $epost;
  public string $losenord_hash;
  public string $roll;

  // Hittar användare via ID. / Znajduje użytkownika po ID.
  public static function hitta(int $id): ?User
  {
    $sql = 'SELECT id, username, email, password, role
            FROM users
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

  // Hittar användare via e-post. / Znajduje użytkownika po e-mailu.
  public static function hittaViaEpost(string $epost): ?User
  {
    $sql = 'SELECT id, username, email, password, role
            FROM users
            WHERE email = :email
            LIMIT 1';

    $stmt = self::pdo()->prepare($sql);
    $stmt->execute(['email' => $epost]);

    $rad = $stmt->fetch();
    if (!$rad) {
      return null;
    }

    return self::franRad($rad);
  }

  // Skapar en ny användare. / Tworzy nowego użytkownika.
  public static function skapa(
    string $anvandarnamn,
    string $epost,
    string $losenord,
    string $roll = 'user'
  ): ?User {
    // Hashar lösenordet. / Hashuje hasło.
    $hash = password_hash($losenord, PASSWORD_DEFAULT);

    $sql = 'INSERT INTO users (username, email, password, role)
            VALUES (:username, :email, :password, :role)';

    $stmt = self::pdo()->prepare($sql);

    $ok = $stmt->execute([
      'username' => $anvandarnamn,
      'email' => $epost,
      'password' => $hash,
      'role' => $roll,
    ]);

    if (!$ok) {
      return null;
    }

    $id = (int) self::pdo()->lastInsertId();
    return self::hitta($id);
  }

  // Verifierar lösenord. / Weryfikuje hasło.
  public function verifieraLosenord(string $losenord): bool
  {
    return password_verify($losenord, $this->losenord_hash);
  }

  // Skapar User från DB-rad. / Tworzy User z wiersza DB.
  private static function franRad(array $rad): User
  {
    $u = new User();

    $u->id = (int) $rad['id'];
    $u->anvandarnamn = (string) $rad['username'];
    $u->epost = (string) $rad['email'];
    $u->losenord_hash = (string) $rad['password'];
    $u->roll = (string) $rad['role'];

    return $u;
  }
}
