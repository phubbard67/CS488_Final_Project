<?php
require 'vendor/autoload.php';

// Below is taken primarily from AWS tutorial on Dynamo with PHP 
// See: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/GettingStarted.PHP.02.html

date_default_timezone_set('UTC');

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

$credentials = json_decode(file_get_contents('aws.credentials.json'), true);

$credentials = $credentials;
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

$tableName = "cs488-papers";

$eav = $marshaler->marshalJson('
    {
        ":id": 101037
    }
');

$params = [
    'TableName' => $tableName,
    'KeyConditionExpression' => '#id = :id',
    'ExpressionAttributesNames' => [ '#id' => 'id'],
    'ExpressionAttributeValues' => $eav
];

echo "Querying for paper with id 101037.\n";

try {
    $result = $dynamodb->query($params);

    echo "Query succeeded.\n";

    foreach ($result['Items'] as $papers){
        echo $marshaler->unmarshalValue($papers['title']);
    }
} catch (DynamoDbException $e) {
    echo "Unable to query:\n";
    echo $e->getMessage() . "\n";
}

