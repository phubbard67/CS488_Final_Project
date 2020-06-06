<?php

include "AttributeGroup.php";


class AttributeRepository
{
    public function __construct($rawAttributes){

        // This includes groups such as name, publisher, school, type, isbn, etc...
        $this->attributeGroups = [];

        foreach ($rawAttributes as $object){
            $newGroup = new AttributeGroup($object);
            $this->attributeGroups[] = $newGroup;

            //If this is the object type group - hold onto it too
            if($newGroup->getGroupName() === "object-type")
                $this->indexGroup = $newGroup;
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

    public function getAllPeople(){
        $allPeople = [];
        foreach ($this->indexGroup->getAttributeValues() as $itemId => $itemType){
            if($itemType === "person")
                $allPeople[] = $itemId;
        }
        return $allPeople;
    }

    public function getAllPapers(){
        $allPapers = [];
        foreach ($this->indexGroup->getAttributeValues() as $itemId => $itemType){
            if($itemType === "paper")
                $allPapers[] = $itemId;
        }
        return $allPapers;
    }

    public function getTypeOfItem($itemId){
        return $this->indexGroup->getAttributeForItemId(strval($itemId))[0];
    }

}