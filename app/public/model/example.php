<?php

class Example
{
    public function read($id)
    {
        $read = [
            "João Silva",
            "Maria Silva",
            "Thiago Pereira",
            "Julia Pereira"
        ];

        return $read[$id];
    }
}
