<?php

$btit_url_rss="http://www.btiteam.org/smf/index.php?type=rss;action=.xml;board=83;sa=news";

if (!defined("IN_BTIT"))
      die("non direct access!");

if (!defined("IN_ACP"))
      die("non direct access!");


$admin=array();

$res=do_sqlquery("SELECT * FROM {$TABLE_PREFIX}tasks");
if ($res)
   {
    while ($result=mysql_fetch_array($res))
          {
          if ($result["task"]=="sanity")
             $admin["lastsanity"]=$language["LAST_SANITY"]."<br />\n".get_date_time($result["last_time"])." (".$language["NEXT"].": ".get_date_time($result["last_time"]+intval($GLOBALS["clean_interval"])).")&nbsp;<a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=sanity&amp;action=now\">Do it now!</a><br />";
          elseif ($result["task"]=="update")
             $admin["lastscrape"]="<br />\n".$language["LAST_EXTERNAL"]."<br />\n".get_date_time($result["last_time"])." (".$language["NEXT"].": ".get_date_time($result["last_time"]+intval($GLOBALS["update_interval"])).")<br />";
       }
   }

// check if XBTT tables are present in current db
$res=do_sqlquery("SHOW TABLES LIKE 'xbt%'");
$xbt_tables=array('xbt_announce_log','xbt_config','xbt_deny_from_hosts','xbt_files','xbt_files_users','xbt_scrape_log','xbt_users');
$xbt_in_db=array();
if ($res)
   {
   while ($result=mysql_fetch_row($res))
         {
             $xbt_in_db[]=$result[0];
         }
 }
 $ad=array_diff($xbt_tables,$xbt_in_db);

 if (count($ad)==0)
    $admin["xbtt_ok"]="IT SEEMS THAT ALL XBTT TABLES ARE PRESENT!";
 else
    $admin["xbtt_ok"]="";

unset($ad);
unset($xbt_tables);
unset($xbt_in_db);
         
unset($result);
mysql_free_result($res);

// check torrents' folder
if (file_exists($TORRENTSDIR))
  {
  if (is_writable($TORRENTSDIR))
        $admin["torrent_ok"]=("<br />\nTorrent's folder $TORRENTSDIR <span style=\"color:#BEC635; font-weight: bold;\">is writable</span><br />\n");
  else
        $admin["torrent_ok"]=("<br />\nTorrent's folder $TORRENTSDIR is <span style=\"color:#FF0000; font-weight: bold;\">NOT writable</span><br />\n");
  }
else
  $admin["torrent_ok"]=("<br />\nTorrent's folder $TORRENTSDIR <span style=\"color:#FF0000; font-weight: bold;\">NOT FOUND!</span><br />\n");

// check cache folder
if (file_exists("$THIS_BASEPATH/cache"))
  {
  if (is_writable("$THIS_BASEPATH/cache"))
        $admin["cache_ok"]=("cache folder <span style=\"color:#BEC635; font-weight: bold;\">is writable</span><br />\n");
  else
        $admin["cache_ok"]=("cache folder is <span style=\"color:#FF0000; font-weight: bold;\">NOT writable</span><br />\n");
  }
else
  $admin["cache_ok"]=("cache folder <span style=\"color:#FF0000; font-weight: bold;\">NOT FOUND!</span><br />\n");


// check censored worlds file
if (file_exists("badwords.txt"))
  {
  if (is_writable("badwords.txt"))
        $admin["badwords_ok"]=("Censored worls file (badwords.txt) <span style=\"color:#BEC635; font-weight: bold;\">is writable</span><br />\n");
  else
        $admin["badwords_ok"]=("Censored worls file (badwords.txt) is <span style=\"color:#FF0000; font-weight: bold;\">NOT writable</span> (cannot writing tracker's configuration change)<br />\n");
   }
else
  $admin["badwords_ok"]=("<br />\nCensored worls file (badwords.txt) <span style=\"color:#FF0000; font-weight: bold;\">NOT FOUND!</span><br />\n");

$admin["infos"]=("<br />\n<table border=\"0\">\n");
$admin["infos"].=("<tr><td>Server's OS:</td><td>".php_uname()."</td></tr>");
$admin["infos"].=("<tr><td>PHP version:</td><td>".phpversion()."</td></tr>");

$sqlver=mysql_fetch_row(do_sqlquery("SELECT VERSION()"));
$admin["infos"].=("\n<tr><td>MYSQL version:</td><td>$sqlver[0]</td></tr>");
$sqlver=mysql_stat();
$sqlver=explode('  ',$sqlver);
$admin["infos"].=("\n<tr><td valign=\"top\" rowspan=\"".(count($sqlver)+1)."\">MYSQL stats  : </td>\n");
for ($i=0;$i<count($sqlver);$i++)
      $admin["infos"].=(($i==0?"":"<tr>")."<td>$sqlver[$i]</td></tr>\n");
$admin["infos"].=("\n</table><br />\n");

unset($sqlver);

// check for news on btiteam site (read rss from comunication forum)

include("$THIS_BASEPATH/include/class.rssreader.php");

$btit_news=get_cached_version($btit_url_rss);

if (!$btit_news)
  {

    $frss=get_remote_file($btit_url_rss);

    if (!$frss)
      $btit_news="<div class=\"blocklist\" style=\"padding:5px;\">Unable to contact Btiteam's site</div>";
    else
      {
        $nrss=new rss_reader();
        $rss_array=$nrss->rss_to_array($frss);

        $btit_news="<div class=\"blocklist\" style=\"padding:5px;\">";
        if (!$rss_array)
           $btit_news="<div class=\"blocklist\" style=\"padding:5px;\">Unable to contact Btiteam's site</div>";
        else
          {
            foreach($rss_array[0]["item"] as $id=>$rss)
              {
                $btit_news.=date("d M Y",strtotime($rss["pubDate"])).":&nbsp;\n<a href=\"".$rss["guid"]."\">".$rss["title"]."</a><br />\n<br />\n";
                $btit_news.="\n".$rss["description"]."<br />\n<hr />\n";
            }
        }
        $btit_news.="</div>";

    }
    write_cached_version($btit_url_rss,$btit_news);

}


$admintpl->set("btit_news",set_block("BtiTacker Lastest News","left",$btit_news));
$admintpl->set("language",$language);
$admintpl->set("admin",$admin);


?>