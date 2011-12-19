<?
// login to the deals site
require_once('php/classes.php'); // we will need our classes and functions
// begin IP geo tracking 
$ipinf = getClientLocData();
// end of IP location stuff
// session stuff
session_start();
if ($_SESSION != null) {
    // the user muust have already logged in
    header('Location: /deal_site/');
}
// end session stuff
if ($_POST['user'] && $_POST['password']) {
    // this means they have already sent credentials, so we will just check them
    $user = new user();
    $user -> user_name = strtolower(SQLClean($_POST['user']));
    $user -> password = SQLClean($_POST['password']);
    // now we can attempt to login and see what happens
    $login_attempt = $user -> login($ipinf);
    // we should probably handle the return from the function
}
// if we aren't worried about attempting a login, display the login view
$ptitle = "Login";
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
// this is where we will inlcude our login.html code
if (isset($login_attempt)) {
    if ($login_attempt != 1) {
        echo "<span style='color:red;'>invalid user name or password</span>";
    }
    if ($login_attempt == 1) {
        // redirect the user to their home page and begin their sesssion
        session_start();
        $_SESSION['user'] = $user;
        echo sprintf("<h1>logged in as %s</h1>", $user->user_name);
        echo "please wait while you are redirected to your home page..";
        echo "<script>t = setTimeout('document.location=\'/deal_site/\'', 2000);</script>";
        die();
    }
}
include('html/login.html'); // should be as simple as including the view :)
// now we will finish out the page
closeSidebar();
include('html/footer.html');
?>