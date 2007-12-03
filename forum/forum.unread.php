<?php


if (!defined("IN_BTIT"))
      die("non direct access!");


if (!defined("IN_BTIT_FORUM"))
      die("non direct access!");


if (isset($_GET["page"]) && $_GET["page"])
$page = max(1,intval(0+$_GET["page"]));
else $page = '';

$block_title=$language["TOPIC_UNREAD_POSTS"];

//------ Page links

//------ Get topic count

$perpage = $CURUSER["topicsperpage"];
if (!$perpage) $perpage = 20;

$res = do_sqlquery("SELECT COUNT(*) FROM {$TABLE_PREFIX}topics t LEFT JOIN {$TABLE_PREFIX}readposts r ON t.id=r.topicid WHERE t.lastpost>IF(r.lastpostread IS NULL,0, r.lastpostread)",true);
$arr = mysql_fetch_row($res);
$numtopics=$arr[0];
mysql_free_result($res);
unset($arr);

list($pagertop, $pagerbottom, $limit)=forum_pager($perpage,$numtopics, "index.php?page=forum&amp;action=viewunread&amp;");

//------ Get topics data

$topicsres = do_sqlquery("SELECT t.*,(SELECT COUNT(*) FROM {$TABLE_PREFIX}posts WHERE topicid=t.id) as num_posts,".
                         " ulp.username as lastposter, ulp.id as lastposter_uid, p.added as start_date, us.username as starter,".
                         " IF(t.lastpost<=(SELECT lastpostread FROM {$TABLE_PREFIX}readposts rp WHERE rp.userid=".intval($CURUSER["uid"]).
                         " AND rp.topicid=t.id) OR t.lastpost IS NULL,'unlocked','unlockednew') as img".
                         " FROM {$TABLE_PREFIX}topics t LEFT JOIN {$TABLE_PREFIX}readposts rp ON t.id=rp.topicid".
                         " LEFT JOIN {$TABLE_PREFIX}users us ON t.userid=us.id LEFT JOIN {$TABLE_PREFIX}forums f ON t.forumid=f.id".
                         " LEFT JOIN {$TABLE_PREFIX}posts p ON t.lastpost=p.id LEFT JOIN {$TABLE_PREFIX}users ulp ON p.userid=ulp.id".
                         " WHERE t.lastpost>IF(rp.lastpostread IS NULL,0, rp.lastpostread) AND IFNULL(f.minclassread,999)<=".$CURUSER["id_level"].
                         " ORDER BY lastpost DESC $limit",true);


$postsperpage = $CURUSER["postsperpage"];
  if (!$postsperpage) $postsperpage = 15;


if ($numtopics > 0)
  {
    $forumtpl->set("NO_TOPICS",false,true);

    $topics=array();
    $i=0;
    while ($topicarr = mysql_fetch_assoc($topicsres))
    {
      $topicid = $topicarr["id"];
      $topic_userid = $topicarr["userid"];
      $topic_views = $topicarr["views"];
      $locked = $topicarr["locked"] == "yes";
      $sticky = $topicarr["sticky"] == "yes";
      $tpages = floor(intval($topicarr["num_posts"]) / $postsperpage);

      if (($tpages * $postsperpage) != intval($topicarr["num_posts"]))
        ++$tpages;

      if ($tpages > 1)
      {
        $topicpages = " (<img src=images/multipage.gif>";
        for ($i = 1; $i <= $tpages; ++$i)
          $topicpages .= " <a href=\"index.php?page=forum&amp;action=viewtopic&amp;topicid=$topicid&amp;page=$i\">$i</a>";
        $topicpages .= ")";
      }
      else
        $topicpages = "";

      $lppostid = 0 + $topicarr["lastpost"];
      $lpuserid = 0 + $topicarr["lastposter_uid"];
      $lpusername = ($topicarr["lastposter"]?"<a href=\"index.php?page=userdetails&amp;id=$lpuserid\"><b>".$topicarr["lastposter"]."</b></a>":"unknown[$topic_userid]");

      $new = $topicarr["img"]=="unlockednew";

      $topicpic = ($locked ? ($new ? "lockednew" : "locked") : $topicarr["img"]);

      $subject = ($sticky ? $language["STICKY"].": " : "") . "<a href=\"index.php?page=forum&amp;action=viewtopic&amp;topicid=$topicid\"><b>" .
      htmlspecialchars(unesc($topicarr["subject"])) .
      "&nbsp;<a href=\"index.php?page=forum&amp;action=viewtopic&amp;topicid=$topicid&amp;msg=new#new\">".image_or_link("$STYLEPATH/images/new.gif","",$language["NEW"])."</a>".
      "</b></a>$topicpages";

      $topics[$i]["view"]=number_format($topic_views);
      $topics[$i]["replies"]=intval($topicarr["num_posts"]) - 1;
      $topics[$i]["starter"]=($topicarr["starter"]?"<a href=\"index.php?page=userdetails&amp;id=$topic_userid\"><b>".$topicarr["starter"]."</b></a>":"unknown[$topic_userid]");
      $topics[$i]["status"]=image_or_link("$STYLEPATH/images/$topicpic.png","",$topicpic);
      $topics[$i]["topic"]=$subject;
      $topics[$i]["lastpost"]=get_date_time($topicarr["start_date"])." ". $language["BY"] . " $lpusername";
      $i++;

    } // while

    $forumtpl->set("topics",$topics);

} // if
else
   $forumtpl->set("NO_TOPICS",true,true);

$forumtpl->set("forum_pager",$pagertop);


?>