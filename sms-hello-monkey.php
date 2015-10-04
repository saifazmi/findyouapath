<?php
    /* Include twilio-php, the official Twilio PHP Helper Library, 
    * which can be found at
    * http://www.twilio.com/docs/libraries
    */
 
include('Services/Twilio.php');

/* Read the contents of the 'Body' field of the Request. */
$body = $_REQUEST['Body'];

/* Remove formatting from $body until it is just lowercase 
characters without punctuation or spaces. */
$body = preg_replace("/[^A-Za-z0-9]/u", " ", $body);
$body = trim($body);
$pathquery = strtolower($body);
$pathquery = explode(" to ", $pathquery);

$pathdata = array(
    'from' => $pathquery[0],
    'to' => $pathquery[1]
);

$pathdata = json_encode(array('path' => $pathdata),  JSON_PRETTY_PRINT | JSON_FORCE_OBJECT);
 
header("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"; 
?>

<Response>
    <Message>    
    <?php 
        echo $pathdata;
    ?>        
    </Message>
</Response>
