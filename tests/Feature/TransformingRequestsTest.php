<?php

namespace Shetabit\TransformRequest\Tests\Feature;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Shetabit\TransformRequest\Facade\Transform as TransformFacade;
use Shetabit\TransformRequest\Tests\Fixtures\KeyCountingTransformer;
use Shetabit\TransformRequest\Tests\Fixtures\NameTransformer;
use Shetabit\TransformRequest\Tests\TestCase;

final class TransformingRequestsTest extends TestCase
{
    protected function defineRoutes($router) : void
    {
        /** @var Router $router */
        $router->post('/users', static fn (Request $request) : array => $request
            ->transform()
            ->get(new NameTransformer()));

        $router->post('/users/named', static fn (Request $request) : array => $request
            ->transform('n', 'f')
            ->get(new KeyCountingTransformer()));

        $router->get('/users/query', static fn (Request $request) : array => $request
            ->transform(['n'])
            ->get(new KeyCountingTransformer()));

        $router->post('/users/helper', static fn (Request $request) : array => transform_request($request->all())
            ->get(new NameTransformer()));

        $router->post('/users/facade', static fn (Request $request) : array => TransformFacade::setOriginalData(
            $request->all()
        )->get(new NameTransformer()));
    }

    public function testARequestIsTransformedOnItsWayThroughTheApplication() : void
    {
        $this->postJson('/users', ['n' => 'mahdi', 'f' => 'khanzadi'])
            ->assertOk()
            ->assertExactJson(['name' => 'mahdi', 'family' => 'khanzadi', 'username' => 'mahdikhanzadi']);
    }

    public function testAFormEncodedRequestIsTransformedAsWell() : void
    {
        $this->post('/users', ['n' => 'mahdi', 'f' => 'khanzadi'])
            ->assertOk()
            ->assertExactJson(['name' => 'mahdi', 'family' => 'khanzadi', 'username' => 'mahdikhanzadi']);
    }

    public function testOnlyTheNamedKeysReachTheTransformer() : void
    {
        $this->postJson('/users/named', ['n' => 'mahdi', 'f' => 'khanzadi', 'password' => 'secret'])
            ->assertOk()
            ->assertExactJson(['keys' => ['n', 'f'], 'count' => 2]);
    }

    public function testTheQueryStringOfARequestIsTransformedToo() : void
    {
        $this->getJson('/users/query?n=mahdi&f=khanzadi')
            ->assertOk()
            ->assertExactJson(['keys' => ['n'], 'count' => 1]);
    }

    public function testAnEmptyRequestIsTransformedIntoTheEmptyShape() : void
    {
        $this->postJson('/users', [])
            ->assertOk()
            ->assertExactJson(['name' => '', 'family' => '', 'username' => '']);
    }

    public function testTheHelperTransformsARequestOfTheApplication() : void
    {
        $this->postJson('/users/helper', ['n' => 'mahdi', 'f' => 'khanzadi'])
            ->assertOk()
            ->assertExactJson(['name' => 'mahdi', 'family' => 'khanzadi', 'username' => 'mahdikhanzadi']);
    }

    public function testTheFacadeTransformsARequestOfTheApplication() : void
    {
        $this->postJson('/users/facade', ['n' => 'mahdi', 'f' => 'khanzadi'])
            ->assertOk()
            ->assertExactJson(['name' => 'mahdi', 'family' => 'khanzadi', 'username' => 'mahdikhanzadi']);
    }

    public function testTheDataOfOneRequestDoesNotLeakIntoTheNext() : void
    {
        $this->postJson('/users/facade', ['n' => 'mahdi', 'f' => 'khanzadi'])->assertOk();

        $this->postJson('/users/facade', ['n' => 'ali'])
            ->assertOk()
            ->assertExactJson(['name' => 'ali', 'family' => '', 'username' => 'ali']);
    }
}
