<?php
require 'vendor/autoload.php';

date_default_timezone_set('UTC');

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

$credentials = json_decode(file_get_contents('aws.credentials.json'), true);

use Aws\DynamoDb\DynamoDbClient;

$client = DynamoDbClient::factory(array(
    'region'   => 'us-west-2',
    'version'  => 'latest',
    'credentials' => [
        'key'    => $credentials["AWS_KEY"],
        'secret' => $credentials["AWS_SECRET"],
    ],
    'http'     => [
        'verify' => 'C:\wamp64\www\CS488_Final_Project\cacert.pem'
    ]
));

$iterator = $client->getIterator('Query', array(
    'TableName'     => 'cs488-papers',
    'KeyConditions' => array (
        'id' => array (
            'AttributeValueList' => array(
                array('S' => '101037')
            ),
            'ComparisonOperator' => 'EQ'
        )
    )
));

// Each item will contain the attributes we added
foreach ($iterator as $item) {
    // Grab the time number value
    echo $item['title']['S'] . "\n";
}