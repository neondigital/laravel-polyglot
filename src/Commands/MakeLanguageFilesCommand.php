<?php

namespace Neondigital\Polyglot\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\AppNamespaceDetectorTrait;
use Illuminate\Filesystem\Filesystem;
use Neondigital\Polyglot\Services\TranslateService;

class MakeLanguageFilesCommand extends Command
{
    use AppNamespaceDetectorTrait;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'polyglot:trans:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates language files for all locales';

    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * Create a new command instance.
     *
     * @param Filesystem $files
     * @param Composer $composer
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle() {

        // Get our current locale
        $defaultLocale = app()->getLocale();

        // Where we will put our new files
        $transLocation = config('polyglot.language_file_location');

        // Our source language
        $sourceLanguageFiles =  $transLocation . '/' . $defaultLocale;

        /**
         * If we can't find the files to use as our base, we can't translate
         */
        if (!$this->files->exists($sourceLanguageFiles)) {
            $this->error("Language files for \"{$locale}\" do not exist");
            return;
        }

        $files = $this->files->allFiles($sourceLanguageFiles, true);

        $locales = config('polyglot.locales');

        // Miss out the current one
        $localeCount = count($locales) - 1;

        $this->info("Parsing {$localeCount} locales");

        foreach ($locales as $locale) {

            if ($locale == $defaultLocale) {
                continue;
            }

            $localeDir = $transLocation . '/' . $locale;

            // First, if the locale doesnt exist in the files, just create the base folder
            if (!$this->files->exists($localeDir)) {
                $this->files->makeDirectory($localeDir);
            }

            foreach ($files as $file) {
                $langKey = $this->getLangKeyFromFile($file);

                // We go from the source as that is where we are picking up the originals from
                $subDirectory = str_replace($sourceLanguageFiles, '', $file->getPath());

                $saveLocation = $transLocation . '/' . $locale . '/';

                if ($subDirectory) {
                    $parentDir = ltrim($subDirectory, '/');
                    $langKey = $parentDir . '/' . $this->getLangKeyFromFile($file);
                    $saveLocation = $saveLocation . $parentDir . '/';
                }

                $translations = trans()->get($langKey);

                // Get the current translations for this locale
                $currentTrans = trans()->get($langKey, [], $locale, false);

                // Initiate our new translations
                $newTrans = [];
                $this->info('');
                $this->info('Starting translations for ' . $langKey . ' in ' . $locale);

                $bar = $this->output->createProgressBar(count($translations));

                foreach ($translations as $key => $translation) {
                    // Does this translation exists already?
                    $transExists = trans()->hasForLocale($langKey . '.' . $key, $locale);

                    if (is_array($translation) && !$transExists) {
                       foreach ($translation as $subKey => $value) {
                            $subTransExists = trans()->hasForLocale($langKey . '.' . $key . '.' . $subKey, $locale);
                            if (!$subTransExists) {
                                $newTrans[$key][$subKey] = $this->getTranslatedString($value, $locale);
                            }
                            // Not again!
                            if (is_array($value)) {
                                foreach ($value as $tinyKey => $subValue) {
                                    $tinyTransExists = trans()->hasForLocale($langKey . '.' . $key . '.' . $subKey . '.' . $subKey, $locale);
                                    if (!$tinyTransExists) {
                                        $newTrans[$key][$subKey][$tinyKey] = $this->getTranslatedString($subValue, $locale);
                                    }
                                }
                            }
                       }
                    }elseif (!$transExists) {
                        $newTrans[$key] = $this->getTranslatedString($translation, $locale);
                    }

                    $bar->advance();
                }

                // Merge them into our current translations
                if (is_array($currentTrans)) {
                    $transToMerge = array_merge($currentTrans, $newTrans);
                } else {
                    $transToMerge = $newTrans;
                }

                if (!$this->files->exists($saveLocation)) {
                    try {
                        $this->files->makeDirectory($saveLocation, 0775, true);
                    } catch (\ErrorException $e) {
                        $this->error('Couldnt make directory ' . $saveLocation);
                    }
                }

                $l = $saveLocation . $file->getFilename();

                $file = '<?php' . PHP_EOL;
                $file .= '/** Generated with Polyglot **/' . PHP_EOL;
                $file .= 'return ' . var_export($transToMerge, true) . ';';
                file_put_contents($l, $file);
                // $this->files->put(, $transToMerge);
            }
        }
        $this->info('All done!');
    }

    protected function getTranslatedString($string, $locale) {
        $translator = new TranslateService();
        return $translator->translate($string, $locale);
    }

    protected function getLangKeyFromFile($file)
    {
        return $file->getBasename('.' . $file->getExtension());
    }
}