<?php

declare(strict_types=1);

namespace Jauert\Validations;

use Jauert\ValidationInterface;

class AlphaNumeric extends RegEx implements ValidationInterface
{
    protected string $regEx = '/^[\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}]+$/Du';

    public function test($input): bool
    {
        if (!is_scalar($input) || is_bool($input)) {
            return false;
        }

        if (!preg_match($this->regEx, $input)) {
            return false;
        }

        return true;
    }
}
