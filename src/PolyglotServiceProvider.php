<?php

namespace Neondigital\Polyglot;

use Config;
use Illuminate\Support\ServiceProvider as ServiceProvider;
use Neondigital\Polyglot\Commands\MakeLanguageFilesCommand;

class PolyglotServiceProvider extends ServiceProvider
{

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot()
    {
        if (!$this->isLumen()) {
            $this->publishes([
                $this->getConfigPath() => $this->app->make('path.config') . '/polyglot.php',
            ], 'config');
        }
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeLanguageFilesCommand::class
            ]);
        }
    }

    public function register()
    {
    }

    /**
     * @return string
     */
    protected function getConfigPath()
    {
        return __DIR__ . '/../config/polyglot.php';
    }

    /**
     * @return bool
     */
    protected function isLumen()
    {
        return str_contains($this->app->version(), 'Lumen');
    }
}