<?php

namespace Neondigital\Polyglot\Services;

use Neondigital\Polyglot\Services\Http\Client;
use Neondigital\Polyglot\TranslateInterface;

class TranslateService extends Client implements TranslateInterface
{

    public function translate($text, $lang, $from_lang = null, $html = false, $detect = false, $exit_on_error = false)
    {
        // Swap out any :instances
        $text = $this->replacePlaceholders($text);

        $result = $this->getTranslatedText([
            'text' => $text,
            'to' => $lang,
            'from' => $from_lang,
            'detect' => $detect
        ]);

        $result = $this->putPlaceholdersBack($result);

        return $result;
    }

    public function detect($text)
    {
        // Swap out any :instances
        $text = $this->replacePlaceholders($text);
        $result = $this->getDetectedLanguage($text);
        $result = $this->putPlaceholdersBack($result);
        return $result;   
    }
    /**
     * Make the place-holder replacements on a line.
     *
     * @param  string  $line
     * @param  array   $replace
     * @return string
     */
    protected function replacePlaceholders($line)
    {
        return preg_replace('/(?<=\:)(\w+)/', '<trans-${1}->', $line);
    }

    public function putPlaceholdersBack($line)
    {   
        // Remove the dodgy colons that got left over
        $line = preg_replace('/(:)/', '', $line);

        // Put our placeholder back
        $line = preg_replace('/(?<=<trans-)(\w+)(?=->)/', ':${1}', $line);

        // Remove the start tag we used
        $line = preg_replace('/(<trans-)/', '', $line);

        // Remove the end tag that closed it off
        $line = preg_replace('/(->)/', '', $line);

        return $line;
    }
}