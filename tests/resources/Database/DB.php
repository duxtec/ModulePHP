<?php

namespace Resources\Database;

use mysqli;
use Exception;
use stdClass;

class Con extends mysqli
{
    public $config;
    public function __construct()
    {
        global $M;
        $this->config = $M->Config->database;

        $host = $this->config->HOST;
        $user = $this->config->USER;
        $pw = $this->config->PW;
        $name = $this->config->NAME;
        $port = $this->config->PORT;

        if ($host && $user && $pw && $name) {
            parent::__construct($host, $user, $pw, $name, $port);
            if ($this->connect_error) {
                throw new Exception("Database connection error");
            } else {
                $this->set_charset("utf8");
            }
        } else {
            throw new Exception("Database not defined");
        }
    }
}

/**
 * Summary of DB
 */
class DB
{
    /**
     * Summary of status
     * @var
     */
    public $status;

    /**
     * Summary of escape
     * @param mixed $string
     * @return string
     */
    public static function escape($string)
    {
        $con = new Con();
        $string = $string === true ? "True" : ($string === false ? "False" : $string);
        $string = $con->real_escape_string($string);
        $con->close();
        return $string;
    }

    /**
     * Summary of query
     * @param mixed $query
     * @throws Exception
     * @return \mysqli_result|bool
     */
    public static function query($query)
    {
        $con = new Con();
        $result = $con->query($query);
        $affected_rows = $con->affected_rows;

        if ($affected_rows > 0 && stripos(trim($query), 'INSERT') === 0) {
            $insert_id = $con->insert_id;
            $con->close();
            return $insert_id;
        }

        $con->close();
        if ($result === false) {
            throw new Exception($con->error);
        }

        $return = is_bool($result) ? $affected_rows : $result;

        return $return;
    }

    /**
     * Summary of condition
     * @param mixed $column_or_array
     * @param mixed $value
     * @param string $operator
     * @param string $logical
     * @return string
     */
    public static function condition($column_or_array, $value = null, $operator = "=", $logical = "AND")
    {

        if (is_array($column_or_array)) {
            foreach ($column_or_array as $array) {
                $column = $array[0];
                $value = $array[1];
                $operator = $array[2] ? $array[2] : $operator;

                $onecondition = self::onecondition($column, $value, $operator);

                if (isset($condition)) {
                    $condition .= "$operator $onecondition";
                } else {
                    $condition = $onecondition;
                }
            }
        } else {
            $column = $column_or_array;
            $condition = self::onecondition($column, $value, $operator);
        }

        return $condition;

    }

    /**
     * Summary of ModulePHP\Database\onecondition
     * @param mixed $column
     * @param mixed $value
     * @param string $operator
     * @return string
     */
    private static function onecondition($column, $value, $operator = "=")
    {
        $column = $column instanceof Reserved ? $column->string : DB::escape($column);

        if (is_array($value)) {
            $null = "";
            foreach ($value as $content) {
                if (is_null($content)) {
                    $null = "$column IS NULL OR ";
                } else {
                    $content = DB::value($content);
                    $valuestring = isset($valuestring) ? "$valuestring, $content" : $content;
                }
            }
            $condition = "$null$column IN ($valuestring)";
        } else {
            if (is_null($value)) {
                $operator = strtoupper($operator) == "IS NOT" ? $operator : "IS";
                $value = "NULL";
            }
            $value = self::value($value);
            $condition = "$column $operator $value";
        }

        return $condition;
    }

    public static function value($value)
    {
        if (is_string($value)) {
            $value = DB::escape($value);
            $value = "\"$value\"";
        } elseif (is_null($value)) {
            $value = "NULL";
        } elseif ($value instanceof Reserved) {
            $value = $value->string;
        }
        return $value;
    }
}
class Reserved
{
    public $string = "";

    public function __construct(string $string)
    {
        $this->string = $string;
    }
    public static function word(string $word)
    {
        $word = is_null($word) ? "NULL" : $word;
        return new self($word);
    }
}

class Column
{
    public string $string = "";
    public function addColumn(Reserved|string|array ...$column_or_array)
    {
        $count = count($column_or_array);
        foreach ($column_or_array as $key => $column) {
            $separator = $key === $count - 1 ? "" : ",\n";
            if (is_array($column)) {
                $this->string .= "$column[0] AS $column[1]$separator";
            } else {
                $this->string .= "$column$separator";
            }
        }
        return $this;
    }
}

class Values
{
    public string $string = "";
    public function addValues(Reserved|string|array|int|null ...$values)
    {
        $string = "";
        $count = count($values);
        foreach ($values as $key => $value) {
            $separator = $key === $count - 1 ? "" : ",\n";
            if (is_string($value)) {
                $value = DB::escape($value);
                $value = "\"$value\"";
            } elseif (is_null($value)) {
                $value = "NULL";
            } elseif ($value instanceof Reserved) {
                $value = $value->string;
            }
            $string .= "$value$separator";
        }

        $this->string = $this->string ?
            "{$this->string}, ($string)" :
            "($string)";

        return $this;
    }
}