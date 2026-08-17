<?php

namespace Shetabit\TransformRequest;

use Shetabit\Transformer\Classes\Transform as BaseTransform;

/**
 * The transformation itself lives in `shetabit/transformer`. This type exists so that
 * an application can rebind it in the container without touching that package.
 */
class Transform extends BaseTransform
{
}
