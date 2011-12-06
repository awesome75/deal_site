<?
// functions_secure.php takes care of a few functions we can't be having in a public repo, 
// like getting a SQL connection or hashing and salting a user's password

function sgetSQLConnection($db) {
    $con = mysql_connect('localhost', 'deals', '');
    mysql_select_db($db);
    return $con;
}

function hashNsalt($str) {
    // turn the string (probably a password) into a hopefully unrecoverable 
    return (crypt($str, 'sh4k31tl1k34s4ltsh4k3r'));
}

?>
