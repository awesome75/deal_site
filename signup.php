<?
// signup page for the deals site
require_once('php/classes.php'); // get our classes and functions
// begin ip geo tracking
$ipinf = getClientLocData();
// end of location stuff
if ($_POST['user_name'] && $_POST['password'] && $_POST['email']) {
    // if the form is to be submitted
    include('php/add_user.php');
    // that should do it, now all we do is alter page flow to show user was added
    $user_added = 1;
}
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
if ($user_added == 1) {
    echo "user adding was attempted";
}

else {
    include('html/signup.html');
}
// finish page
closeSidebar();
include('html/footer.html');
?>