<?php

namespace Shetabit\TransformRequest\Tests\Fixtures;

use Shetabit\Transformer\Contracts\TransformerInterface;

class NameTransformer implements TransformerInterface
{
    /**
     * @param array<array-key, mixed> $data
     * @return array<array-key, mixed>
     */
    public function transform(array $data) : array
    {
        $name = $data['n'] ?? '';
        $family = $data['f'] ?? '';

        return [
            'name' => $name,
            'family' => $family,
            'username' => sprintf('%s%s', is_scalar($name) ? $name : '', is_scalar($family) ? $family : ''),
        ];
    }
}
