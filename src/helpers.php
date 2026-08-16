<?php

use Shetabit\TransformRequest\Transform;

// Not `transform()`: that is a Laravel helper the framework calls itself.
if (! function_exists('transform_request')) {
    /**
     * @param array<array-key, mixed> $values
     */
    function transform_request(array $values = []) : Transform
    {
        return new Transform($values);
    }
}
