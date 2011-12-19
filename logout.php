<?
// log the user out of the site
session_start();
session_destroy(); // there it is
// redirect the user to login
header('Location: login.php');
?>