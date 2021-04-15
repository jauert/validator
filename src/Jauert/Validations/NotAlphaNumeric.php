<?php

declare(strict_types=1);

namespace Jauert\Validations;

use Jauert\ValidationInterface;

class NotAlphaNumeric extends AlphaNumeric implements ValidationInterface
{
    public function test($input): bool
    {
        return !parent::test($input);
    }
}
