<?php
global $USERLANG;
require_once(load_language("lang_blocks.php"));

//die(print_r($CURUSER));

function main_menu()
{
  global $TABLE_PREFIX, $CURUSER, $tpl;

$res =do_sqlquery("SELECT * FROM {$TABLE_PREFIX}blocks WHERE position='t' AND status=1 AND ".$CURUSER["id_level"].">=minclassview  AND ".$CURUSER["id_level"]."<=maxclassview ORDER BY sortid",true);
$blocks=array();
while($result=mysql_fetch_assoc($res)){
    if($result["status"]) {
        $blocks[]=$result;

    }
}

 $return="";
  foreach ($blocks as $id=>$entry){
     if($entry["content"]!="forum" || ($entry["content"]=="forum" && ($GLOBALS["FORUMLINK"]=="" || $GLOBALS["FORUMLINK"]=="internal" || $GLOBALS["FORUMLINK"]=="smf")))
          $return.=get_content(realpath(dirname(__FILE__)."/..")."/blocks/".$entry["content"]."_block.php");
    }

    return set_block("","justify",$return);

}

function center_menu()
{
  global $TABLE_PREFIX, $language, $CURUSER;

$res =do_sqlquery("SELECT * FROM {$TABLE_PREFIX}blocks WHERE position='c' AND status=1 AND ".$CURUSER["id_level"].">=minclassview  AND ".$CURUSER["id_level"]."<=maxclassview ORDER BY sortid",true);
$blocks=array();
while($result=mysql_fetch_assoc($res)){
    if($result["status"]) {
        $blocks[]=$result;

    }
}

 $return="";
  foreach ($blocks as $id=>$entry){
     if($entry["content"]!="forum" || ($entry["content"]=="forum" && ($GLOBALS["FORUMLINK"]=="" || $GLOBALS["FORUMLINK"]=="internal" || $GLOBALS["FORUMLINK"]=="smf")))
          $return.=get_block($language[$entry["title"]],"justify",$entry["content"],$entry["cache"]=="yes");
    }

    return $return;

}


function side_menu()
{

  global $TABLE_PREFIX, $language, $CURUSER;

$res =do_sqlquery("SELECT * FROM {$TABLE_PREFIX}blocks WHERE position='l' AND status=1 AND ".$CURUSER["id_level"].">=minclassview  AND ".$CURUSER["id_level"]."<=maxclassview ORDER BY sortid",true);
$blocks=array();
while($result=mysql_fetch_assoc($res)){
    if($result["status"]) {
        $blocks[]=$result;

    }
}

 $return="";
  foreach ($blocks as $id=>$entry){
     if($entry["content"]!="forum" || ($entry["content"]=="forum" && ($GLOBALS["FORUMLINK"]=="" || $GLOBALS["FORUMLINK"]=="internal" || $GLOBALS["FORUMLINK"]=="smf")))
          $return.=get_block($language[$entry["title"]],"justify",$entry["content"],$entry["cache"]=="yes");
    }

    return $return;
}

function right_menu()
{

  global $TABLE_PREFIX, $language, $CURUSER;

$res =do_sqlquery("SELECT * FROM {$TABLE_PREFIX}blocks WHERE position='r' AND status=1 AND ".$CURUSER["id_level"].">=minclassview  AND ".$CURUSER["id_level"]."<=maxclassview ORDER BY sortid",true);
$blocks=array();
while($result=mysql_fetch_assoc($res)){
    if($result["status"]) {
        $blocks[]=$result;

    }
}

 $return="";
  foreach ($blocks as $id=>$entry){
     if($entry["content"]!="forum" || ($entry["content"]=="forum" && ($GLOBALS["FORUMLINK"]=="" || $GLOBALS["FORUMLINK"]=="internal" || $GLOBALS["FORUMLINK"]=="smf")))
          $return.=get_block($language[$entry["title"]],"justify",$entry["content"],$entry["cache"]=="yes");
    }

    return $return;

}


function bottom_menu()
{

  global $TABLE_PREFIX, $language, $CURUSER;

$res =do_sqlquery("SELECT * FROM {$TABLE_PREFIX}blocks WHERE position='b' AND status=1 AND ".$CURUSER["id_level"].">=minclassview  AND ".$CURUSER["id_level"]."<=maxclassview ORDER BY sortid",true);
$blocks=array();
while($result=mysql_fetch_assoc($res)){
    if($result["status"]) {
        $blocks[]=$result;

    }
}

 $return="";
  foreach ($blocks as $id=>$entry){
     if($entry["content"]!="forum" || ($entry["content"]=="forum" && ($GLOBALS["FORUMLINK"]=="" || $GLOBALS["FORUMLINK"]=="internal" || $GLOBALS["FORUMLINK"]=="smf")))
          $return.=get_block($language[$entry["title"]],"justify",$entry["content"],$entry["cache"]=="yes");
    }

    return $return;

}


?>