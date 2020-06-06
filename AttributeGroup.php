<?php

class AttributeGroup
{
    public function __construct($object){
        $this->object = $object;
        $this->name = strval($this->object->attributes()->{'NAME'});
        $this->allAttributes = $this->getAttributeValues();
    }

    public function getGroupType(){
        return strval($this->object->attributes()->{'ITEM-TYPE'});
    }
    public function getDataType(){
        return strval($this->object->attributes()->{'DATA-TYPE'});
    }
    public function getGroupName(){
        return strval($this->object->attributes()->{'NAME'});
    }

    // Returns a array of attributes
    public function getAttributeValues(){

        $attributeList = array();

        foreach ($this->object->children() as $attribute){
            if(array_key_exists(strval($attribute["ITEM-ID"]), $attributeList)){
                echo "\n\n------------------------------------------------------------------------\n";
                echo "\n\nThere was a duplicate object attribute in the attributes section of the data.\n";
                echo " Aborting as this will likely cause incorrect results.\n\n";
                echo "------------------------------------------------------------------------\n";
                exit();
            }
            $attributeList[strval($attribute["ITEM-ID"])] = strval($attribute->{"COL-VALUE"});
        }

        return $attributeList;
    }

    public function getAttributeForItemId($itemId){
        $foundAttribute = array_key_exists($itemId, $this->allAttributes) ? $this->allAttributes[$itemId] : null;
        return array($this->name, $foundAttribute);
    }
}