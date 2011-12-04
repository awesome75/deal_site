<?
// companies.php script for deals site
// this script may end up like tags and being merged with index
// if so we will keep it around, but the script will be deprecated
require_once('php/classes.php');
// begin ip geo tracking
$ipinf = getClientLocData();
// end of location stuff
// we should have a $_GET var with our companies name(id) on it 
$company = new company();
$company -> id = filterID($_GET['company']);
// tell the DB to populate our company object
$company -> initCompanyData(); 
$ptitle = $company -> name;
// now let's include our HTML elements
include('html/header.html');
// right sidebar will be for our promoted deals, not implemented yet
/*
include('html/sidebar_right.html');
echo "hello world";
closeSidebar();
*/
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
// deals etc here
$deals = getDeals(null, null, null, $company->id);
include('html/deal.html');

// enf of deal container
closeSidebar();
include('html/footer.html');

?>