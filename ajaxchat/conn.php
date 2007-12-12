<?php
/////////////////////////////////////////////////////////////////////////
// xBtit - Bittorrent tracker/frontend
//
// Copyright (C) 2004 - 2007  Btiteam
//
//    This file is part of xBtit.
//
//    xBtit is free software: you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation, either version 3 of the License, or
//    (at your option) any later version.
//
//    xBtit is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//    You should have received a copy of the GNU General Public License
//    along with xBtit.  If not, see <http://www.gnu.org/licenses/>.
//
/////////////////////////////////////////////////////////////////////////


# establishes a connection to a mySQL Database accroding to the details specified in settings.php
function getDBConnection () {
    include("../include/settings.php"); # contains the given DB setup $database, $dbhost, $dbuser, $dbpass
    
    $conn = mysql_connect($dbhost, $dbuser, $dbpass);
    if (!$conn) {
            echo "Connection to DB was not possible!";
            end;
        }
        if (!mysql_select_db($database, $conn)) {
            echo "No DB with that name seems to exist on the server!";
            end;
        }
        return $conn;
}

# establishes a connection to a mySQL Database accroding to the details specified in settings.php
function his_getDBConnection () {
    include("include/settings.php"); # contains the given DB setup $database, $dbhost, $dbuser, $dbpass
    $conn = mysql_connect($dbhost, $dbuser, $dbpass);
    if (!$conn) {
            echo "Connection to DB was not possible!";
            end;
        }
        if (!mysql_select_db($database, $conn)) {
            echo "No DB with that name seems to exist at the server!";
            end;
        }
        return $conn;
}
?>