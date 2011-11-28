// add_user.php for deals site
// add a user to the users table from the input of the POST vars
require_once('functions.php');
require_once('classes.php');
// collect and sanitize the POST variables from the sign up form
$user_name = SQLClean($_POST['user_name']);
$password = crypt(SQLClean($_POST['password']), 'secure_salt'); // hash the password of course, salt not public :)
// sql will take care of last_login and creation_date, so let's go on
$ip_address = $_SERVER['REMOTE_ADDR'];
// we will use the user class method getLocation later for location data
$email = SQLClean($_POST['emai']);
$cell_carrrier = SQLClean($_POST['cell_carrier']); // this one and cell number are optional
$cell_number = SQLClean($_POST['cell_number']);
// that is all we need for now, so let's get to building a user object
$user = new user();
$user -> user_id = null; // this is to be taken care of by SQL
$user -> user_name = $user_name;
$user -> ip_address = $ip_address;
$user -> location = $user -> getLocation();
$user -> email_address = $email;
$user -> cell_carrier = $cell_carrrier;
$user -> cell_number = $cell_number;
$user -> deal_post_count = 0;
// user object built. let's insert it now
$con = getSQLConnection('deals_site');
$result = addUser($user);
return $result;
// clean up
mysql_close($con);