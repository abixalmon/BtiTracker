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

if ($CURUSER["view_news"]=="no")
   {
       err_msg($language["ERROR"],$language["NOT_AUTHORIZED"]."!");
       stdfoot();
       exit;
}

//     global $CURUSER, $limitqry, $adm_menu, $CURRENTPATH, $TABLE_PREFIX;
//     $output="";

if ($limit>0)
  $limitqry="LIMIT $limit";
$res=do_sqlquery("SELECT n.id, n.title, n.news,UNIX_TIMESTAMP(n.date) as news_date, u.username FROM {$TABLE_PREFIX}news n INNER JOIN {$TABLE_PREFIX}users u on u.id=n.user_id ORDER BY date DESC $limitqry");

// $row = mysql_fetch_row($res);
// $count = $row[0];

// load language file
require(load_language("lang_viewnews.php"));

$viewnewstpl = new bTemplate();
$viewnewstpl -> set("language",$language);
$viewnewstpl -> set("can_edit_news", $CURUSER["edit_news"]=="yes", TRUE);
$viewnewstpl -> set("can_edit_news_1", $CURUSER["edit_news"]=="yes", TRUE);
$viewnewstpl -> set("can_delete_news", $CURUSER["delete_news"]=="yes", TRUE);

$viewnews=array();
$i=0;

$viewnewstpl -> set("news_exists", (mysql_num_rows($res) > 0),TRUE);
$viewnewstpl -> set("insert_news_link", (mysql_num_rows($res) == 0?"<a href=\"index.php?page=news&amp;act=add\"><img border=\"0\" alt=\"".$language["ADD"]."\" src=\"$BASEURL/images/new.gif\" /></a>":""));

include("$THIS_BASEPATH/include/offset.php");


while ($rows=mysql_fetch_array($res))
  {
  
      $viewnews[$i]["add_edit_news"] = "<a href=\"index.php?page=news&amp;act=add\">".$language["ADD"]."</a>&nbsp;&nbsp;&nbsp;<a href=\"index.php?page=news&amp;act=edit&amp;id=".$rows["id"]."\">".$language["EDIT"]."</a>";
      $viewnews[$i]["delete_news"] = "&nbsp;&nbsp;&nbsp;<a onclick=\"return confirm('". str_replace("'","\'",DELETE_CONFIRM)."')\" href=\"index.php?page=news&amp;act=del&amp;id=".$rows["id"]."\">".$language["DELETE"]."</a>";
      $viewnews[$i]["user_posted"] = unesc($rows["username"]);
      $viewnews[$i]["posted_date"] = date("d/m/Y H:i",$rows["news_date"]-$offset);
      $viewnews[$i]["news_title"] = unesc($rows["title"]);
      $viewnews[$i]["news"] = format_comment($rows["news"]);
    
      $i++;
    
  }

$viewnewstpl -> set("viewnews", $viewnews);

?>