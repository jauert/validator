<?php

declare(strict_types=1);

namespace Jauert;

interface ValidationInterface
{
    public function test($input): bool;

    public function getErrorMessage(): string;

    public function setErrorMessage(string $message): void;
}
