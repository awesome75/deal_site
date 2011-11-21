// deals site functions.php
// various things we will need to make the site work should go here

function getSQLConnection($db) {
    // very simple, we just need to get a connection to the specified database   
    $con = mysql_connect('localhost', 'user', 'password'); // didn't think I would put creds in the public repo did you??? XD
    return mysql_select_db($db, $con);
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
        "INSERT INTO `deals` (deal_title,deal_poster_id,deal_price," 
        "deal_post_date,deal_end_date,deal_text,deal_location,deal_photo," 
        "tags,views,thumbs_up,thumbs_down,verified_deal,algo_ranking,thanks_count) " 
        "VALUES ("
        "$deal->deal_title,$deal->deal_poster_id,$deal->deal_price,"
        "$deal->deal_post_date,$deal->deal_end_date,$deal->deal_text,"
        "$deal->deal_location,$deal->deal_photo,$deal->tags,$deal->views,"
        "$deal->thumbs_up,$deal->thumbs_down,$deal->verified_deal,$deal->algo_ranking,"
        "$deal->thanks_count"
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
        "INSERT INTO `deal_comments` (deal_id,poster_id,comment_title,comment_text,"
        "thumbs_up,thumbs_down,algo_ranking) "
        "VALUES ("
        "$comment->deal_id,$comment->poster_id,$comment->title,"
        "$comment->text,$comment->thumbs_up,$comment->thumbs_down"
        "$comment->algo_ranking"
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
        "INSERT INTO `users` (user_name,password,ip_address,location,email_addresss,"
        "cell_carrier,cell_nummber) "
        "VALUES ($user->user_name,$user->password,$user->ip_address,$user->location,"
        "$user->email_address,$user->cell_carrier,$user->cell_number"
        ");"
    $result = mysql_query($sql);
    if ($result == true) {
        return 1;   
    }
    else {
        return 0;   
    }
}








