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


switch ($action)
  {

    case 'catchup':

        // we will update the readposts table with max post id for each topic
        $rtopics = get_result("SELECT t.id FROM {$TABLE_PREFIX}topics t LEFT JOIN {$TABLE_PREFIX}forums f ON t.forumid=f.id WHERE IFNULL(f.minclassread,999)<=".$CURUSER["id_level"],true);
        // check if record exist in readposts table

        foreach($rtopics as $id=>$rt)
          {
           $rp=get_result("SELECT id FROM {$TABLE_PREFIX}readposts WHERE topicid=".$rt["id"]." AND userid=".$CURUSER["uid"]);
           if (count($rp)>0)
              do_sqlquery("UPDATE {$TABLE_PREFIX}readposts SET lastpostread=(SELECT MAX(id) FROM {$TABLE_PREFIX}posts WHERE topicid=".$rt["id"].") WHERE topicid=".$rt["id"]." AND userid=".$CURUSER["uid"],true);
           else
              do_sqlquery("INSERT INTO {$TABLE_PREFIX}readposts SET lastpostread=(SELECT MAX(id) FROM {$TABLE_PREFIX}posts WHERE topicid=".$rt["id"]."), topicid=".$rt["id"].", userid=".$CURUSER["uid"],true);
        }
        redirect("index.php?page=forum");
        die();
      break;

    case 'deletetopic':
        $topicid = intval(0+$_GET["topicid"]);
        $forumid = intval(0+$_GET["forumid"]);

        if (!is_valid_id($topicid) || $CURUSER["delete_forum"] != "yes")
            stderr($language["ERROR"],$language["BAD_TOPIC_ID"]);

        if (isset($_GET["sure"]) && $_GET["sure"])
            $sure = htmlspecialchars($_GET["sure"]);
        else
            $sure = "";

        if (!$sure)
        {
          information_msg($language["FRM_CONFIRM"]."?",$language["ERR_DELETE_TOPIC"]."&nbsp;<a href=\"index.php?page=forum&amp;action=deletetopic&amp;topicid=$topicid&amp;sure=1&amp;forumid=$forumid\">".$language["HERE"]."</a>&nbsp;".$language["IF_YOU_ARE_SURE"]."<br />");
        }

        do_sqlquery("DELETE FROM {$TABLE_PREFIX}topics WHERE id=$topicid",true);
        $numtopic=mysql_affected_rows();
        do_sqlquery("DELETE FROM {$TABLE_PREFIX}posts WHERE topicid=$topicid",true);
        $numposts=mysql_affected_rows();
        do_sqlquery("DELETE FROM {$TABLE_PREFIX}readposts WHERE topicid=$topicid",true);

        do_sqlquery("UPDATE {$TABLE_PREFIX}forums SET topiccount=topiccount-$numtopic,postcount=postcount-$numposts WHERE id=$forumid",true);

        redirect("index.php?page=forum&action=viewforum&forumid=$forumid");
    
        die();

      break;


    case 'movetopic':
        $forumid = intval(0 + $_POST["forumid"]);
        $topicid = intval(0 + $_GET["topicid"]);

        if (!is_valid_id($forumid) || !is_valid_id($topicid) || $CURUSER["edit_forum"] != "yes")
            stderr($language["ERROR"],$language["BAD_TOPIC_ID"]);

        $res = do_sqlquery("SELECT minclasswrite FROM {$TABLE_PREFIX}forums WHERE id=$forumid",true);

        if (mysql_num_rows($res) != 1)
            stderr($language["ERROR"],$language["ERR_FORUM_NOT_FOUND"]);

        $arr = mysql_fetch_row($res);

        if ($CURUSER["id_level"] < $arr[0])
            stderr($language["ERROR"],$language["BAD_TOPIC_ID"]);

        $res = do_sqlquery("SELECT subject,forumid FROM {$TABLE_PREFIX}topics WHERE id=$topicid",true);

        if (mysql_num_rows($res) != 1)
            stderr($language["ERROR"],$language["TOPIC_NOT_FOUND"]);

        $arr = mysql_fetch_assoc($res);

        if ($arr["forumid"] != $forumid)
          do_sqlquery("UPDATE {$TABLE_PREFIX}topics SET forumid=$forumid WHERE id=$topicid",true);

        // modifying count topics & post
        $res=do_sqlquery("SELECT count(*) as numposts FROM {$TABLE_PREFIX}posts WHERE topicid=$topicid",true);
        $numposts=mysql_result($res,0,0);

        do_sqlquery("UPDATE {$TABLE_PREFIX}forums SET topiccount=topiccount-1, postcount=postcount-$numposts WHERE id=".$arr["forumid"]);
        do_sqlquery("UPDATE {$TABLE_PREFIX}forums SET topiccount=topiccount+1, postcount=postcount+$numposts WHERE id=$forumid");

        // Redirect to forum page

        redirect("index.php?page=forum&action=viewforum&forumid=$forumid");
        die();

      break;

    case 'setlocked':
        $topicid = intval(0 + $_POST["topicid"]);

        if (!$topicid || $CURUSER["edit_forum"] != "yes")
            stderr($language["ERROR"],$language["BAD_TOPIC_ID"]);

        $locked = sqlesc($_POST["locked"]);
        do_sqlquery("UPDATE {$TABLE_PREFIX}topics SET locked=$locked WHERE id=$topicid") or sqlerr(__FILE__, __LINE__);

        redirect(urldecode($_POST["returnto"]));

        die();

      break;

    case 'setsticky':
        $topicid = intval(0 + $_POST["topicid"]);

        if (!$topicid || $CURUSER["edit_forum"] != "yes")
            stderr($language["ERROR"],$language["BAD_TOPIC_ID"]);

        $sticky = sqlesc($_POST["sticky"]);
        do_sqlquery("UPDATE {$TABLE_PREFIX}topics SET sticky=$sticky WHERE id=$topicid",true);

        redirect(urldecode($_POST[returnto]));
        die();

      break;

    case 'rename':

        if ($CURUSER["edit_forum"] != "yes")
          stderr($language["ERROR"],$language["ERR_NOT_AUTH"]);

        $topicid = intval(0+$_POST['topicid']);

        if (!is_valid_id($topicid))
          stderr($language["ERROR"],$language["BAD_TOPIC_ID"]);

        $subject = $_POST['subject'];

        if ($subject == '')
          stderr($language["ERROR"],$language["ERR_ENTER_NEW_TITLE"]);

        $subject = sqlesc($subject);

        do_sqlquery("UPDATE {$TABLE_PREFIX}topics SET subject=$subject WHERE id=$topicid") or sqlerr();

        $returnto = urldecode($_POST['returnto']);

        if ($returnto)
          redirect("$returnto");
        die();

      break;

    case 'deletepost':
      $postid = intval(0+$_GET["postid"]);
      $forumid = intval(0+$_GET["forumid"]);

      if (isset($_GET["sure"]) && $_GET["sure"])
          $sure = htmlspecialchars($_GET["sure"]);
      else
          $sure = "";

      if ($CURUSER["delete_forum"] != "yes" || !is_valid_id($postid))
        stderr($language["ERROR"],$language["ERR_FORUM_TOPIC"]);

      //------- Get topic id

      $res = do_sqlquery("SELECT (SELECT COUNT(*) FROM {$TABLE_PREFIX}posts WHERE topicid=p.topicid) as total_posts,topicid FROM {$TABLE_PREFIX}posts p WHERE id=$postid",true);
      $arr = mysql_fetch_assoc($res) or stderr($language["ERROR"],$language["ERR_POST_NOT_FOUND"]);
      $topicid = intval($arr["topicid"]);

      if ($arr["total_posts"] < 2)
        information_msg($language["FRM_CONFIRM"]."?",$language["ERR_POST_UNIQUE"]."&nbsp;<a href=\"index.php?page=forum&amp;action=deletetopic&amp;topicid=$topicid&amp;sure=1&amp;forumid=$forumid\">".$language["ERR_POST_UNIQUE_2"]."</a>&nbsp;".$language["ERR_POST_UNIQUE_3"]);

      if (!$sure)
      {
        information_msg($language["FRM_CONFIRM"]."?",$language["ERR_DELETE_POST"]."&nbsp;<a href=\"index.php?page=forum&amp;action=deletepost&amp;postid=$postid&amp;sure=1&amp;forumid=$forumid\">".$language["HERE"]."</a>&nbsp;".$language["IF_YOU_ARE_SURE"]."<br />");
      }

      //------- Delete post
      do_sqlquery("DELETE FROM {$TABLE_PREFIX}posts WHERE id=$postid",true);
      $numposts=mysql_affected_rows();
    
      // update post's count
      do_sqlquery("UPDATE {$TABLE_PREFIX}forums SET postcount=postcount-$numposts WHERE id=$forumid");

      // update last topic's post
      do_sqlquery("UPDATE {$TABLE_PREFIX}topics SET lastpost=(SELECT MAX(id) FROM {$TABLE_PREFIX}posts WHERE topicid=$topicid) WHERE id=$topicid",true);

      redirect("index.php?page=forum&action=viewtopic&topicid=$topicid");
      die();
      break;

}

?>