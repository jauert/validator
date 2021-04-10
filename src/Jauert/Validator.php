<?php

declare(strict_types=1);

namespace Jauert;

use Jauert\Validations\Email;
use Jauert\Validations\NotBlank;
use Jauert\Validations\RegEx;

class Validator
{
    private array $errors = [];
    private array $validations = [];
    private array $requiredFields = [];

    public function validate(array $data): array
    {
        $this->checkForRequiredFields($data);

        foreach ($data as $field => $value) {
            $this->handleFieldValidation($field, $value);
        }

        return $this->errors;
    }

    public function requiredField(string $field): self
    {
        array_push($this->requiredFields, $field);

        return $this;
    }

    public function regex(string $field, string $regEx, ?string $message = null): self
    {
        $validation = new RegEx();
        if ($message) {
            $validation->setErrorMessage($message);
        }
        $validation->setRegex($regEx);
        $this->addValidation($field, $validation);

        return $this;
    }

    public function notBlank(string $field, ?string $message = null): self
    {
        $validation = new NotBlank();
        if ($message) {
            $validation->setErrorMessage($message);
        }
        $this->addValidation($field, $validation);

        return $this;
    }

    public function email(string $field, ?string $message = null): self
    {
        $validation = new Email();
        if ($message) {
            $validation->setErrorMessage($message);
        }
        $this->addValidation($field, $validation);

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

    private function addValidation(string $field, ValidationInterface $validation)
    {
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
        foreach ($this->requiredFields as $requiredField) {
            if (!isset($data[$requiredField])) {
                $this->addError($requiredField, 'Field is required.');
            }
        }
    }
}
