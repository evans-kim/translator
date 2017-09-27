<?php

namespace EvansKim\Translator;

use Illuminate\Support\ServiceProvider;

class TranslatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\MakeTranslationTable::class
            ]);
        }
        $migname = "0000_00_00_000000_create_translatable_table.php";
        $migname = str_replace("0000_00_00_000000", date("Y_m_d_His"), $migname);

        $this->publishes([
            __DIR__.'/config/translator.php' => config_path('translator.php'),
            __DIR__.'/migrations/0000_00_00_000000_create_translatable_table.php' => base_path("database/migrations/".$migname)
        ], 'translator');


        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('evanskim.translator', 'EvansKim\Translator');
        $this->mergeConfigFrom(
            __DIR__.'/config/translator.php', 'translator'
        );

    }
}
