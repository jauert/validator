<?php

declare(strict_types=1);

namespace Jauert\Validations;

use Jauert\ValidationInterface;

class Email extends RegEx implements ValidationInterface
{
    // phpcs:ignore Generic.Files.LineLength
    private const HOSTNAME_PATTERN = '(?:[_\p{L}0-9][-_\p{L}0-9]*\.)*(?:[\p{L}0-9][-\p{L}0-9]{0,62})\.(?:(?:[a-z]{2}\.)?[a-z]{2,})';
    // phpcs:ignore Generic.Files.LineLength
    protected string $regEx = '/^[\p{L}0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[\p{L}0-9!#$%&\'*+\/=?^_`{|}~-]+)*@' . self::HOSTNAME_PATTERN . '$/ui';
}
