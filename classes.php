<?
// deals site classes.php
// various classes we need should go here
// make sure functions are available
require_once('functions.php');

class deal {
    // this is the deals class for the site with the various properties and methods a deal object has
    // first declare all of the class properties
    var $deal_id;
    var $deal_title;
    var $deal_poster_id;
    var $deal_price;
    var $deal_post_date;
    var $deal_end_date;
    var $deal_text;
    var $deal_location;
    var $deal_photo;
    var $tags;
    var $views;
    var $thumbs_up;
    var $thumbs_down;
    var $verified_deal;
    var $algo_ranking;
    var $thanks_count;
    // that is all the properties we will need.. FOR NOW!!!
    // now we need to define the methods IE what the deal object is capable of
    
    function separateTags() {
        $tags_string = $this -> tags;   
        // PHP's handy explode function should be able to handle this :)
        // add error checking to this function bro, since 
        $tags = explode(', ', $tags_string);
        return $tags; // returns a list of tags
    }
}

class deal_comment {
    // this is the object to hold information about comments on a deal   
    // first let's declare the data we will need for the comment
    var $comment_id;
    var $deal_id;
    var $poster_id;
    var $title;
    var $text;
    var $thumbs_up;
    var $thumbs_down;
    var $post_date;
    var $algo_ranking; // will probably not be implemented for quite some time 
    // various object methods for comments will be below
}

class user {
    // this is the object that we will use to store user information in memory
    // declare the properties of the user
    var $user_id;
    var $user_name;
    var $password; // probably won't use this, all we have is our hashes, and why store a hash in memory?
    var $last_login;
    var $creation_date;
    var $ip_address; // sometimes this is from DB, sometimes this is a current value from the server
    var $location; // most recent location should be here, either from IP/GPS location or manually defined/DB value
    var $email_address;
    var $cell_carrier;
    var $cell_number;
    var $deal_post_count;
    // declare the methods the user is capable of
    function getLocation() {
        // get the location of the user and update this object with the data
        // after we have the location assign it to the object and return to the calling script
        $loc_apikey = "ed2da6f194f9306f2dd3c1a8965edfc4bf5afa1c50d70ddcf1677ffb0d19cd97";
        $url = "http://api.ipinfodb.com/v3/ip-city/?key=" . $loc_apikey . "&ip=" . $ip_address;
        $curl_handle=curl_init();
        curl_setopt($curl_handle,CURLOPT_URL,$url);
        curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
        $location_data = curl_exec($curl_handle);
        curl_close($curl_handle);
        // now that we have the location we can parse the contents
        
        
        // now return the data
        $this -> location = $location;
        return $this -> location;
    }
    
    function addDeal() {
        // this is how we will call the add_deal.php script from our user object    
    }
    
    function getDealCount() {
        // this function will get the acmount of deals the user has posted
        $con = getSQLConnection('deals_Site');
        $sql = "SELECT COUNT(*) FROM `deals` WHERE `deal_poster_id` = " . $this -> user_id;
        $res = mysql_result(mysql_query($sql, $con));
        // close out our MySQL connection
        mysql_close($con);
        $this -> deal_post_count = $res;
        return $this -> deal_post_count;
    }
    
    function getCommentCount($deal_id) {
        // retrieve the number of comments the user has posted for a given deal or site total
        // get a SQL connection
        $con = getSQLConnection('deals_site');
        // now build the SQL query
        if ($deal_id) {
            // if a deal ID was passed along to this function the desired behaviour is a comment count for that deal
            $sql = "SELECT COUNT(*) FROM `comments` WHERE `deal_id` = " . $deal_id . " AND `poster_id` = " . $this -> user_id;
        }
        else {
            // if no deal ID is passed to the function get the user's commnet total site wide
            $sql = "SELECT COUNT(*) FROM `comments` WHERE `poster_id` = " . $this -> user_id;
        }
        // now we can execute the query
        $res = mysql_result(mysql_query($sql, $con));
        // close the connection and return the result
        mysql_close();
        return $res;
    }    
    
}

?>




























