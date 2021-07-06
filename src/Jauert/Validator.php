<?php

declare(strict_types=1);

namespace Jauert;

use Jauert\Validations\AlphaNumeric;
use Jauert\Validations\AsciiAlphaNumeric;
use Jauert\Validations\Email;
use Jauert\Validations\EqualTo;
use Jauert\Validations\LengthBetween;
use Jauert\Validations\MaxLength;
use Jauert\Validations\Md5;
use Jauert\Validations\MinLength;
use Jauert\Validations\NotAlphaNumeric;
use Jauert\Validations\NotAsciiAlphaNumeric;
use Jauert\Validations\NotBlank;
use Jauert\Validations\Numeric;
use Jauert\Validations\Range;
use Jauert\Validations\RegEx;

class Validator
{
    private array $errors = [];
    private array $validations = [];
    private array $requiredFields = [];

    public function validate(array $data): array
    {
        $this->errors = [];
        $this->checkForRequiredFields($data);

        foreach ($data as $field => $value) {
            $this->handleFieldValidation($field, $value);
        }

        return $this->errors;
    }

    public function requiredField(string $field, string $message = 'Field is required.'): self
    {
        $this->requiredFields[$field] = $message;

        return $this;
    }

    public function regex(string $field, string $regEx, ?string $message = null): self
    {
        $validation = new RegEx();
        $validation->setRegex($regEx);

        $this->addValidation($field, $validation, $message);

        return $this;
    }

    public function notBlank(string $field, ?string $message = null): self
    {
        $this->addValidation($field, new NotBlank(), $message);

        return $this;
    }

    public function email(string $field, ?string $message = null): self
    {
        $this->addValidation($field, new Email(), $message);

        return $this;
    }

    public function alphaNumeric(string $field, ?string $message = null): self
    {
        $this->addValidation($field, new AlphaNumeric(), $message);

        return $this;
    }

    public function notAlphaNumeric(string $field, ?string $message = null): self
    {
        $this->addValidation($field, new NotAlphaNumeric(), $message);

        return $this;
    }

    public function asciiAlphaNumeric(string $field, ?string $message = null): self
    {
        $this->addValidation($field, new AsciiAlphaNumeric(), $message);

        return $this;
    }

    public function notAsciiAlphaNumeric(string $field, ?string $message = null): self
    {
        $this->addValidation($field, new NotAsciiAlphaNumeric(), $message);

        return $this;
    }

    public function lengthBetween(string $field, int $min, int $max, ?string $message = null)
    {
        $this->addValidation($field, new LengthBetween($min, $max), $message);

        return $this;
    }

    public function equalTo(string $field, $equalTo, ?string $message = null)
    {
        $this->addValidation($field, new EqualTo($equalTo), $message);

        return $this;
    }

    public function minLength(string $field, int $min, ?string $message = null)
    {
        $this->addValidation($field, new MinLength($min), $message);

        return $this;
    }

    public function maxLength(string $field, int $max, ?string $message = null)
    {
        $this->addValidation($field, new MaxLength($max), $message);

        return $this;
    }

    public function md5(string $field, ?string $message = null)
    {
        $this->addValidation($field, new Md5(), $message);
    }

    public function numeric(string $field, ?string $message = null)
    {
        $this->addValidation($field, new Numeric(), $message);

        return $this;
    }

    public function range(string $field, float $lower, float $upper, ?string $message = null)
    {
        $this->addValidation($field, new Range($lower, $upper), $message);

        return $this;
    }

    private function handleFieldValidation(string $field, $value): void
    {
        if (!isset($this->validations[$field])) {
            return;
        }

        /** @var ValidationInterface $validation */
        foreach ($this->validations[$field] as $validation) {
            if (!$validation->test($value)) {
                $this->addError($field, $validation->getErrorMessage());
            }
        }
    }

    private function addValidation(string $field, ValidationInterface $validation, ?string $message)
    {
        if ($message) {
            $validation->setErrorMessage($message);
        }
        $this->validations[$field][] = $validation;
    }

    private function addError(string $field, ?string $error): void
    {
        if ($error) {
            $this->errors[$field][] = $error;
        }
    }

    private function checkForRequiredFields(array $data): void
    {
        foreach ($this->requiredFields as $field => $message) {
            if (!isset($data[$field])) {
                $this->addError($field, $message);
            }
        }
    }
}
