<?php

namespace Neondigital\Polyglot\Contracts;

interface Provider
{
    public function getHost();
    public function getTranslateEndpoint();
    public function getLanguageDetectEndpoint();
}