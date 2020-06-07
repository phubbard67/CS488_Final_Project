<?php
require 'vendor/autoload.php';

// Below is taken primarily from AWS tutorial on Dynamo with PHP 
// See: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/GettingStarted.PHP.02.html

date_default_timezone_set('UTC');

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

class DynamoSender {
    public function __construct(){
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

        $this->tableName = 'cs488-cloud-project';

    }


    function storePersonInDynamo($person){
//        $json = json_encode($person);

        $params = [
            'TableName' => 'cs488-cloud-project',
            'Item' =>  $this->marshaler->marshalItem($person)
//            'Item' => $this->marshaler->marshalJson($json)
        ];

        echo "here";
        try {
            $result = $this->dynamodb->putItem($params);
            echo "Added item"."\n";
        } catch (DynamoDbException $e) {
            echo "Unable to add movie:\n";
            echo $e->getMessage() . "\n";
        }
    }


}







