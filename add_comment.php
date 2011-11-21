// add_comment.php for deals site
// this script will take a comment from the user in POST vars ans insert it into the DB
require_once('functions.php');
require_once('classes.php');
// we need a deal ID to be defined here
preg_match("([\d]+)", $_GET['deal_id'], $deal_id);
$deal_id = $deal_id[0];
// now that is done we can move on to collecting and sanitizing the comment contents from POST
$poster_id = $_POST['poster_id'];
$title = $_POST['title'];
$text = $_POST['text'];
$thumbs_up = 0;
$thumbs_down = 0; // new comments have not up or downs yet
// now we can create the comment object
$comment = new deal_comment();
$comment -> comment_id = null;
$comment -> deal_id = $deal_id;
$comment -> poster_id = $poster_id;
$comment -> title = $title;
$comment -> text = $text;
$comment -> thumbs_up = $thumbs_up;
$comment -> thumbs_down = $thumbs_down;
$comment -> post_date = null; // sql will handle this
$comment -> algo_ranking = 0; // not implemented yet
// now we have our comment object, let's add it to the database
$con = getSQLConnection('deal_site');
$result = addDealComment($comment); // call the add deal function
return $result; // return result to calling script
// clean up, close SQL connection
mysql_close($con);