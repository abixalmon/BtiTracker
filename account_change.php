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

require_once(dirname(__FILE__)."/include/functions.php");
//require_once(dirname(__FILE__)."/include/config.php");
include(dirname(__FILE__)."/btemplate/bTemplate.php");

if (isset($_GET["style"]))
    $style=intval($_GET["style"]);
else
    $style=0;
if (isset($_GET["returnto"]))
   $url=urldecode($_GET["returnto"]);
else
   $url="index.php";
if (isset($_GET["langue"]))
   $langue=intval($_GET["langue"]);
else
   $langue=0;

session_start();

dbconn();

// guest don't need to change language!
if (!$CURUSER || $CURUSER["uid"]==1)
  {
  redirect($url);
  exit;
 }

if ($style!=0)
   do_sqlquery("UPDATE {$TABLE_PREFIX}users SET style=$style WHERE id=".(int)$CURUSER["uid"],true);

if ($langue!=0)
   do_sqlquery("UPDATE {$TABLE_PREFIX}users SET language=$langue WHERE id=".(int)$CURUSER["uid"],true);


$_SESSION['user']['style_url']='';
$_SESSION['user']['language_path']='';


// force user's data
if ($btit_settings['xbtt_use'])
{
  $udownloaded="u.downloaded+IFNULL(x.downloaded,0)";
  $uuploaded="u.uploaded+IFNULL(x.uploaded,0)";
  $utables="{$TABLE_PREFIX}users u LEFT JOIN xbt_users x ON x.uid=u.id";
}
else
{
  $udownloaded="u.downloaded";
  $uuploaded="u.uploaded";
  $utables="{$TABLE_PREFIX}users u";
}
get_result("SELECT u.lip, u.cip, $udownloaded as downloaded, $uuploaded as uploaded, u.smf_fid, u.topicsperpage, u.postsperpage,u.torrentsperpage, u.flag, u.avatar, UNIX_TIMESTAMP(u.lastconnect) AS lastconnect, UNIX_TIMESTAMP(u.joined) AS joined, u.id as uid, u.username, u.password, u.random, u.email, u.language,u.style, u.time_offset, ul.* FROM $utables INNER JOIN {$TABLE_PREFIX}users_level ul ON u.id_level=ul.id WHERE u.id = ".(int)$CURUSER['uid']." LIMIT 1;",false,1);

redirect($url);
?>