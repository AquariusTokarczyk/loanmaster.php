<?php

namespace App\Core;

final class Validator
{
  // Lagrar fel per fält. / Przechowuje błędy per pole.
  private array $fel = [];

  // Returnerar alla fel./ Zwraca wszystkie błędy.
  public function fel(): array
  {
    return $this->fel;
  }

  // Är valideringen OK? / Czy walidacja OK?
  public function ok(): bool
  {
    return empty($this->fel);
  }

  // Kräver att värdet inte är tomt. / Wymaga, żeby wartość nie była pusta.
  public function krav(
    string $falt,
    mixed $varde,
    string $meddelande
  ): self {
    $test = is_string($varde) ? trim($varde) : $varde;

    if ($test === '' || $test === null) {
      $this->fel[$falt][] = $meddelande;
    }

    return $this;
  }

  // Minsta längd för sträng. / Minimalna długość stringa.
  public function minLangd(
    string $falt,
    string $varde,
    int $min,
    string $meddelande
  ): self {
    if (mb_strlen($varde) < $min) {
      $this->fel[$falt][] = $meddelande;
    }

    return $this;
  }

  // Validerar e-post. / Waliduje e-mail.
  public function epost(
    string $falt,
    string $varde,
    string $meddelande
  ): self {
    if (!filter_var($varde, FILTER_VALIDATE_EMAIL)) {
      $this->fel[$falt][] = $meddelande;
    }

    return $this;
  }
}
