<?php

namespace Neondigital\Polyglot;

interface TranslateInterface
{
    public function translate($text, $to, $from = false);
    public function detect($text);
}