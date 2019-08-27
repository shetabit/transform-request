<?php

use Shetabit\TransformRequest\Transform;

if (!function_exists('Transform')) {
    /**
     * Access Transform through helper.
     *
     * @param array $values
     * @return Transform
     */
    function transform($values = []) : Transform
    {
        return (new Transform)->setOriginalData($values);
    }
}
