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