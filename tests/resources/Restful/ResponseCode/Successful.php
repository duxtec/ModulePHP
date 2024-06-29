<?php

namespace Resources\Restful\ResponseCode;

enum Successful: int {
    case OK = 200;
    case CREATED = 201;
    case ACCEPTED = 202;
    case NON_AUTHORITATIVE_INFORMATION = 203;
    case NO_CONTENT = 204;
    case RESET_CONTENT = 205;
    case PARTIAL_CONTENT = 206;

    public function getValue(): int {
        return $this->value;
    }
}