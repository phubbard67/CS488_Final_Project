<?php

class CsObject
{
    public function __construct($object){
        $this->object = $object;
        $this->id = strval($object->attributes()->{'ID'});
    }

    public function getObjectId(){
        return $this->id;
    }

}