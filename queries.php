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

//Query 3: Co-author distance: At what level is Moshe Vardi from Michael J. Franklin?
$moshes = $client->getIterator('Query', array(
    'TableName'     => 'cs488-people',
    'KeyConditions' => array (
        'id' => array (
            'AttributeValueList' => array(
                array('S' => '729526')
            ),
            'ComparisonOperator' => 'EQ'
        )
    )
));

$franklins = $client->getIterator('Query', array(
    'TableName'     => 'cs488-people',
    'KeyConditions' => array (
        'id' => array (
            'AttributeValueList' => array(
                array('S' => '747452')
            ),
            'ComparisonOperator' => 'EQ'
        )
    )
));

// $frankPapers = $client->query(array (
//     'TableName' => 'cs488-people',
//     'IndexName' => 'id',
//     'Select'    => 'ALL_ATTRIBUTES',
//     'KeyConditions' => array (
//         'id' => array (
//             'AttributeValueList' => array(
//                 array('S' => '747452')
//             ),
//             'ComparisonOperator' => 'EQ'
//         )
//     )

// ));

// var_dump($frankPapers);

// Each item will contain the attributes we added
foreach ($moshes as $moshe) {
    $i = 0;
    // Grab the time number value
    echo $moshe['name']['S'] . "\n";
    foreach ($franklins as $franklin) {
        // Grab the time number value
      echo $franklin['papers => title']['S'];
    }
}

