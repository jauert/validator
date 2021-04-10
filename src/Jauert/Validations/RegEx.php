<?php

declare(strict_types=1);

namespace Jauert\Validations;

use Jauert\ValidationInterface;

class RegEx extends AbstractValidation implements ValidationInterface
{
    protected string $regEx;

    public function test($input): bool
    {
        if (!is_scalar($input)) {
            return false;
        }

        if (!preg_match($this->regEx, $input)) {
            return false;
        }

        return true;
    }

    public function setRegex(string $regEx): void
    {
        $this->regEx = $regEx;
    }
}
