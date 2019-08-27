<?php

namespace Shetabit\TransformRequest\Provider;

use Illuminate\Http\Request;
use Shetabit\TransformRequest\Console\Commands\TransformerMakeCommand;
use Illuminate\Support\ServiceProvider as ParentServiceProvider;
use Shetabit\TransformRequest\Transform;

class ServiceProvider extends ParentServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Bind to service container.
         */
        $this->app->bind('shetabit-transform-request', function () {
            return new Transform();
        });

        /**
         * Add essential macros
         */
        $this->addMacros();
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Load artisan commands
     *
     * @return void
     */
    protected function loadCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                TransformerMakeCommand::class,
            ]);
        }
    }

    protected function addMacros()
    {
        Request::macro('transform', function($keys) {
            $keys = is_array($keys) ? $keys : func_get_args();

            $originalData = empty($keys) ? $this->all() : $this->only($keys);

            return new Transform($originalData);
        });
    }
}
