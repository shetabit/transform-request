<?php

namespace Shetabit\TransformRequest\Tests\Unit;

use Illuminate\Http\Request;
use Shetabit\TransformRequest\Tests\Fixtures\KeyCountingTransformer;
use Shetabit\TransformRequest\Tests\Fixtures\NameTransformer;
use Shetabit\TransformRequest\Tests\TestCase;
use Shetabit\TransformRequest\Transform;

final class RequestMacroTest extends TestCase
{
    public function testItTakesEveryInputWhenNoKeyIsGiven() : void
    {
        $transform = $this->request()->transform();

        $this->assertInstanceOf(Transform::class, $transform);
        $this->assertSame(['n' => 'mahdi', 'f' => 'khanzadi', 'age' => '30'], $transform->getOriginalData());
    }

    public function testItTakesTheKeysGivenAsAnArray() : void
    {
        $this->assertSame(
            ['keys' => ['n', 'f'], 'count' => 2],
            $this->request()->transform(['n', 'f'])->get(new KeyCountingTransformer())
        );
    }

    public function testItTakesTheKeysGivenAsSeparateArguments() : void
    {
        $this->assertSame(
            ['keys' => ['n', 'age'], 'count' => 2],
            $this->request()->transform('n', 'age')->get(new KeyCountingTransformer())
        );
    }

    public function testItTakesASingleKey() : void
    {
        $this->assertSame(
            ['keys' => ['f'], 'count' => 1],
            $this->request()->transform('f')->get(new KeyCountingTransformer())
        );
    }

    public function testItFlattensArraysAndArgumentsThatAreMixed() : void
    {
        $this->assertSame(
            ['keys' => ['n', 'f', 'age'], 'count' => 3],
            $this->request()->transform(['n', 'f'], 'age')->get(new KeyCountingTransformer())
        );
    }

    public function testAKeyThatWasNotSentIsLeftOut() : void
    {
        $this->assertSame(
            ['keys' => ['n'], 'count' => 1],
            $this->request()->transform('n', 'nickname')->get(new KeyCountingTransformer())
        );
    }

    public function testItTransformsTheDataItCollected() : void
    {
        $this->assertSame(
            ['name' => 'mahdi', 'family' => 'khanzadi', 'username' => 'mahdikhanzadi'],
            $this->request()->transform()->get(new NameTransformer())
        );
    }

    public function testItReturnsANewTransformOnEveryCall() : void
    {
        $request = $this->request();

        $this->assertNotSame($request->transform(), $request->transform());
    }

    private function request() : Request
    {
        return Request::create('/users', 'POST', ['n' => 'mahdi', 'f' => 'khanzadi', 'age' => '30']);
    }
}
