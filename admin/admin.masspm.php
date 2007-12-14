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

global $CURUSER;
// only for security, maybe someone try to access directly to the file...

if (!defined("IN_BTIT"))
      die("non direct access!");

if (!defined("IN_ACP"))
      die("non direct access!");


/**
Mass PM by vibes, Fuctions include ability to PM all users
or PM by userlevel or PM by Ratio, Ratio and userlevel work together so
you can PM say members with a ratio of 0.5 and below
**/

//MASSPM SETTINGS

//This is for the drop down ratio box Where do you want the ratio range to start from?
$value=0.0;
//This is for the ending ratio range where do you want it to end?
$cutoff=10.0;
//Should we PM the sender a copy of the PM? usage: true or False
$pm_sender= true;
//Should we list the users PMed in the PM sent Box? usage: True or False
$list_users= true;
//what should the default subject be if none set?
$default_subject= "Global Notice";
//Who will the PM be sent from, you can register an acounnt here called system then change to $sender=100; where 100 is the systems UID number
$sender= $CURUSER['uid'];
//This will be added to the end of each message to deactivate set value to false EG $footer = false; by adding a \r in the footer before message will insert a new line
$footer= "\n\nthis is an automated system please do not reply!!!";

//!!!!!*****DEBUG MODE*****!!!!! set to false for testing, DO NOT alter if you do not know how to read the code below this setting (comments added to make reading the code easy)!!! PMs wont send if set to false, recommented modes for testing are $list_users= true; and $pm_sender= true; to check PM is sent ;)
$pm = true;

//END OF SETTINGS, DONOT EDIT BELOW USNLESS YOU KNOW WHAT YOU ARE DOING!!!!

// initialize some variable...
$ratio=0;
$pick=0;
$msg="";
$ratio_details="";
$l_users="";
$level=0;
$level1=0;

// end

if(isset($_GET["error"]))
    $error=$_GET["error"];
else
    $error="";

if (!isset($_GET["action"]))
    $_GET["action"]="write";

$masspm=array();


