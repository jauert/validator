<?php

declare(strict_types=1);

namespace Jauert\Validations;

use Jauert\ValidationInterface;

class Range extends AbstractValidation implements ValidationInterface
{
    private ?float $lower;
    private ?float $upper;

    public function __construct(?float $lower = null, ?float $upper = null)
    {
        $this->lower = $lower;
        $this->upper = $upper;
    }

    public function test($input): bool
    {
        if (!is_numeric($input)) {
            return false;
        }

        return $input >= $this->lower && $input <= $this->upper;
    }
}
