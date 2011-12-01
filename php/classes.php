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
    var $deal_start_date;
    var $deal_end_date;
    var $deal_text;
    var $deal_latitude;
    var $deal_longitude;
    var $deal_photo;
    var $tags;
    var $views;
    var $thumbs_up;
    var $thumbs_down;
    var $verified_deal;
    var $algo_rank;
    var $thanks_count;
    var $active;
    // that is all the properties we will need.. FOR NOW!!!
    // now we need to define the methods IE what the deal object is capable of
    
    function separateTags() {
        $tags_string = $this -> tags;   
        // PHP's handy explode function should be able to handle this :)
        // add error checking to this function bro, since 
        $tags = explode(',', $tags_string);
        return $tags; // returns a list of tags
    }
    
    function getTags($tag_id_array) {
        // take the tag ID array and get the tag text for each
        // grab a SQL connection
        $i = 0;
        $con = getSQLConnection('deal_site');
        foreach ($tag_id_array as $id) {
            // iterate the tag array and make tag objects, return the list
            $sql = "SELECT * FROM `deal_tags` WHERE `tag_id` = " . $id;
            $res = mysql_query($sql, $con);
            while ($row = mysql_fetch_array($res)) {
                // create and add the tag object   
                $tag = new tag();
                $tag -> id = $row['tag_id'];
                $tag -> text = $row['tag_text'];
            }
            $tags[$i] = $tag;
            $i++;
        }
        // close sql connection
        mysql_close($con);
        return $tags; // return our tag object array
    }
    
    function getAddress($coords) {
        // take the coordinate pair from the DB and turn it into an address for the deal   
        exec("./python/location_tools.py revgeocode " . $coords, $output);
        return $output[0]; // looks like '1435 Pearl St, Denver, CO 80203, USA'
    }
    
// end of class    
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
    var $algo_rank; // will probably not be implemented for quite some time 
    var $active;
    // various object methods for comments will be below
}

class user {
    // this is the object that we will use to store user information in memory
    // declare the properties of the user
    var $user_id;
    var $name;
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
    function getLocation($ip_address) {
        /*
            DEPRECATED: 
                We can use our Python location tools script for this now
        
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
        */
        
        // we use Python for this now, the above code will be removed in the future
        exec("../python/location_tools.py getloc " . $ip_address, $output);
        return $output[0]; // returned in a nice easy to explode(';', $loc) format 
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
    
    function geoCode($ip_address) {
        // get the coordinates
    }
}

class company {
    // the company object stores company information for scripts
    var $id;
    var $name;
    var $rating;
    var $about;
    var $address;
    var $thumbs_up;
    var $thumbs_down;
}

class venue {
    // the venue object stores venue information for scripts
    var $id;
    var $name;
    var $company_id;
    var $latitude;
    var $longitude;
    var $about;
    var $rating;
    var $thumbs_up;
    var $thumbs_down;
}

class ipinfo {
    // this object will hold information about ip addresses
    var $id; 
    var $ip_address;
    var $city;
    var $state;
    var $zip;
    var $latitude;
    var $longitude;
    
    function addIP() {
        // this will take an IP object and add it to the database
        // this database is for geolocation, to make our site more accurate
        // let us build the SQL query
        $sql = "INSERT INTO `ipgeoloc`" .
                "(ip_address,city,state,zip_code,latitude,longitude) " .
                "VALUES (" .
                "$this->ip_address,$this->city,$this->state,$this-zip,$this->latitude,$this->longitude" .
                ")";
        // now we can get our SQL connection
        $con = getSQLConnection('deal_site');
        $res = mysql_query($sql, $con);
        // close connection and return result
        mysql_close($con);
        return $res; // should be 1 or 0 for success or fail
    }
    
    function dbRetrieve($ip) {
        // populate this object with information from the database   
        // let is build our query
        $sql = "SELECT * FROM `ipgeoloc` WHERE `ip_address` = " . $ip;
        $con = getSQLConnection('deal_site');
        $res = mysql_query($sql, $con);
        while ($row = mysql_fetch_array($res)) {
            // assign the values from tht DB to the ipinfo object
            $this -> id = $row['id'];
            $this -> ip_address = $row['ip_address'];
            $this -> city = $row['city'];
            $this -> state = $row['state'];
            $this -> zip = $row['zip_code'];
            $this -> latitude = $row['latitude'];
            $this -> longitude = $row['longitude'];
            // now the object is populated, go ahead and close the SQL connection
            mysql_close($con);
        }
    }
    
    
}

class tag {
    // object for deal tags
    var $id;
    var $text;
}




?>




























