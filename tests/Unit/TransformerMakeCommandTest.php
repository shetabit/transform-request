<?php

namespace Shetabit\TransformRequest\Tests\Unit;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Filesystem\Filesystem;
use ReflectionMethod;
use Shetabit\TransformRequest\Console\Commands\TransformerMakeCommand;
use Shetabit\TransformRequest\Tests\TestCase;

final class TransformerMakeCommandTest extends TestCase
{
    public function testItIsRegisteredWithArtisan() : void
    {
        $commands = $this->app->make(Kernel::class)->all();

        $this->assertArrayHasKey('make:transformer', $commands);
        $this->assertInstanceOf(TransformerMakeCommand::class, $commands['make:transformer']);
    }

    public function testItPointsAtAStubThatIsThere() : void
    {
        // The stub used to be looked up under `base_path('vendor/shetabit/…')`,
        // which is only ever there when the package sits in that very directory.
        $this->assertFileExists($this->stubPath());
    }

    public function testTheStubIsValidPhp() : void
    {
        $stub = (string) file_get_contents($this->stubPath());

        $this->assertStringContainsString('{{ namespace }}', $stub);
        $this->assertStringContainsString('{{ class }}', $stub);
        $this->assertStringContainsString('implements TransformerInterface', $stub);
        $this->assertNotFalse(token_get_all(str_replace(
            ['{{ namespace }}', '{{ class }}'],
            ['App\Http\Transformers', 'ExampleTransformer'],
            $stub
        ), TOKEN_PARSE));
    }

    public function testItGeneratesIntoTheTransformersNamespace() : void
    {
        $command = new TransformerMakeCommand($this->app->make(Filesystem::class));
        $method = new ReflectionMethod($command, 'getDefaultNamespace');

        $this->assertSame('App\Http\Transformers', $method->invoke($command, 'App'));
    }

    private function stubPath() : string
    {
        $command = new TransformerMakeCommand($this->app->make(Filesystem::class));

        return (string) new ReflectionMethod($command, 'getStub')->invoke($command);
    }
}
