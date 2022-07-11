<?php
namespace ModulePHP\Database;

class Select {
    private $DB;
    public $table;
    
    private $reservedwords = array(
        "sqldefault" => "DEFAULT",
        "sqlnull" => "NULL"
    );

    private $columnsarray = [];
    private $joinarray = [];
    private $wherearray = [];
    public $limit;

    function __construct($table= False){
        if ($table) {
            $this->table = $table;
        }
    }

    function query() {
        $columns = "";
        $where = "";
        $join = "";
        $limit = "";

        foreach ($this->columnsarray as $value) {
            if (is_null($value[1])) {
                $columns .= "$value[0],";
            } else {
                $columns .= "$value[0] AS '$value[1]',";
            }
        }

        foreach ($this->joinarray as $value) {
            $join .= <<<str
            $value[1] JOIN $value[0]
            ON $value[2] = $value[3]
            str;
            $join .= " ";
        }

        if (count($this->wherearray)) {
            $where .= "WHERE ";

            foreach ($this->wherearray as $value) {
                if (is_array($value[1])) {
                    $value[1] = substr(json_encode($value[1]), 1, -1);
                    DB::escape($value[0]);
                    DB::escape($value[1]);
                    $where .= <<<str
                    $value[0] IN ($value[1]) AND
                    str;
                    $where .= " ";
                } else {
                    $value[0] = DB::escape($value[0]);
                    $value[1] = DB::escape($value[1]);

                    if (is_string($value[1])) {
                        $where .= <<<str
                        $value[0] = "$value[1]" AND
                        str;
                        $where .= " ";
                    } else {
                        $where .= <<<str
                        $value[0] = $value[1] AND
                        str;
                        $where .= " ";
                    }
                }
            }
        }

        if ($this->limit) {
            $limit = "LIMIT $this->limit";
        }

        $columns = substr($columns, 0, -1);
        $where = substr($where, 0, -4);

        $query = <<<query
        SELECT
        $columns
        FROM $this->table
        $join
        $where
        $limit
        query;

        return $query;
    }

    function result($query = False) {
        if (!$query) {
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

    function column($column_or_array = null, $alias = null) {
        if (is_array($column_or_array)) {
            $return = [];
            foreach ($column_or_array as $value) {
                if (is_array($value) && count($value) == 2) {
                    array_push($this->columnsarray, $value);
                    array_push($return, True);
                } else if(is_array($value) && count($value) == 1){
                    array_push($this->columnsarray, [$value[0], null]);
                    array_push($return, True);
                } else if(!is_array($value) && $value){
                    array_push($this->columnsarray, [$value, null]);
                    array_push($return, True);
                } else {
                    array_push($return, False);
                }
            }
            return $return;
        } else {
            if(!is_null($column_or_array)) {
                array_push($this->columnsarray, [$column_or_array, $alias]);
                return True;
            }
            return False;
        }
    }

    function join($table_or_array = null, $type = null, $col1 = null, $col2 = null) {
        if (is_array($table_or_array)) {
            $return = [];
            foreach ($table_or_array as $value) {
                if (count($value) == 4) {
                    if (!is_null($value[0]) && !is_null($value[1]) && !is_null($value[2]) && !is_null($value[3])) {
                        array_push($this->joinarray, $value);
                        array_push($return, True);
                    } else {
                        array_push($return, False);
                    }
                    
                } else {
                    array_push($return, False);
                }
            }
            return $return;
        } else {
            if(!is_null($table_or_array) && !is_null($type) && !is_null($col1) && !is_null($col2)) {
                array_push($this->joinarray, [$table_or_array, $type, $col1, $col2]);
                return True;
            }
            return False;
        }
    }

    function where($column_or_array = null, $value = null) {
        if (is_array($column_or_array)) {
            $return = [];
            foreach ($column_or_array as $value) {
                if (is_array($value) && count($value) == 2) {
                    array_push($this->wherearray, $value);
                    array_push($return, True);
                } else {
                    array_push($return, False);
                }
            }
            return $return;
        } else {
            if(!is_null($column_or_array) && !is_null($value)) {
                array_push($this->wherearray, [$column_or_array, $value]);
                return True;
            }
            return False;
        }
    }
}