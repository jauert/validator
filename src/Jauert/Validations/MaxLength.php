<?php

declare(strict_types=1);

namespace Jauert\Validations;

use Jauert\ValidationInterface;

class MaxLength extends AbstractValidation implements ValidationInterface
{
    private int $max;

    public function __construct(int $max)
    {
        $this->max = $max;
    }

    public function test($input): bool
    {
        if (!is_scalar($input)) {
            return false;
        }

        return mb_strlen((string)$input) <= $this->max;
    }
}
