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