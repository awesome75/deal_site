<?
// tags.php script for deals site, this script will retrieve deals with tags that match $_GET['tag']
require_once('php/classes.php'); // get our classes and functions
// begin ip geo location
$ipinf = getClientLocData();
// get the tag
$tagtext = htmlentities($_GET['tag']); // clean it to prevent XSS or SQLi
// make a new tag object to hold the info
$tag = new tag();
$tag -> text = $tagtext;
$tag -> getID();
// set the page title with tag
$ptitle = $tag -> text;
include('html/header.html');
include('html/sidebar_left.html'); // already closed in the code
?>
<!-- override width until right_sidebar is implemented -->
<style>
#deals_container {
    width: 79%;
}
</style>
<?
include('html/deals_container.html');
// let's use our getDeals() interface to bring back the list
$deals = getDeals(null, $tag->id, null, null);
var_dump($deals);

closeSidebar();
?>









