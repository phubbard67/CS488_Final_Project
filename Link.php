<?php

class Link
{
    public function __construct($object){
        $this->object = $object;
        $this->obj1 = strval($this->getObject1Id());
        $this->obj2 = strval($this->getObject2Id());
        $this->link = [
            "id" => strval($this->getLinkId()),
            "obj1_id" => $this->obj1,
            "obj2_id" => $this->obj2
        ];
    }

    public function getLink(){
        return $this->link;
    }

    public function getLinkId(){
        return strval($this->object->attributes()->{'ID'});
    }
    public function getObject1Id(){
        return strval($this->object->attributes()->{'O1-ID'});
    }
    public function getObject2Id(){
        return strval($this->object->attributes()->{'O2-ID'});
    }

    public function isObjectPartOfLink($objectId){
        if($objectId === $this->obj1 || $objectId === $this->obj1)
            return true;
        else
            return false;
    }

}