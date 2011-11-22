<?
// comments.php file for deals website
// this file will take a deal ID, and gather the comments for that deal in memory, if there is comments to display
require_once('functions.php');
require_once('classes.php');
/*
$deal_id = $_GET['deal_id']; // further cleaning of this variable needed
preg_match("([\d]+)", $_GET['deal_id'], $deal_id);
$deal_id = $deal_id[0];
*/
$deal_id = 1; // hardcoded deal id for testing
// the site including this will by rule have already had included functions and classes, so let's not worry about that
// let's grab a SQL connection
$con = getSQLConnection('deal_site');
try {
    // define our SQL query
    $sql = "SELECT * FROM `deal_comments` WHERE `comment_deal_id` = " . $deal_id;
    $res = mysql_query($sql, $con);
    $ctrl = 0; // loop index
    while ($row = mysql_fetch_array($res)) {
        // go through the comments and add them to memory for the calling script
        $current = new deal_comment();
        $current -> comment_id = $row['comment_id'];
        $current -> deal_id = $row['comment_deal_id'];
        $current -> poster_id = $row['comment_poster_id'];
        $current -> title = $row['comment_title'];
        $current -> text = $row['comment_text'];
        $current -> thumbs_up = $row['comment_thumbs_up'];
        $current -> thumbs_down = $row['comment_thumbs_down'];
        $current -> post_date = $row['comment_date'];
	$current -> algo_rank = $row['algo_rank'];
        $current -> active = $row['comment_active'];
        //$current -> algo_rank = rank somehow; figure this one out later, not implemented for now
        // now that the object has been created add it to the list of comments
        $comments[$ctrl] = $current;
        $ctrl++;
    }
    return 1; // if we were able to get the comments let the calling script know of the success
}
// if the system could not get the comments alert the user
catch(Exception $e) {
    return 0; // just return the failure, the calling script will deal with handling it for the user
}

// close out our SQL connection like a good boy
mysql_close($con);
?>
