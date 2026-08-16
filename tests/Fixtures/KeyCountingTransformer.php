<?php

namespace Shetabit\TransformRequest\Tests\Fixtures;

use Shetabit\Transformer\Contracts\TransformerInterface;

class KeyCountingTransformer implements TransformerInterface
{
    /**
     * @param array<array-key, mixed> $data
     * @return array<array-key, mixed>
     */
    public function transform(array $data) : array
    {
        return ['keys' => array_keys($data), 'count' => count($data)];
    }
}
