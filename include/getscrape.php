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


$BASEDIR=dirname(__FILE__);
require_once ("$BASEDIR/BDecode.php");
require_once ("$BASEDIR/config.php");
require_once ("$BASEDIR/functions.php");

ignore_user_abort(1);

function escapeURL($info)
{
    $ret = "";
    $i=0;
    while (strlen($info) > $i)
    {
        $ret .= "%".$info[$i].$info[$i + 1];
        $i+=2;
    }
    return $ret;
}

function stristr_reverse($haystack, $needle) {
  $pos = strrpos($haystack, $needle);
  return substr($haystack, 0, $pos);
}

function scrape($url,$infohash="")
  {

  global $TABLE_PREFIX;

    if (isset($url))
      {

       $u = urldecode($url);
       $extannunce = str_replace("announce","scrape",$u);

       //$purl=parse_url($extannunce);
       //$port=isset($purl["port"])?$purl["port"]:"80";
       //$path=isset($purl["path"])?$purl["path"]:"/scrape.php";
       //$an=($purl["scheme"]!="http"?$purl["scheme"]."://":"").$purl["host"];
       //$fd=@fsockopen($an,$port,$errno,$errstr, 60);

       if ($infohash!="")
          {
           $ihash=array();
           $ihash=explode("','",$infohash);
           $info_hash="";
           foreach($ihash as $myihash)
                $info_hash.="&info_hash=".escapeURL($myihash);
           $info_hash=substr($info_hash,1);
           $stream=get_remote_file("$extannunce?$info_hash");
          }
       else
          $stream=get_remote_file("$extannunce");

        $stream=trim(stristr($stream,"d5:files"));

        if (strpos($stream,"d5:files")===false)
          {
           $ret = do_sqlquery("UPDATE {$TABLE_PREFIX}files SET lastupdate=NOW() WHERE announce_url = \"$url\"".($infohash=="" ? "" : " AND info_hash IN ('$infohash')"));
           write_log("FAILED update external torrent ".($infohash=="" ? "" : "(infohash: $infohash)")." from $url tracker (not connectable)","");
           return;
        }

        $array = BDecode($stream);
        if (!isset($array))
        {
              $ret = do_sqlquery("UPDATE {$TABLE_PREFIX}files SET lastupdate=NOW() WHERE announce_url = \"$url\"".($infohash=="" ? "" : " AND info_hash IN ('$infohash')"));
              write_log("FAILED update external torrent ".($infohash=="" ? "" : "(infohash: $infohash)")." from $url tracker (not bencode data)","");
              return;
        }
        if ($array == false)
        {
            $ret = do_sqlquery("UPDATE {$TABLE_PREFIX}files SET lastupdate=NOW() WHERE announce_url = \"$url\"".($infohash=="" ? "" : " AND info_hash IN ('$infohash')"));
            write_log("FAILED update external torrent ".($infohash=="" ? "" : "(infohash: $infohash)")." from $url tracker (not bencode data)","");
            return;
        }
            if (!isset($array["files"]))
            {
                $ret = do_sqlquery("UPDATE {$TABLE_PREFIX}files SET lastupdate=NOW() WHERE announce_url = \"$url\"".($infohash=="" ? "" : " AND info_hash IN ('$infohash')"));
                write_log("FAILED update external ".($infohash=="" ? "" : "(infohash: $infohash)")." torrent from $url tracker (not bencode data)","");
                return;
            }
            $files = $array["files"];


            if(!is_array($files))
              {
                $ret = do_sqlquery("UPDATE {$TABLE_PREFIX}files SET lastupdate=NOW() WHERE announce_url = \"$url\"".($infohash=="" ? "" : " AND info_hash IN ('$infohash')"));
                write_log("FAILED update external torrent ".($infohash=="" ? "" : "(infohash: $infohash)")." from $url tracker (probably deleted torrent(s))","");
                return;
              }
            foreach ($files as $hash => $data)
            {
              $seeders = $data["complete"];
              $leechers = $data["incomplete"];
              if (isset($data["downloaded"]))
                 $completed = $data["downloaded"];
              else
                  $completed = "0";
              $torrenthash=bin2hex(stripslashes($hash));
              $ret = do_sqlquery("UPDATE {$TABLE_PREFIX}files SET lastupdate=NOW(), lastsuccess=NOW(), seeds = $seeders, leechers = $leechers, finished= $completed WHERE announce_url = \"$url\"".($hash=="" ? "" : " AND info_hash='$torrenthash'"));
              if (mysql_affected_rows()==1)
                    write_log("SUCCESS update external torrent from $url tracker (infohash: $torrenthash)","");
            }
    }
}

?>