<?
require_once('classes.php');

class deal_suggest {
    var $location;
    var $company;
    var $price;
    var $type;
    var $deals;
    
    function getSuggestion() {
        if (!is_int($this->company)) {
            $this->location = SQLClean($_GET['location']);   
            $this->company = SQLClean($_GET['company']);
            $this->price = SQLClean($_GET['price']);
            $this->type = SQLCLean($_GET['type']);
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
            if (is_array($this->deals)) {
                // this means we made great success
                return 1;
            }
        }
    } // end of getSuggest() method
    
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
    $response = $suggest->encodeDeals();
    echo $response; // echo it out to the script
    return 1; // success
} // end of main

main();
?>



