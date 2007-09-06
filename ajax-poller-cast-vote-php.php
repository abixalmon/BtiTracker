<?php

if(isset($_GET['pollId'])){

  require_once(dirname(__FILE__)."/include/functions.php");
  dbconn();

  $optionId = false;

  if(isset($_GET['optionId'])){
    $optionId = $_GET['optionId'];
    $optionId = preg_replace("/[^0-9]/si","",$optionId);
  }
  $pollId = $_GET['pollId'];
  $pollId = preg_replace("/[^0-9]/si","",$pollId);

  $userID = $CURUSER['uid'];


  // Insert new vote into the database
  // You may put in some more code here to limit the number of votes the same ip adress could cast.

  if($optionId)mysql_query("INSERT INTO {$TABLE_PREFIX }poller_vote(pollerID,optionID,ipAddress,voteDate,memberID) VALUES('".$pollId."','".$optionId."','".ip2long(getenv("REMOTE_ADDR"))."',unix_timestamp(),'".$userID."')");

  // Returning data as xml

  echo '<?xml version="1.0" ?>';

  $res = mysql_query("select ID,pollerTitle from {$TABLE_PREFIX }poller where ID='".$pollId."'");
  if($inf = mysql_fetch_array($res)){
    echo "<pollerTitle>".$inf["pollerTitle"]."</pollerTitle>\n";

    $resOptions = mysql_query("select ID,optionText from {$TABLE_PREFIX }poller_option where pollerID='".$inf["ID"]."' order by pollerOrder") or die(mysql_error());
    while($infOptions = mysql_fetch_array($resOptions)){
      echo "<option>\n";
      echo "\t<optionText>".$infOptions["optionText"]."</optionText>\n";
      echo "\t<optionId>".$infOptions["ID"]."</optionId>\n";

      $resVotes = mysql_query("select count(ID) from {$TABLE_PREFIX }poller_vote where optionID='".$infOptions["ID"]."' AND pollerID='".$inf["ID"]."'");
      if($infVotes = mysql_fetch_array($resVotes)){
        echo "\t<votes>".$infVotes["count(ID)"]."</votes>\n";
      }
      echo "</option>";
      
    }
  }
  exit;

}else{
  echo "No success";

}

?>