<?php

namespace Resources\Database\Statement;

use Resources\Database\DB;
use Resources\Database\Clause\Where;

class Delete
{
    public string $table;

    private Where $whereobj;
    private string $limitstring = "";

    /**
     * Summary of __construct
     * @param string $table
     */
    public function __construct(string|false $table = false)
    {
        if ($table) {
            $this->table = $table;
        }
        $this->whereobj = new Where();
    }

    public function query()
    {
        $query = <<<query
        DELETE INTO $this->table
        {$this->whereobj->string}
        query;

        $queryp = preg_replace("/\R\R/", "\n", $query);
        while ($query != $queryp) {
            $query = $queryp;
            $queryp = preg_replace("/\R\R/", "\n", $query);
        }
        $query = preg_replace("/\r?\n;/", ";", $query);

        return $query;
    }

    public function execute(string $query = null)
    {
        if (is_null($query)) {
            $query = $this->query();
        }

        $return = DB::query($query);

        return $return;
    }

    public function where($column_or_array, $value, $operator = "=", $logical = "AND")
    {
        $this->whereobj->addCondition($column_or_array, $value, $operator = "=", $logical = "AND");

        return $this;
    }
}