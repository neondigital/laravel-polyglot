<?php

namespace Neondigital\Polyglot\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\AppNamespaceDetectorTrait;

class MakeLanguageFilesCommand extends Command
{
    use AppNamespaceDetectorTrait;

    protected $signature = 'language:files:make';

    protected $description = 'Generates language files for all locales';

    public function handle() {
        $this->info('Files generated!');
    }
}