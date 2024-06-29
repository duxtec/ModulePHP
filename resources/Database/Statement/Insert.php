<?php

namespace Resources\Database\Statement;

use Resources\Database\DB;
use Resources\Database\Column;
use Resources\Database\Values;
use Resources\Database\Reserved;

/**
 * Summary of Insert
 */
class Insert
{
    public string $table;
    private Column $columnobj;
    private Values $valuesobj;
    public $ignorebool = false;

    /**
     * Summary of __construct
     * @param mixed $table
     */
    public function __construct($table = false)
    {
        if ($table) {
            $this->table = $table;
        }
        $this->columnobj = new Column();
        $this->valuesobj = new Values();
    }

    /**
     * Summary of into
     * @param string $table
     * @return Insert
     */
    public static function into(string $table)
    {
        return new self($table);
    }

    /**
     * Summary of column
     * @param string[] $column_or_array
     * @return Insert
     */
    public function column(string...$columns)
    {
        $this->columnobj->addColumn(...$columns);

        return $this;
    }

    public function ignore(bool $ignore = true)
    {
        $this->ignorebool = $ignore;

        return $this;
    }
    public function values(Reserved|string|array|int|null...$values)
    {
        $this->valuesobj->addValues(...$values);

        return $this;
    }

    public function query()
    {
        $columns = "({$this->columnobj->string})";
        $values = "{$this->valuesobj->string}";

        $ignore = $this->ignorebool ? "IGNORE" : "";

        $query = <<<query
        INSERT $ignore INTO  $this->table$columns
        VALUES $values
        query;

        return $query;
    }

    public function execute($con = false, $query = false)
    {

        if (!$query) {
            $query = $this->query();
        }

        return DB::query($query);
    }
}