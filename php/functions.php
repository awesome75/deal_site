<?
// deals site functions.php
// various things we will need to make the site work should go here
include('functions_secure.php');

function getSQLConnection($db) {
    //very simple, we just need to get a connection to the specified database   
    //$con = mysql_connect('localhost', 'user', 'password'); // didn't think I would put creds in the public repo did you??? XD 
    $con = sgetSQLConnection($db);
    return $con;
}

function SQLClean($val) {
    // sanitize the values that go into the DB
    // probably build some error check into this function at some point lol
    $val = htmlspecialchars(mysql_real_escape_string($val));
    return $val;
}

function addDeal($deal) {
    // add the passed deal object to the deals DB
    // returns a 0 or 1 as result of insert operation to the calling script
    $sql = 
        "INSERT INTO `deals` (deal_title,deal_poster_id,deal_price," . 
        "deal_post_date,deal_end_date,deal_text,deal_location,deal_photo," . 
        "tags,views,thumbs_up,thumbs_down,verified_deal,algo_ranking,thanks_count) " .
        "VALUES (" .
        "$deal->deal_title,$deal->deal_poster_id,$deal->deal_price," .
        "$deal->deal_post_date,$deal->deal_end_date,$deal->deal_text," .
        "$deal->deal_location,$deal->deal_photo,$deal->tags,$deal->views," .
        "$deal->thumbs_up,$deal->thumbs_down,$deal->verified_deal,$deal->algo_ranking," .
        "$deal->thanks_count" .
        ")";
    // now with the query ready to go let's get moving
    // the calling script should have already opened a MySQL connection
    $result = mysql_query($sql);
    if ($result == true) {
        return 1;   
    }
    else {
        return 0;   
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
    $sql = 
        "INSERT INTO `users` (user_name,password,ip_address,location,email_addresss," . 
        "cell_carrier,cell_nummber) " .
        "VALUES ($user->user_name,$user->password,$user->ip_address,$user->location," .
        "$user->email_address,$user->cell_carrier,$user->cell_number" .
        ")";
    $result = mysql_query($sql);
    if ($result == true) {
        return 1;   
    }
    else {
        return 0;   
    }
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

function getDeals($deal_id=null,$tag=null,$location=null,$company=null) {   
    // this function is what will retrieve deals for the various areas of the site
    // we need to build a query with the desired result and include get_deals.php
    // which will return the desired deals in array $deals
    if ($deal_id != null) {
        // easy case, since if this is here tags, location etc don't matter
        $sql = "SELECT * FROM `deals` WHERE `deal_id` = " . $deal_id;
    }
    
    
    else if ($tag != null && $location == null && $company == null) {
        // this case is where we are just getting a tag, 
        // normally you would have a location or company with this but whatever
        $sql = "SELECT * FROM `deals` WHERE `deal_tags` = ??";
        // need to do some research to find best way to search tags
        
    }
   
   // $sql needs to be defined at this point!
   include('get_deals.php');
   
}

function getCoords($address) {
    // get the coordinates from the specified address, we will use our
    // location tools python script to get the data
    exec("../python/location_tools.py geocode " . $address, $output);
    return $output[0]; // returned as coords 'lat,long'
}


?>














