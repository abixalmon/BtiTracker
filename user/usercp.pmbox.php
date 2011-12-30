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

if (!defined("IN_BTIT"))
      die("non direct access!");


$usercptpl->set("MSG_LIST",false,true);
$usercptpl->set("MSG_READ",false,true);
$usercptpl->set("MSG_EDIT",false,true);
$usercptpl->set("MSG_MENU",false,true);
$usercptpl->set("PREVIEW",false,true);
$usercptpl->set("OUTBOX",(($_GET["what"]=="outbox")?true:false),true);

global $FORUMLINK, $db_prefix;

switch ($action)
{
    case 'post':
           $res=do_sqlquery("SELECT id FROM {$TABLE_PREFIX}users WHERE username=".sqlesc($_POST["receiver"]),true);
           if (!$res || mysql_num_rows($res)==0)
         {
               err_msg($language["ERROR"],$language["ERR_USER_NOT_FOUND"]);
               stdfoot();
               exit;
         }
           else
         {
               if ($_POST["confirm"]==$language["FRM_PREVIEW"])
           {
             $usercptpl->set("PREVIEW",true,true);
             $usercptpl->set("MSG_EDIT",true,true);

             $subject=$_POST["subject"];
             $receiver=$_POST["receiver"];
             $body=$_POST["msg"];

              if (empty($_POST["subject"]))
                $subject=$language["NO_SUBJECT"];

             $pmpreviewtpl=array();
             $pmpreviewtpl["subject"]=$subject;
             $pmpreviewtpl["body"]=  format_comment(unesc($_POST["msg"]));
             $usercptpl->set("pmpreview",$pmpreviewtpl);

             $pmedittpl=array();
             $pmedittpl["frm_action"]="index.php?page=usercp&amp;do=".$do."&amp;action=post&amp;uid=".$uid."&amp;what=".htmlspecialchars($what)."";
             $pmedittpl["receiver"]=$receiver;
             $pmedittpl["readonly"]=($what!="new" ? " readonly=\"readonly\"" : "");
             $pmedittpl["searchuser"]=($what=="new" ? "<a href=\"javascript:popusers('searchusers.php');\">".$language["FIND_USER"]."</a>" : "");
             $pmedittpl["subject"]=$subject;
             $pmedittpl["bbcode"]=textbbcode("edit","msg",$body);
             $pmedittpl["frm_preview"]="index.php?page=usercp&amp;do=".$do."&amp;action=post&amp;uid=".$uid."&amp;what=".htmlspecialchars($what)."&amp;preview=yes";
             $pmedittpl["frm_cancel"]="index.php?page=usercp&amp;uid=".$uid."&amp;do=pm&amp;action=list";
             $usercptpl->set("pmedit",$pmedittpl);
           }
               else
           {
             $result=mysql_fetch_array($res);
             $subject=sqlesc($_POST["subject"]);
             $msg=sqlesc($_POST["msg"]);
             $rec=$result["id"];
             $send=$CURUSER["uid"];

             if ($rec==1 || $rec==$send)
                stderr($language["ERROR"],$language["ERR_PM_GUEST"]);

             if ($subject=="''")
                $subject="'no subject'";

             send_pm($CURUSER['uid'],$rec,$subject, $msg);
             redirect("index.php?page=usercp&uid=".$uid."&do=pm&action=list");
             exit();
           }
         }
    break;

     case 'deleteall':
        if(substr($FORUMLINK,0,3)=="smf")
            redirect("index.php?page=forum&action=pm".(($_GET["type"]=="out")?";f=outbox":""));
        // MODIFIED DELETE ALL VERSION BY gAnDo
            if (isset($_GET["type"]))
                $what=$_GET["type"];
            else
                {
                redirect("index.php?page=usercp&uid=".$uid."&do=pm&action=list&what=".($what=="in"?"inbox":"outbox"));
                exit;
                }
                if($_GET["type"]=="out"){
           foreach($_POST["msg"] as $selected=>$msg)
           do_sqlquery("UPDATE {$TABLE_PREFIX}messages SET deletedBySender=1 WHERE id='".$msg."'",true);
           redirect("index.php?page=usercp&uid=".$uid."&do=pm&action=list&what=".($what=="in"?"inbox":"outbox"));
           exit();
	        } 
	        else{
		   foreach($_POST["msg"] as $selected=>$msg)
	   	   do_sqlquery("DELETE FROM {$TABLE_PREFIX}messages WHERE id='".$msg."' AND readed='yes'",true);
	   	   redirect("index.php?page=usercp&uid=".$uid."&do=pm&action=list&what=".($what=="in"?"inbox":"outbox"));
           exit();
	        }
		
            break;

    case 'delete':
            if(substr($FORUMLINK,0,3)=="smf")
                redirect("index.php?page=forum&action=pm".(($_GET["type"]=="out")?";f=outbox":""));
            $id=intval($_GET["id"]);
            if($_GET["type"]=="out"){
			do_sqlquery("UPDATE {$TABLE_PREFIX}messages SET deletedBySender=1 WHERE id='".$id."'",true);
            }else{
            do_sqlquery("DELETE FROM {$TABLE_PREFIX}messages WHERE receiver=".$uid." AND id=".$id." AND readed='yes'",true);
		    }
            redirect("index.php?page=usercp&uid=".$uid."&do=pm&action=list&what=inbox");
            exit();
    break;

    case '':
    case 'change':
    default:
        if ($what=="outbox" && $action=="list")
          {
           $usercptpl->set("MSG_LIST",true,true);
           $pmoutboxtpl=array();
           $pmoutboxtpl["frm_action"]="index.php?page=usercp&amp;do=pm&amp;action=deleteall&amp;uid=".$uid."&amp;type=out";
           $usercptpl->set("pmbox",$pmoutboxtpl);

           if(substr($FORUMLINK,0,3)=="smf")
           {
               if($FORUMLINK=="smf")
                   $res=do_sqlquery("SELECT pm.ID_PM id, pmr.ID_MEMBER receiver, pm.msgtime added, pm.subject, pm.body msg, IF(pmr.is_read=0,'no','yes') readed, u.username receivername FROM {$db_prefix}personal_messages pm LEFT JOIN {$db_prefix}pm_recipients pmr ON pm.ID_PM=pmr.ID_PM LEFT JOIN {$TABLE_PREFIX}users u ON pmr.ID_MEMBER=u.smf_fid WHERE pm.ID_MEMBER_FROM=".$CURUSER["smf_fid"]." AND pm.deletedBySender!=1 ORDER BY added DESC",true);
               else
                   $res=do_sqlquery("SELECT `pm`.`id_pm` `id`, `pmr`.`id_member` `receiver`, `pm`.`msgtime` `added`, `pm`.`subject`, `pm`.`body` `msg`, IF(`pmr`.`is_read`=0,'no','yes') `readed`, `u`.`username` `receivername` FROM `{$db_prefix}personal_messages` `pm` LEFT JOIN `{$db_prefix}pm_recipients` `pmr` ON `pm`.`id_pm`=`pmr`.`id_pm` LEFT JOIN `{$TABLE_PREFIX}users` `u` ON `pmr`.`id_member`=`u`.`smf_fid` WHERE `pm`.`id_member_from`=".$CURUSER["smf_fid"]." AND `pm`.`deleted_by_sender`!=1 ORDER BY `added` DESC",true);
           }
           elseif($FORUMLINK=="ipb")
               header("Location: index.php?page=forum&action=pm");
           else
               $res=do_sqlquery("select m.*, IF(m.receiver=0,'System',u.username) as receivername FROM {$TABLE_PREFIX}messages m LEFT JOIN {$TABLE_PREFIX}users u on u.id=m.receiver WHERE sender=$uid AND deletedBySender=0 ORDER BY added DESC",true);
           if (!$res || mysql_num_rows($res)==0)
             {
                $usercptpl->set("NO_MESSAGES",true,true);
             }
           else
             {
            $pmouttpl=array();
            $i=0;
            $usercptpl->set("NO_MESSAGES",false,true);
                        while ($result=mysql_fetch_array($res))
                {
                $pmouttpl[$i]["readed"]=unesc($result["readed"]);
                $pmouttpl[$i]["senderid"]=($result["receiver"]==0||empty($result["receivername"])?"#":((substr($FORUMLINK,0,3)=="smf")?$BASEURL."/index.php?page=forum&action=profile;u=".$result["receiver"]:"index.php?page=userdetails&amp;id=".$result["receiver"]));
                $pmouttpl[$i]["sendername"]=unesc($result["receivername"]);
                $pmouttpl[$i]["added"]=get_date_time($result["added"]);
                $pmouttpl[$i]["pmlink"]=((substr($FORUMLINK,0,3)=="smf")?$BASEURL."/index.php?page=forum&amp;action=pm;f=outbox":"index.php?page=usercp&amp;do=pm&amp;action=read&amp;uid=".$uid."&amp;id=".$result["id"]."&amp;what=outbox");
                $pmouttpl[$i]["subject"]=format_comment(unesc($result["subject"]));
                $pmouttpl[$i]["msgid"]=$result["id"];
                $i++;
                }
            $usercptpl->set("pm",$pmouttpl);
             }
          }
        elseif ($what=="inbox" && $action=="list")
          {
           $usercptpl->set("MSG_LIST",true,true);
           $pminboxtpl=array();
           $pminboxtpl["frm_action"]="index.php?page=usercp&amp;do=pm&amp;action=deleteall&amp;uid=".$uid."&amp;type=in";
           $usercptpl->set("pmbox",$pminboxtpl);

           if(substr($FORUMLINK,0,3)=="smf")
           {
               if($FORUMLINK=="smf")
                   $res=do_sqlquery("SELECT `pm`.`ID_PM` `id`, `pm`.`ID_MEMBER_FROM` `sender`, `pmr`.`ID_MEMBER` `receiver`, `pm`.`msgtime` `added`, `pm`.`subject`, `pm`.`body` `msg`, IF(`pmr`.`is_read`=0,'no','yes') `readed`, `pm`.`fromName` `sendername` FROM `{$db_prefix}personal_messages` `pm` LEFT JOIN `{$db_prefix}pm_recipients` `pmr` ON `pm`.`ID_PM`=`pmr`.`ID_PM` WHERE `pmr`.`ID_MEMBER`=".$CURUSER["smf_fid"]." AND `pmr`.`deleted`!=1 ORDER BY `added` DESC",true);
               else
                   $res=do_sqlquery("SELECT `pm`.`id_pm` `id`, `pm`.`id_member_from` `sender`, `pmr`.`id_member` `receiver`, `pm`.`msgtime` `added`, `pm`.`subject`, `pm`.`body` `msg`, IF(`pmr`.`is_read`=0,'no','yes') `readed`, `pm`.`from_name` `sendername` FROM `{$db_prefix}personal_messages` `pm` LEFT JOIN `{$db_prefix}pm_recipients` `pmr` ON `pm`.`id_pm`=`pmr`.`id_pm` WHERE `pmr`.`id_member`=".$CURUSER["smf_fid"]." AND `pmr`.`deleted`!=1 ORDER BY `added` DESC",true);
           }
           elseif($FORUMLINK=="ipb")
               header("Location: index.php?page=forum&action=pm");
           else
               $res=do_sqlquery("select m.*, IF(m.sender=0,'System',u.username) as sendername FROM {$TABLE_PREFIX}messages m LEFT JOIN {$TABLE_PREFIX}users u on u.id=m.sender WHERE receiver=$uid ORDER BY added DESC",true);
           if (!$res || mysql_num_rows($res)==0)
             {
                $usercptpl->set("NO_MESSAGES",true,true);
             }
                   else
             {
                $pmintpl=array();
                $i=0;
                $usercptpl->set("NO_MESSAGES",false,true);
                while ($result=mysql_fetch_array($res))
        {
        $pmintpl[$i]["readed"]=unesc($result["readed"]);
        $pmintpl[$i]["senderid"]=($result["sender"]==0||empty($result["sendername"])?"#":((substr($FORUMLINK,0,3)=="smf")?$BASEURL."/index.php?page=forum&amp;action=profile;u=".$result["sender"]:"index.php?page=userdetails&amp;id=".$result["sender"]));
        $pmintpl[$i]["sendername"]=unesc($result["sendername"]);
        $pmintpl[$i]["added"]=get_date_time($result["added"]);
        $pmintpl[$i]["pmlink"]=((substr($FORUMLINK,0,3)=="smf")?$BASEURL."/index.php?page=forum&amp;action=pm":"index.php?page=usercp&amp;do=pm&amp;action=read&amp;uid=".$uid."&amp;id=".$result["id"]."&amp;what=inbox");
        $pmintpl[$i]["subject"]=format_comment(unesc($result["subject"]));
        $pmintpl[$i]["msgid"]=$result["id"];
        $i++;
        }
    $usercptpl->set("pm",$pmintpl);
       }
          }
        elseif ($what=="new" && $action=="edit" || $do == "pm" && $action == "edit")
  {
           if($FORUMLINK=="ipb")
               header("Location: index.php?page=forum&action=newpm&to=".(isset($_GET['to'])?htmlentities($_GET['to']):'')."");//insert name to ipb form.

   $usercptpl->set("MSG_EDIT",true,true);

           // if new pm will give id=0 and empty array
           if (isset($_GET['id']) && $_GET['id'])
                $id=intval(0+$_GET['id']);
           else $id=0;
           if (!isset($_GET['what'])) $_GET['what'] = '';
           if (!isset($_GET['to'])) $_GET['to'] = '';

           if (urldecode($_GET['to'])==$CURUSER["username"])
              stderr($language["ERROR"],$language["ERR_PM_GUEST"]);

           $res=do_sqlquery("select m.*, IF(m.sender=0,'System',u.username) as sendername FROM {$TABLE_PREFIX}messages m LEFT JOIN {$TABLE_PREFIX}users u on u.id=m.sender WHERE receiver=$uid AND m.id=$id",true);

           if (!$res)
             {                        
            err_msg($language["ERROR"],$language["BAD_ID"]);
            stdfoot();
            exit;
             }
           else
             {
            $result=mysql_fetch_array($res);
            $pmedittpl=array();
            $pmedittpl["frm_action"]="index.php?page=usercp&amp;do=".$do."&amp;action=post&amp;uid=".$uid."&amp;what=".htmlspecialchars($what)."";
            $pmedittpl["receiver"]=($what!="new" ? unesc($result["sendername"]):htmlspecialchars(urldecode($_GET["to"])));
            $pmedittpl["readonly"]=($what!="new" ? " readonly=\"readonly\"" : "");
            $pmedittpl["searchuser"]=($what=="new" ? "<a href=\"javascript:popusers('searchusers.php');\">".$language["FIND_USER"]."</a>" : "");
            $pmedittpl["subject"]=($what!="new" ? (strpos(unesc($result["subject"]), "Re:")===false?"Re:":"").unesc($result["subject"]):"");
            $pmedittpl["bbcode"]=textbbcode("edit","msg",($what=="quote" ? "[quote=".htmlspecialchars($result["sendername"])."]".unesc($result["msg"])."[/quote]" : ""));
            $pmedittpl["frm_preview"]="index.php?page=usercp&amp;do=".$do."&amp;action=post&amp;uid=".$uid."&amp;what=".htmlspecialchars($what)."";
            $pmedittpl["frm_cancel"]="index.php?page=usercp&amp;uid=".$uid."&amp;do=pm&amp;action=list";
            $usercptpl->set("pmedit",$pmedittpl);
             }
          }
        elseif ($what=="inbox" && $action=="read" || $what=="outbox" && $action=="read")
  {
   $usercptpl->set("MSG_READ",true,true);

           $id=intval($_GET["id"]);
           if ($what=="inbox")
              $res=do_sqlquery("select m.*, IF(m.sender=0,'System',u.username) as sendername FROM {$TABLE_PREFIX}messages m LEFT JOIN {$TABLE_PREFIX}users u on u.id=m.sender WHERE receiver=$uid AND m.id=$id",true);
           elseif ($what=="outbox")
               $res=do_sqlquery("select m.*, IF(m.receiver=0,'System',u.username) as sendername FROM {$TABLE_PREFIX}messages m LEFT JOIN {$TABLE_PREFIX}users u on u.id=m.receiver WHERE sender=$uid AND m.id=$id",true);

           if (mysql_num_rows($res) == "0")
             {
            err_msg($language["ERROR"],$language["BAD_ID"]);
            stdfoot();
            exit;
             }
           else
             {
            $result=mysql_fetch_array($res);
            $pmreadtpl=array();
            $pmreadtpl["sender_link"]="index.php?page=userdetails&amp;id=".$result["sender"]."";
            $pmreadtpl["sender_name"]=unesc($result["sendername"]);
            $pmreadtpl["added"]=get_date_time($result["added"]);
            $pmreadtpl["elapsed"]=get_elapsed_time($result["added"]);
            $pmreadtpl["comment"]=format_comment(unesc($result["subject"]));
            $pmreadtpl["body"]=format_comment(unesc($result["msg"]));

            if ($what=="inbox")
              {
                $usercptpl->set("MSG_MENU",true,true);
                $pmreadtpl["quote_link"]="location.href='index.php?page=usercp&amp;do=pm&amp;action=edit&amp;what=quote&amp;uid=".$uid."&amp;id=".$id."'";
                $pmreadtpl["answer_link"]="location.href='index.php?page=usercp&amp;do=pm&amp;action=edit&amp;uid=".$uid."&amp;id=".$id."'";
                $pmreadtpl["delete_link"]="location.href='index.php?page=usercp&amp;do=pm&amp;action=delete&amp;uid=".$uid."&amp;id=".$id."'";
                do_sqlquery("UPDATE {$TABLE_PREFIX}messages SET readed='yes' WHERE id=$id",true);
              }
            $usercptpl->set("pmread",$pmreadtpl);
             }
          }
    break;
}
?>