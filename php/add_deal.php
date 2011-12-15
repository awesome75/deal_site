<?
// add_deal.php
// this will be the script that handles adding a deal
require_once('functions.php');
require_once('classes.php');
// get our SQL connection
$con = getSQLConnection('deal_site');
// before we build the deal object there are some things we need to know
// we need to get the company and tag id's that we will be adding, plus make sure we have a user 
// object to reference as the person adding the deal
if (!$user -> user_id) {
    // they are probably not logged in, we will not continue with adding a deal   
    //die('fail:login');
    $user = new user();
    $user -> user_id = 11;
}
// now we know we will be able to add a poster to the deal, so let's get the company id this deal goes to
$company_name = SQLClean($_POST['company']);
$sql = sprintf("SELECT * FROM `companies` WHERE `company_name` = '%s'", $company_name);
$res = mysql_query($sql, $con);
if ($res) {
    $company = new company();
    while ($row = mysql_fetch_array($res)) {
        $company -> id = $row['company_id'];
        $company -> name = $row['company_name'];
        $company -> algo_rank = $row['company_algo_rank'];
        $company -> about = $row['company_about'];
        $company -> address = $row['company_address'];
        $company -> thumbs_up = $row['company_thumbs_up'];
        $company -> thumbs_down = $row['company_thumbs_down'];
    }
}
// now let's get the tag id's we will need 
$tag_strings = $_POST['tags']; // they will be cleaned individually
try {
    $tag_strings = explode(', ', $tag_strings); // they should have came all nice from the user
}
catch(Exception $e) {
    // we will assume that the user provided tags like a goon and we can't parse them
    // we'll add code to attempt to handle this
    unset($tag_strings);
}
// we must go through each tag and attempt to find it's ID
$i = 0;
foreach ($tag_strings as $tag_string) {
    $tag = new tag();
    $tag -> text = SQLClean($tag_string);
    $tag -> getID(); // handy methods ftw
    if ($tag -> is_new == 1) { // getID() sets this property after it runs so we will know 
        // let's introduce it to the DB
        $tag -> addTag(); // method will auto update id, not need to call getID() again
    }
    // after this tag object if fully instantiated
    $tags[$i] = $tag;
    if ($i == 0) {
        $tags_id_string = $tag -> id;   
    }
    else {
        $tags_id_string .= "," . $tag -> id;   
    }
    $i++;
    // $tags_id_string is what we will actually put into the DB
    // we just instantiate the object for the purpose of using it's methods
}
// tags should be taken care of and fully accessable via the $tags object array
// we need to get the location information now
if ($_POST['address']) {
    // we will turn this address into geocode we can use
    $coords = getCoords($_POST['address']);
    $coords = explode(',', $coords);
    $lat = $coords[0];
    $lng = $coords[1];
}
// we have all the information we will need for now, 
// let's instantiate our deal object
$deal = new deal();
// populate the new deal onject's properties with their respective values
$deal -> deal_id = null; // this won't exist until aftet the INSERT operation 
$deal -> deal_title = SQLClean($_POST['deal_title']);
$deal -> deal_poster_id = $user -> user_id;
$deal -> company_id = $company -> id;
$deal -> deal_price = SQLClean($_POST['price']);
$deal -> deal_start_date = SQLClean($_POST['start_date']);
$deal -> deal_end_date = SQLCLean($_POST['end_date']);
$deal -> deal_text = SQLClean($_POST['deal_text']);
$deal -> deal_latitude = $lat;
$deal -> deal_longitude = $lng;
$deal -> deal_photo = null; // we have not implemented this feature yet
$deal -> tags = $tags_id_string; 
$deal -> views = 0;
$deal -> thumbs_up = 0;
$deal -> thumbs_down = 0;
$deal -> verified_deal = 0;
$deal -> algo_ranking = 0;
$deal -> thanks_count = 0;
$deal -> active = 1;
// now we have the deal object, pass it to the insert function
$result = addDeal($deal); // takes a deal object 
echo $result;
///return $result; // return the result of the operation to the script that requested it
// clean up
// close out our SQL connection
mysql_close($con);
?>