<?php

declare(strict_types=1);

namespace Jauert\Validations;

use Jauert\ValidationInterface;

class NotEqualTo extends AbstractValidation implements ValidationInterface
{
    // upgrade to mixed in php 8
    private $equalTo;

    public function __construct($equalTo)
    {
        $this->equalTo = $equalTo;
    }

    public function test($input): bool
    {
        return $input !== $this->equalTo;
    }
}
