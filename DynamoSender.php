<?php
require 'vendor/autoload.php';

// Below is taken primarily from AWS tutorial on Dynamo with PHP 
// See: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/GettingStarted.PHP.02.html

date_default_timezone_set('UTC');

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

class DynamoSender {
    public function __construct($table){
        $credentials = json_decode(file_get_contents('aws.credentials.json'), true);

        $this->credentials = $credentials;
        $this->sdk = new Aws\Sdk([
            'region'   => 'us-west-2',
            'version'  => 'latest',
            'credentials' => [
                'key'    => $credentials["AWS_KEY"],
                'secret' => $credentials["AWS_SECRET"],
            ],
        ]);

        $this->dynamodb = $this->sdk->createDynamoDb();
        $this->marshaler = new Marshaler();

        $this->tableName = $table;

    }


    function storeItemInDynamo($person){

        $params = [
            'TableName' => $this->tableName,
            'Item' =>  $this->marshaler->marshalItem($person)
        ];

        try {
            $result = $this->dynamodb->putItem($params);
            echo "Added item to Dynamo"."\n";
        } catch (DynamoDbException $e) {
            echo "Unable to add item to dynamo:\n";
            echo $e->getMessage() . "\n";
        }
    }


}







