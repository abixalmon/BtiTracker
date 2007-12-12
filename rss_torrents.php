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

if ($XBTT_USE)
   {
    $tseeds="f.seeds+ifnull(x.seeders,0)";
    $tleechs="f.leechers+ifnull(x.leechers,0)";
    $ttables="{$TABLE_PREFIX}files f INNER JOIN xbt_files x ON x.info_hash=f.bin_hash";
   }
else
    {
    $tseeds="f.seeds";
    $tleechs="f.leechers";
    $ttables="{$TABLE_PREFIX}files f";
    }

dbconn(true);

if ($CURUSER["view_torrents"]!="yes")
   {
   header(ERR_500);
   die;
}

header("Content-type: text/xml");

print("<?xml version=\"1.0\" encoding=\"".$GLOBALS["charset"]."\"?>");
?>

<rss version="2.0">
<channel>
<title><?php print $SITENAME;?></title>
<description>rss feed script designed and coded by beeman (modified by Lupin and VisiGod)</description>
<link><?php print $BASEURL;?></link>
<lastBuildDate><?php print date("D, d M Y H:i:s O");?></lastBuildDate>
<copyright><?php print "(c) ". date("Y",time())." " .$SITENAME;?></copyright>

<?php

  $getItems = "SELECT f.info_hash as id, f.comment as description, f.filename, $tseeds AS seeders, $tleechs as leechers, UNIX_TIMESTAMP( f.data ) as added, c.name as cname, f.size FROM $ttables LEFT JOIN {$TABLE_PREFIX}categories c ON c.id = f.category ORDER BY data DESC LIMIT 20";
  $doGet=do_sqlquery($getItems) or die(mysql_error());;

  while($item=mysql_fetch_array($doGet))
   {
    $id=$item['id'];
    $filename=($item['filename']);
    $added=strip_tags(date("D, d M Y H:i:s O",$item['added']));
    $cat=strip_tags($item['cname']);
    $seeders=strip_tags($item['seeders']);
    $leechers=strip_tags($item['leechers']);
    $desc=format_comment($item['description']);
    $f=rawurlencode($item['filename']);
    // output to browser

?>

  <item>
  <title><![CDATA[<?php print htmlspecialchars("[$cat] $filename [".SEEDERS." ($seeders)/".LEECHERS." ($leechers)]");?>]]></title>
  <description><![CDATA[<?php print $desc; ?>]]></description>
  <link><?php print "$BASEURL/details.php?id=$id";?></link>
  <guid><?php print "$BASEURL/details.php?id=$id";?></guid>
  <enclosure url="<?php print("$BASEURL/download.php?id=$id&amp;f=$f.torrent");?>" length="<?php print $item["size"] ?>" type="application/x-bittorrent" />
  <pubDate><?php print $added;?></pubDate>
  </item>

<?php
}

?>
</channel>
</rss>