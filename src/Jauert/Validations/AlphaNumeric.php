<?php

declare(strict_types=1);

namespace Jauert\Validations;

use Jauert\ValidationInterface;

class AlphaNumeric extends RegEx implements ValidationInterface
{
    protected string $regEx = '/[^\s]+/';
}
