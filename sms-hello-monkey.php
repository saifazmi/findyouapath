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
    
    // return $pathdata;
}

function googleMagic($origin, $destination) {
    /* Google Directions API */
    $apiCallURL = "https://maps.googleapis.com/maps/api/directions/json?";
    //$apiCallURL .= "origin=".urlencode($origin);
    //$apiCallURL .= "&destination=".urlencode($destination);
    
    //$apiCallURL .= "origin=26+dawlish+road+birmingham";
    //$apiCallURL .= "&destination=bull+ring,birmingham";
    
    $apiCallURL .= "origin=B297AE";
    $apiCallURL .= "&destination=B152QX";
    
    $apiCallURL .= "&mode=walking";
    $apiCallURL .= "&region=uk";
    $apiCallURL .= "&key=AIzaSyAVnFbRJ8vu79913sZlOeacRJn9bNEXpoQ";
    
    return file_get_contents($apiCallURL);
}

function getPathMsg() {
    $pathJSON = googleMagic();
    echo $pathJSON;
    $pathJSON = json_decode($pathJSON, true);
    $routes = $pathJSON["routes"];
    $legs = $routes[0]["legs"];
    
    $distance = $legs[0]["distance"]["text"];
    $duration = $legs[0]["duration"]["text"];
    
    echo "Distance: $distance";
    echo "\nTime: $duration";
    
    //$steps = legs[0]["steps"];
    
    /*
    for($i = 0; $i < count(steps); $i++) {
        $step = steps[$i]["html_instructions"];
        $step = preg_replace("/[\x00-\x1F\x80-\xFF]/", "", $step);
        echo "\n$step\n";
    }*/
    
    /*
    for removing all the unicode in html instructions
    $string = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $string); */
}

function findYouAPath() {
    /* Get the path data */
    $findyouapath = getPathData();
    $findyouapath = json_decode($findyouapath, true);
    $findyouapath = $findyouapath["findpath"];
    
    $origin = $findyouapath["from"];
    $destination = $findyouapath["to"];
    
    /*
    echo $origin;
    echo "\n";
    echo $destination;
    */
    googleMagic($origin, $destination);
    getPathMsg();
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
