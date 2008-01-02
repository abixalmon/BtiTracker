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

if (!defined("IN_BTIT"))
      die("non direct access!");


require_once(load_language("lang_usercp.php"));
global $CURUSER, $language, $usercptpl;

if (isset($_GET["what"]) && $_GET["what"])
      $what=$_GET["what"];
else $what = "inbox";

if (isset($_GET["action"]) && $_GET["action"])
      $action=$_GET["action"];
else $action = "";

if (isset($_GET["preview"]) && $_GET["preview"])
      $preview=$_GET["preview"];
else $preview = "";

$uid=(isset($_GET["uid"])?intval($_GET["uid"]):1);

if ($CURUSER["uid"]!=$uid || $CURUSER["uid"]==1)
   {
       err_msg($language["ERROR"],$language["ERR_USER_NOT_USER"]);
       stdfoot();
       exit;
   }
else
    {
    $utorrents=max(0,$CURUSER["torrentsperpage"]);
    if (isset($_GET["do"])) $do=$_GET["do"];
      else $do = "";
    if (isset($_GET["action"]))
       $action=$_GET["action"];

$USER_PATH=dirname(__FILE__);

require_once("$USER_PATH/usercp.menu.php");
$menucptpl=new bTemplate();
$menucptpl->set("usercp_menu",$usercp_menu);
$tpl->set("main_left",set_block($language["USER_CP_1"],"center",$menucptpl->fetch(load_template("usercp.menu.tpl"))));

$usercptpl=new bTemplate();
$usercptpl->set("language",$language);

switch ($do)
    {
    case 'pm':
    include("$USER_PATH/usercp.pmbox.php");
    $tpl->set("main_content",set_block($language["MNU_UCP_PM"],"center",$usercptpl->fetch(load_template("usercp.pmbox.tpl"))));
    break;

    case 'user':
    include("$USER_PATH/usercp.profile.php");
    $tpl->set("main_content",set_block($language["ACCOUNT_EDIT"],"center",$usercptpl->fetch(load_template("usercp.profile.tpl"))));
    break;

    case 'pwd':
    include("$USER_PATH/usercp.pass.php");
    $tpl->set("main_content",set_block($language["MNU_UCP_CHANGEPWD"],"center",$usercptpl->fetch(load_template("usercp.pass.tpl"))));
    break;

    case 'pid_c':
    include("$USER_PATH/usercp.pidchange.php");
    $tpl->set("main_content",set_block($language["CHANGE_PID"],"center",$usercptpl->fetch(load_template("usercp.pidchange.tpl"))));
    break;

    default:
    include("$USER_PATH/usercp.main.php");
    $tpl->set("main_content",set_block($language["MNU_UCP_HOME"],"center",$usercptpl->fetch(load_template("usercp.main.tpl"))));
    break;
}


// Reverify Mail Hack by Petr1fied - Start --->
// Update the members e-mail account if the validation link checks out
// ==========================================================================================
    // If both "do=verify" and "action=changemail" are in the url

    if ($do=="verify" && $action=="changemail")
       {
       // Get the other values we need from the url
       $newmail=$_GET["newmail"];
       $id=max(0,$_GET["uid"]);
       $random=max(0,$_GET["random"]);
       $idlevel=$CURUSER["id_level"];

       // Get the members random number, current email and temp email from their record
       $getacc=mysql_fetch_assoc(do_sqlquery("SELECT random, email, temp_email".(($GLOBALS["FORUMLINK"]=="smf") ? ", smf_fid" : "")." from {$TABLE_PREFIX}users WHERE id=".$id));
       $oldmail=$getacc["email"];
       $dbrandom=$getacc["random"];
       $mailcheck=$getacc["temp_email"];

       // If the random number in the url matches that in the member record
       if ($random==$dbrandom)
       {

           // Verify the email address in the url is the address we sent the mail to
           if ($newmail!=$mailcheck) {
             err_msg($language["ERROR"],$language["NOT_MAIL_IN_URL"]);
             stdfoot();
             exit;
           }

            // Update their tracker member record with the now verified email address
            do_sqlquery("UPDATE {$TABLE_PREFIX}users SET email='".mysql_escape_string($newmail)."' WHERE id='".$id."'");

            // If using SMF, update their record on that too.            
            if($GLOBALS["FORUMLINK"]=="smf")
            {
                $basedir=substr(str_replace("\\", "/", dirname(__FILE__)), 0, strrpos(str_replace("\\", "/", dirname(__FILE__)), '/'));
                $language2=$language;
                require_once($basedir."/smf/Settings.php");
                $language=$language2;
                do_sqlquery("UPDATE {$db_prefix}members SET emailAddress='".mysql_escape_string($newmail)."' WHERE ID_MEMBER=".$getacc["smf_fid"]);
            }
            
            // Print a message stating that their email has been successfully changed
            success_msg($language["SUCCESS"],$language["REVERIFY_CONGRATS1"]." ".$oldmail." ".$language["REVERIFY_CONGRATS2"]." ".$newmail." ".$language["REVERIFY_CONGRATS3"]."<a href=\"".$BASEURL."\">".$language["MNU_INDEX"]."</a>");
            stdfoot(true,false);
            // If the member clicking the link is validating...
            if ($idlevel==2)
            {
                // ...we may as well upgrade their rank to member whilst we're at it.
                do_sqlquery("UPDATE {$TABLE_PREFIX}users SET id_level=3 WHERE id='".$id."'");
                if($GLOBALS["FORUMLINK"]=="smf")
                    do_sqlquery("UPDATE {$db_prefix}members SET ID_GROUP=13 WHERE ID_MEMBER=".$getacc["smf_fid"]);
            }
       }
       // If the random number in the url is incorrect print an error message
       else
         {
         err_msg($language["REVERIFY_FAILURE"]."<a href=\"".$BASEURL."\">".$language["MNU_INDEX"]."</a>");
         stdfoot();
         exit;
       }
       // End the block and add a couple of linespaces afterwards.

       }
// <--- Reverify Mail Hack by Petr1fied - End

     }
?>