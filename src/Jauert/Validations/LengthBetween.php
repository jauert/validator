<?php

declare(strict_types=1);

namespace Jauert\Validations;

use Jauert\ValidationInterface;

class LengthBetween extends AbstractValidation implements ValidationInterface
{
    private int $min;
    private int $max;

    public function __construct(int $min, int $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function test($input): bool
    {
        if (!is_scalar($input)) {
            return false;
        }
        $length = mb_strlen((string)$input);

        return $length >= $this->min && $length <= $this->max;
    }
}
