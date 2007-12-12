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