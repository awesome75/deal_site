<?
// deals site functions.php
// various things we will need to make the site work should go here
include('functions_secure.php'); // our non public functions

function getSQLConnection($db) {
    //very simple, we just need to get a connection to the specified database   
    //$con = mysql_connect('localhost', 'user', 'password'); // didn't think I would put creds in the public repo did you??? XD 
    if (!isset($con)) {
        $con = sgetSQLConnection($db);
    }
    return $con;
}

function SQLClean($val) {
    // sanitize the values that go into the DB
    // probably build some error check into this function at some point lol
    if (!isset($con)) {
        $con = getSQLConnection('deal_site');
    }
    $val = htmlspecialchars(mysql_real_escape_string(trim($val)));
    //mysql_close($con);
    return $val;
}

function addDeal($deal) {
    // add the passed deal object to the deals DB
    // returns a 0 or 1 as result of insert operation to the calling script
    
    // now we can move on to our SQL query
    $con = getSQLConnection('deal_site');
    $sql = 
        "INSERT INTO `deals`" . 
        "(deal_poster_id, company_id, deal_title, " .
        "deal_price, deal_start_date, " .
        "deal_end_date, deal_text, deal_latitude, " .
        "deal_longitude, deal_photo, deal_tags) " .
        "VALUES (" .
        "'%s', '%s', '%s', " .
        "'%s', '%s', " .
        "'%s', '%s', '%s', " .
        "'%s', '%s', '%s'" .
        ")";
    $sql = sprintf($sql,
            $deal->deal_poster_id, $deal->company_id, $deal->deal_title,
            $deal->deal_price, $deal->deal_start_date,
            $deal->deal_end_date, $deal->deal_text, $deal->deal_latitude,
            $deal->deal_longitude, $deal->deal_photo, $deal->tags
           );
    
    echo $sql;
    // now with the query ready to go let's get moving
    // the calling script should have already opened a MySQL connection
    //$result = mysql_query($sql, $con);
    // close the mysql connection
    mysql_close($con);
    if ($result == true) {
        return 'success';   
    }
    else {
        return 'fail';   
    }
}

function addDealComment($comment) {
    // adds a passed comment object to the comments table for a deal specified in the passed comment object
    // should return a 0 or 1 depending on if the comment is successfully inserted
    $sql = 
        "INSERT INTO `deal_comments` (deal_id,poster_id,comment_title,comment_text," .
        "thumbs_up,thumbs_down,algo_ranking) " .
        "VALUES (" .
        "$comment->deal_id,$comment->poster_id,$comment->title," .
        "$comment->text,$comment->thumbs_up,$comment->thumbs_down" .
        "$comment->algo_ranking" .
        ")";
    // now we have a query built, let's fire it off
    // we should already have a connection
    $result = mysql_query($sql);
    if ($result == true) {
        return 1;   
    }
    else {
        return 0;   
    }
}

function addUser($user) {
    // takes a passed user object and adds it to the DB
    // returns 1 or 0 for success or fail
    $con = getSQLConnection('deal_site');
    // first we need to determine if the user name is already taken
    // we will deprecate this part because we will use AJAX on the field to verify before user submits form
    $sql = "SELECT COUNT(*) FROM `users` WHERE `user_name` = '" . $user->user_name . "'";
    $res = mysql_result(mysql_query($sql, $con), 0);
    if ($res == 1) {
        // the user exists in the DB, I am afraid we can't allow that
        return 'fail:user';
    }
    
    
    // if we are still running then I guess we can add the user
    $sql = 
        "INSERT INTO `users` (user_name,password,ip_address,location,email_address) " . 
        //"cell_carrier,cell_nummber) " .
        "VALUES ('$user->user_name','$user->password','$user->ip_address','$user->location'," .
        "'$user->email_address'" . //,$user->cell_carrier,$user->cell_number" .
        ")";
    $result = mysql_query($sql, $con); //user registration enabled for now
    if ($result == true) {
        // this means we can get the user's new ID and update our object
        // we do this using their new user name for our identifier
        $sql = "SELECT `user_id` FROM `users` WHERE `user_name` = '" . $user -> user_name . "'";
        $user -> user_id = mysql_result(mysql_query($sql, $con), 0);
        return 1;
    }
    else {
        // we will add code for handling problems later
    }
    // close our SQL connection
    mysql_close($con);
}

function closeSidebar() {
    echo "\n</div>\n";
}
 
