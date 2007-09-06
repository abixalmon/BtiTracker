<?php

  /*################################################################
  #
  #         Ajax MySQL shoutbox for btit
  #         Version  1.0
  #         Author : miskotes
  #         Created: 11/07/2007
  #         Contact: miskotes [at] yahoo.co.uk
  #         Website: YU-Corner.com
  #         Credits: linuxuser.at, plasticshore.com
  #
  ################################################################*/
    
  require_once("format_shout.php");
  require_once("../include/functions.php");

# Headers are sent to prevent browsers from caching.. IE is still resistent sometimes
header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" ); 
header( "Cache-Control: no-cache, must-revalidate" ); 
header( "Pragma: no-cache" );
header("Content-Type: text/html; charset=$GLOBALS[charset]");


# getting the style
print "<link href='default.css' rel='stylesheet' type='text/css' />";


# if no id of the last known message id is set to 0
if (!$lastID) { $lastID = 0; }

# call to retrieve all messages with an id greater than $lastID
getData($lastID);

# function that do retrieve all messages with an id greater than $lastID
function getData($lastID) {

  require_once("conn.php"); # getting connection data
  include("../include/settings.php");	# getting table prefix
  include("../include/offset.php");

	$sql = 	"SELECT * FROM {$TABLE_PREFIX}chat WHERE id > ".$lastID." ORDER BY id DESC LIMIT 10";
	$conn = getDBConnection(); # establishes the connection to the database
	$results = mysql_query($sql, $conn);
	
	# getting the data array
	while ($row = mysql_fetch_array($results)) {
	
    # getting the data array
		$id   = $row[id];
		$uid  = $row[uid];
		$time = $row[time];
		$name = $row[name];
		$text = $row[text];
		
		# if no name is present somehow, $name and $text are set to the strings under
		# we assume all must be ok, othervise no post will be made by javascript check
		# if ($name == '') { $name = 'Anonymous'; $text = 'No message'; }

	  
	  # we put together our chat using some css		
	  $chatout = "
	             <li><span class='name'>".date("d/m/Y H:i:s", $time - $offset)." | <a href=index.php?page=userdetails&id=".$uid.">".$name."</a>:</span></li>
	             	 	    <div style='text-align:right;
	 	                  	          margin-top:-13px;
	                                margin-bottom:0px;
	                                color: #006699;
	 	                  '>
	 	                  # $id</div>
 
	             <!-- # chat output -->
	             <div class='chatoutput'>".format_shout($text)."</div>
	             ";

		 echo $chatout; # echo as known handles arrays very fast...

	}
}
?>