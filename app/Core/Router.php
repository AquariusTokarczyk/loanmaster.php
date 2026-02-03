<?php

namespace App\Core;

final class Router
{
  // Registrerade routes per HTTP-metod.
  // Zarejestrowane trasy per metoda HTTP.
  private array $rutter = [
    'GET' => [],
    'POST' => [],
  ];

  // Registrerar GET-route.
  // Rejestruje trasę GET.
  public function get(string $sokvag, callable $hanterare): void
  {
    $this->rutter['GET'][$this->normalisera($sokvag)] = $hanterare;
  }

  // Registrerar POST-route.
  // Rejestruje trasę POST.
  public function post(string $sokvag, callable $hanterare): void
  {
    $this->rutter['POST'][$this->normalisera($sokvag)] = $hanterare;
  }

  // Kör rätt route.
  // Uruchamia właściwą trasę.
  public function dispatch(): void
  {
    $metod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $uri = $_SERVER['REQUEST_URI'] ?? '/';

    // Tar bort query string.
    // Usuwa query string.
    $path = parse_url($uri, PHP_URL_PATH) ?? '/';

    // Tar bort bas-sökväg när projektet ligger i subfolder (t.ex. /loanmaster/public).
    // Usuwa bazową ścieżkę, gdy projekt jest w podfolderze (np. /loanmaster/public).
    $bas = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));

    // Normaliserar basen (ingen slash på slutet).
    // Normalizuje bazę (bez slasha na końcu).
    $bas = rtrim($bas, '/');

    // Om URL börjar med basen, ta bort den.
    // Jeśli URL zaczyna się od bazy, utnij bazę.
    if ($bas !== '' && $bas !== '/' && str_starts_with($path, $bas)) {
      $path = substr($path, strlen($bas));

      // Om allt togs bort -> vi är på root.
      // Jeśli wszystko ucięte -> jesteśmy na root.
      if ($path === '') {
        $path = '/';
      }
    }

    // Normaliserar path.
    // Normalizuje path.
    $path = $this->normalisera($path);

    // Hämtar routes för metoden.
    // Pobiera trasy dla metody.
    $metodRutter = $this->rutter[$metod] ?? [];

    // Om route finns, kör handler.
    // Jeśli trasa istnieje, uruchom handler.
    if (isset($metodRutter[$path])) {
      call_user_func($metodRutter[$path]);
      return;
    }

    // 404 om inget matchar.
    // 404 jeśli nic nie pasuje.
    http_response_code(404);
    echo '404 - Sidan hittades inte.';
  }

  // Normaliserar sökväg.
  // Normalizuje ścieżkę.
  private function normalisera(string $sokvag): string
  {
    $sokvag = trim($sokvag);

    if ($sokvag === '') {
      return '/';
    }

    if ($sokvag[0] !== '/') {
      $sokvag = '/' . $sokvag;
    }

    if ($sokvag !== '/' && str_ends_with($sokvag, '/')) {
      $sokvag = rtrim($sokvag, '/');
    }

    return $sokvag;
  }
}

