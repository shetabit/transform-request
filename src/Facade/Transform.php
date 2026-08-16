<?php

namespace Shetabit\TransformRequest\Facade;

use Illuminate\Support\Facades\Facade;
use Shetabit\Transformer\Contracts\TransformerInterface;
use Shetabit\TransformRequest\Transform as RequestTransform;

/**
 * @method static RequestTransform setOriginalData(array<array-key, mixed> $originalData)
 * @method static array<array-key, mixed> getOriginalData()
 * @method static RequestTransform use(TransformerInterface $transformer)
 * @method static RequestTransform setTransformer(TransformerInterface $transformer)
 * @method static array<array-key, mixed> get(TransformerInterface|null $transformer = null)
 * @method static array<array-key, mixed> getTransformedData()
 *
 * @see RequestTransform
 */
class Transform extends Facade
{
    /**
     * The name the transformer is bound to in the service container.
     */
    public const string SERVICE_NAME = 'shetabit-transform-request';

    /**
     * A transform carries the data it is working on, so a cached one would hand the
     * next caller — the next request, under a worker that keeps the process alive —
     * whatever the previous one left behind.
     *
     * @var bool
     */
    protected static $cached = false;

    /**
     * Get the registered name of the component.
     */
    public static function getFacadeAccessor() : string
    {
        return self::SERVICE_NAME;
    }
}
