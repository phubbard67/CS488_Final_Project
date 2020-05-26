<html>
 <head>
  <title>PHP Test</title>
 </head>
 <body>
 <?php 
    include sample.php;
    $data = new SimpleXMLElement($xmlstr);
    echo $data;

 ?>
 </body>
</html>

