<?php
namespace ModulePHP\Database;

class Delete {
    private $DB;
    public $table;

    function __construct($table= False){
        if ($table) {
            $this->table = $table;
        }
        $this->DB = new DB;
    }

    function query() {

        $where = "";

        if (count($this->wherearray)) {
            foreach ($this->wherearray as $value) {
                if (is_array($value[1])) {
                    $value[1] = substr(json_encode($value[1]), 1, -1);
                    $this->DB->escape($value[0]);
                    $this->DB->escape($value[1]);
                    $where .= <<<str
                    $value[0] IN ($value[1]) AND
                    str;
                    $where .= " ";
                } else {
                    $value[0] = $this->DB->escape($value[0]);
                    $value[1] = $this->DB->escape($value[1]);

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
        DELETE INTO $this->table
        WHERE $where
        query;
    }

    function execute($con = False, $query = False) {

        if (!$query) {
            $query = $this->query();
        }

        $result = $this->DB->query($query);

        if (!$con) {
            $this->DB->close();
        }

        return $result?$this->DB->affected_rows:False;
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