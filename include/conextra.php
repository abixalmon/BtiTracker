<?php

function getConnection () {

    $CURRENTPATH = dirname(__FILE__);

    include($CURRENTPATH."/settings.php"); # contains the given DB setup $database, $dbhost, $dbuser, $dbpass
    
    $Econn = mysql_connect($dbhost, $dbuser, $dbpass);
    if (!$Econn) {
            echo "Connection to DB was not possible!";
            end;
        }
        if (!mysql_select_db($database, $Econn)) {
            echo "No DB with that name seems to exist on the server!";
            end;
        }
        
        if($GLOBALS["charset"]=="UTF-8")
            mysql_query("SET NAMES utf8");
        
        return $Econn;
}

function getPrefix () {

    $CURRENTPATH = dirname(__FILE__);

    include($CURRENTPATH."/settings.php"); # contains the given DB setup $database, $dbhost, $dbuser, $dbpass
    
    return $TABLE_PREFIX;
}
?>