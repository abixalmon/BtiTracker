<?php
/////////////////////////////////////////////////////////////////////////////////////
// xbtit - Bittorrent tracker/frontend
//
// Copyright (C) 2004 - 2007  Btiteam
//
//    This file is part of xbtit.
//
// Redistribution and use in source and binary forms, with or without modification,
// are permitted provided that the following conditions are met:
//
//   1. Redistributions of source code must retain the above copyright notice,
//      this list of conditions and the following disclaimer.
//   2. Redistributions in binary form must reproduce the above copyright notice,
//      this list of conditions and the following disclaimer in the documentation
//      and/or other materials provided with the distribution.
//   3. The name of the author may not be used to endorse or promote products
//      derived from this software without specific prior written permission.
//
// THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR IMPLIED
// WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
// MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
// IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
// SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED
// TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
// PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
// LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
// NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE,
// EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
//
////////////////////////////////////////////////////////////////////////////////////

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