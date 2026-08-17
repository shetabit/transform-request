<?php

namespace Shetabit\TransformRequest\Tests\Unit;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use ReflectionClass;
use Shetabit\TransformRequest\Facade\Transform as TransformFacade;
use Shetabit\TransformRequest\Provider\ServiceProvider;
use Shetabit\TransformRequest\Tests\Fixtures\NameTransformer;
use Shetabit\TransformRequest\Tests\TestCase;
use Shetabit\TransformRequest\Transform;

final class ServiceProviderTest extends TestCase
{
    public function testItBindsTheTransformerWhileRegistering() : void
    {
        // The binding used to be made in `boot()`, so anything resolving it from
        // another provider's `register()` — a facade among them — did not find it.
        $application = new Application();
        $application->register(new ServiceProvider($application));

        $this->assertTrue($application->bound(TransformFacade::SERVICE_NAME));
        $this->assertInstanceOf(Transform::class, $application->make(TransformFacade::SERVICE_NAME));
    }

    public function testItResolvesAFreshTransformerEveryTime() : void
    {
        $first = $this->app->make(TransformFacade::SERVICE_NAME);
        $second = $this->app->make(TransformFacade::SERVICE_NAME);

        $this->assertInstanceOf(Transform::class, $first);
        $this->assertNotSame($first, $second);
    }

    public function testTheFacadeResolvesTheBoundTransformer() : void
    {
        $this->assertSame(
            ['name' => 'mahdi', 'family' => 'khanzadi', 'username' => 'mahdikhanzadi'],
            TransformFacade::setOriginalData(['n' => 'mahdi', 'f' => 'khanzadi'])->get(new NameTransformer())
        );
    }

    public function testTheFacadeDoesNotCarryDataOverToTheNextCall() : void
    {
        TransformFacade::setOriginalData(['n' => 'mahdi']);

        $this->assertSame([], TransformFacade::getOriginalData());
    }

    public function testTheAliasOfThePackageReachesTheSameFacade() : void
    {
        $this->assertTrue(class_exists('RequestTransformer'));
        $this->assertTrue(is_a('RequestTransformer', TransformFacade::class, true));
    }

    public function testItRegistersTheRequestMacro() : void
    {
        $this->assertTrue(Request::hasMacro(ServiceProvider::REQUEST_MACRO));
    }
}
