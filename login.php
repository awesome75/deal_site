<?
// login to the deals site
require_once('php/classes.phpp'); // we will need our classes and functions
// begin IP geo tracking 
$ipinf = getClientLocData();
// end of IP location stuff
if ($_POST['user'] && $_POST['password']) {
    // this means they have already sent credentials, so we will just check them
    $user = new user();
    $user -> user_name = SQLClean($_POST['user]);
    $user -> password = SQLClean($_POST['password']);
    // now we can attempt to login and see what happens
    $login_attempt = $user -> login();
    // later will will use the result in $login_attempt to alter page view for a failed/successsful login
}
// if we aren't worried about attempting a login, display the login view

?>