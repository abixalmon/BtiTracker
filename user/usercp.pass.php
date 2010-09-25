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
                $respwd = do_sqlquery("SELECT * FROM {$TABLE_PREFIX}users WHERE id=$uid AND password='".md5($_POST["old_pwd"])."' AND username=".sqlesc($CURUSER["username"])."",true);
                if (!$respwd || mysql_num_rows($respwd)==0)
                   err_msg($language["ERROR"],$language["ERR_RETR_DATA"]);
                else {
                    $arr=mysql_fetch_assoc($respwd);
                    do_sqlquery("UPDATE {$TABLE_PREFIX}users SET password='".md5($_POST["new_pwd"])."' WHERE id=$uid AND password='".md5($_POST["old_pwd"])."' AND username=".sqlesc($CURUSER["username"])."",true);
                    
                if($GLOBALS["FORUMLINK"]=="smf")
                {
                    $passhash=smf_passgen($CURUSER["username"], $_POST["new_pwd"]);
                    do_sqlquery("UPDATE {$db_prefix}members SET passwd='$passhash[0]', passwordSalt='$passhash[1]' WHERE ID_MEMBER=".$arr["smf_fid"],true);
                }
                    
            success_msg($language["PWD_CHANGED"], "".$language["NOW_LOGIN"]."<br /><a href=\"index.php?page=login\">Go</a>");
            stdfoot(true,false);
            exit;
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