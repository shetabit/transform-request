<?php

namespace Shetabit\TransformRequest\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * Class RequestTransformer
 *
 * @package Shetabit\TransformRequest\Facade
 */
class Transform extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'shetabit-transform-request';
    }
}
