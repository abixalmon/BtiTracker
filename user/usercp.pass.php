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

switch ($action)
{
    case 'post':
            if ($_POST["old_pwd"]=="")
          {
        err_msg($language["ERROR"],$language["INS_OLD_PWD"]);
        stdfoot();
        exit;
          }
            elseif ($_POST["new_pwd"]=="")
          {
        err_msg($language["ERROR"],$language["INS_NEW_PWD"]);
        stdfoot();
        exit;
          }
            elseif ($_POST["new_pwd"]!=$_POST["new_pwd1"])
          {
        err_msg($language["ERROR"],$language["DIF_PASSWORDS"]);
        stdfoot();
        exit;
          }
            else
                {
                $respwd = do_sqlquery("SELECT * FROM {$TABLE_PREFIX}users WHERE id=$uid AND password='".md5($_POST["old_pwd"])."' AND username=".sqlesc($CURUSER["username"])."");
                if (!$respwd || mysql_num_rows($respwd)==0)
                   err_msg($language["ERROR"],$language["ERR_RETR_DATA"]);
                else {
                    $arr=mysql_fetch_assoc($respwd);
                    do_sqlquery("UPDATE {$TABLE_PREFIX}users SET password='".md5($_POST["new_pwd"])."' WHERE id=$uid AND password='".md5($_POST["old_pwd"])."' AND username=".sqlesc($CURUSER["username"])."") or die(mysql_error());
                    
                if($GLOBALS["FORUMLINK"]=="smf")
                {
                    $passhash=smf_passgen($CURUSER["username"], $_POST["new_pwd"]);
                    do_sqlquery("UPDATE {$db_prefix}members SET passwd='$passhash[0]', passwordSalt='$passhash[1]' WHERE ID_MEMBER=".$arr["smf_fid"]) or die(mysql_error());
                }
                    
            success_msg($language["PWD_CHANGED"], "".$language["NOW_LOGIN"]."<br /><a href=\"index.php?page=login\">Go</a>");
            stdfoot(true,false);
                    }
                }
    break;

    case '':
    case 'change':
    default:
    $pwdtpl=array();
    $pwdtpl["frm_action"]="index.php?page=usercp&amp;do=pwd&amp;action=post&amp;uid=".$uid."";
    $pwdtpl["frm_cancel"]="index.php?page=usercp&amp;uid=".$uid."";
    $usercptpl->set("pwd",$pwdtpl);
    break;
}
?>