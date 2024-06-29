<?php

namespace Resources\Database\Clause;

use Resources\Database\DB;
use Resources\Database\Reserved;

/**
 * Summary of Where
 */
class Join
{
    /**
     * Summary of string
     * @var string
     */
    public string $string = "";

    /**
     * Summary of join
     * @param string|array $table_or_array
     * @param string|array|Reserved $column1_or_array
     * @param string|Reserved $column2
     * @param string $operator
     * @param string $logical
     * @param string $type
     * @return Join
     */
    public function join(
        string|array $table_or_array,
        string|array|Reserved $column1_or_array,
        string|array|Reserved|null $column2 = null,
        string $operator = "=",
        string $logical = "AND",
        string $type = "INNER"
    ) {
        if (!is_array($table_or_array)) {
            $table_or_array = [[$table_or_array, $column1_or_array, $column2, $operator, $logical, $type]];
        }
        foreach ($table_or_array as $array) {
            $array[1] = $array[1] instanceof Reserved ? $array[1]->string : Reserved::word($array[1]);
            $array[2] = $array[2] instanceof Reserved ? $array[2]->string : Reserved::word($array[2]);
            $condition = DB::condition($array[1], $array[2], $array[3] ? $array[3] : $operator, $logical);

            $table = $array[0];
            $type = isset($array[5]) ? $array[5] : $type;

            $join = <<<join
            $type JOIN $table
            ON $condition
            join;

            $this->string = $this->string ? "{$this->string}\n$join" : $join;
        }

        return $this;
    }
}