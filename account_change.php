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

require_once("include/functions.php");
require_once("include/config.php");

if (isset($_GET["style"]))
    $style=intval($_GET["style"]);
else
    $style=1;
if (isset($_GET["returnto"]))
   $url=urldecode($_GET["returnto"]);
else
   $url="index.php";
if (isset($_GET["langue"]))
   $langue=intval($_GET["langue"]);
else
   $langue=1;

dbconn();

// guest don't need to change language!
if (!$CURUSER || $CURUSER["uid"]==1)
  {
  redirect($url);
  exit;
 }

if (isset($_GET["style"]))
   do_sqlquery("UPDATE {$TABLE_PREFIX}users SET style=$style WHERE id=".$CURUSER["uid"]);

if (isset($_GET["langue"]))
   do_sqlquery("UPDATE {$TABLE_PREFIX}users SET language=$langue WHERE id=".$CURUSER["uid"]);

redirect($url);
?>