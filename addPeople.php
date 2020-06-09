<?php

include "AttributeRepository.php";
include "LinksRepository.php";
include "CsObject.php";
include "DynamoSender.php";

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
//$allLinks = $linksRepository->getAllLinks();

$attributeRepository = new AttributeRepository($data->ATTRIBUTES->ATTRIBUTE);
$allAttributeGroups = $attributeRepository->getAllGroups();



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


$allPeople = $attributeRepository->getAllPeople();
$dynamoPeopleSender = new DynamoSender("cs488-people");

$allPeopleWithNestedInfo = [];


// Idea for constructing the data object:

// 1. Find all the people (done above)
// 2. Get all the links for the people
// 3. See what those links go to,
// 4. Get those objects and put them into one consolidated object
// 5. Send the consolidated object to DynamoDB



//Loop through all people and get attributes
foreach ($allPeople as $index => $personId){


    if($index < 200000 || $index > 220000){ continue; }

    echo "\nProcessing person #".$index." with id# ".$personId."\n";

    $allLinksForPerson = $linksRepository->getAllLinksForItem(strval($personId));

    $personInfo = [
        "id" => strval($personId),
        "papers" => [],
        "proceedings" => [],
        "www" => [],
        "mthesis" => [],
        "phdthesis" => [],
        "books" => []
    ];

    $personAttributes = $attributeRepository->getAllAttributesForItem(strval($personId));
    foreach ($personAttributes as $label => $value){
        $personInfo[$label] = $value;
    }

    foreach ($allLinksForPerson as $linkId => $linkedObjectId){
        echo $attributeRepository->getTypeOfItem($linkedObjectId)." with link ID ".$linkId." \n";

        $linkBetweenObjects = $attributeRepository->getAllAttributesForItem($linkId);
        $linkedObject = $attributeRepository->getAllAttributesForItem($linkedObjectId);

        switch($linkedObject["object-type"]){
            case "paper":
                $paper = [];
                $paper["id"] = $linkedObjectId;
                foreach ($linkedObject as $label => $value){
                    $paper[$label] = $value;
                }

                if(array_key_exists("link-type", $linkBetweenObjects)){
                    $paper["isAuthor"] = $linkBetweenObjects["link-type"] === "author-of";
                    $paper["isEditor"] = $linkBetweenObjects["link-type"] === "editor-of";
                }

                $personInfo["papers"][] = $paper;
                break;

            case "proceedings":
                $proceedings = [];
                $proceedings["id"] = $linkedObjectId;
                foreach ($linkedObject as $label => $value){
                    $proceedings[$label] = $value;
                }

                if(array_key_exists("link-type", $linkBetweenObjects)){
                    $proceedings["isAuthor"] = $linkBetweenObjects["link-type"] === "author-of";
                    $proceedings["isEditor"] = $linkBetweenObjects["link-type"] === "editor-of";
                }

                $personInfo["proceedings"][] = $proceedings;
                break;

            case "www":
                $www = [];
                $www["id"] = $linkedObjectId;
                foreach ($linkedObject as $label => $value){
                    $www[$label] = $value;
                }

                if(array_key_exists("link-type", $linkBetweenObjects)){
                    $www["isAuthor"] = $linkBetweenObjects["link-type"] === "author-of";
                    $www["isEditor"] = $linkBetweenObjects["link-type"] === "editor-of";
                }

                $personInfo["www"][] = $www;
                break;

            case "msthesis":
                $mthesis = [];
                $mthesis["id"] = $linkedObjectId;
                foreach ($linkedObject as $label => $value){
                    $mthesis[$label] = $value;
                }

                if(array_key_exists("link-type", $linkBetweenObjects)){
                    $mthesis["isAuthor"] = $linkBetweenObjects["link-type"] === "author-of";
                    $mthesis["isEditor"] = $linkBetweenObjects["link-type"] === "editor-of";
                }

                $personInfo["mthesis"][] = $mthesis;
                break;
            case "phdthesis":
                $phdthesis = [];
                $phdthesis["id"] = $linkedObjectId;
                foreach ($linkedObject as $label => $value){
                    $phdthesis[$label] = $value;
                }

                if(array_key_exists("link-type", $linkBetweenObjects)){
                    $phdthesis["isAuthor"] = $linkBetweenObjects["link-type"] === "author-of";
                    $phdthesis["isEditor"] = $linkBetweenObjects["link-type"] === "editor-of";
                }

                $personInfo["phdthesis"][] = $phdthesis;
                break;
            case "book":
                $book = [];
                $book["id"] = $linkedObjectId;
                foreach ($linkedObject as $label => $value){
                    $book[$label] = $value;
                }

                if(array_key_exists("link-type", $linkBetweenObjects)){
                    $book["isAuthor"] = $linkBetweenObjects["link-type"] === "author-of";
                    $book["isEditor"] = $linkBetweenObjects["link-type"] === "editor-of";
                }

                $personInfo["books"][] = $book;
                break;
        }
    }

    // Uncomment to send to dynamo
    $dynamoPeopleSender->storeItemInDynamo($personInfo);

//    echo "\n".json_encode($personInfo)."\n";

}

