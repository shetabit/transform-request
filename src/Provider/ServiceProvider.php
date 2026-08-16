<?php

namespace Shetabit\TransformRequest\Provider;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider as ParentServiceProvider;
use Shetabit\TransformRequest\Console\Commands\TransformerMakeCommand;
use Shetabit\TransformRequest\Facade\Transform as TransformFacade;
use Shetabit\TransformRequest\Transform;

class ServiceProvider extends ParentServiceProvider
{
    /**
     * The name of the macro this package adds to `Illuminate\Http\Request`.
     */
    public const string REQUEST_MACRO = 'transform';

    /**
     * Register any package services.
     */
    public function register() : void
    {
        $this->app->bind(TransformFacade::SERVICE_NAME, static fn () : Transform => new Transform());
    }

    /**
     * Perform post-registration booting of services.
     */
    public function boot() : void
    {
        $this->addMacros();

        $this->loadCommands();
    }

    /**
     * Load artisan commands.
     */
    protected function loadCommands() : void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                TransformerMakeCommand::class,
            ]);
        }
    }

    /**
     * Add essential macros.
     */
    protected function addMacros() : void
    {
        Request::macro(self::REQUEST_MACRO, function (array|string ...$keys) : Transform {
            /** @var Request $request */
            $request = $this;

            $keys = Arr::flatten($keys);

            return new Transform($keys === [] ? $request->all() : $request->only($keys));
        });
    }
}
