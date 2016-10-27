<?php

return [

    /**
     * Set your Google api key here
     */
    'google_api_key' => env('google_api_key', null),

    /**
     * Set your Bing api key here
     */
    'bing_api_key' => env('bing_api_key', null),

    /*
    |--------------------------------------------------------------------------
    | Locales
    |--------------------------------------------------------------------------
    |
    | Below is a list of locales that are currently available in the Google Translate API
    | For each locale you see here there will be a new folder created within your languages
    |
    | Remove any locales you do not want/need and they won't be generated (or comment them out)
    |
    */
    
    'locales' =>  [
        'af',       // Afrikaans
        'ga',       // Irish
        'sq',       // Albanian
        'it',       // Italian
        'ar',       // Arabic
        'ja',       // Japanese
        'az',       // Azerbaijani
        'kn',       // Kannada
        'eu',       // Basque
        'ko',       // Korean
        'bn',       // Bengali
        'la',       // Latin
        'be',       // Belarusian
        'lv',       // Latvian
        'bg',       // Bulgarian
        'lt',       // Lithuanian
        'ca',       // Catalan
        'mk',       // Macedonian
        'zh-CN',    // Chinese Simplified
        'ms',       // Malay
        'zh-TW',    // Chinese Traditional
        'mt',       //  Maltese
        'hr',       // Croatian
        'no',       // Norwegian
        'cs',       // Czech
        'fa',       // Persian
        'da',       // Danish
        'pl',       // Polish
        'nl',       // Dutch
        'pt',       // Portuguese
        'en',       // English
        'ro',       // Romanian
        'eo',       // Esperanto
        'ru',       // Russian
        'et',       // Estonian
        'sr',       // Serbian
        'tl',       // Filipino
        'sk',       // Slovak
        'fi',       // Finnish
        'sl',       // Slovenian
        'fr',       // French
        'es',       // Spanish
        'gl',       // Galician
        'sw',       // Swahili
        'ka',       // Georgian
        'sv',       // Swedish
        'de',       // German
        'ta',       // Tamil
        'el',       // Greek
        'te',       // Telugu
        'gu',       // Gujarati
        'th',       // Thai
        'ht',       // Haitian Creole
        'tr',       // Turkish
        'iw',       // Hebrew
        'uk',       // Ukrainian
        'hi',       // Hindi
        'ur',       // Urdu
        'hu',       // Hungarian
        'vi',       // Vietnamese
        'is',       // Icelandic
        'cy',       // Welsh
        'id',       // Indonesian
        'yi',       // Yiddish
    ]
];