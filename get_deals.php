<?
// deal site deals.php
// as you see, this is not HTML or anything. These files are purely just logic
// the idea hopefully is they will integrate easily and make the site more efficient for code reuse
// basiically we need to retrieve the SQL values for the deals :D
require_once('functions.php'); // don't forget our functions file
require_once('classes.php'); // gonna need that deal class
// first grab a connection to the SQL DB
$con = getSQLConnection('deal_site'); // $con has our DB reference 
// go on to getting the deals and populating them into a class so we can use this script anywhere on site
$sql = "SELECT * FROM `deals` ORDER BY `deal_post_date` DESC"; // grab our deals
$res = mysql_query($sql, $con);
$ctrl = 0; // loop counter
// now to go through them
while ($row = mysql_fetch_array($res)) {
    $current = new deal();
    $current -> deal_id = $row['deal_id'];
    $current -> deal_title = $row['deal_title'];
    $current -> deal_poster_id = $row['deal_poster_id'];
    $current -> deal_price = $row['deal_price'];
    $current -> deal_post_date = $row['deal_post_date'];
    $current -> deal_end_date = $row['deal_end_date'];
    $current -> deal_text = $row['deal_text'];
    $current -> deal_location = $row['deal_location'];
    $current -> deal_photo = $row['deal_photo'];
    $current -> tags = $row['tags'];
    $current -> views = $row['views'];
    $current -> thumbs_up = $row['thumbs_up'];
    $current -> thumbs_down = $row['thumbs_down'];
    $current -> verified_deal = $row['verified_deal'];
    $current -> algo_ranking = $row['algo_ranking'];
    $current -> thanks_count = $row['thanks_count'];
    // now that the deal object is done store a reference
    $deals[$ctrl] = $current;
    // increment index and continue
    $ctrl++;
}
// clean up
// with the deals in memory we can close the SQL connection
mysql_close($con);
?>
