<?
// these 'helper' functions will do things like date validation for the JS scripts
require_once('classes.php');

switch ($_GET['do']) {
    case 'validate_date':
        if ($_GET['date']) {
            echo validateDate($_GET['date']);   
        }
        else {
            echo "invalid date";   
        }
        break;
        
    case 'markcodeissued':
        $sql = sprintf("UPDATE `signup_codes` SET `issued` = 1 WHERE `code` = '%s'", SQLClean($_GET['code']));
        $con = getSQLConnection('deal_site');
        $res = mysql_query($sql, $con);
        if ($res) {
            echo 0; // we're good   
        }
        else {
            echo 1;   
        }
        break;
}
// end of get handling
?>