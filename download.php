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

$THIS_BASEPATH=dirname(__FILE__);

require_once("$THIS_BASEPATH/include/functions.php");
require_once ("$THIS_BASEPATH/include/BDecode.php");
require_once ("$THIS_BASEPATH/include/BEncode.php");

dbconn();

if (!$CURUSER || $CURUSER["can_download"]=="no")
   {
       require(load_language("lang_main.php"));
       die($language["NOT_AUTH_DOWNLOAD"]);
   }

if(ini_get('zlib.output_compression'))
  ini_set('zlib.output_compression','Off');

$infohash=$_GET["id"];
$filepath=$TORRENTSDIR."/".$infohash . ".btf";

if (!is_file($filepath) || !is_readable($filepath))
   {

       require(load_language("lang_main.php"));
       die($language["CANT_FIND_TORRENT"]);
    }

$f=urldecode($_GET["f"]);

// pid code begin
$result=do_sqlquery("SELECT pid FROM {$TABLE_PREFIX}users WHERE id=".$CURUSER['uid']);
$row = mysql_fetch_assoc($result);
$pid=$row["pid"];
if (!$pid)
   {
   $pid=md5(uniqid(rand(),true));
   do_sqlquery("UPDATE {$TABLE_PREFIX}users SET pid='".$pid."' WHERE id='".$CURUSER['uid']."'");
   if ($XBTT_USE)
      do_sqlquery("UPDATE xbt_users SET torrent_pass='".$pid."' WHERE uid='".$CURUSER['uid']."'");
}

$result=do_sqlquery("SELECT * FROM {$TABLE_PREFIX}files WHERE info_hash='".$infohash."'");
$row = mysql_fetch_assoc($result);

if ($row["external"]=="yes" || !$PRIVATE_ANNOUNCE)
   {
    $fd = fopen($filepath, "rb");
    $alltorrent = fread($fd, filesize($filepath));
    fclose($fd);
    header("Content-Type: application/x-bittorrent");
    header('Content-Disposition: attachment; filename="'.$f.'"');
    print($alltorrent);
   }
else
    {
    $fd = fopen($filepath, "rb");
    $alltorrent = fread($fd, filesize($filepath));
    $array = BDecode($alltorrent);
    fclose($fd);
//    print($alltorrent."<br />\n<br />\n");
    if ($XBTT_USE)
    {
       $array["announce"] = $XBTT_URL."/$pid/announce";
       if (isset($array["announce-list"]) && is_array($array["announce-list"]))
          {
          for ($i=0;$i<count($array["announce-list"]);$i++)
              {
              if (in_array($array["announce-list"][$i][0],$TRACKER_ANNOUNCEURLS))
                 {
                 if (strpos($array["announce-list"][$i][0],"announce.php")===false)
                    $array["announce-list"][$i][0] = trim(str_replace("/announce", "/$pid/announce", $array["announce-list"][$i][0]));
                 else
                    $array["announce-list"][$i][0] = trim(str_replace("/announce.php", "/announce.php?pid=$pid", $array["announce-list"][$i][0]));
                 }
              }
          }
    }
    else
    {
       $array["announce"] = $BASEURL."/announce.php?pid=$pid";
       if (isset($array["announce-list"]) && is_array($array["announce-list"]))
          {
          for ($i=0;$i<count($array["announce-list"]);$i++)
              {
              if (in_array($array["announce-list"][$i][0],$TRACKER_ANNOUNCEURLS))
                 {
                 if (strpos($array["announce-list"][$i][0],"announce.php")===false)
                    $array["announce-list"][$i][0] = trim(str_replace("/announce", "/$pid/announce", $array["announce-list"][$i][0]));
                 else
                    $array["announce-list"][$i][0] = trim(str_replace("/announce.php", "/announce.php?pid=$pid", $array["announce-list"][$i][0]));
                 }
              }
          }

    }
    $alltorrent=BEncode($array);

    header("Content-Type: application/x-bittorrent");
    header('Content-Disposition: attachment; filename="'.$f.'"');
    print($alltorrent);
    }
?>