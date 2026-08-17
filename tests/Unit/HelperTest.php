<?php

namespace Shetabit\TransformRequest\Tests\Unit;

use PHPUnit\Framework\TestCase;
use ReflectionFunction;
use Shetabit\TransformRequest\Tests\Fixtures\NameTransformer;
use Shetabit\TransformRequest\Transform;

final class HelperTest extends TestCase
{
    public function testItReturnsATransformSeededWithTheGivenValues() : void
    {
        $transform = transform_request(['n' => 'mahdi']);

        $this->assertInstanceOf(Transform::class, $transform);
        $this->assertSame(['n' => 'mahdi'], $transform->getOriginalData());
    }

    public function testItDefaultsToAnEmptyDataSet() : void
    {
        $this->assertSame([], transform_request()->getOriginalData());
    }

    public function testItTransformsWithTheGivenTransformer() : void
    {
        $data = transform_request(['n' => 'mahdi', 'f' => 'khanzadi'])->get(new NameTransformer());

        $this->assertSame(
            ['name' => 'mahdi', 'family' => 'khanzadi', 'username' => 'mahdikhanzadi'],
            $data
        );
    }

    public function testItReturnsANewTransformOnEveryCall() : void
    {
        $this->assertNotSame(transform_request(), transform_request());
    }

    public function testItLeavesTheTransformHelperOfTheFrameworkAlone() : void
    {
        $helper = new ReflectionFunction('transform');

        $this->assertStringContainsString('illuminate', strtolower((string) $helper->getFileName()));
        $this->assertSame('MAHDI', transform('mahdi', strtoupper(...)));
    }
}
