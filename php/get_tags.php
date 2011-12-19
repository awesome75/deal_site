<?
// tag suggest
require_once('classes.php'); // get our classes and functions

if (isset($_GET['q'])) {
    $sql = sprintf("SELECT * FROM `deal_tags` WHERE `tag_text` LIKE '%%%s%%'", SQLClean($_GET['q']));
}
$con = getSQLConnection('deals_site');
$res = mysql_query($sql, $con);
$i = 0;
while ($row = mysql_fetch_array($res)) {
    $tag = new tag();
    $tag -> id = $row['tag_id'];
    $tag -> text = $row['tag_text'];
    $tags[$i] = $tag;
    $i++;
}
if (isset($_GET['q'])) {
    // a JS script expects a response
    foreach ($tags as $tag) {
        $ret = "%s,%s;";
        echo sprintf($ret, $tag->id, $tag->text);
    }
}
// the calling script will be able to deal with the $tags array later
mysql_close($con);
?>