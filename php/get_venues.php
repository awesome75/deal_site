<?
// get_venues.php script for deals site
// get the venues and store them in memory for the calling script
require_once('functions.php'); // don't forget our functions file
require_once('classes.php'); // gonna need that deal class
// get a SQL connection
$con = getSQLConnection('deal_site');
$sql = "SELECT * FROM `venues`";
$res = mysql_query($sql, $con);
$ctrl = 0; // loop count
while ($row = mysql_fetch_array($res)) {
    // create a venue object
    $venue = new venue();
    // populate our new venue object
    
    // add the venue to the array
    $venues[$ctrl] = $venue;
    // incrememt the counter
    $ctrl++;
}

// close our sql connection

mysql_close($con);
?>