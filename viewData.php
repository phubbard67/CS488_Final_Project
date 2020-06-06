<?php

include "AttributeRepository.php";
include "LinksRepository.php";
include "CsObject.php";

$xml_str = file_get_contents('sample.xml');
//$xml_str = file_get_contents('dblp-data.xml');

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
echo "The first 15 objects are:\n\n";
foreach ($allObjects as $index => $object){
    echo "\n\n\n";
    var_dump($attributeRepository->getAllAttributesForItem(strval($object->getObjectId())));
    var_dump($linksRepository->getAllLinksForItem(strval($object->getObjectId())));
//    echo $attributeGroup->getGroupName()." with ".count($attributeGroup->getAttributeValues())." items inside"."\n\n";
    echo "hi";
    if($index > 15) break;
}





//    $z = $object[4]->{'ATTR-VALUE'}[0];
//    echo "\n\n";
////    echo $object[4]->{'ATTR-VALUE'}[0]->{'COL-VALUE'};
//    echo $z->attributes();
//    var_dump($object);
//
//    echo "\n\nhi";
//    var_dump($object[4]->{'ATTR-VALUE'}[0]);
//    var_dump($object[4]->{'ATTR-VALUE'}[0]->{'COL-VALUE'});
//    var_dump($object[4]->{'ATTR-VALUE'}[0]->{'ITEM-ID'});
//    echo "la-ID: ".$object[4]->attributes()->{'O2-ID'}."\n";