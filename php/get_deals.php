<?
// deal site deals.php
// the idea hopefully is they will integrate easily and make the site more efficient for code reuse
// basiically we need to retrieve the SQL values for the deals :D
require_once('functions.php'); // don't forget our functions file
require_once('classes.php'); // gonna need that deal class
// first grab a connection to the SQL DB
$con = getSQLConnection('deal_site'); // $con has our DB reference 
// go on to getting the deals and populating them into a class so we can use this script anywhere on site
/*
    * $sql = "SELECT * FROM `deals` ORDER BY `deal_post_date` DESC"; // grab our deals
    * We don't need this anymore, getDeals() will defin the SQL for us, this script just retrieves
    * the deals ands stores them in memory
*/
$res = mysql_query($sql, $con);
$ctrl = 0; // loop counter
// now to go through them
while ($row = mysql_fetch_array($res)) {
    $current = new deal();
    $current -> deal_id = $row['deal_id'];
    $current -> deal_title = $row['deal_title'];
    $current -> deal_poster_id = $row['deal_poster_id'];
    $current -> company_id = $row['company_id'];
    $current -> deal_price = $row['deal_price'];
    $current -> deal_post_date = $row['deal_post_date'];
    $current -> deal_start_date = $row['deal_start_date'];
    $current -> deal_end_date = $row['deal_end_date'];
    $current -> deal_text = $row['deal_text'];
    $current -> deal_latitude = $row['deal_latitude'];
    $current -> deal_longitude = $row['deal_longitude'];
    $current -> deal_photo = $row['deal_photo'];
    $current -> tags = $row['deal_tags'];
    $current -> views = $row['deal_views'];
    $current -> thumbs_up = $row['deal_thumbs_up'];
    $current -> thumbs_down = $row['deal_thumbs_down'];
    $current -> verified_deal = $row['deal_verified'];
    $current -> algo_rank = $row['algo_ranking'];
    $current -> thanks_count = $row['thanks_count'];
    $current -> active = $row['deal_active'];
    $current->distance = $row['distance'];
    // now that the deal object is done store a reference
    $deals[$ctrl] = $current;
    // increment index and continue
    $ctrl++;
}
// clean up
// with the deals in memory we can close the SQL connection
mysql_close($con);
?>
