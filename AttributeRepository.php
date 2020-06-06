<?php

include "AttributeGroup.php";


class AttributeRepository
{
    public function __construct($rawAttributes){

        // This includes groups such as name, publisher, school, type, isbn, etc...
        $this->attributeGroups = [];

        foreach ($rawAttributes as $object){
            $this->attributeGroups[] = new AttributeGroup($object);
        }
    }

    public function getAllGroups(){
        return $this->attributeGroups;
    }

    public function getAllAttributesForItem($itemId){
        $itemAttributes = [];
        foreach ($this->attributeGroups as $group){
            $result = $group->getAttributeForItemId($itemId);
            if($result[1] !== null)
                $itemAttributes[$result[0]] = $result[1];
        }
        return $itemAttributes;
    }

}