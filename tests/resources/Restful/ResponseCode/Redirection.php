<?php

namespace Resources\Restful\ResponseCode;

enum Redirection: int {
    case MULTIPLE_CHOICES = 300;
    case MOVED_PERMANENTLY = 301;
    case FOUND = 302;
    case SEE_OTHER = 303;
    case NOT_MODIFIED = 304;
    case USE_PROXY = 305;
    case TEMPORARY_REDIRECT = 307;

    public function getValue(): int {
        return $this->value;
    }
}