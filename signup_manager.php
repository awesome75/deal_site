<?
// signup page for the deals site
require_once('php/classes.php'); // get our classes and functions
// make sure the user is allowed to issue codes
$allowed[] = 'tzdev';
session_start();
$user = $_SESSION['user'];
for ($i = 0; $i < sizeof($allowed); $i++) {
    if (strtolower($user->user_name) == $allowed[$i]) {
        $approved = 1;   
    }
}
if (!isset($approved)) {
    // user may not get codes
    $approved = 0;
}
if ($_GET['getcode'] == 1 && $approved == 1) {
     // get a fresh sign up code from the server
    $con = getSQLConnection('deal_site');
    $sql = "SELECT * FROM `signup_codes` WHERE `redeemed` = 0 AND `issued` = 0 LIMIT 1";
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
<script type="text/javascript" language="javascript" href="js/functions.js"></script>
<script>
function getSignupCode(button) {
    // get a signup code with AJAX for the signup manager
    var req = getXmlHttp();
    var code; 
    req.onreadystatechange = function() {
           if (req.readyState == 4) {
                if (req.status == 200) {
                    code = req.responseText;  
                    //console.log('signup resp: ' + code);
                    document.getElementsByName('codebox')[0].innerHTML = code;
                    if (button.value == "mark issued") {
                        markIssued(code, button);
                    }
                    button.value = "mark issued";
                    return 1;
                }
           }
    }
    // attempt to get the information
    req.open('GET', 'signup_manager.php?getcode=1', true);
    req.send(null);   
}

function markIssued(code, button) {
    // mark the code as issued
    var req = getXmlHttp();
    req.onreadystatechange = function() {
        // the script will mark the code as issued and return the result
        if (req.readyState == 4 && req.status == 200) {
            resp = req.responseText.trim();
            console.log(resp);
            button.value = "get code";
            document.getElementsByName('codebox')[0].innerHTML = "";
            return 1;
        }
        
    }
    req.open('GET', 'php/helpers.php?do=markcodeissued&code=' + code);
    req.send(null);
}
</script>
<?
include('html/deals_container.html');
if ($approved == 0) {
    echo "<h1>not allowed :(</h1>\n";
    echo "Sorry, you are not authorized to generate codes. If this is a mistake or you " .
         "would like to request a code, please <a href=\"signup_manager.php?request_code=1\">click here</a>.";
    echo "\n<br />\n<br />\n";
    echo sprintf("If you are not redirected in two seconds please <a href='%s'>click here</a>", '/deal_site/');
    include('html/footer.html');
    die();
}
?>
<h1>invite someone to join</h1>
<div style="width:100%;text-align:center;margin-top:50px;">
    <input type="button" value="get code" class="big_button" onClick="getSignupCode(this);" />
    <div style="margin-top: 15px;">
        <h1 name="codebox"></h1>
    </div>
</div>
<?
closeSidebar();
include('html/footer.html');
?>