<?php

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