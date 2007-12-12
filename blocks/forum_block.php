<?php
global $CURUSER,$CACHE_DURATION, $FORUMLINK, $THIS_BASEPATH, $db_prefix;

if (!$CURUSER || $CURUSER["view_forum"]=="no")
   {
    // do nothing
   }
else
{
    if ($FORUMLINK=="smf")
    {

       $search1 = mysql_query("SELECT COUNT(*) AS topic_total FROM {$db_prefix}topics");
       if ($search1)
       {
           $row = mysql_fetch_assoc($search1);
           $topics = $row['topic_total'];

           $search2 = mysql_query("SELECT COUNT(*) AS post_total FROM {$db_prefix}messages");
           if ($search2)
           {
               $row = mysql_fetch_assoc($search2);
               $posts = $row['post_total'];
               if ($posts>0)
                   $posts_avg = number_format(($topics/$posts) * 100, 0);
               else
                   $posts_avg = 0;
           }
       }
       else
       {
           $topics = 0;
           $posts = 0;
           $posts_avg = 0;
       }
       print("<table cellpadding=\"4\" cellspacing=\"1\" width=\"100%\">\n<tr><td class=\"lista\">\n");
       print("<table width=\"100%\" cellspacing=\"2\" cellpading=\"2\">\n");
       print("<tr><td>" . $language["TOPICS"] . ":</td><td align=\"right\">" . number_format($topics) . "</td></tr>\n");
       print("<tr><td>" . $language["POSTS"] . ":</td><td align=\"right\">" . number_format($posts) . "</td></tr>\n");
       print("<tr><td>" . $language["TOPICS"] . "/" . $language["POSTS"] . ":</td><td align=\"right\">" . $posts_avg . " %</td></tr>\n");
       print("</table>\n</td></tr>\n");

       if ( $topics > 0 )
       {
           $query=mysql_query("SELECT ID_BOARD, memberGroups FROM {$db_prefix}boards");
           $exclude="";
           while($check=mysql_fetch_array($query))
           {
               $forumid=$check["ID_BOARD"];
               $read=explode(',',$check['memberGroups']);
               if (!in_array($CURUSER["id_level"]+10, $read))
               {
                   $exclude=($exclude." AND {$db_prefix}messages.ID_BOARD!=".$forumid);
               }
           }
          if (isset($GLOBALS["block_forumlimit"]))
              $limit="LIMIT " . $GLOBALS["block_forumlimit"];
          else
              $limit="LIMIT 5";

         $query ="SELECT {$db_prefix}messages.ID_TOPIC AS tid, {$db_prefix}messages.subject AS title, ";
         $query.="{$db_prefix}topics.ID_MEMBER_UPDATED AS last_poster_id, {$db_prefix}messages.posterTime ";
         $query.="AS last_post, {$db_prefix}topics.ID_LAST_MSG AS goto_last_post, {$db_prefix}messages.posterName ";
         $query.="AS last_poster_name, {$db_prefix}messages.ID_BOARD AS forumid, {$db_prefix}topics.ID_BOARD AS id, ";
         $query.="{$db_prefix}boards.memberGroups AS forum_permissions FROM {$db_prefix}messages, {$db_prefix}topics, ";
         $query.="{$db_prefix}boards WHERE {$db_prefix}messages.ID_BOARD = {$db_prefix}topics.ID_BOARD ";
         $query.="AND {$db_prefix}messages.ID_MSG = {$db_prefix}topics.ID_LAST_MSG AND {$db_prefix}boards.ID_BOARD ";
         $query.="= {$db_prefix}topics.ID_BOARD  ".$exclude." ORDER BY {$db_prefix}messages.posterTime DESC ".$limit;
         
         $tres = mysql_query($query);

           while ($trow = mysql_fetch_array($tres))
           {
               $title=preg_replace("/Re:/", "", htmlspecialchars_decode($trow['title']));
               if (strlen($title>30))
               {
                   print("<tr><td class=\"lista\"><b><a title=\"".$language["FIRST_UNREAD"].": ".preg_replace("/Re:/", "", $trow["title"])."\" href=\"index.php?page=forum&amp;action=viewtopic&amp;topicid=" . $trow['tid'] . ".msg" . $trow['goto_last_post'] . "#new\">" . substr($title,0,30) . "...</a></b><br />".$language["LAST_POST_BY"]." <a href='smf/index.php?action=profile;u=" . $trow['last_poster_id'] . "'>" .$trow['last_poster_name'] ."</a><br />On " . date('d/m/Y H:i:s',$trow["last_post"]). "</td></tr>\n");
               }
               else
               {
                   print("<tr><td class=\"lista\"><b><a title=\"".$language["FIRST_UNREAD"].": ".preg_replace("/Re:/", "", $trow["title"])."\" href=\"index.php?page=forum&amp;action=viewtopic&amp;topicid=" . $trow['tid'] . ".msg" . $trow['goto_last_post'] . "#new\">" . $title . "</a></b><br />".$language["LAST_POST_BY"]." <a href='index.php?page=forum&amp;action=profile;u=" . $trow['last_poster_id'] . "'>" .$trow['last_poster_name'] ."</a><br />On " . date('d/m/Y H:i:s',$trow["last_post"]). "</td></tr>\n");
               }
           }
       }
       else
       {
           print("<tr><td class=\"lista\">" . $language["NO_TOPIC"] . "</td></tr>\n");
       }
       print("</table>\n");
       block_end();
} else {


    $topics = 0;
    $posts = 0;
    $posts_avg = 0;


    $row=get_result("SELECT COUNT(*) AS topic_total FROM {$TABLE_PREFIX}topics",true,$CACHE_DURATION);
    $topics = $row[0]['topic_total'];

    $row = get_result("SELECT COUNT(*) AS post_total FROM {$TABLE_PREFIX}posts",true,$CACHE_DURATION); //mysql_fetch_array($res1);
    $posts = $row[0]['post_total'];
    if ($posts>0)
       $posts_avg = number_format(($topics/$posts) * 100, 0);
    else
        $posts_avg = 0;

     print("<table cellpadding=\"4\" cellspacing=\"1\" width=\"100%\">\n<tr><td class=\"lista\">\n");
     print("<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\">\n");
      
     print("<tr><td>" . $language["TOPICS"] . ":</td><td align=\"right\">" . number_format($topics) . "</td></tr>\n");
     print("<tr><td>" . $language["POSTS"] . ":</td><td align=\"right\">" . number_format($posts) . "</td></tr>\n");
     print("<tr><td>" . $language["TOPICS"] . "/" . $language["POSTS"] . ":</td><td align='right'>" . $posts_avg . " %</td></tr>\n");

     print("</table>\n</td></tr>\n");

     if ( $topics > 0 )
     {
          if (isset($GLOBALS["block_forumlimit"]))
              $limit="LIMIT " . $GLOBALS["block_forumlimit"];
          else
              $limit="LIMIT 5";

       $tres=get_result("SELECT t.id, t.subject,t.lastpost FROM {$TABLE_PREFIX}topics as t INNER JOIN {$TABLE_PREFIX}forums as f on f.id=t.forumid WHERE f.minclassread<=".$CURUSER["id_level"]." ORDER BY lastpost DESC $limit",true,$CACHE_DURATION);
       foreach($tres as $id=>$trow)
       {
         $lpres =get_result("SELECT p.added, p.userid, u.username, u.id_level, prefixcolor, suffixcolor
           FROM {$TABLE_PREFIX}posts p, {$TABLE_PREFIX}users u INNER JOIN {$TABLE_PREFIX}users_level ul on u.id_level=ul.id
              WHERE p.userid = u.id
                AND p.topicid = " . $trow['id'] ." ORDER BY p.added",true,$CACHE_DURATION);
         foreach($lpres as $id=>$lprow)
         {
           $last_post_userid = $lprow['userid'];
           $last_poster = $lprow['username'];
           $last_post_time = get_date_time($lprow['added']);

           $pcolor=unesc($lprow["prefixcolor"]);
           $scolor=unesc($lprow["suffixcolor"]);

        }

         if ($trow['lastpost'])
            print("<tr><td class=\"lista\"><b><a href=\"index.php?page=forum&amp;action=viewtopic&amp;topicid=" . $trow['id'] . "&amp;pages=last#" . $trow['lastpost'] . "\">" . htmlspecialchars(unesc($trow['subject'])) . "</a></b><br />".$language["LAST_POST_BY"]." <a href=\"index.php?page=userdetails&amp;id=" . $last_post_userid . "\">" . $pcolor . $last_poster . $scolor ."</a><br />On " . $last_post_time . "</td></tr>\n");
         else
            print("<tr><td class=\"lista\"><b><a href=\"index.php?page=forum&amp;action=viewtopic&amp;topicid=" . $trow['id'] . "&amp;pages=last\">" . htmlspecialchars(unesc($trow['subject'])) . "</a></b><br />".$language["LAST_POST_BY"]." <a href=\"index.php?page=userdetails&amp;id=" . $last_post_userid . "\">" . $pcolor . $last_poster . $scolor ."</a><br />On " . $last_post_time . "</td></tr>\n");
       }
     }
     else
     {
       print("<tr><td class=\"lista\">" . $language["NO_TOPIC"] . "</td></tr>\n");
     }

     print("</table>\n");

     block_end();
    }
} // end if user can view
?>