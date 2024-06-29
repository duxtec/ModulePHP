<?php

namespace Resources\Restful\ResponseCode;

enum Informational: int {
    case CONTINUE = 100;
    case SWITCHING_PROTOCOLS = 101;
    case PROCESSING = 102;

    public function getValue(): int {
        return $this->value;
    }
}