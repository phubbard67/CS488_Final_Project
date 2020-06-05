<?php
require 'vendor/autoload.php';

// Below is taken primarily from AWS tutorial on Dynamo with PHP 
// See: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/GettingStarted.PHP.02.html

date_default_timezone_set('UTC');

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

$sdk = new Aws\Sdk([
    'endpoint'   => 'http://localhost:8000',
    'region'   => 'us-west-2',
    'version'  => 'latest'
]);

$dynamodb = $sdk->createDynamoDb();
$marshaler = new Marshaler();

$tableName = 'cs488-cloud-project';

// $movies = json_decode(file_get_contents('moviedata.json'), true);

echo "\n\nhere\n\n";

// foreach ($movies as $movie) {

//     $year = $movie['year']; 
//     $title = $movie['title'];
//     $info = $movie['info'];

//     $json = json_encode([
//         'year' => $year,
//         'title' => $title,
//         'info' => $info
//     ]);

//     $params = [
//         'TableName' => $tableName,
//         'Item' => $marshaler->marshalJson($json)
//     ];

//     try {
//         $result = $dynamodb->putItem($params);
//         echo "Added movie: " . $movie['year'] . " " . $movie['title'] . "\n";
//     } catch (DynamoDbException $e) {
//         echo "Unable to add movie:\n";
//         echo $e->getMessage() . "\n";
//         break;
//     }

// }

