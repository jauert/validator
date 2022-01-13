<?php

declare(strict_types=1);

namespace Jauert\Validations;

use Jauert\ValidationInterface;

class ContainsNumber extends RegEx implements ValidationInterface
{
    protected string $regEx = '/[0-9]/';
    private int $min;

    public function __construct(int $min)
    {
        $this->min = $min;
    }

    public function test($input): bool
    {
        if (!is_scalar($input) || is_bool($input)) {
            return false;
        }

        if ($this->min > preg_match_all($this->regEx, $input)) {
            return false;
        }

        return true;
    }
}
