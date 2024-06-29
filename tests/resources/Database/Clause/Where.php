<?php

namespace Resources\Database\Clause;

use Resources\Database\DB;
use Resources\Database\Reserved;

/**
 * Summary of Where
 */
class Where
{
    /**
     * Summary of string
     * @var string
     */
    public $string = "";

    /**
     * Summary of addCondition
     * @param mixed $column_or_array
     * @param mixed $value
     * @param mixed $operator
     * @param mixed $logical
     * @return Where
     */
    public function addCondition(
        string|array|Reserved $column_or_array,
        string|array|Reserved|null $value,
        string $operator = "=",
        string $logical = "AND"
    ) {
        $condition = DB::condition($column_or_array, $value, $operator, $logical);
        if ($this->string) {
            $this->string .= "\n$logical $condition";
        } else {

            $this->string = "WHERE $condition";
        }

        return $this;
    }
}