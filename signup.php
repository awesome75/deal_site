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
if ($failed == 'code') {
    echo "<h1>sorry :(</h1>you must have a valid sign up code to use the beta site";
}

else if ($failed == 'username') {
    echo "<h1>sorry :(</h1> That user name is already taken, please choose another";   
}

else if ($result == 1) {
    // this means the user made it to the DB
    echo sprintf("<h1>welcome %s!</h1>", $user -> user_name);
    echo "You're good to go! Head over to your home page to start checking out all the best" .
         " deals in your area, or post the deals you already know for others to check out!";
}

else {
    include('html/signup.html');
}
// finish page
closeSidebar();
include('html/footer.html');
?>