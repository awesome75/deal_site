<?
// add_deal.php
// this will be the script that handles adding a deal
require_once('functions.php');
require_once('classes.php');
// first let's collect and sanitize the POST variables that should contain our deal data
$deal_title = SQLClean($_POST['deal_title']);
// $deal_poster_id = ; decide how you want to do that one before we define it here
$deal_price = SQLClean($_POST['deal_price']);
$deal_post_date = SQLClean($_POST['deal_post_date']); // will actually be a SQL TIMESTAMP, ignore line
$deal_end_date = SQLClean($_POST['deal_end_date']);
$deal_text = SQLClean($_POST['deal_text']);
$deal_location = SQLClean($_POST['deal_location']);
$deal_photo = SQLClean($_POST['deal_photo']);
$tags = SQLClean($_POST['tags']);
$views = 0; // initially this is obviously a zero field
$thumbs_up = 0;
$thumbs_down = 0;
$verified_deal = false; // at first it will never be verified, so false
$algo_ranking = null; // this is not implemented yet, need to make the DealRank algorithm
$thanks_count = 0; // another 0 initial value
// let's get our deal to be a class of it's own
$deal = new deal();
// populate the new deal onject's properties with their respective values
$deal -> deal_id = null; // this won't exist until aftet the INSERT operation 
$deal -> deal_title = $deal_title;
$deal -> deal_poser_id = $deal_poster_id;
$deal -> deal_price = $deal_price;
$deal -> deal_post_date = $deal_post_date; // we will use TIMESTAMP instead
$deal -> deal_end_date = $deal_end_date; // this will need to be date formatted before it is useful, don't forget
$deal -> deal_tect = $deal_text;
$deal -> deal_location = $deal_location;
$deal -> deal_photo = $deal_photo; // probably going to be a file object $_FILES[0], type check and all that
$deal -> tags = deal -> separateTags($tags); // explodes the tags
$deal -> views = $views; // should be 0
$deal -> thumbs_up = $thumbs_up; // should be 0
$deal -> thumbs_down = $thumbs_down; // another 0
$deal -> verified_deal = $verified_deal; // should be false
$deal -> algo_ranking = $algo_ranking; // null for now, no algorithm yet
$deal -> thanks_count = $thanks_count; // should be 0
// now we have the deal object, pass it to the insert function
// get our SQL connection
$con = getSQLConnection('deal_site');
$result = addDeal($deal); // takes a deal object 
return $result; // return the result of the operation to the script that requested it
// clean up
// close out our SQL connection
mysql_close($con);
?>