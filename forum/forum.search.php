<?php

if (isset($_GET["keywords"]) && $_GET["keywords"])
    $keywords = trim($_GET["keywords"]);
else
    $keywords = "";

if ($keywords != "")
{
    $perpage = $CURUSER["topicsperpage"];
    if (!$perpage) $perpage = 20;

    $pagemenu1="";
    $page = (isset($_GET["page"])?max(1, intval(0 + $_GET["page"])):1);

    $ekeywords = sqlesc($keywords);

    $res = do_sqlquery("SELECT COUNT(*) FROM {$TABLE_PREFIX}posts WHERE MATCH (body) AGAINST ($ekeywords IN BOOLEAN MODE)",true);
    $arr = mysql_fetch_row($res);
    $hits = intval(0 + $arr[0]);
    if ($hits == 0)
      {
        $forumtpl->set("NO_TOPICS",true, true);
    }
    else
      {
        $forumtpl->set("NO_TOPICS",false, true);
        list($pagertop,$pagerbottom, $limit)=forum_pager($perpage,$hits, "index.php?page=forum&amp;action=search&amp;keywords=" . htmlspecialchars($keywords) . "&amp;");


        $res = get_result("SELECT p.*, t.subject, f.id as forumid, f.name as forumname, u.username, p.added, MATCH (p.body) AGAINST ($ekeywords IN BOOLEAN MODE) as score FROM {$TABLE_PREFIX}posts p".
                        " LEFT JOIN {$TABLE_PREFIX}users u ON p.userid=u.id INNER JOIN {$TABLE_PREFIX}topics t ON p.topicid=t.id".
                        " INNER JOIN {$TABLE_PREFIX}forums f ON t.forumid=f.id".
                        " WHERE IFNULL(f.minclassread,999)<=".$CURUSER["id_level"]." AND MATCH (p.body) AGAINST ($ekeywords IN BOOLEAN MODE)".
                        " ORDER BY score, added DESC  $limit",true);

        $search=array();
        $i=0;
        foreach($res as $id=>$sr)
        {
            if ($sr["forumname"] == "")
                continue;

            $search[$i]["match"]=$sr["score"];
            $search[$i]["postid"]=$sr["id"];
            $search[$i]["topic"]="<a href=\"index.php?page=forum&amp;action=viewtopic&amp;topicid=".$sr["topicid"]."&amp;hl=".urlencode($keywords)."&amp;msg=".$sr["id"]."#".$sr["id"]."\">".unesc(htmlspecialchars($sr["subject"]))."</a>";
            $search[$i]["forum"]="<a href=\"index.php?page=forum&amp;action=viewforum&amp;forumid=".$sr["forumid"]."\">".unesc(htmlspecialchars($sr["forumname"]))."</a>";
            $search[$i]["author"]=get_date_time($sr["added"])."&nbsp;".$language["AT"]."&nbsp;".($sr["username"] == ""?"unknown[".$sr["userid"]."]":"<a href=\"index.php?page=userdetails&amp;id=".$sr["userid"]."\">".unesc(htmlspecialchars($sr["username"]))."</a>");
            $search[$i]["body"]=highlight_search(cut_string($sr["body"],200),explode(" ",$keywords));
            $i++;
        }
        $forumtpl->set("topics",$search);

    }

    $forumtpl->set("forum_pager",$pagertop);
    $forumtpl->set("results",true, true);
    $forumtpl->set("search_hits",$i);

}
else
    $forumtpl->set("results",false, true);

$forumtpl->set("search_keywords",$keywords);


?>