function getUser($user_id) {
    // take a user ID and return a user object from it
    // first create a user object 
    $user = new user();
    // now we need to build a SQL query
    $con = getSQLConnection('deal_site');
    $sql = "SELECT * FROM `users` WHERE `user_id` = " . $user_id;
    // now let's query it 
    $res = mysql_query($sql, $con);
    while ($row = mysql_fetch_array($res)) {
        $user -> user_id = $row['user_id'];
        $user -> name = $row['user_name'];
	    $user -> password = $row['password'];
	    $user -> last_login = $row['last_login'];
    	$user -> creation_date = $row['creation_date'];
    	$user -> ip_address = $row['ip_address'];
    	$user -> location = $row['location'];
    	$user -> email_address = $row['email_address'];
    	$user -> cell_carrier = $row['cell_carrier'];
    	$user -> cell_number = $row['cell_number'];        

    }	
    // close the SQL connection
    mysql_close($con);
    return $user;
}

function getDeals($params = null) { // allow optional
  /*
    * getDeals does most of the heavy lifting for deal searches.
    * we will use the parameter input on the function to construct a query
  */
  // check each potential param, and allow it to be added to a query
  //echo "params: ";
  //die(var_dump($params));
  if ($params['location']) {
    // first, we need to turn this location into a coord plot
    $com = "python ../python/location_tools.py geocode '" . $params['location'] . "'";
    $coords = system($com);
    // now split it to lat and long
    $coords = explode(',', $coords);
    $lat = $coords[0];
    $long = $coords[1];
    // now we can use them in a query
    $loc = ",
      ( 3959 * acos( cos( radians($lat) ) * cos( radians( deal_latitude ) ) 
      * cos( radians( deal_longitude ) - radians($long) ) + sin( radians($lat) ) 
      * sin( radians( deal_latitude ) ) ) ) AS distance 
    ";
    
  }
  if ($params['company'])
    $clause .= "AND `company_id` = " . $params['company'];
  if ($params['type']) 
    $a = 1; // don't fix until we figure out new tag schemes
  if ($params['price'])
    $clause .= "AND `deal_price` < " . $params['price'];
  // special one for if there is location
  if ($params['location'])
    $clause .= "HAVING distance < 10";
  $sql = "
    SELECT * $loc
    FROM `deals`
    WHERE 1=1
    $clause
  ";
  // final steps, include get_deals only after return from function
  // it will query whatever var $sql is, and instantiate deal objects from it
  include('get_deals.php');
  return $deals; // return the deals to the script
}

