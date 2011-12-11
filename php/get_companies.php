<?
// deals site get_companies.php
require_once('functions.php'); // don't forget our functions file
require_once('classes.php'); // gonna need that deal class
// we need to use the GET url values to determine the query
if ($_GET['q']) {
     // this means we are going to do an auto-suggest
     $sql = sprintf("SELECT * FROM `companies` WHERE `company_name` LIKE '%%%s%%'", SQLClean($_GET['q']));
}
// get a SQL connection
$con = getSQLConnection('deal_site');
$res = mysql_query($sql, $con);
$ctrl = 0; // loop count
// iterate the companies
while ($row = mysql_fetch_array($res)) {
    $company = new company();
    $company -> id = $row['company_id'];
    $company -> name = $row['company_name'];
    $company -> rating = $row['company_rating'];
    $company -> about = $row['company_about'];
    $company -> address = $row['company_address'];
    $company -> thumbs_up = $row['company_thumbs_up'];
    $company -> thumbs_down = $row['thumbs_down'];
    $companies[$ctrl] = $company;
    // increment the loop
    $ctrl++;
}
if ($_GET['q']) {
    // this means a JS script is expecting us to return a company object for it to create
    $ret = "%s,%s,%s,%s,%s,%s,%s";
    echo sprintf($ret,$company->id,$company->name,$company->rating,$company->about,$company->address,$company->thumbs_up,$company->thumbs_down);
}
// close our MySQL connection
mysql_close($con);
// the calling script can now access the companies in the $companies array
?>






