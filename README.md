# CS488_Final_Project
This is a DynamoDB / PHP app using the DBLP dateset. 

## Installation 

1. Run `composer install` to pull down the AWS SDK and allow the PHP scripts to execute normally. 
2. Put AWS credentials into a `aws.credentials` file, following the same format as in `aws.credentials.example`. 

## Loading / Viewing the Data

To view the data, run `php -d memory_limit=8192M viewData.php`

Note that this process is very memory intensive and needs around 5Gb to run. 


