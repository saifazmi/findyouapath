<?php
    /* Include twilio-php, the official Twilio PHP Helper Library, 
    * which can be found at
    * http://www.twilio.com/docs/libraries
    */
 
include('Services/Twilio.php');

function getPathData() {
    /* Read the contents of the 'Body' field of the Request. */
    $body = $_REQUEST["Body"];
    
    /*
    $city = "Birmingham";
    $state = "West Midlands";
    $zip = "B297AE";
    $country = "UK"
    
    echo $body;
    echo $city;
    echo $state;
    echo $zip;
    echo $country;
    echo $from;
    */
    
    /* Remove formatting from $body until it is just lowercase 
    characters without punctuation or spaces. */
    $body = preg_replace("/[^A-Za-z0-9]/u", " ", $body);
    $body = trim($body);
    $pathquery = strtolower($body);
    
    /* Spliting the query in FROM and TO */
    $pathquery = explode(" to ", $pathquery);
    
    /* Creating a JSON object of path data */
    $pathdata = array(
        "from" => str_replace(" ","+",$pathquery[0]),
        "to" => str_replace(" ","+",$pathquery[1])
    );
    $pathdata = json_encode(array("findpath" => $pathdata),  JSON_PRETTY_PRINT | JSON_FORCE_OBJECT);
    
    //return $pathdata;
}

function googleMagic($origin, $destination) {
    /* Google Directions API */
    $apiCallURL = "https://maps.googleapis.com/maps/api/directions/json?";
    //$apiCallURL .= "origin=".urlencode($origin);
    //$apiCallURL .= "&destination=".urlencode($destination);
    $apiCallURL .= "origin=26+dawlish+road,birmingham";
    $apiCallURL .= "&destination=bull+ring,birmingham";
    $apiCallURL .= "&region=uk";
    $apiCallURL .= "&key=AIzaSyAVnFbRJ8vu79913sZlOeacRJn9bNEXpoQ";
    
    return strval(file_get_contents($apiCallURL));
}


function findYouAPath() {
    /* Get the path data */
    $findyouapath = getPathData();
    $findyouapath = json_decode($findyouapath, true);
    $findyouapath = $findyouapath["findpath"];
    
    $origin = $findyouapath[from];
    $destination = $findyouapath[to];
    
    /*
    echo $origin;
    echo "\n";
    echo $destination;
    */
    echo googleMagic($origin, $destination);
}

header("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"; 
?>

<Response>
    <Message>
        <?php
            //getPathData();
            //echo strval(getPathData());
            findYouAPath();
        ?>
    </Message>
</Response>
