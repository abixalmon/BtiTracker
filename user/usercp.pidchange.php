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
          $pid=md5(uniqid(rand(),true));
          $res=do_sqlquery("UPDATE {$TABLE_PREFIX}users SET pid='".$pid."' WHERE id='".$CURUSER['uid']."'");
          if ($res)
             {
             do_sqlquery("UPDATE xbt_users SET torrent_pass='".$pid."' WHERE uid='".$CURUSER['uid']."'");
             redirect("index.php?page=usercp&uid=".$uid."");
             exit();
             }
          else
         {
        err_msg($language["ERROR"],$language["NOT_POSS_RESET_PID"]."<br /><a href=\"index.php?page=usercp&amp;uid=".$uid."\">".$language["HOME"]."</a><br />");
        stdfoot();
        exit;
         }
    break;

    case '':
    case 'change':
    default:
    $result=do_sqlquery("SELECT pid FROM {$TABLE_PREFIX}users WHERE id=".$CURUSER['uid']);
    $row = mysql_fetch_assoc($result);
    $pid=$row["pid"];
    if (!$pid)
      {
        $pid=md5(uniqid(rand(),true));
        $res=do_sqlquery("UPDATE {$TABLE_PREFIX}users SET pid='".$pid."' WHERE id='".$CURUSER['uid']."'");
      }
    else
      {
        $usercptpl->set("IS_PEER",false,true);
        // we must check if user is currently a peer
        if ($XBTT_USE)
          {
        $rp=do_sqlquery("SELECT COUNT(*) FROM xbt_files_users xfu INNER JOIN xbt_users xu ON xfu.uid=xu.uid WHERE xu.torrent_pass='$pid'",true);
        $ispeer=mysql_fetch_row($rp);
        if ($ispeer[0] > "0") $usercptpl->set("IS_PEER",true,true);
        mysql_free_result($rp);
          }
        else
          {
        $rp=do_sqlquery("SELECT COUNT(*) FROM {$TABLE_PREFIX}peers WHERE pid='$pid'");
        $ispeer=mysql_fetch_row($rp);
        if ($ispeer[0] > "0") $usercptpl->set("IS_PEER",true,true);
        mysql_free_result($rp);
          }
      }
    $pid_ctpl=array();
    $pid_ctpl["frm_action"]="index.php?page=usercp&amp;do=pid_c&amp;action=post&amp;uid=".$uid."";
    $pid_ctpl["userpid"]=$pid;
    $pid_ctpl["ispeer"]=($ispeer[0]>0?$language["CURRENTLY_PEER"]."<br />".$language["STOP_PEER"]."\n":"");
    $pid_ctpl["reset_disabled"]=($ispeer[0]>0?"disabled":"");
    $pid_ctpl["frm_cancel"]="index.php?page=usercp&amp;uid=".$uid."";
    $usercptpl->set("pid_c",$pid_ctpl);
    break;
}
?>