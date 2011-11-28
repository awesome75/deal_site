<?
// deals site get_companies.php
require_once('functions.php'); // don't forget our functions file
require_once('classes.php'); // gonna need that deal class
// get a SQL connection
$con = getSQLConnection('deal_site');
$sql = "SELECT * FROM `companies`";
$res = mysql_query($sql, $con);
$ctrl = 0; // loop count
// iterate the companies
while ($row = mysql_fetch_array($res)) {
    $company = new company();
    $company -> id = $row['company_id'];
    $company -> name = $row['company_name'];
    $company -> rating = $row['company_rating'];
    $company -> about = $row['company_about'];
    $company -> thumbs_up = $row['company_thumbs_up'];
    $company -> thumbs_down = $row['thumbs_down'];
    $companies[$ctrl] = $company;
    // increment the loop
    $ctrl++;
}
// close our MySQL connection
mysql_close($con);
// the calling script can now access the companies in the $companies array
?>






