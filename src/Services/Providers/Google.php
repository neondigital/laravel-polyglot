<?php

namespace Neondigital\Polyglot\Services\Providers;

use Neondigital\Polyglot\Contracts\Provider;

class Google implements Provider
{
    protected $apiKey;

    protected $host = 'https://www.googleapis.com/';

    public function __construct()
    {
        $this->apiKey = config('polyglot.google_api_key');
    }
    public function getTokenParameter()
    {
        return 'key';
    }
    public function getHost()
    {
        return $this->host;
    }
    public function getTranslateEndpoint()
    {
        return $this->host . 'language/translate/v2';
    }
    public function getLanguageDetectEndpoint()
    {
        return $this->host . 'language/translate/v2/detect';
    }
    public function getTranslateParams($options)
    {
        return [
            'q' => $options['text'],
            'source' => $options['from'],
            'html' => true,
            'key' => $this->apiKey,
            'target' => $options['to'],
        ];
    }
    public function getDetectParams($text)
    {
        return [
            'q' => $text,
            'key' => $this->apiKey
        ];
    }
}