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
            success_msg($language["REVERIFY_CONGRATS1"]." ".$oldmail." ".$language["REVERIFY_CONGRATS2"]." ".$newmail." ".$language["REVERIFY_CONGRATS3"]."<a href=\"".$BASEURL."\">".$language["MNU_INDEX"]."</a>");
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
           else err_msg($language["REVERIFY_FAILURE"]."<a href=\"".$BASEURL."\">".$language["MNU_INDEX"]."</a>");
       stdfoot();
       exit;
           // End the block and add a couple of linespaces afterwards.

       }
// <--- Reverify Mail Hack by Petr1fied - End

     }
?>