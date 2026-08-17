<?php

namespace Shetabit\TransformRequest\Tests\Feature;

use Illuminate\Support\Facades\File;
use Illuminate\Testing\PendingCommand;
use ReflectionClass;
use Shetabit\Transformer\Contracts\TransformerInterface;
use Shetabit\TransformRequest\Tests\TestCase;
use Shetabit\TransformRequest\Transform;

final class MakeTransformerCommandTest extends TestCase
{
    /**
     * @var array<int, string>
     */
    private array $generated = [];

    public function testItGeneratesATransformer() : void
    {
        $this->generate('OrderTransformer');

        $contents = $this->contentsOf('OrderTransformer');

        $this->assertStringContainsString('namespace App\Http\Transformers;', $contents);
        $this->assertStringContainsString('use Shetabit\Transformer\Contracts\TransformerInterface;', $contents);
        $this->assertStringContainsString('class OrderTransformer implements TransformerInterface', $contents);
        $this->assertStringContainsString('public function transform(array $data) : array', $contents);
    }

    public function testTheGeneratedTransformerIsUsableAsIs() : void
    {
        $this->generate('UsableTransformer');

        require_once $this->pathOf('UsableTransformer');

        /** @var class-string<TransformerInterface> $class */
        $class = $this->app->getNamespace().'Http\Transformers\UsableTransformer';

        $reflection = new ReflectionClass($class);

        $this->assertTrue($reflection->implementsInterface(TransformerInterface::class));
        $this->assertSame([], new Transform(['n' => 'mahdi'])->get($reflection->newInstance()));
    }

    public function testItLeavesAnExistingTransformerAlone() : void
    {
        $this->generate('TwiceTransformer');

        $path = $this->pathOf('TwiceTransformer');
        File::put($path, '<?php // written by hand');

        $command = $this->artisan('make:transformer', ['name' => 'TwiceTransformer']);
        $this->assertInstanceOf(PendingCommand::class, $command);
        $command->run();

        $this->assertSame('<?php // written by hand', File::get($path));
    }

    private function generate(string $name) : void
    {
        $command = $this->artisan('make:transformer', ['name' => $name]);

        $this->assertInstanceOf(PendingCommand::class, $command);

        // `run()` is what executes it: a pending command that is only asserted
        // on runs when it is destroyed, which is after this test is done.
        $this->assertSame(0, $command->run());
    }

    private function pathOf(string $name) : string
    {
        $this->generated[] = $path = $this->app->path(sprintf('Http/Transformers/%s.php', $name));

        return $path;
    }

    private function contentsOf(string $name) : string
    {
        $path = $this->pathOf($name);

        $this->assertFileExists($path);

        return (string) file_get_contents($path);
    }

    protected function tearDown() : void
    {
        File::delete($this->generated);

        parent::tearDown();
    }
}
