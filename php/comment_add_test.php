<?
include('add_comment.php');

$comment = new deal_comment();
$comment -> deal_id = 1;
$comment -> poster_id = 2323;
$comment -> title = "comment add test";
$comment -> text = "this is a comment add test from the add comment script";
$comment -> thumbs_up = 3242;
$comment -> thumbs_down = 23;
$comment -> algo_rank = 0;
$comment -> active = 1;

addDealComment($comment)
?>
