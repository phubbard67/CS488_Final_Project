<html>
 <head>
  <title>PHP Test</title>
 </head>
 <body>
 <?php 
    include "sample.php";
    $data = new SimpleXMLElement($xmlstr);
    //turns the data to json
    $jsonData = json_encode($xmlstr);

    //----------Taken from https://stackify.com/how-to-log-to-console-in-php/
    function console_log($output, $with_script_tags = true) {
        $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . ');';
        if ($with_script_tags) {
            $js_code = '<script>' . $js_code . '</script>';
        }
        echo $js_code;
    }
    //---------end taken from https://stackify.com/how-to-log-to-console-in-php/

    //------------------------------------------Examples
    //shows the object
    console_log($data);

    //shows the json output
    console_log($jsonData);

    //shows what a 'name' attribute object contains
    console_log($data->ATTRIBUTES->ATTRIBUTE[4]);

    //gets all the attributes for a particular xml object
    //example shows how to get the ITEM-TYPE, NAME and DATA-TYPE in a foreach 
    foreach($data->ATTRIBUTES->ATTRIBUTE[4]->attributes() as $a => $b)
    {
        $toPrint = $a . '="'. $b . "\"\n";
        console_log($toPrint);
    }

    //access attributes individualy
    //example gets the ITEM_TYPE for a particular ATTRIBUTE
    console_log($data->ATTRIBUTES->ATTRIBUTE[4]->attributes()->{'ITEM-TYPE'});
    
    //get a particular item from an xml object
    //this example gets one of the author's names in attribute 4
    console_log($data->ATTRIBUTES->ATTRIBUTE[4]->{'ATTR-VALUE'}[0]->{'COL-VALUE'});
    //----------------------------------------End Examples

 ?>
 </body>
</html>

