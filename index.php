<?
require_once('php/classes.php'); // make sure we have our functions and classes
// begin ip geo tracking
$ipinf = getClientLocData();
// end of location stuff
session_start();
// if the user is not logged in they aren't allowed here
if ($_SESSION != null) {
    $user = $_SESSION['user'];
}
else {
    // the user is not logged in and must be sent to the login page
    header('Location: login.php');
}
// check if a tag is set
if ($_GET['tag']) {
    $tag = new tag();
    $tag -> text = htmlentities($_GET['tag']);
    $tag -> getID(); // if tag is new the DB will tell us in the is_new property (0 or 1)
}
// include html elements
$ptitle = "Home";
if (isset($user)) {
    include('html/header_loggedin.html');   
}
else {
    include('html/header.html');
}
// include the left sidebar of deal filters
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
// individual deal view should not have add deal button
if (!$_GET['deal_id']){
    include('html/add_deal_button.html');
}
// deals will go here
// if there is a tag set let's get those deals
if ($tag) {
    $deals = getDeals(null, $tag->id, null, null);
}

else if ($_GET['deal_id']) {
    $deals = getDeals(array('id' => filterID($_GET['deal_id'])));
}

else {
    // otherwise just general deal retrieve
    $deals = getDeals();
}
// we can accesss our deal array with $deals, or we can include the deal.html view to iterate and display them
include('html/deal.html');
// end of the deals, finish up the page
closeSidebar(); // ends the div
// page is over incluude the footer
include('html/footer.html');
?>
