<?php
require 'vendor/autoload.php';

// Below is taken primarily from AWS tutorial on Dynamo with PHP 
// See: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/GettingStarted.PHP.02.html

date_default_timezone_set('UTC');

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

$credentials = json_decode(file_get_contents('aws.credentials.json'), true);


$sdk = new Aws\Sdk([
    'region'   => 'us-west-2',
    'version'  => 'latest',
    'credentials' => [
        'key'    => $credentials["AWS_KEY"],
        'secret' => $credentials["AWS_SECRET"],
    ],
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

     $json = json_encode([
         'id' => "1",
         'name' => "test"
     ]);

     $params = [
         'TableName' => $tableName,
         'Item' => $marshaler->marshalJson($json)
     ];

     try {
         $result = $dynamodb->putItem($params);
         echo "Added somethinf"."\n";
     } catch (DynamoDbException $e) {
         echo "Unable to add movie:\n";
         echo $e->getMessage() . "\n";
     }

// }

