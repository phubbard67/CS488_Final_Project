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


$allPapers = $attributeRepository->getAllPapers();
$dynamoPaperSender = new DynamoSender("cs488-papers");

$allPeopleWithNestedInfo = [];


// Idea for constructing the data object:

// 1. Find all the people (done above)
// 2. Get all the links for the people
// 3. See what those links go to,
// 4. Get those objects and put them into one consolidated object
// 5. Send the consolidated object to DynamoDB


echo "------------------------------------------------------------------------";
echo "\n\n\n PAPERS!!!!!! \n\n\n";
echo "------------------------------------------------------------------------\n\n";

//Loop through all people and get attributes
foreach ($allPapers as $index => $paperId){
    echo "\n\n\n";
    $allLinksForPaper = $linksRepository->getAllLinksForItem(strval($paperId));

    $paperInfo = [
        "id" => strval($paperId),
        "isInBooks" => false,
        "isInProceedings" => false,
        "isInJournals" => false,
        "authors" => [],
        "proceedings" => [],
        "www" => [],
        "books" => []
    ];

    // Add all the attributes of the "paper"
    $paperAttributes = $attributeRepository->getAllAttributesForItem(strval($paperId));
    foreach ($paperAttributes as $label => $value){
        $paperInfo[$label] = $value;
    }

    foreach ($allLinksForPaper as $linkId => $linkedObjectId){
        $linkBetweenObjects = $attributeRepository->getAllAttributesForItem($linkId);
        $linkedObject = $attributeRepository->getAllAttributesForItem($linkedObjectId);

        switch($linkedObject["object-type"]){
            case "person":
                $author = [];
                $author["id"] = $linkedObjectId;
                foreach ($linkedObject as $label => $value){
                    $author[$label] = $value;
                }

                $paperInfo["authors"][] = $author;
                break;


            case "proceedings":
                $proceedings = [];
                $proceedings["id"] = $linkedObjectId;
                foreach ($linkedObject as $label => $value){
                    $proceedings[$label] = $value;
                }

                $paperInfo["proceedings"][] = $proceedings;
                break;

            case "journal":
                $journal = [];
                $journal["id"] = $linkedObjectId;
                foreach ($linkedObject as $label => $value){
                    $journal[$label] = $value;
                }

                $paperInfo["journals"][] = $journal;
                break;

            case "www":
                $www = [];
                $www["id"] = $linkedObjectId;
                foreach ($linkedObject as $label => $value){
                    $www[$label] = $value;
                }

                $paperInfo["www"][] = $www;
                break;


            case "book":
                $book = [];
                $book["id"] = $linkedObjectId;
                foreach ($linkedObject as $label => $value){
                    $book[$label] = $value;
                }

                $paperInfo["books"][] = $book;
                break;
        }
    }



    $paperInfo["isInProceedings"] = count($paperInfo["proceedings"]) > 0;
    $paperInfo["isInJournals"] = count($paperInfo["journals"]) > 0;
    $paperInfo["isInBooks"] = count($paperInfo["books"]) > 0;

    // Uncomment to send to dynamo
    $dynamoPaperSender->storeItemInDynamo($paperInfo);

    echo "\nProcessing paper #".$index."\n";
//    echo json_encode($paperInfo)."\n";

    // To remove limit from queries - uncomment this line
    //    if($index > 10) break;
}



