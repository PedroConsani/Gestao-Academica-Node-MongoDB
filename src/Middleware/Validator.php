<?php
// src/Middleware/Validator.php

class Validator {
    private array $errors = [];
    private array $data;

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function required(string $field, string $label): self {
        if (empty($this->data[$field])) {
            $this->errors[$field] = "O campo '$label' é obrigatório.";
        }
        return $this;
    }

    public function email(string $field, string $label): self {
        if (!empty($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "O campo '$label' deve ser um email válido.";
        }
        return $this;
    }

    public function maxLength(string $field, string $label, int $max): self {
        if (!empty($this->data[$field]) && mb_strlen($this->data[$field]) > $max) {
            $this->errors[$field] = "O campo '$label' não pode exceder $max caracteres.";
        }
        return $this;
    }

    public function minLength(string $field, string $label, int $min): self {
        if (!empty($this->data[$field]) && mb_strlen($this->data[$field]) < $min) {
            $this->errors[$field] = "O campo '$label' deve ter pelo menos $min caracteres.";
        }
        return $this;
    }

    public function numeric(string $field, string $label): self {
        if (!empty($this->data[$field]) && !is_numeric($this->data[$field])) {
            $this->errors[$field] = "O campo '$label' deve ser numérico.";
        }
        return $this;
    }

    public function between(string $field, string $label, float $min, float $max): self {
        $val = $this->data[$field] ?? null;
        if ($val !== null && $val !== '' && ($val < $min || $val > $max)) {
            $this->errors[$field] = "O campo '$label' deve estar entre $min e $max.";
        }
        return $this;
    }

    public function date(string $field, string $label): self {
        if (!empty($this->data[$field])) {
            $d = DateTime::createFromFormat('Y-m-d', $this->data[$field]);
            if (!$d || $d->format('Y-m-d') !== $this->data[$field]) {
                $this->errors[$field] = "O campo '$label' deve ser uma data válida.";
            }
        }
        return $this;
    }

    public function inArray(string $field, string $label, array $allowed): self {
        if (!empty($this->data[$field]) && !in_array($this->data[$field], $allowed)) {
            $this->errors[$field] = "O valor do campo '$label' é inválido.";
        }
        return $this;
    }

    public function passes(): bool {
        return empty($this->errors);
    }

    public function errors(): array {
        return $this->errors;
    }

    public function firstError(): ?string {
        return $this->errors ? reset($this->errors) : null;
    }
}
