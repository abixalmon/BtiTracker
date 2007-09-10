<?php

if (!$CURUSER || $CURUSER["uid"]==1)
   {
   stderr($language["ERROR"],$language["ONLY_REG_COMMENT"]);
}

$comment = ($_POST["comment"]);

$id = $_GET["id"];
if (isset($_GET["cid"]))
    $cid = intval($_GET["cid"]);
else
    $cid=0;


if (isset($_GET["action"]))
 {
  if ($CURUSER["admin_access"]=="yes" && $_GET["action"]=="delete")
    {
     do_sqlquery("DELETE FROM {$TABLE_PREFIX}comments WHERE id=$cid");
     redirect("index.php?page=torrent-details&id=$id#comments");
     exit;
    }
 }

$tpl_comment=new bTemplate();

$tpl_comment->set("language",$language);
$tpl_comment->set("comment_id",$id);
$tpl_comment->set("comment_username",$CURUSER["username"]);
$tpl_comment->set("comment_comment",textbbcode("comment","comment",htmlspecialchars(unesc($comment))));


if (isset($_POST["info_hash"])) {
   if ($_POST["confirm"]==$language["FRM_CONFIRM"]) {
   $comment = addslashes($_POST["comment"]);
      $user=AddSlashes($CURUSER["username"]);
      if ($user=="") $user="Anonymous";
  do_sqlquery("INSERT INTO {$TABLE_PREFIX}comments (added,text,ori_text,user,info_hash) VALUES (NOW(),\"$comment\",\"$comment\",\"$user\",\"" . mysql_escape_string(StripSlashes($_POST["info_hash"])) . "\")",true);
  redirect("index.php?page=torrent-details&id=" . StripSlashes($_POST["info_hash"])."#comments");
  die();
  }

# Comment preview by miskotes
#############################

if ($_POST["confirm"]==$language["FRM_PREVIEW"]) {

$tpl_comment->set("PREVIEW",TRUE,TRUE);
$tpl_comment->set("comment_preview",set_block($language["COMMENT_PREVIEW"],"center",format_comment($comment),false));

#####################
# Comment preview end
}
  else
    {
    redirect("index.php?page=torrent-details&id=" . StripSlashes($_POST["info_hash"])."#comments");
    die();
  }
}
else
    $tpl_comment->set("PREVIEW",FALSE,TRUE);

?>