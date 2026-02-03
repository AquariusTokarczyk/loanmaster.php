<?php

namespace App\Core;

final class View
{
  // Sökväg till views. / Ścieżka do views.
  private string $viewsSokvag;

  public function __construct()
  {
    $this->viewsSokvag = dirname(__DIR__, 2) . '/views';
  }

  // Renderar vy + layout. / Renderuje widok + layout.
  public function render(string $vy, array $data = []): void
  {
    extract($data, EXTR_SKIP);

    $fil = $this->viewsSokvag . '/' . $vy . '.php';

    if (!is_file($fil)) {
      http_response_code(500);
      echo 'View saknas: ' . htmlspecialchars($vy);
      return;
    }

    $this->inkludera('layout/header', $data);
    require $fil;
    $this->inkludera('layout/footer', $data);
  }

  // Inkluderar en vy om den finns. / Dołącza widok, jeśli istnieje.
  private function inkludera(string $vy, array $data = []): void
  {
    extract($data, EXTR_SKIP);

    $fil = $this->viewsSokvag . '/' . $vy . '.php';

    if (is_file($fil)) {
      require $fil;
    }
  }
}