function getDeals_old($deal_id=null,$tag=null,$location=null,$company=null) {   
    // this function is what will retrieve deals for the various areas of the site
    // we need to build a query with the desired result and include get_deals.php
    // which will return the desired deals in array $deals
    /*
        TODO:
            We still need to add code to get deals with price
            Also we need to add search by location
            Look into easier way to cover all cases
    */
    if ($deal_id != null) {
        // easy case, since if this is here tags, location etc don't matter
        $sql = "SELECT * FROM `deals` WHERE `deal_id` = " . $deal_id;
    }
    
    // just grabbing some deals by tag ($tag will be an id not string)
    else if ($tag != null && $location == null && $company == null && $price == null) {
        // this case is where we are just getting a tag, 
        // normally you would have a location or company with this but whatever
        if (!is_int($tag)) {
            // we will automagically get the id for the calling script because we're nice like that
            $tag_obj = new tag();
            $tag_obj->text = $tag;
            $tag_obj->getID();
            $tag = $tag_obj->id;
        }
        $sql = "SELECT * FROM `deals` WHERE `deal_tags` LIKE '%" . $tag . "%'";
        // need to do some research to find best way to search tags
    }
    
    // just grabbing some deals by location
    else if ($tag == null && $location && $company == null && $price == null) {
      /* 
        * we will use the haversine formula for this search. The formula will need the
        * current coordindates of the origin of the request, ie the location of the 
        * person searching, or a defined point on the map. We will also need the coords
        * of the deal, which we are already storing in the db
      */
      /******************** MODEL *********************************
        // Closest within radius of 25 Miles
        // 37, -122 are your current coordinates
        // To search by kilometers instead of miles, replace 3959 with 6371
        SELECT feature_name, 
         ( 3959 * acos( cos( radians(37) ) * cos( radians( lat ) ) 
          * cos( radians( long ) - radians(-122) ) + sin( radians(37) ) 
          * sin( radians( lat ) ) ) ) AS distance 
        FROM geo_features HAVING distance < 25 
        ORDER BY distance LIMIT 1;
      */
      // we will need to turn the passed 'location' into coords.
      // luckily we have just the script for that
      echo "dfsfsd";
      $sql = "
        SELECT *,
          ( 3959 * acos( cos( radians(37) ) * cos( radians( deal_latitude ) ) 
          * cos( radians( deal_longitude ) - radians(-122) ) + sin( radians(37) ) 
          * sin( radians( deal_latitude ) ) ) ) AS distance 
        FROM `deals`
        ORDER BY distance
      "; // ah yeah son, haversine in this bitch
    }
    
    // only getting deals by company
    else if ($tag == null && $location == null && $company != null && $price == null) {
        // for now simple query to get us going
        $sql = "SELECT * FROM `deals` WHERE `company_id` = " . $company;
    }
    
    // retrieve deals with only with price filter
    else if ($tag == null && $location == null && $company == null && $price != null) {
        $sql = "SELECT * FROM `deals` WHERE `deal_price` < " . $price;
    }
    
    // this case we go by tag and location
    else if ($tag != null && $location !=null && $company == null && $price == null) {
        // research location search first
    }
    
    // go by tag and company
    else if ($tag != null && $location == null && $company != null && $price == null) {
        $sql = ("SELECT * FROM `deals` WHERE `company_id` = %d AND`deal_tags` LIKE '%'" % $company) . $tag . "%'";
        // simple enough, look into speed improvements however
    }
    
    // go by tag and price
    else if ($tag != null && $location == null && $company == null && $price != null) {
        $sql = sprintf("SELECT * FROM `deals` WHERE `deal_tags` LIKE '%%%s%%' AND `deal_price` < %s", $tag, $price);
        // simple query, look into speed improvements
    }
    
    else if ($tag == null && $location == null && $company != null && $price != null) {
        $sql = sprintf("SELECT * FROM `deals` WHERE `company_id` = %s AND `deal_price` < %s", $company, $price);
        // probably a pretty solid query
    }
    
    /*
        this will be section with various location mixes, unfortunately we have not figured
        location search out quite just yet
    */
    
    else {
        // this is if all else fails, just grab the newest deals from the DB I guess
        $sql = "SELECT * FROM `deals` ORDER BY `deal_post_date` DESC"; // grab our deals
    }
   
   // $sql needs to be defined at this point!
   include('get_deals.php');
   return $deals; // return the deals to the script
   
}

function getCoords($address) {
    // get the coordinates from the specified address, we will use our
    // location tools python script to get the data
    exec("../python/location_tools.py geocode " . $address, $output);
    return $output[0]; // returned as coords 'lat,long'
}

function initIPObject() {
    // this can be called by any page wishing to perform operations involving the ipgeoloc table
    // to build an IP object based on the current remote client's IP information
    $ipinf = new ipinfo();
    // let's set what we know
    $ipinf -> ip_address = $_SERVER['REMOTE_ADDR'];
    // and that is pretty much it until we run a geoloc, so just return what we got
    return $ipinf;
}

function getClientLocData() {
    // this function is what will make an ipinf object for the user
    // whether or not we make a new one or get one from the DB
    $ipinf = initIPObject();
    $rawloc = $ipinf -> getLocation();
    $ipinf -> initLocationData($rawloc);
    return $ipinf;
}

function filterID($id) {
    // make a user supplied ID safe for SQL queries
    // this is for numeric IDs, obviously
    preg_match('([\d]+)', $id, $matches);
    return $matches[0];
}

function checkSignupCode($code) {
    // see if the user is attempting to register with a valid code
    $sql = "SELECT COUNT(*) FROM `signup_codes` WHERE `code` = '". SQLClean($code) . "' AND `redeemed` = 0";
    $con = getSQLConnection('deal_site');
    $res = mysql_result(mysql_query($sql, $con), 0);
    if ($res == 1) {
        // this means the code is available to use and we are good to go
        return 'good';
    }
    else {
        return 'bad';   
    }
    mysql_close($con);
}

function validateDate($date) {
    // validate a date string with strtotime()
    if (date('y', strtotime($date)) == '69') {
        // we don't have a valid date return 0
        return 'fail';
    }
    else {
        return date('m/d/y', strtotime($date));
    }
}

?>














