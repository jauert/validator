<?php

declare(strict_types=1);

namespace Jauert\Validations;

abstract class AbstractValidation
{
    private string $errorMsg = 'Field is invalid.';

    public function getErrorMessage(): string
    {
        return $this->errorMsg;
    }

    public function setErrorMessage(string $message): void
    {
        $this->errorMsg = $message;
    }
}
