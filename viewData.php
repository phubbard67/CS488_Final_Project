<?php

include "AttributeRepository.php";
include "LinksRepository.php";
include "CsObject.php";

//$xml_str = file_get_contents('sample.xml');
$xml_str = file_get_contents('dblp-data.xml');

//turn the xml string data into an object
$data = new SimpleXMLElement($xml_str);

$allObjects = [];
$allLinks = [];
$allAttributeGroups = [];

foreach ($data->OBJECTS->OBJECT as $object){
    $allObjects[] = new CsObject($object);
}

$linksRepository = new LinksRepository($data->LINKS->LINK);
$allLinks = $linksRepository->getAllLinks();
//foreach ($data->LINKS->LINK as $object){
//    $allLinks[] = new Link($object);
//}

$attributeRepository = new AttributeRepository($data->ATTRIBUTES->ATTRIBUTE);
$allAttributeGroups = $attributeRepository->getAllGroups();
//foreach ($data->ATTRIBUTES->ATTRIBUTE as $object){
//    $allAttributeGroups[] = new AttributeGroup($object);
//}


echo "                          Read-In The following: \n";
echo "------------------------------------------------------------------------\n";
echo "Objects: ".strval(count($allObjects))." \n";
echo "Links: ".strval(count($allObjects))." \n";
echo "Attribute Groups: ".strval(count($allAttributeGroups))." \n";
echo "------------------------------------------------------------------------\n\n";
echo "The Attribute Groups are:\n\n";
foreach ($allAttributeGroups as $attributeGroup){
    echo $attributeGroup->getGroupName()." with ".count($attributeGroup->getAttributeValues())." items inside"."\n\n";
}
echo "------------------------------------------------------------------------\n\n";

// Can un-comment the below for de-bugging if needed
//echo "The first 15 objects are:\n\n";
//foreach ($allObjects as $index => $object){
//    echo "\n\n\n";
//    var_dump($attributeRepository->getAllAttributesForItem(strval($object->getObjectId())));
//    var_dump($linksRepository->getAllLinksForItem(strval($object->getObjectId())));
//    if($index > 15) break;
//}

$allPeople = $attributeRepository->getAllPeople();
$allPapers = $attributeRepository->getAllPapers();

// Demo getting all links for a person
foreach ($allPeople as $index => $personId){
    echo "\n\n\n";
    $allLinksForPerson = $linksRepository->getAllLinksForItem(strval($personId));

    echo "Person #".$personId." has the following links: \n";

    foreach ($allLinksForPerson as $linkId => $linkedObjectId){
        echo $attributeRepository->getTypeOfItem($linkedObjectId)." with link ID ".$linkId." \n";
    }

    if($index > 10) break;
}

// Idea for constructing the data object:

// 1. Find all the people (done above)
// 2. Get all the links for the people
// 3. See what those links go to,
// 4. Get those objects and put them into one consolidated object
// 5. Send the consolidated object to DynamoDB


