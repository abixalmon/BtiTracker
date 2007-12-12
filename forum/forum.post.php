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



if (!defined("IN_BTIT"))
      die("non direct access!");


if (!defined("IN_BTIT_FORUM"))
      die("non direct access!");




$maxsubjectlength = 40;
$postsperpage = $CURUSER["postsperpage"];
if (!$postsperpage)
    $postsperpage = 15;


switch ($action)
   {

   case 'editpost':
      $postid = intval(0+$_GET["postid"]);
      if (!is_valid_id($postid))
         stderr($language["ERROR"],$language["ERR_POST_ID_NA"]);

      $res = do_sqlquery("SELECT p.*,t.locked FROM {$TABLE_PREFIX}posts p LEFT JOIN {$TABLE_PREFIX}topics t ON p.topicid=t.id WHERE p.id=$postid",true);

      if (mysql_num_rows($res) != 1)
         stderr($language["ERROR"],$language["ERR_NO_POST_WITH_ID"]." $postid.");

      $arr = mysql_fetch_assoc($res);

      if (!$arr["locked"])
         stderr($language["ERROR"],$language["ERR_NO_TOPIC_POST_ID"]." $postid.");

      $locked = ($arr2["locked"] == 'yes');

      if (($CURUSER["uid"] != $arr["userid"] || $locked) && $CURUSER["edit_forum"] != "yes")
         stderr($language["ERROR"],$language["ERR_PERM_DENIED"]);

      if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
          $body = $_POST['body'];
          if ($body == "")
             stderr($language["ERROR"],$language["ERR_BODY_EMPTY"]);
          $body = sqlesc($body);
          $editedat = sqlesc(time());
          do_sqlquery("UPDATE {$TABLE_PREFIX}posts SET body=$body, editedat=$editedat, editedby=".intval($CURUSER["uid"])." WHERE id=$postid",true);

          $returnto = urldecode($_POST["returnto"]);
            if ($returnto != "")
            {
                $returnto .= "#$postid";
                redirect("$returnto");
                die();
            }
            else
            {
             success_msg($language["SUCCESS"],$language["SUC_POST_SUC_EDIT"]);
             stdfoot();
             die();
            }
        }


      $block_title=$language["EDIT_POST"];
      $forumtpl->set("frm_action","index.php?page=forum&amp;action=editpost&amp;postid=$postid");
      $forumtpl->set("return_to",htmlspecialchars($_SERVER["HTTP_REFERER"]));
      $forumtpl->set("post_body",textbbcode("edit","body",htmlspecialchars(unesc($arr["body"]))));

     break;

   case 'reply':
   case 'quotepost':
        
      if ($action=="quotepost")
          $quote=true;
      else
          $quote=false;


      $topicid=intval(0+$_GET["topicid"]);
      // current user has create acces to this forum ?
      $aut=get_result("SELECT id,subject FROM {$TABLE_PREFIX}topics WHERE id=$topicid LIMIT 1",true);
      if (count($aut)<1)
          stderr($language["ERROR"],$language["TOPIC_NOT_FOUND"]);

      if (!is_valid_id($topicid))
          stderr($language["ERROR"],$language["BAD_TOPIC_ID"]);

      $block_title=$language["REPLY"]."&nbsp;".$language["TOPIC"]."&nbsp;<a href=\"index.php?page=forum&amp;action=viewtopic&amp;topicid=$topicid\">".htmlspecialchars(unesc($aut[0]["subject"]))."</a>";

      unset($aut);

      if ($XBTT_USE)
         $query = "SELECT p.*, u.username, ul.level as user_group, u.avatar, u.uploaded+IFNULL(x.uploaded,0) as uploaded".
                  ", u.downloaded+IFNULL(x.downloaded,0) as downloaded, c.name as name, ue.username as editor, flagpic FROM {$TABLE_PREFIX}posts p".
                  " LEFT JOIN {$TABLE_PREFIX}users u ON p.userid=u.id LEFT JOIN xbt_users x ON x.uid=u.id INNER JOIN {$TABLE_PREFIX}users_level ul".
                  " ON u.id_level=ul.id LEFT JOIN {$TABLE_PREFIX}countries c ON u.flag = c.id LEFT JOIN {$TABLE_PREFIX}users ue ON p.editedby=ue.id".
                  " WHERE topicid=$topicid ORDER BY id DESC LIMIT 10";
      else
         $query = "SELECT p.*, u.username, ul.level as user_group, u.avatar, u.uploaded".
                  ", u.downloaded, c.name as name, ue.username as editor, flagpic FROM {$TABLE_PREFIX}posts p".
                  " LEFT JOIN {$TABLE_PREFIX}users u ON p.userid=u.id INNER JOIN {$TABLE_PREFIX}users_level ul".
                  " ON u.id_level=ul.id LEFT JOIN {$TABLE_PREFIX}countries c ON u.flag = c.id LEFT JOIN {$TABLE_PREFIX}users ue ON p.editedby=ue.id".
                  " WHERE topicid=$topicid ORDER BY id DESC LIMIT 10";

      // get last 10 posts
      $res = get_result($query,true);
      $posts=array();
      $pn=0;
      foreach($res as $id=>$arr)
      {
        if ($arr["username"])
          $posts[$pn]["username"]="<a href=\"index.php?page=userdetails&amp;id=".$arr["userid"]."\">".unesc($arr["username"])."</a>";
        else
          $posts[$pn]["username"]="unknown[".$arr["userid"]."]";

        $posts[$pn]["date"]=get_date_time($arr["added"]);
        $posts[$pn]["elapsed"]="(".get_elapsed_time($arr["added"]) . " ago)";
        $avatar_size=GetImageSize(htmlspecialchars($arr["avatar"]));
        $posts[$pn]["avatar"]="<img ".($avatar_size[0]>80?"width=\"80\"":"")." src=\"".($arr["avatar"] && $arr["avatar"] != "" ? htmlspecialchars($arr["avatar"]): "$STYLEURL/images/default_avatar.gif" )."\" alt=\"\" />";
        $posts[$pn]["user_group"]=$arr["user_group"];
        $posts[$pn]["flag"]="<img src=\"images/flag/".($arr["flagpic"] && $arr["flagpic"]!=""?$arr["flagpic"]:"unknown.gif")."\" alt=\"".($arr["name"] && $arr["name"]!=""?$arr["name"]:"unknown")."\" />";
        $posts[$pn]["ratio"]=(intval($arr['downloaded']) > 0?number_format($arr['uploaded'] / $arr['downloaded'], 2):"---");

        $sql = get_result("SELECT COUNT(*) as posts FROM {$TABLE_PREFIX}posts p INNER JOIN {$TABLE_PREFIX}users u ON p.userid = u.id WHERE u.id = " . $arr["userid"],true);
        $posts[$pn]["posts"]=intval(0+$sql[0]["posts"]);
        $posts[$pn]["id"]=$arr["id"];

        $posts[$pn]["actions"]="";
        if ((!$locked || $CURUSER["edit_forum"] == "yes") && $usercan_write)
          $posts[$pn]["actions"].="<a href=\"index.php?page=forum&amp;action=quotepost&amp;topicid=$topicid&amp;postid=".$arr["id"]."\">".image_or_link($STYLEPATH."/images/f_quote.png","","[".$language["QUOTE"]."]")."</a>";

        if (($CURUSER["uid"] == $posterid && !$locked) || $CURUSER["edit_forum"] == "yes")
          $posts[$pn]["actions"].="&nbsp;&nbsp;<a href=\"index.php?page=forum&amp;action=editpost&amp;postid=".$arr["id"]."\">".image_or_link($STYLEPATH."/images/f_edit.png","","[".$language["EDIT"]."]")."</a>";

        if ($CURUSER["delete_forum"] == "yes")
          $posts[$pn]["actions"].="&nbsp;&nbsp;<a href=\"index.php?page=forum&amp;action=deletepost&amp;postid=".$arr["id"]."&amp;forumid=$forumid\">".image_or_link($STYLEPATH."/images/f_delete.png","","[".$language["DELETE"]."]")."</a>";

        $posts[$pn]["body"]=format_comment($arr["body"]);

        if (is_valid_id($arr['editedby']))
          $posts[$pn]["body"].= "<p><font size=\"1\">".$language["LAST_EDITED_BY"]." <a href=index.php?page=userdetails&amp;id=".$arr["editedby"]."<b>".$arr["editor"]."</b></a> at ".get_date_time($arr['editedat'])."</font></p>\n";

        $posts[$pn]["pm"]=($CURUSER["uid"]>1?"<a href=\"index.php?page=usercp&amp;do=pm&amp;action=edit&amp;uid=$userid&amp;what=new&amp;to=".urlencode($arr["username"])."\">".image_or_link("$STYLEPATH/images/pm.png","",$language["PM"])."</a>":"");
        $posts[$pn]["top"]=image_or_link("$STYLEPATH/images/top.gif","",$language["TOP"]);
        ++$pn;

      }

      unset($arr);
      unset($res);

      $forumtpl->set("old_posts",($pn>0),true);
      $forumtpl->set("frm_action","index.php?page=forum&amp;action=post");
      $forumtpl->set("topic_id","$topicid");
      $forumtpl->set("newtopic",false,true);
      $forumtpl->set("newtopic_1",false,true);
      $forumtpl->set("posts",$posts);
      $forumtpl->set("replies",true,true);
      $forumtpl->set("post_subject","");
      if ($quote)
        {
          $postid=intval(0+$_GET["postid"]);
          $arr=get_result("SELECT p.*, u.username FROM {$TABLE_PREFIX}posts p LEFT JOIN {$TABLE_PREFIX}users u ON p.userid = u.id WHERE p.id=$postid LIMIT 1",true);
          if (count($arr)<1)
            stderr($language["ERROR"],$language["ERR_NO_POST_WITH_ID"]."&nbsp;$postid.");
      }

      $forumtpl->set("post_bbcode",textbbcode("compose","body",($quote?"[quote".($arr[0]["username"]?"=".htmlspecialchars($arr[0]["username"]):"")."]".htmlspecialchars(unesc($arr[0]["body"]))."[/quote]":"")));
      
      unset($arr);

      break;


   case 'newtopic':
      $forumid=intval(0+$_GET["forumid"]);
      // current user has create acces to this forum ?
      $aut=get_result("SELECT id,name FROM {$TABLE_PREFIX}forums WHERE id=$forumid AND minclasscreate<=".$CURUSER["id_level"]." LIMIT 1",true, $btit_settings["cache_duration"]);
      if (count($aut)<1)
          stderr($language["ERROR"],$language["ERR_CANT_START_TOPICS"]);

      if (!is_valid_id($forumid))
          stderr($language["ERROR"],$language["BAD_FORUM_ID"]);

      $block_title=$language["NEW_TOPIC"]."&nbsp;".$language["IN"]."&nbsp;<a href=\"index.php?page=forum&amp;action=viewforum&amp;forumid=$forumid\">".$aut[0]["name"]."</a>&nbsp;".$language["FORUM"];

      $forumtpl->set("old_posts",false,true);
      $forumtpl->set("frm_action","index.php?page=forum&amp;action=post");
      $forumtpl->set("newtopic",true,true);
      $forumtpl->set("newtopic_1",true,true);
      $forumtpl->set("replies",false,true);        ;
      $forumtpl->set("forum_id","$forumid");
      $forumtpl->set("post_subject","");
      $forumtpl->set("post_bbcode",textbbcode("compose","body",""));

      break;

    case 'post':
      $forumid = isset($_POST["forumid"])?intval($_POST["forumid"]):false;
      $topicid = isset($_POST["topicid"])?intval($_POST["topicid"]):false;

      if (!is_valid_id($forumid) && !is_valid_id($topicid))
        stderr($language["ERROR"],$language["ERR_FORUM_TOPIC"]);

      if ($_POST["confirm"]==$language["FRM_CONFIRM"])
        {

          $newtopic = $forumid > 0;
          $subject = isset($_POST["subject"])?sqlesc(htmlspecialchars(trim($_POST["subject"]))):false;

          if ($newtopic)
          {
            if (!$subject)
              stderr($language["ERROR"],$language["ERR_SUBJECT"]);

            if (strlen($subject) > $maxsubjectlength)
              stderr($language["ERROR"],$language["SUBJECT_MAX_CHAR"]." $maxsubjectlength ".$language["CHARACTERS"]);

            $query="SELECT id, minclasswrite, minclasscreate FROM {$TABLE_PREFIX}forums WHERE id=$forumid LIMIT 1";
          }
          else
            $query = "SELECT f.id, minclasswrite, minclasscreate, t.locked FROM {$TABLE_PREFIX}forums f INNER JOIN {$TABLE_PREFIX}topics t ON t.forumid=f.id WHERE t.id=$topicid LIMIT 1";


          $aut=get_result($query,true);
          $forumid=$aut[0]["id"];
          //------ Make sure sure user has write access in forum

          if ($CURUSER["id_level"] < $aut[0]["minclasswrite"] || ($newtopic && $CURUSER["id_level"] < $aut[0]["minclasscreate"]))
            stderr($language["ERROR"],$language["ERR_PERM_DENIED"]);

          $body = sqlesc(trim($_POST["body"]));

          if ($body == "''")
            stderr($language["ERROR"],$language["ERR_NO_BODY"]);

          $userid = intval($CURUSER["uid"]);

          if ($newtopic)
          {
            //---- Create topic
            $add_topic_count=", topiccount=topiccount+1";
            do_sqlquery("INSERT INTO {$TABLE_PREFIX}topics (userid, forumid, subject) VALUES($userid, $forumid, $subject)",true);
            $topicid = mysql_insert_id() or stderr($language["ERROR"],$language["ERR_NO_TOPIC_ID"]);
          }
          else
          {
            //---- Make sure topic exists and is unlocked
            if ($aut[0]["locked"] == 'yes' && $CURUSER["edit_forum"] != "yes")
                  stderr($language["ERROR"],$language["ERR_TOPIC_LOCKED"]);
            $add_topic_count="";
          }

          //------ Insert post

          do_sqlquery("INSERT INTO {$TABLE_PREFIX}posts (topicid, userid, added, body) VALUES($topicid, $userid, UNIX_TIMESTAMP(), $body)",true);
          $postid = mysql_insert_id() or stderr($language["ERROR"],$language["ERR_POST_ID_NA"]);

          //------ Update topic last post

          do_sqlquery("UPDATE {$TABLE_PREFIX}topics SET lastpost=(SELECT MAX(id) FROM {$TABLE_PREFIX}posts WHERE topicid=$topicid) WHERE id=$topicid",true);
          
          // update post/topic count

          do_sqlquery("UPDATE {$TABLE_PREFIX}forums SET postcount=postcount+1 $add_topic_count WHERE id=$forumid", true);

          //------ All done, redirect user to the post

          //---- Get reply count
          $res = do_sqlquery("SELECT COUNT(*) FROM {$TABLE_PREFIX}posts WHERE topicid=$topicid",true);
          $arr = mysql_fetch_row($res);
          $posts = $arr[0];

          $tpages = floor($posts / $postsperpage);

          if ($tpages * $postsperpage != $posts)
            ++$tpages;

          for ($i = 1; $i <= $tpages; ++$i)
              $headerstr = "index.php?page=forum&action=viewtopic&topicid=$topicid&pages=$i";


          if ($newtopic)
            redirect($headerstr);
          else
            redirect("$headerstr#$postid");

        }
        else
          {              
          if ($forumid)
             redirect("index.php?page=forum&action=viewforum&forumid=$forumid");
          elseif ($topicid)
             redirect("index.php?page=forum&action=viewtopic&topicid=$topicid");
          else
             redirect("index.php?page=forum");
        }
        die();


        break;

}

?>