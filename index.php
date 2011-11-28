<?
$ptitle = "Home";
include('php/get_deals.php');
include('html/header.html');
include('html/sidebar_right.html');
echo "hello world";
closeSidebar();
include('html/sidebar_left.html'); // already closed in the code
include('html/deals_container.html');
// deals will go here
// we can accesss our deal array with $deals, or we can include the deal.html view to iterate and display them
include('html/deal.html');
// end of the deals, finish up the page
closeSidebar(); // ends the div
// page is over incluude the footer
include('html/footer.html');
?>
