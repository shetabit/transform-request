<?php

namespace Shetabit\TransformRequest\Tests;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Shetabit\TransformRequest\Facade\Transform;
use Shetabit\TransformRequest\Provider\ServiceProvider;

abstract class TestCase extends BaseTestCase
{
    /**
     * @param  Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app) : array
    {
        return [ServiceProvider::class];
    }

    /**
     * @param  Application  $app
     * @return array<string, class-string>
     */
    protected function getPackageAliases($app) : array
    {
        return ['RequestTransformer' => Transform::class];
    }
}
