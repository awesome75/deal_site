<?
// signup page for the deals site
require_once('php/classes.php'); // get our classes and functions
// make sure the user is allowed to issue codes
$allowed = ('tzdev');
session_start();
for ($i = 0; $i < sizeof($allowed); $i++) {
    if ($_SESSION['user']->user_name == $allowed[$i]) {
        $approved = 1;
    }
    else {
        continue;   
    }
}
if (!isset($approved)) {
    // user may not get codes
    $approved = 0;
}
if ($_GET['getcode'] == 1 && $approved == 1) {
     // get a fresh sign up code from the server
    $con = getSQLConnection('deal_site');
    $sql = "SELECT * FROM `signup_codes` WHERE `redeemed` = 0 LIMIT 1";
    $res = mysql_query($sql, $con);
    while ($row = mysql_fetch_array($res)) {
        echo $row['code'];
    }
    die();
}

// begin ip geo tracking
$ipinf = getClientLocData();
// end of location stuff
$ptitle = "Sign Up Manager";
include('html/header.html');
?>
<style>
#deals_container {
    width: 100%;
    text-align: justify;
}
</style>
<script>
function getSignupCode() {
    // get a signup code with AJAX for the signup manager
    var req;
    var code;
    if (window.XMLHttpRequest) {
        // for most sane browsers
        try {
            req = new XMLHttpRequest();   
        } 
        catch(e) {
            req = false; // we didn't get the connection
        }
    }
    
    else if (window.ActiveXObject) {
        try {
            req = new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch(e) {
            req = false;
        }
    }
    // hopefully we have a request object to use by now
    if (!req) {
        // alert the calling script we couldn't make it happen by returning 0
        return 0;
    }    
    req.onreadystatechange = function() {
           if (req.readyState == 4) {
                if (req.status == 200) {
                    code = req.responseText;   
                    document.getElementsByName('codebox')[0].innerHTML = code;
                }
           }
    }
    // attempt to get the information
    req.open('GET', 'signup_manager.php?getcode=1', true);
    req.send(null);   
}
</script>
<?
include('html/deals_container.html');
if ($approved == 0) {
    echo "<h1>not allowed :(</h1>\n";
    echo "Sorry, you are not authorized to generate codes. If this is a mistake or you " .
         "would like to request a code, please <a href=\"signup_manager.php?request_code=1\">click here</a>.";
    include('html/footer.html');
    die();
}
?>
<h1>invite someone to join</h1>
<div style="width:100%;text-align:center;margin-top:50px;">
    <input type="button" value="get code" class="big_button" onClick="getSignupCode();" />
    <div style="margin-top: 15px;">
        <h1 name="codebox"></h1>
    </div>
</div>
<?
closeSidebar();
include('html/footer.html');
?>