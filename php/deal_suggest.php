<?
require_once('classes.php');

class deal_suggest {
    var $location = SQLClean($_GET['location']);   
    var $company = SQLClean($_GET['company']);
    var $price = SQLClean($_GET['price']);
    var $type = SQLCLean($_GET['type']);

    function determineQuery() {
        if (!is_int($this->company)) {
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
        if ($company != "" && $price == "" && $type == "") {
            // we are going company only
            $deals = getDeals(null, null, null, $this->company);
        }
    }






}
?>