<?php

namespace App\Services;

class AntiSpam
{

  private $mailer;
  private $locale;
  private $minLength;

  public function __construct(\Swift_Mailer $mailer, $locale, $minLength)
  {
    $this->mailer    = $mailer;
    $this->locale    = $locale;
    $this->minLength = (int) $minLength;
  }

    public function isSpam(String $text)
    {
        return strlen($text) < $this->minLength;
    }
}
