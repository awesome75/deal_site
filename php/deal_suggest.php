<?
require_once('classes.php');

class deal_suggest {
    var $location;
    var $company;
    var $price;
    var $type;
    var $deals;
    
    function getSuggestion() {
        $this->location = SQLClean($_GET['location']);   
        $this->company = SQLClean($_GET['company']);
        $this->price = SQLClean($_GET['price']);
        $this->type = SQLCLean($_GET['type']);
        // if there is a company wee want the ID
        if ($this->company != null && !is_int($this->company)) {
            // we need to retrieve the company ID
            $sql = sprintf("SELECT `company_id` FROM `companies` WHERE `company_name` = '%s'", trim($this->company));
            $con = getSQLConnection('deal_site');
            $res = mysql_query($sql, $con);
            if (mysql_num_rows($res) == 0) {
                echo "company not found";
                die();
            }
            while ($row = mysql_fetch_array($res)) {
                $this->company = $row['company_id'];   
            }
            mysql_close($con);
        }
        // now we know we have a company id and can move on
        if ($this->company != "" && $this->price == "" && $this->type == "") {
            // we are going company only
            $this->deals = getDeals(null, null, null, $this->company);
        }
        else if ($this->company == "" && $this->price == "" && $this->type != "") {
            $this->deals = getDeals(null, $this->type, null, null);
        }
        
        else {
            // we had problem determining the query type
            return 0;
        }
        // now let's see if our result means anything
        if (is_array($this->deals)) {
            // this means we made great success
            return 1;
        }
        else {
            return 0;   
        }
    } // end of getSuggest() method
    
    function formatDeals() {
        // format the date, user, tags etc for the user
        foreach ($this->deals as $deal) {
            // iterate deals and reformat the data
            // first format the user name
            $user = new user();
            $user->user_id = $deal->deal_poster_id;
            $user->buildById();
            $deal->deal_poster_id = $user->user_name;
            // now we need to format the dates
            $deal->deal_post_date = date('F j, Y \a\t g:ia', strtotime($deal->deal_post_date));
            $deal->deal_end_date = date('F j, Y \a\t g:ia', strtotime($deal->deal_end_date));
            if (date('Y', strtotime($deal->deal_end_date)) == '1969') {
                $deal->deal_end_date = "Indefinite";   
            }
            // now let's format the tags
            $tag_array = $deal -> separateTags($deal->tags);
            $tags = $deal -> getTags($tag_array);
            $i = 0;
            $deal->tags = "";
            foreach ($tags as $tag) {
                if ($i == 0) {
                    $deal->tags = $tag->text;   
                }
                else {
                    $deal->tags .= sprintf(",%s", $tag->text);   
                }
                $i++;
            }
        }
    
    }
    
    function encodeDeals() {        
        return json_encode($this->deals);
    } // end of returnDeals() method
} // end of deal suggest class


function main() {
    $suggest = new deal_suggest();
    $result = $suggest->getSuggestion();
    if ($result != 1) {
        die('fail');
    }
    // if the script is still running we will need to parse and return data to the calling JS function
    $suggest->formatDeals();
    // now encode it to JSON
    $response = $suggest->encodeDeals();
    echo $response; // echo it out to the script
    return 1; // success
} // end of main

main();
?>



