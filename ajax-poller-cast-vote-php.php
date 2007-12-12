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

  $userID = intval(0+$CURUSER['uid']);


  // Insert new vote into the database
  // You may put in some more code here to limit the number of votes the same ip adress could cast.

  if($optionId)mysql_query("INSERT INTO {$TABLE_PREFIX }poller_vote(pollerID,optionID,ipAddress,voteDate,memberID) VALUES('".$pollId."','".$optionId."','".ip2long(getenv("REMOTE_ADDR"))."',unix_timestamp(),'".$userID."')");

  // Returning data as xml

  echo '<?xml version="1.0" ?>';

  $res = mysql_query("select ID,pollerTitle from {$TABLE_PREFIX}poller where ID='".$pollId."'");
  if($inf = mysql_fetch_array($res)){
    echo "<pollerTitle>".unesc($inf["pollerTitle"])."</pollerTitle>\n";

    $resOptions = mysql_query("select ID,optionText from {$TABLE_PREFIX}poller_option where pollerID='".$inf["ID"]."' order by pollerOrder") or die(mysql_error());
    while($infOptions = mysql_fetch_array($resOptions)){
      echo "<option>\n";
      echo "\t<optionText>".unesc($infOptions["optionText"])."</optionText>\n";
      echo "\t<optionId>".$infOptions["ID"]."</optionId>\n";

      $resVotes = mysql_query("select count(ID) from {$TABLE_PREFIX}poller_vote where optionID='".$infOptions["ID"]."' AND pollerID='".$inf["ID"]."'");
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