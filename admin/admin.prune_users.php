<?php

if (!defined("IN_BTIT"))
      die("non direct access!");

if (!defined("IN_ACP"))
      die("non direct access!");

$action=(isset($_GET["action"])?$_GET["action"]:"");
$days=(isset($_POST["days"])?max(0,$_POST["days"]):"");

if ($action=="prune")
   {
     if (!isset($_POST["id"]))
         {
            redirect("index.php?page=admin&user=".$CURUSER["uid"]."&code=".$CURUSER["random"]."&do=pruneu");
            die();
         }
     $count=0;
     $del_id=array();
     
     foreach($_POST["id"] as $id=>$uid)
            {
             if ($uid==1) continue;
             $del_id[]=$uid;
             }
     do_sqlquery("DELETE FROM {$TABLE_PREFIX}users WHERE id IN (".implode("','",$del_id).")",true);
     
     if($GLOBALS["FORUMLINK"]=="smf")
     {
         $basedir=substr(str_replace("\\", "/", dirname(__FILE__)), 0, strrpos(str_replace("\\", "/", dirname(__FILE__)), '/'));
         require_once($basedir."/smf/Settings.php");
        
         $smf_fid=array();
         foreach($_POST["smf_fid"] AS $v)
         {
             $smf_fid[]=intval($v);
         }
        
         do_sqlquery("DELETE FROM {$db_prefix}members WHERE ID_MEMBER IN(".implode(",", $smf_fid).")",true);
     }
     
     $block_title=$language["PRUNE_USERS_PRUNED"];
     $admintpl->set("pruned_done",true,true);
     $admintpl->set("prune_done_msg","n.".count($del_id)." users pruned!<br />\n<a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."\">".$language["BACK"]."</a>");
     $admintpl->set("prune_list",false,true);

   }
elseif ($action=="view")
   {
      // 30 DAYS
      if ($days==0)
          {
          // days not set!!
          redirect("index.php?page=admin&user=".$CURUSER["uid"]."&code=".$CURUSER["random"]."&do=pruneu");
          exit;
          }
      $timeout=(60*60*24)*$days;

      $res=get_result("SELECT u.id, u.username, UNIX_TIMESTAMP(u.joined) as joined, UNIX_TIMESTAMP(u.lastconnect) as lastconnect, ul.level".(($GLOBALS["FORUMLINK"]=="smf") ? ", u.smf_fid" : "")." from {$TABLE_PREFIX}users u INNER JOIN {$TABLE_PREFIX}users_level ul ON ul.id=u.id_level WHERE (u.id>1 AND ul.id_level<3 AND UNIX_TIMESTAMP(joined)<(UNIX_TIMESTAMP()-$timeout)) OR (u.id>1 AND ul.id_level<7 AND UNIX_TIMESTAMP(lastconnect)<(UNIX_TIMESTAMP()-$timeout)) ORDER BY ul.id_level DESC, lastconnect",true);


      $block_title=$language["PRUNE_USERS"];

       include("$THIS_BASEPATH/include/offset.php");

       $ru=array();
       $i=0;
       foreach($res as $id=>$rusers)
           {
             $ru[$i]["username"]=unesc($rusers["username"]);
             $ru[$i]["joined"]=date("d/m/Y H:i",$rusers["joined"]-$offset)." (".get_elapsed_time($rusers["joined"]-$offset)." ago)";
             $ru[$i]["lastconnect"]=date("d/m/Y H:i",$rusers["lastconnect"]-$offset)." (".get_elapsed_time($rusers["lastconnect"]-$offset)." ago)";;
             $ru[$i]["level"]=unesc($rusers["level"]);
             $ru[$i]["id"]=$rusers["id"];
             if($GLOBALS["FORUMLINK"]=="smf")
                 $ru[$i]["smf_fid"]=$rusers["smf_fid"];
             $i++;
           }
      unset($res);
      
      $admintpl->set("language",$language);
      $admintpl->set("frm_action","index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=pruneu&amp;action=prune");
      $admintpl->set("pruned_done",false,true);
      $admintpl->set("prune_list",true,true);
      $admintpl->set("no_records",($i==0),true);
      $admintpl->set("users",$ru);



}
else
{
    $block_title=$language["PRUNE_USERS"];
    $admintpl->set("language",$language);
    $admintpl->set("frm_action","index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=pruneu&amp;action=view");
    $admintpl->set("pruned_done",false,true);
    $admintpl->set("prune_list",false,true);
    $admintpl->set("prune_days","30");

}
?>