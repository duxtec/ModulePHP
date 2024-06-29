<?php

namespace Resources\Utils;

class Validator
{
    public static function validateRequiredFields(array $data, array $requiredFields)
    {
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                throw new \InvalidArgumentException("O campo '$field' é obrigatório.");
            }
        }

        return $data;
    }

    public static function validateOptionalFields(array $data, array $optionalFields)
    {
        foreach ($optionalFields as $field) {
            if (!isset($data[$field])) {
                // Defina o valor padrão para propriedades opcionais ausentes como null
                $data[$field] = null;
            }
        }

        return $data;
    }
}