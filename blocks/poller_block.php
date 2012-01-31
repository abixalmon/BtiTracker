<?php
/////////////////////////////////////////////////////////////////////////////////////
// xbtit - Bittorrent tracker/frontend
//
// Copyright (C) 2004 - 2012  Btiteam
//
//    This file is part of xbtit.
//
// Redistribution and use in source and binary forms, with or without modification,
// are permitted provided that the following conditions are met:
//
//   1. Redistributions of source code must retain the above copyright notice,
//      this list of conditions and the following disclaimer.
//   2. Redistributions in binary form must reproduce the above copyright notice,
//      this list of conditions and the following disclaimer in the documentation
//      and/or other materials provided with the distribution.
//   3. The name of the author may not be used to endorse or promote products
//      derived from this software without specific prior written permission.
//
// THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR IMPLIED
// WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
// MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
// IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
// SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED
// TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
// PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
// LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
// NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE,
// EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
//
////////////////////////////////////////////////////////////////////////////////////


global $STYLEURL,$btit_settings;

if ($GLOBALS["ajax_poller"])
{

  if (!$CURUSER || $CURUSER["view_users"]=="yes")
    {
      print("<a name=\"poll\" /></a>");
      block_begin(LATEST_POLL);
  ?>
  <table border="0" class="block" cellspacing="0" cellpadding="0" width="100%"><tr><td align="center">
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return false" method="post" name="poller">
  <div id="mainContainer">
      <div id="mainContent">
    <?php
    $poll = get_result("SELECT * FROM {$TABLE_PREFIX}poller WHERE active='yes' ORDER BY ID DESC LIMIT 1",true,$btit_settings['cache_duration']);
    if($poll)
    $pollerId = $poll[0]["ID"];  // Id of poller
    ?>
    <!-- START OF POLLER -->
    <div class="poller">
      <div class="poller_question" style="padding-left:5px; padding-right:5px;" id="poller_question<?php echo $pollerId; ?>">
      <?php      
      // Retreving poll from database
      $res = get_result("select * from {$TABLE_PREFIX}poller where ID='$pollerId' LIMIT 1",true,$btit_settings['cache_duration']);
      if($res){
        $inf=$res[0];
        echo "<div class=\"pollerTitle\">".$inf["pollerTitle"]."</div>";  // Output poller title
        $resOptions = get_result("select * from {$TABLE_PREFIX}poller_option where pollerID='$pollerId' order by pollerOrder",true,$btit_settings['cache_duration']);  // Find poll options, i.e. radio buttons
        foreach($resOptions as $id=>$infOptions){
          if($infOptions["defaultChecked"])$checked=" checked=\"checked\""; else $checked = "";
          echo "<div class=\"pollerOption\"><input$checked type=\"radio\" value=\"".$infOptions["ID"]."\" name=\"vote[".$inf["ID"]."]\" id=\"pollerOption".$infOptions["ID"]."\" /><label for=\"pollerOption".$infOptions["ID"]."\" id=\"optionLabel".$infOptions["ID"]."\">".$infOptions["optionText"]."</label></div>";  
        }
      }      
      ?>      
      <!-- <a href="#poll" onclick="castMyVote(<?php echo $pollerId; ?>,document.forms['poller'])"><img src="images/vote_button.gif"></a> -->
      <img src="images/vote_button.gif" alt="<?php echo $language["CAST_VOTE"]; ?>" title="<?php echo $language["CAST_VOTE"]; ?>" onclick="castMyVote(<?php echo $pollerId; ?>,document.forms['poller'])" style="cursor:pointer;"/>

      </div>
      <div class="poller_waitMessage" id="poller_waitMessage<?php echo $pollerId; ?>" align="center">
        <br /><br /><br /><br /><table border="0" cellspacing="0" cellpadding="4"><tr><td align="center" style="background-image: url('images/ajax-loader.gif'); background-repeat: no-repeat; background-position:center center; width:16px; height:16px;"></td><td align="left"><?php echo $language["FETCHING_RESULTS"]; ?></td></tr></table><br /><br /><br /><br /><br />
      </div>
      <div class="poller_results" style="padding-left:5px; padding-right:5px;" id="poller_results<?php echo $pollerId; ?>">
      <!-- This div will be filled from Ajax, so leave it empty --></div>
      <br />
      <br />
    </div>
    <!-- END OF POLLER -->
      <?php

        $uid_query = get_result("SELECT COUNT(ID) FROM {$TABLE_PREFIX}poller_vote WHERE memberID='".$CURUSER['uid']."' AND pollerID='".$pollerId."'",true,$btit_settings['cache_duration']);
        $ip_query = get_result("SELECT COUNT(ID) FROM {$TABLE_PREFIX}poller_vote WHERE ipAddress='".ip2long($_SERVER['REMOTE_ADDR'])."' AND pollerID='".$pollerId."'",true,$btit_settings['cache_duration']);

          if ($GLOBALS["ipcheck_poller"]==false)
      {
        if ($uid_query)
          $uidcount = $uid_query[0];
        $uid = $uidcount["COUNT(ID)"];
        $ip = 0;
      }
          elseif ($GLOBALS["ipcheck_poller"]==true)
       {
        if ($uid_query)
          $uidcount = $uid_query[0];
        if ($ip_query)
          $ipcount = $ip_query[0];
        $uid = $uidcount["COUNT(ID)"];
        $ip = $ipcount["COUNT(ID)"];
      }

          if ($uid == "0" && $ip >= "1" || $uid >= "1" && $ip >= "1" || $uid >= "1" && $ip == "0")
      {
        print("<script type=\"text/javascript\">\n");
        print("if(useCookiesToRememberCastedVotes){\n");
        // This is the code you can use to prevent someone from casting a vote. You should check on cookie or ip address
        print("displayResultsWithoutVoting(".$pollerId.");\n");
        print("}\n");
        print("</script>\n");
      }

      ?>

      </div>
      <div class="clear"></div>
  </div>
  </form>
  </td></tr></table>
      <?php
      block_end();
    }

}
else
{

require_once ("include/functions.php");
require_once ("include/config.php");

dbconn();

     $res =mysql_query("SELECT * FROM {$TABLE_PREFIX}polls WHERE status='true'") or die(mysql_error());
     $result=mysql_fetch_array($res);
   $pid=$result["pid"];
if($result){
     $res2=mysql_query("SELECT * FROM {$TABLE_PREFIX}poll_voters WHERE pid='$pid'") or die(mysql_error());
     $question=$result["poll_question"];
     block_begin("Poll: $question");
     print("<tr><td class=blocklist align=center>\n");
     print("<table cellspacing=2 cellpading=2>\n");
if(!isset ($CURUSER)) global $CURUSER;
$total_votes = 0;
$check=0;
if($CURUSER["id_level"]<3 || (isset($_POST['showres']) && $_POST['showres'] == 'Show Results')) $check=1;
else $check=0;
while($voters=mysql_fetch_array($res2)){
if($CURUSER["uid"]==$voters["memberid"]) $check=1;
}


        if($check==1){  
          
          $poll_answers = unserialize(stripslashes($result["choices"]));
          
          reset($poll_answers);
          foreach ($poll_answers as $entry)
          {
            $id     = $entry[0];
            $choice = $entry[1];
            $votes  = $entry[2];
            
            $total_votes += $votes;
            
            if ( strlen($choice) < 1 )
            {
              continue;
            }
            
                   
            $percent = $votes == 0 ? 0 : $votes / $result["votes"] * 100;
            $percent = sprintf( '%.2f' , $percent );
            $width   = $percent > 0 ? floor( round( $percent )*0.7) : 0;
      $percent = floor($percent);
            
      print ("<tr><td width=\"50%\" class=\"lista\">$choice</td><td class=\"lista\"> (<b>$votes</b>) </td><td class=\"lista\"><img src=\"images/bar.gif\" width=\"4\" height=\"11\" align=\"left\" alt=\"\" title=\"bar\" border=\"0\" /></td><td align=\"left\" class=\"lista\">&nbsp;($percent%)</td></tr>");
          }
  }



  elseif($check==0){
// Show poll form


    $poll_answers = unserialize(stripslashes($result["choices"]));
          reset($poll_answers);
          
     ?>     
   <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">     
  <?php
          foreach ($poll_answers as $entry)
          {
            $id     = $entry[0];
            $choice = $entry[1];
            $votes  = $entry[2];
            
            $total_votes += $votes;
            
            if ( strlen($choice) < 1 )
            {
              continue;
            }
            
        ?>
      <tr><td colspan="3" align="left"><input type="radio" name="poll_vote" value="<?php echo $id?> " /><b>&nbsp;<?php echo $choice ?><b> </td></tr>
  <?php


          }

    print("\n<td align=\"left\" class=\"lista\"><input type=\"submit\" name=\"submit\" value=\"Submit\" />&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" name=\"showres\" value=\"Show Results\" /></td>");
?>
</form>
<?php
}            
if(isset($_POST['submit']) && $_POST['submit'] == 'Submit' && isset($_POST['poll_vote']) && $check!=1){
  $voteid=$_POST['poll_vote'];
  $memberid=$CURUSER["uid"];
  $ip= $_SERVER['REMOTE_ADDR'];
  $new_poll_array=array();
  mysql_query("INSERT INTO poll_voters SET ip='$ip', votedate='".time()."', pid='$pid', memberid='$memberid'");
  $poll_answers = unserialize(stripslashes($result["choices"]));
  reset($poll_answers);

  foreach ($poll_answers as $var){
            $id     = $var[0];
            $choice = $var[1];
            $votes  = $var[2];
    if($id==$voteid) $votes++;
    $new_poll_array[] = array( $id, $choice, $votes);
  }
  $votings= addslashes(serialize($new_poll_array));
  $uvotes=$result["votes"]+1;
  mysql_query("UPDATE {$TABLE_PREFIX}polls SET votes='$uvotes', choices='$votings' WHERE pid='$pid'");
  redirect("index.php");
  
}
    


     print("</table>\n</td></tr>");
     block_end();
}

}

?>