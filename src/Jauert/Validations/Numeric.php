<?php

declare(strict_types=1);

namespace Jauert\Validations;

use Jauert\ValidationInterface;

class Numeric extends AbstractValidation implements ValidationInterface
{
    public function test($input): bool
    {
        return is_numeric($input);
    }
}
