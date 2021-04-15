<?php

declare(strict_types=1);

namespace Jauert\Validations;

use Jauert\ValidationInterface;

class NotAsciiAlphaNumeric extends AsciiAlphaNumeric implements ValidationInterface
{
    public function test($input): bool
    {
        return !parent::test($input);
    }
}
