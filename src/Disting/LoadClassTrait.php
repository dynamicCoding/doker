<?php

namespace Disting;

trait LoadClassTrait 
{
    public $data = array();
    
    protected function key($key){
        return array_key_exists($key, $this->data);
    }

    protected function exists($key)
    {
        return property_exists($this, $this->data[$key]);
    }

    protected function register($property, $class)
    {
        array_push($this->data, $property);

        $this->singleton($property, $class);
    }

    protected function singleton($key, $value)
    {
        try{
            if(!class_exists($value)){
                throw new \Exception('the class not exist'. $value);
            }
            
            if($this->key($key)){
            	throw new \Exception('la clave '.$key.'ya existe');
            }
            
            $create = function($c)use($key,$value){

                if(in_array($key, $this->data)){
                    $this->{$key} = new $c();
                }
            };
					
            return $create($value);

        }catch(\Exception $e){
            die($e->getMessage());
        }
    }
}