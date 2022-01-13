<?php

declare(strict_types=1);

namespace Jauert\Validations;

use Jauert\ValidationInterface;

class LowerCase extends RegEx implements ValidationInterface
{
    protected string $regEx = '/[a-z]/';

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
