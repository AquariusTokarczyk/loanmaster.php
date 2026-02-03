<?php

namespace App\Services;

final class FormBuilder
{
  // Escapar text. / Escapuje tekst.
  private static function e(string $text): string
  {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
  }

  // Skapar inputfält. / Tworzy pole input.
  public static function input(
    string $typ,
    string $namn,
    string $etikett,
    string $varde = '',
    array $fel = []
  ): string {
    $felHtml = '';

    if (isset($fel[$namn])) {
      $felHtml = '<div class="fel">'
        . self::e((string) $fel[$namn][0])
        . '</div>';
    }

    return
      '<div class="falt">' . "\n" .
      '<label>' . self::e($etikett) . '</label>' . "\n" .
      '<input type="' . self::e($typ) . '"' . "\n" .
      'name="' . self::e($namn) . '"' . "\n" .
      'value="' . self::e($varde) . '">' . "\n" .
      $felHtml . "\n" .
      '</div>';
  }

  // Skapar textarea. / Tworzy textarea.
  public static function textarea(
    string $namn,
    string $etikett,
    string $varde = '',
    array $fel = []
  ): string {
    $felHtml = '';

    if (isset($fel[$namn])) {
      $felHtml = '<div class="fel">'
        . self::e((string) $fel[$namn][0])
        . '</div>';
    }

    return
      '<div class="falt">' . "\n" .
      '<label>' . self::e($etikett) . '</label>' . "\n" .
      '<textarea name="' . self::e($namn) . '">' .
      self::e($varde) .
      '</textarea>' . "\n" .
      $felHtml . "\n" .
      '</div>';
  }

  // Skapar knapp. / Tworzy przycisk.
  public static function knapp(string $text): string
  {
    return '<button class="knapp" type="submit">'
      . self::e($text)
      . '</button>';
  }

  // Skapar hidden input. / Tworzy ukryte pole.
  public static function hidden(string $namn, string $varde): string
  {
    return '<input type="hidden" name="' .
      self::e($namn) .
      '" value="' .
      self::e($varde) .
      '">';
  }
}
