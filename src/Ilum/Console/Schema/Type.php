<?php

namespace Ilum\Console\Schema;

use Ilum\Ilum;

class Type
{
    public $engine;

    public $charset = 'utf8';

    public $collate = 'utf8_unicode_ci';

    protected $sql;

    protected $unique;

    protected $id;

    protected $ilum;

    public function __construct($action, $table)
    {
        $this->ilum = new Ilum;

        if(!is_null($action)) {
            $this->$action($table);
        }
    }

    protected function createTable($table)
    {
        $this->sql = 'CREATE TABLE IF NOT EXISTS '.$table.'(';
    }

    public function id($id = 'id')
    {
        $this->id = $id;

        return $this;
    }

    public function unique($uniq, $varchar = 250, $notnull = 'NOT NULL', $name = '')
    {
        $this->sql .= ', `'.$uniq.'` VARCHAR('.$varchar.') '.$notnull;

        $this->unique = [$uniq, $name];

        return $this;
    }

    public function autoincrement()
    {
        if(isset($this->id)){
            $this->sql .= '`'.$this->id.'` INT NOT NULL AUTO_INCREMENT';
        }elseif(isset($this->integers)){
            $this->sql .= '`'.$this->integers.'` INT NOT NULL AUTO_INCREMENT';
        }

        return $this;
    }

    public function string($string, $varchar = 250, $not = 'NOT NULL')
    {
        if(!is_string($string)){

        }
        $this->sql .= ', `'.$string.'` VARCHAR('.$varchar.') '.$not;

        return $this;
    }

    public function char($name, $length = 10, $not = 'NOT NULL')
    {
        $this->sql .= ', `'.$name.'` CHAR('.$length.') '.$not;;

        return $this;
    }

    public function text($name, $not = 'NOT NULL')
    {
        $this->sql .= ', `'.$name.'` TEXT '.$not;

        return $this;
    }

    public function integers($name, $int = 11, $not = 'NOT NULL')
    {
        $this->sql .= ', `'.$name.'` INT('.$int.') '.$not;

        return $this;
    }

    public function date($name, $not = 'NOT NULL')
    {
        $this->sql .= ', `'.$name.'` DATE '.$not;

        return $this;
    }

    public function dateTime($name, $not = 'NOT NULL')
    {
        $this->sql .= ', `'.$name.'` DATETIME '.$not;

        return $this;
    }

    public function timeStamp($name, $not = 'NOT NULL')
    {
        $this->sql .= ', `'.$name.'` TIMESTAMP '.$not;

        return $this;
    }

    /**
    * save new table
    */
    public function save()
    {
        if(!empty($this->id)){
            $this->sql .= ', PRIMARY KEY(`'.$this->id.'`)';
        }
        if(!empty($this->unique[0])) {
            $this->sql .= ', UNIQUE ';
            $this->sql .= !empty($this->unique[1]) ? "`{$this->unique[1]}` " : '';
            $this->sql .='(`'.$this->unique[0].'`)';
        }
        $this->sql .= ') ENGINE = '.$this->engine.' CHARACTER SET '.$this->charset.' COLLATE '.$this->collate;

        if(empty($this->id) && $two = substr(strstr($this->sql, ','), 1)) {
            $one = strstr($this->sql, ',', true);
            $this->sql = $one.$two;
        }

        $this->ilum->migration($this->sql);
    }

    /**
    * @param $table
    */
    public function down($table)
    {
        $this->ilum->migration('DROP TABLE '.$table);
    }

    /**
    * @param $table
    */
    public function truncate($table)
    {
        $this->ilum->migration('TRUNCATE '.$table);
    }

    public function rollBack()
    {

    }
}