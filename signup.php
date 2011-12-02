<?
// signup page for the deals site
require_once('php/classes.php'); // get our classes and functions
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
?>
<!-- signup content -->
<div id="signup_box">
</div>
<div id="signup_splash">
    <h1>Sign up now to always have access to the latest deals!</h1>
    <script>
        for (i = 0; i < 50; i++) {
            document.write('hello world. ');   
        }
    </script>
</div>
<!-- finish page -->
<?
closeSidebar();
include('html/footer.html');
?>