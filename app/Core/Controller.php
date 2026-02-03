<?php

namespace App\Core;

class Controller
{
  // Renderar en vy med data.
  // Renderuje widok z danymi.
  protected function render(string $vy, array $data = []): void
  {
    $view = new View();
    $view->render($vy, $data);
  }

  // Bygger bas-URL (när projektet ligger i subfolder).
  // Buduje base-URL (gdy projekt jest w podfolderze).
  protected function baseUrl(): string
  {
    // SCRIPT_NAME: /loanmaster/public/index.php
    // SCRIPT_NAME: /loanmaster/public/index.php
    $bas = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));

    // Tar bort slash i slutet.
    // Usuwa slash na końcu.
    $bas = rtrim($bas, '/');

    return $bas === '' ? '' : $bas;
  }

  // Redirect till URL (alltid inom appens bas).
  // Przekierowanie na URL (zawsze w bazie aplikacji).
  protected function redirect(string $url): void
  {
    // Om någon skickar full URL, rör inte.
    // Jeśli ktoś poda pełny URL, nie ruszaj.
    if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
      header('Location: ' . $url);
      exit;
    }

    // Säkerställ att det börjar med slash.
    // Upewnij się, że zaczyna się od slash.
    if ($url === '' || $url[0] !== '/') {
      $url = '/' . $url;
    }

    // Bygg korrekt URL med bas.
    // Zbuduj poprawny URL z bazą.
    $plats = $this->baseUrl() . $url;

    header('Location: ' . $plats);
    exit;
  }

  // Escapar text för att undvika XSS.
  // Escapuje tekst, żeby uniknąć XSS.
  protected function e(string $text): string
  {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
  }
}
