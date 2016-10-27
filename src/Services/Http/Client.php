<?php

namespace Neondigital\Polyglot\Services\Http;

use GuzzleHttp\Client as GuzzleClient;
use Neondigital\Polyglot\Services\Providers\Google;

abstract class Client
{
    /**
     * The client to make the requests to the translator
     * @var Foo
     */
    protected $client;

    /**
     * The provider class
     * @var Object
     */
    protected $provider;

    public function __construct()
    {
        $this->client = new GuzzleClient();
        $this->provider = new Google();
    }

    public function getTranslatedText($config)
    {
        // If not from language is set, make set it to the current locale
        if (!isset($config['from']) || !$config['from']) {
            if (isset($config['detect']) && $config['detect']) {
                $config['from'] = $this->getDetectedLanguage($config['text']);
            }
            $config['from'] = app()->getLocale();
        }

        try {
            $response = $this->client->get($this->provider->getTranslateEndpoint(), [
                'query' => $this->provider->getTranslateParams($config)
            ]);

            $data = json_decode($response->getbody()->getContents(), true);

            return $data['data']['translations'][0]['translatedText'];

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return $config['text'];
        }

        return $config['text'];
    }

    public function getDetectedLanguage($string)
    {
        try {
            $request = $this->client->get($this->provider->getTranslateEndpoint(), [
                'query' => $this->provider->getDetectParams($string)
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return app()->getLocale();
        }
        $response = $request->getResponse();
        return $response['data']['detections']['language'];
    }
}