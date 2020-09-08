<?php
$mydbhost = "localhost";
$mydbuser = "root";
$mydbpw = "root";
$mydbname = "colour_manage";
$mydbcharset = "UTF8";
$db = @mysql_connect($mydbhost,$mydbuser,$mydbpw);
if (!$db) {
        exit('Unable connect MYSQL at this time');
}
if (!@mysql_select_db($mydbname)) {
        exit ('Unable connect DB at this time');
}
mysql_query("SET NAMES $mydbcharset", $db);
?>