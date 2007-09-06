<?php


if (!defined("IN_BTIT"))
      die("non direct access!");

if (!defined("IN_ACP"))
      die("non direct access!");


if (!$CURUSER || $CURUSER["admin_access"]!="yes")
   {
       err_msg(ERROR,NOT_ADMIN_CP_ACCESS);
       stdfoot();
       exit;
}
else
{
    $delete_timeout=time() - (60*60*24*7); // delete log older then 7 days
    do_sqlquery("DELETE FROM {$TABLE_PREFIX}logs where added<$delete_timeout");
    $logres=do_sqlquery("SELECT COUNT(*) FROM {$TABLE_PREFIX}logs ORDER BY added DESC");
    $lognum=mysql_fetch_row($logres);
    $num=$lognum[0];
    $perpage=(max(0,$CURUSER["postsperpage"])>0?$CURUSER["postsperpage"]:20);
    list($pagertop, $pagerbottom, $limit) = pager($perpage, $num, "index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=logview&amp;");
    
    $admintpl->set("language",$language);
    $admintpl->set("pager_top",$pagertop);
    $admintpl->set("pager_bottom",$pagerbottom);

    $logres=do_sqlquery("SELECT * FROM {$TABLE_PREFIX}logs ORDER BY added DESC $limit");
    $log=array();
    $i=0;

    include("$THIS_BASEPATH/include/offset.php");

    if ($logres)
        {
        while ($logview=mysql_fetch_assoc($logres))
            {
            if ($logview["type"]=="delete")
                $log[$i]["class"]="class=\"deleted\"";
            elseif ($logview["type"]=="add")
                $log[$i]["class"]="class=\"added\"";
            elseif ($logview["type"]=="modify")
                $log[$i]["class"]="class=\"modified\"";
            else
                $log[$i]["class"]="class=\"lista\"";

          $log[$i]["date"]=date("d/m/Y H:i:s",$logview["added"]-$offset);
          $log[$i]["username"]=$logview["user"];
          $log[$i]["action"]=$logview["txt"];
          $i++;
         }

    }

    $admintpl->set("logs",$log);

    unset($logview);
    mysql_free_result($logres);
    unset($log);

}
?>