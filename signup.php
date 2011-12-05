<?
// signup page for the deals site
require_once('php/classes.php'); // get our classes and functions
// begin ip geo tracking
$ipinf = getClientLocData();
// end of location stuff
$ptitle = "Sign Up";
include('html/header.html');
?>
<style>
#deals_container {
    width: 100%;
    text-align: justify;
}
</style>
<?
include('html/deals_container.html');
// signup content
include('html/signup.html');
// finish page
closeSidebar();
include('html/footer.html');
?>