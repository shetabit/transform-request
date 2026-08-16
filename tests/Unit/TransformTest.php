<?php

namespace Shetabit\TransformRequest\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Shetabit\Transformer\Classes\Transform as BaseTransform;
use Shetabit\Transformer\Exceptions\TransformerNotValidException;
use Shetabit\TransformRequest\Tests\Fixtures\KeyCountingTransformer;
use Shetabit\TransformRequest\Tests\Fixtures\NameTransformer;
use Shetabit\TransformRequest\Transform;

final class TransformTest extends TestCase
{
    public function testItIsATransformOfTheTransformerPackage() : void
    {
        $this->assertInstanceOf(BaseTransform::class, new Transform());
    }

    public function testItKeepsTheDataItWasConstructedWith() : void
    {
        $this->assertSame(['a' => 1], new Transform(['a' => 1])->getOriginalData());
    }

    public function testItRemembersTheTransformerItWasGiven() : void
    {
        $transform = new Transform(['n' => 'mahdi', 'f' => 'khanzadi'])->use(new NameTransformer());

        $this->assertSame(
            ['name' => 'mahdi', 'family' => 'khanzadi', 'username' => 'mahdikhanzadi'],
            $transform->get()
        );
        $this->assertSame($transform->get(), $transform->getTransformedData());
    }

    public function testTheTransformerGivenToGetWinsOverTheRememberedOne() : void
    {
        $transform = new Transform(['n' => 'mahdi'])->use(new NameTransformer());

        $this->assertSame(['keys' => ['n'], 'count' => 1], $transform->get(new KeyCountingTransformer()));
    }

    public function testItRefusesToTransformWithoutATransformer() : void
    {
        $this->expectException(TransformerNotValidException::class);

        new Transform(['a' => 1])->get();
    }
}
