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