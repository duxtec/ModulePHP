<?php
namespace ModulePHP\Database;

class Insert {
    private $DB;
    public $table;
    
    private $reservedwords = array(
        "sqldefault" => "DEFAULT",
        "sqlnull" => "NULL"
    );

    public $defaultvalue = "null";

    private $columnsarray = [];
    private $valuesarray = [];
    public $ignore = false;

    function __construct($table= False){
        if ($table) {
            $this->table = $table;
        }
        $this->DB = new DB;
    }

    function query() {
        $columns = "(".implode(",", $this->columnsarray).")";
        
        $values = "";

        foreach ($this->valuesarray as $value_list) {
            $valuesstring = "";
            foreach ($value_list as $value) {
                if(isset($value)){
                    if (isset($this->reservedwords[strtolower($value)])) {

                        $value = $this->reservedwords[strtolower($value)];

                        $valuesstring .= <<<VALUES
                        {$value},
                        VALUES;
                    } else if (is_string($value)) {
                        $value = $this->DB->escape($value);
                        $valuesstring .= <<<VALUES
                        "{$value}",
                        VALUES;
                    } else {
                        $value = $this->DB->escape($value);
                        $valuesstring .= <<<VALUES
                        {$value},
                        VALUES;
                    }
                } else {
                    $valuesstring .= "$this->defaultvalue,";
                }
            }

            $valuesstring = substr($valuesstring, 0, -1);
            $values .= "($valuesstring),";
        }


        $values = substr($values, 0, -1);

        $ignore = $this->ignore? "IGNORE":"";

        $query = <<<query
        INSERT $ignore INTO  $this->table$columns
        VALUES $values
        query;

        return $query;
    }

    function execute($con = False, $query = False) {

        if (!$query) {
            $query = $this->query();
        }

        if ($result = $this->DB->query($query)) {
            $return = $this->DB->affected_rows;
        } else {
            $return = False;
        }

        if (!$con) {
            $this->DB->close();
        }

        return $return;
    }

    function column($column_or_array) {
        if (is_array($column_or_array)) {
            $return = [];
            foreach ($column_or_array as $value) {
                if (is_string($value)) {
                    array_push($this->columnsarray, $value);
                    array_push($return, True);
                } else {
                    array_push($return, False);
                }
            }
            return $return;
        }
        
        if (is_string($value)) {
            array_push($this->columnsarray, $value);
            return True;
        }
        
        return False;
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

    function values($array) {
        if (is_array($array)) {
            $return = [];
            
            foreach ($array as $value_list) {
                array_push($return, $this->value($value_list)); 
            }

            return $return;
        }

        return False;
    }
}