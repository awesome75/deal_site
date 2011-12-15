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
    
    
    

}
// end of get handling
?>