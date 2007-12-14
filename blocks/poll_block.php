<?php
/////////////////////////////////////////////////////////////////////////////////////
// xbtit - Bittorrent tracker/frontend
//
// Copyright (C) 2004 - 2007  Btiteam
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



     $res =do_sqlquery("SELECT * FROM {$TABLE_PREFIX}polls WHERE status='true'") or die(mysql_error());
     $result=mysql_fetch_array($res);
     $pid=$result["pid"];
if($result){
     $res2=do_sqlquery("SELECT * FROM {$TABLE_PREFIX}poll_voters WHERE pid='$pid'") or die(mysql_error());
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
                
            print ("<tr><td width=50% class=lista>$choice</td><td class=lista> (<b>$votes</b>) </td><td class=lista><img src=images/bar.gif width=$width height=11 align=left /></td><td align=left class=lista>&nbsp;($percent%)</td></tr>");
            }
    }



    elseif($check==0){
// Show poll form


        $poll_answers = unserialize(stripslashes($result["choices"]));
            reset($poll_answers);
            
     ?>     
   <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
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

        print("\n<td align=left class=lista><input type=\"submit\" name=\"submit\" value=\"Submit\" />&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" name=\"showres\" value=\"Show Results\" /></td>");
?>
</form>
<?php
}               
if(isset($_POST['submit']) && $_POST['submit'] == 'Submit' && isset($_POST['poll_vote']) && $check!=1){
    $voteid=$_POST['poll_vote'];
    $memberid=$CURUSER["uid"];
    $ip= $_SERVER['REMOTE_ADDR'];
    $new_poll_array=array();
    do_sqlquery("INSERT INTO {$TABLE_PREFIX}poll_voters SET ip='$ip', votedate='".time()."', pid='$pid', memberid='$memberid'");
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
    do_sqlquery("UPDATE {$TABLE_PREFIX}polls SET choices='$votings' WHERE pid='$pid'");
    do_sqlquery("UPDATE {$TABLE_PREFIX}polls SET votes='$uvotes' WHERE pid='$pid'");
    redirect($_SERVER['REQUEST_URI']);
    
}
        


     print("</table>\n</td></tr>");
     block_end();
}
else echo "<div align=\"center\">".$language["CANT_FIND_POLL"]."</div>";
?>