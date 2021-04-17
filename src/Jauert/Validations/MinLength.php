<?php

declare(strict_types=1);

namespace Jauert\Validations;

use Jauert\ValidationInterface;

class MinLength extends AbstractValidation implements ValidationInterface
{
    private int $min;

    public function __construct(int $min)
    {
        $this->min = $min;
    }

    public function test($input): bool
    {
        if (!is_scalar($input)) {
            return false;
        }

        return mb_strlen((string)$input) >= $this->min;
    }
}
