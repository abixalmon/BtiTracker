<?php
require_once ("include/functions.php");
require_once ("include/config.php");

dbconn();

$id = AddSlashes($_GET["id"]);
if (!isset($id) || !$id)
    die("Error ID");
$scriptname = htmlspecialchars($_SERVER["PHP_SELF"]."?history&id=$id");
$addparam = "";

// control if torrent exist in our db
$res = do_sqlquery("SELECT * FROM {$TABLE_PREFIX}files WHERE info_hash='$id'") or 
die(mysql_error());

if ($res) {
   $row=mysql_fetch_array($res);
   if ($row) {
      $tsize=0+$row["size"];
      }
}
else
    die("Error ID");

$res = do_sqlquery("SELECT h.*, u.username, c.name AS country, c.flagpic, ul.level, ul.prefixcolor, ul.suffixcolor FROM {$TABLE_PREFIX}history h INNER JOIN {$TABLE_PREFIX}users u ON h.uid=u.id INNER JOIN {$TABLE_PREFIX}countries c ON u.flag=c.id INNER JOIN {$TABLE_PREFIX}users_level ul ON u.id_level=ul.id WHERE h.infohash='$id' AND h.date IS NOT NULL ORDER BY date DESC LIMIT 0,30") or die(mysql_error());

echo mysql_num_rows($res);
echo "SELECT {$TABLE_PREFIX}history.*,username,  {$TABLE_PREFIX}countries.name AS country,  {$TABLE_PREFIX}countries.flagpic, level, prefixcolor,suffixcolor FROM {$TABLE_PREFIX}history INNER JOIN {$TABLE_PREFIX}users ON {$TABLE_PREFIX}history.uid={$TABLE_PREFIX}users.id INNER JOIN {$TABLE_PREFIX}countries  ON {$TABLE_PREFIX}users.flag={$TABLE_PREFIX}countries.id INNER JOIN {$TABLE_PREFIX}users_level  ON {$TABLE_PREFIX}users.id_level={$TABLE_PREFIX}users_level.id WHERE {$TABLE_PREFIX}history.infohash='$id' AND {$TABLE_PREFIX}history.date IS NOT NULL ORDER BY date DESC LIMIT 30";

require(load_language("lang_history.php"));

$historytpl=new bTemplate();
$historytpl->set("language",$language);
$historytpl->set("history_script","index.php");

while ($row = mysql_fetch_array($res))
{
  if ($GLOBALS["usepopup"])
    $history[$i]["USER_NAME"]="<a href=\"javascript: windowunder('index.php?page=userdetails&amp;id=".$row["uid"]."')\">".unesc($row["username"])."</a>".
       "<td align=\"center\" class=\"lista\"><a href=\"javascript: windowunder('index.php?page=usercp&amp;do=pm&action=edit&uid=$CURUSER[uid]&what=new&to=".urlencode(unesc($row["username"]))."')\">".image_or_link("$STYLEPATH/images/pm.png","","PM")."</a></td>";
  else
    $history[$i]["USER_NAME"]="<a href=\"index.php?page=userdetails&amp;id=".$row["uid"]."\">".unesc($row["username"])."</a>".
       "<td align=\"center\" class=\"lista\"><a href=\"index.php?page=usercp&amp;do=pm&action=edit&uid=$CURUSER[uid]&what=new&to=".urlencode(unesc($row["username"]))."\">".image_or_link("$STYLEPATH/images/pm.png","","PM")."</a></td>";
  if ($row["flagpic"]!="")
    $history[$i]["FLAG"]="<img src=images/flag/".$row["flagpic"]." alt=".$row["country"]." />";
  else
    $history[$i]["FLAG"]="<img src=images/flag/unknown.gif alt=".UNKNOWN." />";
  $history[$i]["ACTIVE"]=$row["active"];
  $history[$i]["CLIENT"]=htmlspecialchars($row["agent"]);
  $dled=makesize($row["downloaded"]);
  $upld=makesize($row["uploaded"]);
  $history[$i]["DOWNLOADED"]=$dled;
  $history[$i]["UPLOADED"]=$upld;
//Peer Ratio
  if (intval($row["downloaded"])>0) {
     $ratio=number_format($row["uploaded"]/$row["downloaded"],2);}
  else {$ratio="oo";}
  $history[$i]["RATIO"]=$ratio;
//End Peer Ratio

  $history[$i]["FINISHED"]=get_elapsed_time($row["date"])." ago";
$i++;
}

if (mysql_num_rows($res)==0)
    $historytpl->set("NOHISTORY",TRUE,TRUE);
else
    $historytpl->set("NOHISTORY",FALSE,TRUE);

if ($GLOBALS["usepopup"])
    $historytpl->set("BACK2","<br /><br /><center><a href=\"javascript:window.close()\"><tag:language.CLOSE /></a></center>");
else
   $historytpl->set("BACK2", "</div><br /><br /><center><a href=\"javascript: history.go(-1);\"><tag:language.BACK /></a>");
$historytpl->set("XBTT",$XBTT_USE,TRUE);
$historytpl->set("XBTT2",$XBTT_USE,TRUE);
$historytpl->set("history",$history);



stdfoot(($GLOBALS["usepopup"]?false:true),false);
?>