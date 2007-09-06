<?php
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

if ($CURUSER["view_torrents"]!="yes" && $CURUSER["view_forum"]!="yes")
   {
   header(ERR_500);
   die;
}

header("Content-type: text/xml");
print("<?xml version=\"1.0\" encoding=\"".$GLOBALS["charset"]."\"?>");

function safehtml($string)
{
$validcharset=array(
"ISO-8859-1",
"ISO-8859-15",
"UTF-8",
"cp866",
"cp1251",
"cp1252",
"KOI8-R",
"BIG5",
"GB2312",
"BIG5-HKSCS",
"Shift_JIS",
"EUC-JP");

   if (in_array($GLOBALS["charset"],$validcharset))
      return htmlentities($string,ENT_COMPAT,$GLOBALS["charset"]);
   else
       return htmlentities($string);
}

?>

<rss version="2.0" >
<channel>
<title><?php print $SITENAME;?></title>
<description>rss feed script designed and coded by beeman (modified by Lupin and VisiGod)</description>
<link><?php print $BASEURL;?></link>
<lastBuildDate><?php print date("D, d M Y H:i:s T");?></lastBuildDate>
<copyright><?php print "(c) ". date("Y",time())." " .$SITENAME;?></copyright>

<?php

if ($CURUSER["view_torrents"]=="yes")
{
  $getItems = "SELECT f.info_hash as id, f.comment as description, f.filename, $tseeds AS seeders, $tleechs as leechers, UNIX_TIMESTAMP( f.data ) as added, c.name as cname, f.size FROM $ttables LEFT JOIN {$TABLE_PREFIX}categories c ON c.id = f.category $where ORDER BY data DESC LIMIT 20";
  $doGet=do_sqlquery($getItems,true) or die(mysql_error());;

  while($item=mysql_fetch_array($doGet))
   {
    $id=$item['id'];
    $filename=strip_tags($item['filename']);
    $added=strip_tags(date("d/m/Y H:i:s",$item['added']));
    $descr=format_comment($item['description']."\n");
    $seeders=strip_tags($item['seeders']);
    $leechers=strip_tags($item['leechers']);
    // output to browser

?>

  <item>
  <title><![CDATA[<?php print htmlspecialchars("[".TORRENT."] ".$filename);?>]]></title>
  <description><![CDATA[<?php print ($descr)." (".SEEDERS." ".safehtml($seeders)." -- ".LEECHERS." ".safehtml($leechers);?>)]]></description>
  <link><?php print $BASEURL;?>/details.php?id=<?php print $id;?></link>
  <pubDate><?php print $added;?></pubDate>
  </item>

<?php
  }
}
// forums
if ($CURUSER["view_forum"]=="yes")
{
  $getItems = "select t.id as topicid, p.id as postid, f.name, u.username,t.subject,p.added, p.body from {$TABLE_PREFIX}topics t inner join {$TABLE_PREFIX}posts p on p.topicid=t.id inner join {$TABLE_PREFIX}forums f on t.forumid=f.id inner join {$TABLE_PREFIX}users u on u.id=p.userid ORDER BY added DESC LIMIT 50";
  $doGet=do_sqlquery($getItems,true) or die(mysql_error());

  while($item=mysql_fetch_array($doGet))
   {
    $topicid=$item['topicid'];
    $postid=$item['postid'];
    $forum=(htmlspecialchars($item['name']));
    $subject=(htmlspecialchars($item['subject']));
    $added=strip_tags(date("d/m/Y H:i:s",$item['added']));
    $body=format_comment("[b]Author: ".$item['username']."[/b]\n\n".$item['body']."\n");
    // output to browser
    $link=htmlspecialchars($BASEURL."/index.php?page=forum&action=viewtopic&topicid=$topicid&page=p$postid#$postid");
?>

  <item>
  <title><![CDATA[<?php print ("[".FORUM."] ".$forum." - ".$subject);?>]]></title>
  <description><![CDATA[<?php print ($body); ?>]]></description>
  <link><?php print $link;?></link>
  <pubDate><?php print $added;?></pubDate>
  </item>

<?php
    }
}

?>
</channel>
</rss>