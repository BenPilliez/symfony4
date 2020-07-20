<?php

namespace App\Services;

class AntiSpam
{
    public function isSpam(String $text)
    {
        return strlen($text) < 50;
    }
}
