<?php
namespace ModulePHP\Database;

class Update {
    public $table;
    
    private $reservedwords = array(
        "sqldefault" => "DEFAULT",
        "sqlnull" => "NULL"
    );

    public $defaultvalue = "null";

    private $columnsvaluesarray = [];
    private $wherearray = [];
    public $ignore = false;

    function __construct($table= False){
        if ($table) {
            $this->table = $table;
        }
    }

    function query() {
        $set = "";
        $where = "";

        if (count($this->columnsvaluesarray)) {
            foreach ($this->columnsvaluesarray as $value) {
                if (is_string($value[0])) {
                    if (is_string($value[1])) {
                        $value[0] = DB::escape($value[0]);
                        $value[1] = DB::escape($value[1]);
                        $set .= <<<str
                        $value[0] = "$value[1]",
                        str;
                    } else {
                        $value[0] = DB::escape($value[0]);
                        $value[1] = DB::escape($value[1]);
                        $set .= <<<str
                        $value[0] = $value[1],
                        str;
                    }
                }
            }
            $set .= substr($set, 0, -1);
        }

        if (count($this->wherearray)) {
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
            $where = substr($where, 0, -4);
        }

        $query = <<<query
        UPDATE $this->table
        SET $set
        WHERE $where
        query;

        return $query; 
    }

    function execute($query = False) {

        if (!$query) {
            $query = $this->query();
        }

        if ($result = DB::query($query)) {
            $return = $result;
        } else {
            $return = False;
        }

        return $return;
    }

    function value($value_list) {
        if (is_array($value_list)) {
            if (count($value_list) == count($this->columnsarray)) {
                array_push($this->valuesarray, $value_list);
                return True;
            }
        }

        return False;
    }

    function column($column_or_array = null, $value = null) {
        if (is_array($column_or_array)) {
            $return = [];
            foreach ($column_or_array as $value) {
                if (is_array($value) && count($value) == 2) {
                    array_push($this->columnsvaluesarray, $value);
                    array_push($return, True);
                } else {
                    array_push($return, False);
                }
            }
            return $return;
        } else {
            if(!is_null($column_or_array) && !is_null($value)) {
                array_push($this->columnsvaluesarray, [$column_or_array, $value]);
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