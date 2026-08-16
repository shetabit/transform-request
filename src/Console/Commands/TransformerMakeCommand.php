<?php

namespace Shetabit\TransformRequest\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'make:transformer')]
class TransformerMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:transformer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new transformer class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Transformer';

    /**
     * Get the stub file for the generator.
     *
     * The path used to be built with `base_path()`, which only ever pointed at the
     * right file when the package sat in `vendor/shetabit/transform-request`.
     */
    protected function getStub() : string
    {
        return dirname(__DIR__).'/stubs/transformer.stub';
    }

    /**
     * Get the default namespace for the class.
     */
    protected function getDefaultNamespace($rootNamespace) : string
    {
        return $rootNamespace.'\Http\Transformers';
    }
}