//check if Mass PM was posted
if ($_GET["action"]=="post")
{
    if(isset($_POST['masspm']))
    {
    //collect info from form
      $ratio = (isset($_POST["ratio"])?$_POST["ratio"]:0);
      $pick = (isset($_POST["pick"])?$_POST["pick"]:0);
      $level=intval(0+$_POST["level"]);
      $level1=intval(0+$_POST["level1"]);
      $subject = sqlesc($_POST["subject"]);
      $original_subject=htmlspecialchars(($subject=="''"?$default_subject:$_POST["subject"]));
      $msg = (isset($_POST["msg"])?$_POST["msg"]:"");
    //check if a subject was set, if not asign one
    if ($subject=="''")
        $subject="'$default_subject'";
    //check if a message was set, if not redirect back to form with error
    if($msg == "")
    {
      redirect("index.php?page=admin&user=".$CURUSER["uid"]."&code=".$CURUSER["random"]."&do=masspm&action=write&error=return");
      exit();
    }
    //check if we want to PM selected userlevels
      if ($level>0)
      {
         
        $user_id_query = do_sqlquery("SELECT level FROM {$TABLE_PREFIX}users_level WHERE id_level=$level",true);
        $user_rank=mysql_fetch_array($user_id_query);
        $user_level=$user_rank['level'];

        $user_id_query1 = do_sqlquery("SELECT level FROM {$TABLE_PREFIX}users_level WHERE id_level=$level1",true);
        $user_rank1=mysql_fetch_array($user_id_query1);
        $user_level1=$user_rank1['level'];

        if($level1>0 && $level < $level1)
        {
          $where = " AND id_level>=$level AND id_level<=$level1";
          $usr_lev = "in ".$language["USER_LEVEL"]."s <b>($user_level - $user_level1)</b>";
        }

        elseif($level1>0 && $level > $level1)
        {
          $where = " AND id_level<=$level AND id_level>=$level1";
          $usr_lev = "in ".$language["USER_LEVEL"]."s <b>($user_level1 - $user_level)</b>";
        }

        elseif($level>0 && $level1==0 || $level1>0 && $level1 == $level)
        {
          $where = " AND id_level=$level";
          $usr_lev = "in ".$language["USER_LEVEL"]." <b>($user_level)</b>";
        }

      }
    // this just incase first box is set to all and second is set to a level, setup to PM the one level selected :)
      elseif($level==0 && $level1>0)
      {
        $user_id_query1 = do_sqlquery("SELECT level FROM {$TABLE_PREFIX}users_level WHERE id_level=$level1")or sqlerr(__FILE__, __LINE__);
        $user_rank1=mysql_fetch_array($user_id_query1);
        $user_level1=$user_rank1['level'];
        $where = " AND id_level=$level1";
        $usr_lev = "in ".$language["USER_LEVEL"]." <b>($user_level1)</b>";
      }
    //no userlevels selected to PM so PM everyone
      else
      {
        $where = "";
        $usr_lev = "in all ".$language["USER_LEVEL"]."s";
      }

    // do we want to PM users based on ratio?
    $check_ratio=false;
    if($ratio>0)
    {
    $check_ratio=true;
    }

    //add a footer to the message
    if($footer)
        $msg .= $footer;

    $original_msg=$msg;
    $msg = sqlesc($msg);
    $i = 0;

    if ($XBTT_USE)
       {
          $udownloaded="u.downloaded+IFNULL(x.downloaded,0)";
          $uuploaded="u.uploaded+IFNULL(x.uploaded,0)";
          $utables="{$TABLE_PREFIX}users u LEFT JOIN xbt_users x ON x.uid=u.id";
       }
    else
        {
          $udownloaded="u.downloaded";
          $uuploaded="u.uploaded";
          $utables="{$TABLE_PREFIX}users u";
        }

    //do database call
        $result_id = do_sqlquery("SELECT u.id, username, $udownloaded as downloaded, $uuploaded as uploaded FROM $utables where u.id > 1$where",true);
       while ($id_collect = mysql_fetch_array ($result_id))
        {
          if(!$list_users)
              $l_users ="not listing users as its deactivated";
          $user_id = $id_collect['id'];
          // stop PM to sender added function below to PM sender ;)
          if($user_id == $CURUSER['uid']) continue;

          //did we want to PM based on ratio?
          if($check_ratio)
          {
          $downloaded = $id_collect["downloaded"];
          $uploaded = $id_collect["uploaded"];
          //added in to stop divisons by zero
            if($downloaded == 0)
              $downloaded = "0.2";
            if($uploaded == 0)
              $uploaded = "0.1";
            $ratio1=number_format($uploaded/$downloaded,2);
          // if matching ratio
            if($pick == 0)
            {
              $ratio_details = "with a ".$language["RATIO"]." of <b>($ratio)</b>";
              if($ratio == $ratio1)
                {
                if($list_users)
                      $l_users[] ="<a href=\"index.php?page=userdetails&amp;id=$user_id\">".$id_collect['username']."</a>";
                if($pm)
                      do_sqlquery("INSERT INTO {$TABLE_PREFIX}messages (sender, receiver, added, subject, msg) VALUES ($sender,$user_id,UNIX_TIMESTAMP(),$subject,$msg)");
                }
            else continue;
            }
          //if ratio X + greater
            if($pick == 1)
            {
              $ratio_details = "with a ".$language["RATIO"]." of <b>($ratio)</b> and above";
              if($ratio < $ratio1)
                {
                if($list_users)
                      $l_users[] ="<a href=\"index.php?page=userdetails&amp;id=$user_id\">".$id_collect['username']."</a>";
                if($pm)
                      do_sqlquery("INSERT INTO {$TABLE_PREFIX}messages (sender, receiver, added, subject, msg) VALUES ($sender,$user_id,UNIX_TIMESTAMP(),$subject,$msg)");
                }
            else continue;
            }
          //if ratio X + lower
            if($pick == 2)
            {
              $ratio_details = "with a ".$language["RATIO"]." of <b>($ratio)</b> and below";
              if($ratio > $ratio1)
                {
                if($list_users)
                      $l_users[] ="<a href=\"index.php?page=userdetails&amp;id=$user_id\">".$id_collect['username']."</a>";
                if($pm)
                      do_sqlquery("INSERT INTO {$TABLE_PREFIX}messages (sender, receiver, added, subject, msg) VALUES ($sender,$user_id,UNIX_TIMESTAMP(),$subject,$msg)");
                }
            else continue;
            }

          }
          //otherwise we did not want to pm users based on ratio
          else
          {
          if($list_users)
              $l_users[] ="<a href=\"index.php?page=userdetails&amp;id=$user_id\">".$id_collect['username']."</a>";
          if($pm)
              do_sqlquery("INSERT INTO {$TABLE_PREFIX}messages (sender, receiver, added, subject, msg) VALUES ($sender,$user_id,UNIX_TIMESTAMP(),$subject,$msg)");
          }
          $i = $i+ 1;
    }
    }
    // PM sender if true
    if($pm_sender)
        do_sqlquery("INSERT INTO {$TABLE_PREFIX}messages (sender, receiver, added, subject, msg) VALUES ($sender,".$CURUSER['uid'].",UNIX_TIMESTAMP(),$subject,$msg)");

    $block_title=$language["MASS_SENT"];
    $admintpl->set("language",$language);
    $masspm["subject"]=$original_subject;
    $masspm["body"]=format_comment($original_msg);
    $masspm["info"]="<b>$i</b> ".$language["USERS_FOUND"]." $usr_lev $ratio_details !!<br /><br />".$language["USERS_PMED"]."<br />".implode(" - ",$l_users);
    $admintpl->set("masspm",$masspm);
    $admintpl->set("masspm_post",true,true);



}
// no pm set so display the form
elseif($_GET["action"]=="write")
   {

    $block_title=$language["MASS_SENT"];
   
    //error?
    if($error=="return")
        $admintpl->set("frm_error",true,true);
    else
        $admintpl->set("frm_error",false,true);

    $admintpl->set("language",$language);
    $admintpl->set("frm_action","index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=masspm&amp;action=post");


    $res=get_result("SELECT id,level FROM {$TABLE_PREFIX}users_level WHERE id_level>1 ORDER BY id_level",true);
    $combo="\n<select name=\"level\">";
    $combo.="\n<option value=\"0\"".($level==0 ? " selected=\"selected\" " : "").">".$language["ALL"]."</option>";
    foreach($res as $id=>$row)
        {
           $combo.="\n<option value=\"".$row["id"]."\"";
           if ($level==$row["id"])
              $combo.="selected=\"selected\"";
           $combo.=">".$row["level"]."</option>";
     }
     $combo.="\n</select>";

    $masspm["combo_from_group"]=$combo;

    $combo="\n<select name=\"level1\">";
    $combo.="\n<option value=\"0\"".($level1==0 ? " selected=\"selected\" " : "").">".$language["ALL"]."</option>";
    reset($res);
    foreach($res as $id=>$row)
        {
           $combo.="\n<option value=\"".$row["id"]."\"";
           if ($level1==$row["id"])
              $combo.="selected=\"selected\"";
           $combo.=">".$row["level"]."</option>";
     }
     $combo.="\n</select>";


    $masspm["combo_to_group"]=$combo;

    $combo="\n<select name=\"ratio\"><option value=\"0\"".($ratio==0 ? " selected=\"selected\" " : "").">".$language["ANY"]."</option>";

    for($value=0;$value <= ($cutoff*10);$value++)
        {
        $cur=($value/10);
        $combo.="\n<option value=\"$cur\"".($ratio==$cur ? " selected=\"selected\" " : "").">$cur</option>";
       }
    $combo.="\n</select>";

    unset($res);

    $masspm["combo_from_ratio"]=$combo;

    $combo="\n<select name=\"pick\">";
    $combo.="\n<option value=\"0\"".($pick==0 ? " selected=\"selected\" " : "").">".$language["RATIO_ONLY"]."</option>";
    $combo.="\n<option value=\"1\"".($pick==1 ? " selected=\"selected\" " : "").">".$language["RATIO_GREAT"]."</option>";
    $combo.="\n<option value=\"2\"".($pick==2 ? " selected=\"selected\" " : "").">".$language["RATIO_LOW"]."</option>";
    $combo.="\n</select>";

    $masspm["combo_pick_ratio"]=$combo;
    $masspm["body"]=textbbcode("masspm","msg","$msg");
    $admintpl->set("masspm",$masspm);
    $admintpl->set("masspm_post",false,true);



}
else
    redirect("index.php?page=admin&user=".$CURUSER["uid"]."&code=".$CURUSER["random"]."");
?>