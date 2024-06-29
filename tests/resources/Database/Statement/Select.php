<?php

namespace Resources\Database\Statement;

use Resources\Database\DB;
use Resources\Database\Column;
use Resources\Database\Clause\Where;
use Resources\Database\Clause\Join;

/**
 * Summary of Select
 * Future features
 * SELECT DISTINCT statement
 * SELECT INTO statement
 * NOT operator
 * BETWEEN operator
 * ORDER BY keyword
 * GROUP BY statement
 * LIMIT clause
 * UNION operator
 * UNION ALL operator
 */
class Select
{
    public string $table;

    private Column $columnobj;
    private Where $whereobj;
    private Join $joinobj;
    private string $limitstring = "";
    private string $orderstring = "";

    /**
     * Summary of __construct
     * @param string $table
     */
    public function __construct(string|false $table = false)
    {
        if ($table) {
            $this->table = $table;
        }
        $this->columnobj = new Column();
        $this->whereobj = new Where();
        $this->joinobj = new Join();
    }

    /**
     * Summary of from
     * @param string $table
     * @return Select
     */
    public static function from(string $table)
    {
        return new self($table);
    }

    /**
     * Summary of column
     * @param string|array[] $column_or_array
     * @return Select
     */
    public function column(string|array ...$column_or_array)
    {
        $this->columnobj->addColumn(...$column_or_array);

        return $this;
    }

    public function limit(int $offset = 1, int $row_count = null)
    {
        if (is_null($row_count)) {
            $row_count = $offset;
            $this->limitstring = "LIMIT $row_count";
        } else {
            $this->limitstring = "LIMIT $offset, $row_count";
        }

        return $this;
    }

    /**
     * Summary of join
     * @param mixed $table_or_array
     * @param mixed $column1_or_array
     * @param mixed $column2
     * @param mixed $operator
     * @param mixed $logical
     * @param mixed $type
     * @return Select
     */
    public function join($table_or_array, $column1_or_array, $column2 = null, $operator = "=", $logical = "AND", $type = "INNER")
    {
        $this->joinobj->join($table_or_array, $column1_or_array, $column2, $operator, $logical, $type);

        return $this;
    }

    /**
     * Summary of where
     * @param mixed $column_or_array
     * @param mixed $value
     * @param mixed $operator
     * @param mixed $logical
     * @return Select
     */
    public function where($column_or_array, $value, $operator = "=", $logical = "AND")
    {
        $this->whereobj->addCondition($column_or_array, $value, $operator, $logical);

        return $this;
    }

    /**
     * Summary of query
     * @return array|string|null
     */
    public function query()
    {
        $columns = $this->columnobj->string ? $this->columnobj->string : "*";
        $query = <<<query
        SELECT
        $columns
        FROM $this->table
        {$this->joinobj->string}
        {$this->whereobj->string}
        $this->limitstring
        query;

        $queryp = preg_replace("/\R\R/", "\n", $query);
        while ($query != $queryp) {
            $query = $queryp;
            $queryp = preg_replace("/\R\R/", "\n", $query);
        }
        $query = preg_replace("/\r?\n;/", ";", $query);

        return $query;
    }

    /**
     * Summary of result
     * @param string|null $query
     * @return array
     */
    public function result(string $query = null)
    {
        if (is_null($query)) {
            $query = $this->query();
        }

        $return = [];

        $result = DB::query($query);

        if ($result) {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                array_push($return, $row);
            }
        }

        return $return;
    }
}