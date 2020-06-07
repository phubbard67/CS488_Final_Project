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

$year = 2015;
$title = 'The Big New Movie';

$item = $marshaler->marshalJson('
    {
        "id":"5",
        "year": ' . $year . ',
        "title": "' . $title . '",
        "info": {
            "plot": "Nothing happens at all.",
            "rating": 0
        }
    }
');

$params = [
    'TableName' => 'cs488-cloud-project',
    'Item' => $item
];


try {
    $result = $dynamodb->putItem($params);
    echo "Added item: $year - $title\n";

} catch (DynamoDbException $e) {
    echo "Unable to add item:\n";
    echo $e->getMessage() . "\n";
}

// }
