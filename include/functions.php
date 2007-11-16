<?php

$tracker_version="2.0.0 Beta 2";

error_reporting(E_ALL ^ E_NOTICE);


#
// Emulate register_globals off
#
if (ini_get('register_globals')) {
  $superglobals = array($_SERVER, $_ENV,$_FILES, $_COOKIE, $_POST, $_GET);
  if (isset($_SESSION)) {
      array_unshift($superglobals, $_SESSION);
  }
  foreach ($superglobals as $superglobal) {
      foreach ($superglobal as $global => $value) {
          unset($GLOBALS[$global]);
      }
  }
  @ini_set('register_globals', false);
}

// control if magic_quote_gpc = on
if(get_magic_quotes_gpc()){

  // function which remove unwanted slashes
  function remove_magic_quotes(&$array)
  {
   foreach($array as $key => $val){

    // it's an array call recursive
    if(is_array($val)){
     remove_magic_quotes($array[$key]);
    } else if(is_string($val)){
     $array[$key] = stripslashes($val);
    }
   }
  }

  remove_magic_quotes($_POST);
  remove_magic_quotes($_GET);
  remove_magic_quotes($_REQUEST);
  remove_magic_quotes($_SERVER);
  remove_magic_quotes($_FILES);
  remove_magic_quotes($_COOKIE);
}



$CURRENTPATH = dirname(__FILE__);
// protection against sql injection, xss attack
require_once("$CURRENTPATH/crk_protection.php");
// protection against sql injection, xss attack
require_once("$CURRENTPATH/config.php");
require_once("$CURRENTPATH/common.php");
require_once("$CURRENTPATH/smilies.php");

if (!isset($TRACKER_ANNOUNCEURLS))
    {
    $TRACKER_ANNOUNCEURLS=array();
    $TRACKER_ANNOUNCEURLS[]="$BASEURL/announce.php";
    }


function load_css($css_name)
  {
  // control if input template name exist in current user's stylepath, else return default
  global $BASEURL, $STYLEPATH, $STYLEURL;

  $DEFAULT_STYLE_URL="$BASEURL/style/xbtit_default";

  if (file_exists("$STYLEPATH/$css_name"))
      return "$STYLEURL/$css_name";
  else
      return "$DEFAULT_STYLE_URL/$css_name";

}



function load_template($tpl_name)
  {
  // control if input template name exist in current user's stylepath, else return default
  global $THIS_BASEPATH, $STYLEPATH;

  $DEFAULT_STYLE_PATH="$THIS_BASEPATH/style/xbtit_default";

  if (file_exists("$STYLEPATH/$tpl_name"))
      return "$STYLEPATH/$tpl_name";
  else
      return "$DEFAULT_STYLE_PATH/$tpl_name";

}

function load_language($mod_language_name)
  {
  // control if input language exist in current user's language path, else return default
  global $THIS_BASEPATH, $USERLANG;

  $DEFAULT_LANGUAGE_PATH="$THIS_BASEPATH/language/english";

  if (file_exists("$USERLANG/$mod_language_name"))
      return "$USERLANG/$mod_language_name";
  else
      return "$DEFAULT_LANGUAGE_PATH/$mod_language_name";

}


function get_microtime()
  {
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
}



function cut_string($ori_string,$cut_after)
{
    $rchars=array("_",".","-");
         
    $ori_string=str_replace($rchars," ",$ori_string);

    if (strlen($ori_string)>$cut_after && $cut_after>0)
        return substr($ori_string,0,$cut_after)."...";
    else
        return $ori_string;
}

function print_version()
{

  GLOBAL $time_start, $gzip, $PRINT_DEBUG,$tracker_version,$num_queries;

  $time_end=get_microtime();
  $version=("<br /><br /><p align=center valign=middle>");
  if ($PRINT_DEBUG)
  $version.=("<a href=\"#\">Back To Top</a><br />[&nbsp;&nbsp;<u>XBtit Styles Designed By: </u><a href=\"http://global-bttracker.no-ip.org/forum/\" target=\"_blank\">TreetopClimber</a>&nbsp;&nbsp;]&nbsp;[&nbsp;&nbsp;<u>BtiTracker ($tracker_version) By: </u><a href=\"http://www.btiteam.org/\" target=\"_blank\">Btiteam</a>&nbsp;&nbsp;]<br />[Queries: $num_queries] - [ Script Execution time: ".number_format(($time_end-$time_start),4)." sec. ] - [ GZIP: $gzip ]</p>");

  return $version;

}

// check online passed session and user's location
// this function will update the information into
// online table (session ID, ip, user id and location
function check_online($session_id, $location)
{
    global $TABLE_PREFIX, $CURUSER;

    $location=sqlesc($location);
    $ip=getip();
    $uid=max(0,$CURUSER["uid"]);
    $suffix=sqlesc($CURUSER["suffixcolor"]);
    $prefix=sqlesc($CURUSER["prefixcolor"]);
    $uname=sqlesc($CURUSER["username"]);
    $ugroup=sqlesc($CURUSER["level"]);
    if ($uid==1)
        $where="WHERE session_id='$session_id'";
    else
        $where="WHERE user_id='$uid' OR session_id='$session_id'";

    @mysql_query("UPDATE {$TABLE_PREFIX}online SET session_id='$session_id', user_name=$uname, user_group=$ugroup, prefixcolor=$prefix, suffixcolor=$suffix, location=$location, user_id=$uid, lastaction=UNIX_TIMESTAMP() $where");
    // record don't already exist, then insert it
    if (mysql_affected_rows()==0)
        mysql_query("INSERT INTO {$TABLE_PREFIX}online SET session_id='$session_id', user_name=$uname, user_group=$ugroup, prefixcolor=$prefix, suffixcolor=$suffix, user_id=$uid, user_ip='$ip', location=$location, lastaction=UNIX_TIMESTAMP()");


  //  $timeout=time()-(60*5); // 5 minutes
  //    $timeout=time()-(60*3); // 3 minutes
        $timeout=time()-(60*1); // 1 minute
    @mysql_query("UPDATE {$TABLE_PREFIX}users SET lastconnect=NOW() WHERE id IN (SELECT user_id FROM {$TABLE_PREFIX}online ol WHERE ol.lastaction<$timeout AND ol.user_id>1)");
    @mysql_query("DELETE FROM {$TABLE_PREFIX}online WHERE lastaction<$timeout");

}

//Disallow special characters in username

function straipos($haystack,$array,$offset=0)
{
   $occ = Array();
   for ($i = 0;$i<sizeof($array);$i++)
   {
       $pos = strpos($haystack,$array[$i],$offset);
       if (is_bool($pos)) continue;
       $occ[$pos] = $i;
   }
   if (sizeof($occ)<1) return false;
   ksort($occ);
   reset($occ);
   list($key,$value) = each($occ);
   return array($key,$value);
}


// Even if you're missing PHP 4.3.0, the MHASH extension might be of use.
// Someone was kind enought to email this code snippit in.
if (function_exists('mhash') && (!function_exists('sha1')) &&
defined('MHASH_SHA1'))
{
    function sha1($str)
    {
        return bin2hex(mhash(MHASH_SHA1,$str));
    }
}

// begin of function added from original

function unesc($x) {
        return stripslashes($x);
}

function mksecret($len = 20) {
    $ret = "";
    for ($i = 0; $i < $len; $i++)
        $ret .= chr(mt_rand(0, 255));
    return $ret;
}

function logincookie($id, $passhash, $expires = 0x7fffffff)
{
    setcookie("uid", $id, $expires, "/");
    setcookie("pass", $passhash, $expires, "/");
}

function logoutcookie() {
    setcookie("uid", "", 0x7fffffff, "/");
    setcookie("pass", "", 0x7fffffff, "/");
}

function hash_pad($hash) {
    return str_pad($hash, 20);
}




function userlogin() {

    global $CURUSER, $TABLE_PREFIX, $err_msg_install;
    unset($GLOBALS["CURUSER"]);


    $ip = getip(); //$_SERVER["REMOTE_ADDR"];
    $nip = ip2long($ip);
    $res = do_sqlquery("SELECT * FROM {$TABLE_PREFIX}bannedip WHERE $nip >= first AND $nip <= last") or sqlerr(__FILE__, __LINE__);
    if (mysql_num_rows($res) > 0)
    {
      header("HTTP/1.0 403 Forbidden");
      print("<html><body><h1>403 Forbidden</h1>Unauthorized IP address.</body></html>\n");
      die;
    }

    // guest
    if (empty($_COOKIE["uid"]) || empty($_COOKIE["pass"]))
       $id=1;

    if (!isset($_COOKIE["uid"])) $_COOKIE["uid"] = 1;
     $id = max(1 ,$_COOKIE["uid"]);
    // it's guest
    if (!$id)
       $id=1;

    $res = do_sqlquery("SELECT u.topicsperpage, u.postsperpage,u.torrentsperpage, u.flag, u.avatar, UNIX_TIMESTAMP(u.lastconnect) AS lastconnect, UNIX_TIMESTAMP(u.joined) AS joined, u.id as uid, u.username, u.password, u.random, u.email, u.language,u.style, u.time_offset, ul.* FROM {$TABLE_PREFIX}users u INNER JOIN {$TABLE_PREFIX}users_level ul ON u.id_level=ul.id WHERE u.id = $id",true);
    $row = mysql_fetch_array($res);
    if (!$row)
       {
       $id=1;
       $res = do_sqlquery("SELECT u.topicsperpage, u.postsperpage,u.torrentsperpage, u.flag, u.avatar, UNIX_TIMESTAMP(u.lastconnect) AS lastconnect, UNIX_TIMESTAMP(u.joined) AS joined, u.id as uid, u.username, u.password, u.random, u.email, u.language,u.style, u.time_offset, ul.* FROM {$TABLE_PREFIX}users u INNER JOIN {$TABLE_PREFIX}users_level ul ON u.id_level=ul.id WHERE u.id = 1");
       $row = mysql_fetch_array($res);
       }
    if (!isset($_COOKIE["pass"])) $_COOKIE["pass"] = "";
    if (($_COOKIE["pass"] != md5($row["random"].$row["password"].$row["random"])) && $id != 1)
       {
       $id=1;
       $res = do_sqlquery("SELECT u.topicsperpage, u.postsperpage,u.torrentsperpage, u.flag, u.avatar, UNIX_TIMESTAMP(u.lastconnect) AS lastconnect, UNIX_TIMESTAMP(u.joined) AS joined, u.id as uid, u.username, u.password, u.random, u.email, u.language,u.style, u.time_offset, ul.* FROM {$TABLE_PREFIX}users u INNER JOIN {$TABLE_PREFIX}users_level ul ON u.id_level=ul.id WHERE u.id = 1");
       $row = mysql_fetch_array($res);
       }

    //$ip=$_SERVER["REMOTE_ADDR"]);
    //$ip=sprintf("%u", ip2long($_SERVER["REMOTE_ADDR"]));

    if ($id>1)
       do_sqlquery("UPDATE {$TABLE_PREFIX}users SET lip=".$nip.", cip='".AddSlashes($ip)."' WHERE id = $id");

    // CHECK FOR INSTALLATION FOLDER WITHOUT INSTALL.ME
    if (file_exists("install.php") && $row["id_level"]==8) // only owner level
         $err_msg_install=("<div align=\"center\" style=\"color:red; font-size:12pt; font-weight: bold;\">SECURITY WARNING: Delete install.php!</div>");
    else
         $err_msg_install="";


    $GLOBALS["CURUSER"] = $row;
    unset($row);

}

function dbconn($do_clean=false) {

    global $dbhost, $dbuser, $dbpass, $database;

    if ($GLOBALS["persist"])
        $conres=mysql_pconnect($dbhost, $dbuser, $dbpass);
    else
        $conres=mysql_connect($dbhost, $dbuser, $dbpass);

    if (!$conres)
    {
      switch (mysql_errno())
      {
        case 1040:
        case 2002:
            if ($_SERVER["REQUEST_METHOD"] == "GET")
                die("<html><head><meta http-equiv=refresh content=\"20 ".$_SERVER["REQUEST_URI"]."\"></head><body><table border=\"0\" width=\"100%\" height=\"100%\" > <tr><td><h3 align=\"center\">".ERR_SERVER_LOAD."</h3></td></tr></table></body></html>");
            else
                die(ERR_CANT_CONNECT);
        default:
            die("[" . mysql_errno() . "] dbconn: mysql_connect: " . mysql_error());
      }
    }
    mysql_select_db($database)
        or die(ERR_CANT_OPEN_DB." $database - ".mysql_error());

    userlogin();

    if ($do_clean)
       register_shutdown_function("cleandata");
}

function cleandata() {

    global $CURRENTPATH, $TABLE_PREFIX;

    require_once("$CURRENTPATH/sanity.php");

    global $clean_interval;

    if ((0+$clean_interval)==0)
       return;

    $now = time();

    $res = do_sqlquery("SELECT last_time FROM {$TABLE_PREFIX}tasks WHERE task='sanity'");
    $row = mysql_fetch_array($res);
    if (!$row) {
        do_sqlquery("INSERT INTO {$TABLE_PREFIX}tasks (task, last_time) VALUES ('sanity',$now)");
        return;
    }
    $ts = $row[0];
    if ($ts + $clean_interval > $now)
        return;
    do_sqlquery("UPDATE {$TABLE_PREFIX}tasks SET last_time=$now WHERE task='sanity' AND last_time = $ts");
    if (!mysql_affected_rows())
        return;

    do_sanity();

}

function updatedata() {

    global $CURRENTPATH, $TABLE_PREFIX;

    require_once("$CURRENTPATH/getscrape.php");

    global $update_interval;

    if ((0+$update_interval)==0)
       return;

    $now = time();

    $res = do_sqlquery("SELECT last_time FROM {$TABLE_PREFIX}tasks WHERE task='update'");
    $row = @mysql_fetch_array($res);
    if (!$row) {
        do_sqlquery("INSERT INTO {$TABLE_PREFIX}tasks (task, last_time) VALUES ('update',$now)");
        return;
    }
    $ts = $row[0];
    if ($ts + $update_interval > $now)
        return;

    do_sqlquery("UPDATE {$TABLE_PREFIX}tasks SET last_time=$now WHERE task='update' AND last_time = $ts");
    if (!mysql_affected_rows())
        return;

    $res = do_sqlquery("SELECT announce_url FROM {$TABLE_PREFIX}files WHERE external='yes' ORDER BY lastupdate ASC LIMIT 1");
    if (!$res || mysql_num_rows($res)==0)
       return;

    // get the url to scrape, take 5 torrent at a time (try to getting multiscrape)
    $row = mysql_fetch_row($res);

    $resurl=do_sqlquery("SELECT info_hash FROM {$TABLE_PREFIX}files WHERE external='yes' AND announce_url='".$row[0]."' ORDER BY lastupdate ASC LIMIT 5");
    if (!$resurl || mysql_num_rows($resurl)==0)
        return

    $combinedinfohash=array();
    while ($rhash=mysql_fetch_row($resurl))
        $combinedinfohash[]=$rhash[0];

    //scrape($row["announce_url"],$row["info_hash"]);
    scrape($row[0],implode("','",$combinedinfohash));

}

function pager($rpp, $count, $href, $opts = array()) {

    global $language;

    if($rpp!=0) $pages = ceil($count / $rpp);
    else $pages=1;

    if (!isset($opts["lastpagedefault"]))
        $pagedefault = 1;
    else {
        $pagedefault = floor(($count - 1) / $rpp);
        if ($pagedefault < 1)
            $pagedefault = 1;
    }

    $pagename="pages";

    if (isset($opts["pagename"]))
      {
       $pagename=$opts["pagename"];
       if (isset($_GET[$opts["pagename"]]))
          $page = max(1 ,intval($_GET[$opts["pagename"]]));
       else
          $page = $pagedefault;
      }
    elseif (isset($_GET["pages"])) {
        $page = max(1,intval(0 + $_GET["pages"]));
        if ($page < 0)
            $page = $pagedefault;
    }
    else
        $page = $pagedefault;

    $pager = "";

    if ($pages>1)
      {
        $pager.="\n<select class=\"drop_pager\" name=\"pages\" onchange=\"location=document.change_page.pages.options[document.change_page.pages.selectedIndex].value\" size=\"1\">";
        for ($i = 1; $i<=$pages;$i++)
            $pager.="\n<option ".($i==$page?"selected=\"selected\"":"")."value=\"$href$pagename=$i\">$i</option>";
        $pager.="\n</select>";
    }

    $mp = $pages;// - 1;
    $begin=($page > 3?($page<$pages-2?$page-2:$pages-2):1);
    $end=($pages>$begin+2?($begin+2<$pages?$begin+2:$pages):$pages);
    if ($page > 1)
      {
        $pager .= "\n&nbsp;<span class=\"pager\"><a href=\"{$href}$pagename=1\">&nbsp;&laquo;</a></span>";
        $pager .= "\n<span class=\"pager\"><a href=\"{$href}$pagename=".($page-1)."\">&lt;&nbsp;</a></span>";
    }
//    else
//        $pager .= "\n<span class=\"pager\">&lt;&nbsp;</span>";

    if ($count) {
        for ($i = $begin; $i <= $end; $i++) {
            if ($i != $page)
                $pager .= "\n&nbsp;<span class=\"pager\"><a href=\"{$href}$pagename=$i\">$i</a></span>";
            else
                $pager .= "\n&nbsp;<span class=\"pagercurrent\"><b>$i</b></span>";
        }


        if ($page < $mp && $mp >= 1)
         {
            $pager .= "\n&nbsp;<span class=\"pager\"><a href=\"{$href}$pagename=".($page+1)."\">&nbsp;&gt;</a></span>";
            $pager .= "\n&nbsp;<span class=\"pager\"><a href=\"{$href}$pagename=$pages\">&nbsp;&raquo;</a></span>";
        }
//        else
//            $pager .= "\n&nbsp;<span class=\"pager\">&nbsp;&gt;</span>";

        $pagertop = "$pager\n";
        $pagerbottom = "$pager\n";
    }
    else {
        $pagertop = "$pager\n";
        $pagerbottom = $pagertop;
    }

    $start = ($page-1) * $rpp;
    if ($pages<2)
        {
        // only 1 page??? don't need pager ;)
        $pagertop="";
        $pagerbottom="";
    }
    return array($pagertop, $pagerbottom, "LIMIT $start,$rpp");

}

// give back categories recorset
function genrelist()
     {

     global $TABLE_PREFIX;

    $ret = array();
    $res = do_sqlquery("SELECT * FROM {$TABLE_PREFIX}categories ORDER BY sort_index, id");

    while ($row = mysql_fetch_assoc($res))
        $ret[] = $row;

    unset($row);
    mysql_free_result($res);

    return $ret;
}
// this returns all the categories
function categories($val="")
{

  global $TABLE_PREFIX,$CACHE_DURATION;

    $return="";
    $return.= "\n<select name='category'><option value='0'>----</option>";
    $c_q = get_result("SELECT * FROM {$TABLE_PREFIX}categories WHERE sub='0' ORDER BY id ASC",true,$CACHE_DURATION);
    foreach ($c_q as $id=>$c)
    {
        $cid = $c["id"];
        $name = unesc($c["name"]);
        // lets see if it has sub-categories.
        $s_q = get_result("SELECT * FROM {$TABLE_PREFIX}categories WHERE sub='$cid'",true,$CACHE_DURATION);
        $s_t = count($s_q);
        if($s_t == 0)
        {
            $checked = "";
            if($cid == $val){ $checked = "selected=\"selected\""; }
            $return.= "\n<option $checked value='$cid'>$name</option>";
        } else {
            $return.= "\n<optgroup label='$name'>";
            foreach($s_q as $id=>$s)
            {
                $sub = $s["id"];
                $name  = $s["name"];
                $checked = "";
                if($sub == $val){ $checked = "selected==\"selected\""; }
                $return.= "<option $checked value='$sub'>$name</option>";
            }
            $return.= "</optgroup>";
        }
    }
    $return.= "</select>";

    return $return;
}
// this returns all the subcategories
function sub_categories($val="")
{
  
  global $TABLE_PREFIX;

    $return="\n<select name='sub_category'><option value='0'>---</option>";
    $c_q = get_result("SELECT * FROM {$TABLE_PREFIX}categories WHERE sub='0' ORDER BY id ASC",true,$CACHE_DURATION);
    foreach($c_q as $id=>$c)
    {
        $cid = $c["id"];
        $name = unesc($c["name"]);
        $selected = ($cid == $val)?"selected=\"selected\"":"";
        $return.= "\n<option $selected value='$cid'>$name</option>";
    }
    $return.= "\n</select>";

    return $return;
}
// this returns the category of a sub-category
function sub_cat($sub)
{
  
  global $TABLE_PREFIX;

    $c_q = @mysql_fetch_assoc(do_sqlquery("SELECT name FROM {$TABLE_PREFIX}categories WHERE id='$sub'") );
    $name = unesc($c_q["name"]);
    return $name;
}



function style_list()
         {

         global $TABLE_PREFIX;

         $ret = array();
         $res = do_sqlquery("SELECT * FROM {$TABLE_PREFIX}style ORDER BY id");

         while ($row = mysql_fetch_assoc($res))
             $ret[] = $row;

         unset($row);
         mysql_free_result($res);

         return $ret;
}

function language_list()
         {

         global $TABLE_PREFIX;

         $ret = array();
         $res = do_sqlquery("SELECT * FROM {$TABLE_PREFIX}language ORDER BY language");

         while ($row = mysql_fetch_assoc($res))
             $ret[] = $row;

         unset($row);
         mysql_free_result($res);

         return $ret;
}

function flag_list($with_unknown=false)
{

  global $TABLE_PREFIX;

  $ret = array();
    $res = do_sqlquery("SELECT * FROM {$TABLE_PREFIX}countries ".(!$with_unknown?"WHERE id<>100":"")." ORDER BY name");

    while ($row = mysql_fetch_assoc($res))
      $ret[] = $row;

    unset($row);
    mysql_free_result($res);

    return $ret;
}

function timezone_list()
{
  global $TABLE_PREFIX;

  $ret = array();
    $res = do_sqlquery("SELECT * FROM {$TABLE_PREFIX}timezone");

    while ($row = mysql_fetch_assoc($res))
      $ret[] = $row;

    unset($row);
    mysql_free_result($res);

    return $ret;
}

function rank_list()
         {

         global $TABLE_PREFIX;

         $ret = array();
         $res = do_sqlquery("SELECT * FROM {$TABLE_PREFIX}users_level ORDER BY id_level");

         while ($row = mysql_fetch_assoc($res))
             $ret[] = $row;

         unset($row);
         mysql_free_result($res);

         return $ret;
}
            
function stdfoot($normalpage=true, $update=true, $adminpage=false, $torrentspage=false, $forumpage=false) {

    global $STYLEPATH, $tpl;
    $tpl->set("main_footer",bottom_menu()."<br />\n");
    $tpl->set("btit_version",print_version());

    if ($normalpage)
        echo $tpl->fetch(load_template("main.tpl"));
        elseif ($adminpage)
        echo $tpl->fetch(load_template("main.left_column.tpl"));
        elseif ($torrentspage)
            echo $tpl->fetch(load_template("main.no_columns.tpl"));
        elseif ($forumpage)
            echo $tpl->fetch(load_template("main.no_columns.tpl"));
        else
        echo $tpl->fetch(load_template("main.no_header_1_column.tpl")); 

    ob_end_flush();

    if ($update)
        register_shutdown_function("updatedata");
}

function linkcolor($num) {

    if (!$num)
        return "red";
    if ($num == 1)
        return "yellow";

    return "green";
}

function format_quote($text)
{

  global $language;

  $string=$text;
  $prev_string = "";
  while ($prev_string != $string)
        {
    $prev_string = $string;
    $string = preg_replace("/\[quote\]\s*((\s|.)+?)\s*\[\/quote\]\s*/i", "<br /><b>".$language["QUOTE"].":</b><br /><table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"10\" class=\"quote\"><tr><td >\\1</td></tr></table><br />", $string);
    $string = preg_replace("/\[quote=(.+?)\]\s*((\s|.)+?)\s*\[\/quote\]\s*/i", "<br /><b>\${1} ".$language["WROTE"].":</b><br /><table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"10\" class=\"quote\"><tr><td>\\2</td></tr></table><br />", $string);
    // code
    $string = preg_replace("/\[code\]\s*((\s|.)+?)\s*\[\/code\]\s*/i", "<br /><b>Code</b><br /><table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"10\" class=\"code\"><tr><td>\\1</td></tr></table><br />", $string);

  }

return $string;
}


function format_comment($text, $strip_html = true)
{
    global $smilies, $privatesmilies, $BASEURL;

    $s = $text;

    if ($strip_html)
        $s = htmlspecialchars($s);

    $s = unesc($s);

    $f=@fopen("badwords.txt","r");
    if ($f && filesize ("badwords.txt")!=0)
       {
       $bw=fread($f,filesize("badwords.txt"));
       $badwords=explode("\n",$bw);
       for ($i=0;$i<count($badwords);++$i)
           $badwords[$i]=trim($badwords[$i]);
       $s = str_replace($badwords,"*censored*",$s);
       }
    @fclose($f);

    // [*]
    $s = preg_replace("/\[\*\]/", "<li>", $s);

    // [b]Bold[/b]
    $s = preg_replace("#\[b\](.*?)\[/b\]#si", "<b>\\1</b>", $s);
    $s = preg_replace("#\[B\](.*?)\[/B\]#si", "<b>\\1</b>", $s);

    // [i]Italic[/i]
    $s = preg_replace("#\[i\](.*?)\[/i\]#si", "<i>\\1</i>", $s);
    $s = preg_replace("#\[I\](.*?)\[/I\]#si", "<i>\\1</i>", $s);

    // [u]Underline[/u]
    $s = preg_replace("#\[u\](.*?)\[/u\]#si", "<u>\\1</u>", $s);
    $s = preg_replace("#\[U\](.*?)\[/U\]#si", "<u>\\1</u>", $s);

    // [img]http://www/image.gif[/img]
    $s = preg_replace("/\[img\](http:\/\/[^\s'\"<>]+(\.gif|\.jpg|\.png))\[\/img\]/i", "<img border=\"0\" src=\"\\1\">", $s);
    //$s = preg_replace("/\[IMG\](http:\/\/[^\s'\"<>]+(\.gif|\.jpg|\.png))\[\/IMG\]/", "<img border=0 src=\"\\1\">", $s);

    // [img=http://www/image.gif]
    $s = preg_replace("/\[img=(http:\/\/[^\s'\"<>]+(\.gif|\.jpg|\.png))\]/i", "<img border=\"0\" src=\"\\1\">", $s);
    //$s = preg_replace("/\[IMG=(http:\/\/[^\s'\"<>]+(\.gif|\.jpg|\.png))\]/", "<img border=0 src=\"\\1\">", $s);

    // [color=blue]Text[/color]
    $s = preg_replace(
        "/\[color=([a-zA-Z]+)\]((\s|.)+?)\[\/color\]/i",
        "<font color='\\1'>\\2</font>", $s);

    // [color=#ffcc99]Text[/color]
    $s = preg_replace(
        "/\[color=(#[a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9])\]((\s|.)+?)\[\/color\]/i",
        "<font color='\\1'>\\2</font>", $s);

    // [url=http://www.example.com]Text[/url]
    $s = preg_replace(
        "/\[url=((http|ftp|https|ftps|irc):\/\/[^<>\s]+?)\]((\s|.)+?)\[\/url\]/i",
        "<a href='\\1' target='_blank'>\\3</a>", $s);

    // [url]http://www.example.com[/url]
    $s = preg_replace(
        "/\[url\]((http|ftp|https|ftps|irc):\/\/[^<>\s]+?)\[\/url\]/i",
        "<a href='\\1' target='_blank'>\\1</a>", $s);

    // [size=4]Text[/size]
    $s = preg_replace(
        "/\[size=([1-7])\]((\s|.)+?)\[\/size\]/i",
        "<font size='\\1'>\\2</font>", $s);

    // [font=Arial]Text[/font]
    $s = preg_replace(
        "/\[font=([a-zA-Z ,]+)\]((\s|.)+?)\[\/font\]/i",
        "<font face=\"\\1\">\\2</font>", $s);

    $s=format_quote($s);

    // Linebreaks
    $s = nl2br($s);

    // Maintain spacing
    $s = str_replace("  ", " &nbsp;", $s);

    reset($smilies);
    while (list($code, $url) = each($smilies))
        $s = str_replace($code, "<img border=\"0\" src=\"$BASEURL/images/smilies/$url\" alt=\"$url\" />", $s);

    reset($privatesmilies);
    while (list($code, $url) = each($privatesmilies))
        $s = str_replace($code, "<img border=\"0\" src=\"$BASEURL/images/smilies/$url\" alt=\"$url\" />", $s);

    return $s;
}

function image_or_link($image,$pers_style="",$link="")
{
    global $STYLEURL, $STYLEPATH;

  if ($image=="")
      return $link;
  elseif (file_exists($image))
     {
     // replace realpath with url
     $image=str_replace($STYLEPATH,$STYLEURL,$image);
     return "<img src=\"$image\" border=\"0\" $pers_style alt=\"$link\"/>";
     }
  else
      return $link;
}


function success_msg($heading="Success!",$string,$close=false)
{

    global $language,$STYLEPATH, $tpl, $page, $STYLEURL;


    $suc_tpl=new bTemplate();
    $suc_tpl->set("success_title",$heading);
    $suc_tpl->set("success_message",$string);
    $suc_tpl->set("success_image","$STYLEURL/images/success.gif");

    $tpl->set("main_content",set_block($heading,"center",$suc_tpl->fetch(load_template("success.tpl"))));

    $page=$tpl->fetch(load_template("main.tpl"));

}

function err_msg($heading="Error!",$string,$close=false)
{

    global $language,$STYLEPATH, $tpl, $page,$STYLEURL;

 // just in case not found the language
 if (!$language["BACK"])
      $language["BACK"]="Back";

    $err_tpl=new bTemplate();
    $err_tpl->set("error_title",$heading);
    $err_tpl->set("error_message",$string);
    $err_tpl->set("error_image","$STYLEURL/images/error.gif");
    $err_tpl->set("language",$language);

    if ($close)
        $err_tpl->set("error_footer","<a href=\"javascript: window.close();\">".$language["CLOSE"]."</a>");
    else
        $err_tpl->set("error_footer","<a href=\"javascript: history.go(-1);\">".$language["BACK"]."</a>");


    $tpl->set("main_content",set_block($heading,"center",$err_tpl->fetch(load_template("error.tpl"))));

    $page=$tpl->fetch(load_template("main.tpl"));

}

function information_msg($heading="Error!",$string,$close=false)
{

    global $language,$STYLEPATH, $tpl, $page,$STYLEURL;

 // just in case not found the language
 if (!$language["BACK"])
      $language["BACK"]="Back";

    $err_tpl=new bTemplate();
    $err_tpl->set("information_title",$heading);
    $err_tpl->set("information_message",$string);
    $err_tpl->set("information_image","$STYLEURL/images/error.gif");
    $err_tpl->set("language",$language);

    if ($close)
        $err_tpl->set("information_footer","<a href=\"javascript: window.close();\">".$language["CLOSE"]."</a>");
    else
        $err_tpl->set("information_footer","<a href=\"javascript: history.go(-1);\">".$language["BACK"]."</a>");


    $tpl->set("main_content",set_block($heading,"center",$err_tpl->fetch(load_template("information.tpl"))));

    $page=$tpl->fetch(load_template("main.tpl"));
    stdfoot(true,false);
    die;

}

function sqlesc($x) {
   return "'".mysql_escape_string($x)."'";
}



function get_content($file)
{

    global $STYLEPATH, $TABLE_PREFIX, $language;

    ob_start();
    include($file);
    $content=ob_get_contents();
    ob_end_clean();

    return $content;
}

function set_block($block_title,$alignement,$block_content,$width100=true)
{
    global $STYLEPATH, $TABLE_PREFIX, $language;

    $blocktpl=new bTemplate();
    $blocktpl->set("block_width",($width100?"width=\"100%\"":""));
    $blocktpl->set("block_title",$block_title);
    $blocktpl->set("block_align",$alignement);
    $blocktpl->set("block_content",$block_content);
    return $blocktpl->fetch(load_template("block.tpl"));

}


function get_block($block_title,$alignement,$block,$use_cache=true,$width100=true)
{
    global $STYLEPATH, $TABLE_PREFIX, $language, $CACHE_DURATION, $CURUSER;

    $blocktpl=new bTemplate();
    $blocktpl->set("block_width",($width100?"width=\"100%\"":""));
    $blocktpl->set("block_title",$block_title);
    $blocktpl->set("block_align",$alignement);

    $cache_dir=realpath(dirname(__FILE__)."/..")."/cache/";
    $cache_ext=".txt";
    $cache_file=$cache_dir.md5($block.$CURUSER["id_level"]).$cache_ext;

    // caching part
    if ($CACHE_DURATION>0 && $use_cache)
        { // read cache
        if (file_exists($cache_file) && (time()-$CACHE_DURATION) < filemtime($cache_file))
            $block_content=file_get_contents($cache_file);
        else
            {
            ob_start();
            include(realpath(dirname(__FILE__)."/..")."/blocks/".$block."_block.php");
            $block_content=ob_get_contents();
            ob_end_clean();
            // write cache file
            $fp=fopen($cache_file,"w");
            fputs($fp,$block_content);
            fclose($fp);
        }
    }
    else
        {
        ob_start();
        include(realpath(dirname(__FILE__)."/..")."/blocks/".$block."_block.php");
        $block_content=ob_get_contents();
        ob_end_clean();
    }
    $blocktpl->set("block_content",$block_content);
    return $blocktpl->fetch(load_template("block.tpl"));

}

function block_begin($title="-",$colspan=1,$calign="justify") {
}

function block_end($colspan=1) {
}

function makesize($bytes) {
  if (abs($bytes) < 1000 * 1024)
    return number_format($bytes / 1024, 2) . " KB";
  if (abs($bytes) < 1000 * 1048576)
    return number_format($bytes / 1048576, 2) . " MB";
  if (abs($bytes) < 1000 * 1073741824)
    return number_format($bytes / 1073741824, 2) . " GB";
  return number_format($bytes / 1099511627776, 2) . " TB";
}

function redirect($redirecturl) {
// using javascript for redirecting
// some hosting has warning enabled and this is causing
// problem withs header() redirecting...

        print("If your browser doesn't have javascript enabled, click <a href=\"$redirecturl\"> here </a>");
        print("<script LANGUAGE=\"javascript\">window.location.href=\"$redirecturl\"</script>");

}

function textbbcode($form,$name,$content="") {

$tpl_bbcode=new bTemplate();
$tpl_bbcode->set("form_name",$form);
$tpl_bbcode->set("object_name",$name);
$tpl_bbcode->set("content",$content);
$tbbcode="<table width=\"100%\" cellpadding=\"1\" cellspacing=\"1\">";

global $smilies, $STYLEPATH, $language;
$count=0;
reset($smilies);
$tbbcode.="<tr>";
while ((list($code, $url) = each($smilies)) && $count<16) {
      $tbbcode.="\n<td><a href=\"javascript: SmileIT('".str_replace("'","\'",$code)."',document.forms.$form.$name);\"><img border=\"0\" src=\"images/smilies/$url\" alt=\"$url\" /></a></td>";
      $count++;
}
$tbbcode.="\n</tr>\n</table>";
$tpl_bbcode->set("smilies_table",$tbbcode);
$tpl_bbcode->set("language",$language);
return $tpl_bbcode->fetch(load_template("txtbbcode.tpl"));

}

// begin functions for the forum

function is_valid_id($id)
{
  return is_numeric($id) && ($id > 0) && (floor($id) == $id);
}


function get_date_time($timestamp = 0)
{

  global $CURRENTPATH;

  include("$CURRENTPATH/offset.php");
  if ($timestamp)
    return date("d/m/Y H:i:s", $timestamp-$offset);
  else
    return gmdate("d/m/Y H:i:s");
}

function stderr($heading, $text,$close=false)
{

  err_msg($heading,$text,$close);
  stdfoot(true,false);
  die;
}
function encodehtml($s, $linebreaks = true)
{
  $s = str_replace("<", "&lt;", str_replace("&", "&amp;", $s));
  if ($linebreaks)
    $s = nl2br($s);
  return $s;
}

function get_elapsed_time($ts)
{
  $mins = floor((time() - $ts) / 60);
  $hours = floor($mins / 60);
  $mins -= $hours * 60;
  $days = floor($hours / 24);
  $hours -= $days * 24;
  $weeks = floor($days / 7);
  $days -= $weeks * 7;
  $t = "";
  if ($weeks > 0)
    return "$weeks week" . ($weeks > 1 ? "s" : "");
  if ($days > 0)
    return "$days day" . ($days > 1 ? "s" : "");
  if ($hours > 0)
    return "$hours hour" . ($hours > 1 ? "s" : "");
  if ($mins > 0)
    return "$mins min" . ($mins > 1 ? "s" : "");
  return "< 1 min";
}

function sql_timestamp_to_unix_timestamp($s)
{
  return mktime(substr($s, 11, 2), substr($s, 14, 2), substr($s, 17, 2), substr($s, 5, 2), substr($s, 8, 2), substr($s, 0, 4));
}

function gmtime()
{
    return strtotime(get_date_time());
}

function sqlerr($file = '', $line = '')
{
  print("<table border=0 bgcolor=blue align=left cellspacing=0 cellpadding=10 style='background: blue'>" .
    "<tr><td class=embedded><font color=white><h1>".ERR_SQL_ERR."</h1>\n" .
  "<b>" . mysql_error() . ($file != '' && $line != '' ? "<p>in $file, line $line</p>" : "") . "</b></font></td></tr></table>");
  die;
}

function attach_frame($padding = 10)
{
  print("</td></tr><tr><td style='border-top: 0px'>\n");
}

function httperr($code = 404) {
    header("HTTP/1.0 404 Not found");
    print("<h1>".ERR_NOT_FOUND."</h1>\n");
    exit();
}

function peercolor($num)
{
    if(!$num){
        return "#FF0000";
    } elseif($num == 1){
        return "#BEC635";
    } else {
        return "green";
    }
}
// ----------------------------------------------------------------
  class ocr_captcha {
    var $key;       // ultra private static text
    var $long;      // size of text
    var $lx;        // width of picture
    var $ly;        // height of picture
    var $nb_noise;  // nb of background noisy characters
    var $filename;  // file of captcha picture stored on disk
    var $imagetype="png"; // can also be "png";
    var $public_key;    // public key
    var $font_file="./include/adlibn.ttf";
    function ocr_captcha($long=6,$lx=120,$ly=30,$nb_noise=25) {
      $this->key=md5("A nicely little text to stay private and use for generate private key");
      $this->long=$long;
      $this->lx=$lx;
      $this->ly=$ly;
      $this->nb_noise=$nb_noise;
      $this->public_key=substr(md5(uniqid(rand(),true)),0,$this->long); // generate public key with entropy
    }

    function get_filename($public="") {
        global $TORRENTSDIR;
      if ($public=="")
        $public=$this->public_key;
      return $TORRENTSDIR."/".$public.".".$this->imagetype;
    }

    // generate the private text coming from the public text, using $this->key (not to be public!!), all you have to do is here to change the algorithm
    function generate_private($public="") {
      if ($public=="")
        $public=$this->public_key;
      return substr(md5($this->key.$public),16-$this->long/2,$this->long);
    }

    // check if the public text is link to the private text
    function check_captcha($public,$private) {
      // when check, destroy picture on disk
      if (file_exists($this->get_filename($public)))
        unlink($this->get_filename($public));
      return (strtolower($private)==strtolower($this->generate_private($public)));
    }

    // display a captcha picture with private text and return the public text
    function make_captcha($noise=true) {
      $private_key = $this->generate_private();
      $image = imagecreatetruecolor($this->lx,$this->ly);
      $back=ImageColorAllocate($image,intval(rand(224,255)),intval(rand(224,255)),intval(rand(224,255)));
      ImageFilledRectangle($image,0,0,$this->lx,$this->ly,$back);
      if ($noise) { // rand characters in background with random position, angle, color
        for ($i=0;$i<$this->nb_noise;$i++) {
          $size=intval(rand(6,14));
          $angle=intval(rand(0,360));
          $x=intval(rand(10,$this->lx-10));
          $y=intval(rand(0,$this->ly-5));
          $color=imagecolorallocate($image,intval(rand(160,224)),intval(rand(160,224)),intval(rand(160,224)));
          $text=chr(intval(rand(45,250)));
          ImageTTFText ($image,$size,$angle,$x,$y,$color,$this->font_file,$text);
        }
      }
      else { // random grid color
        for ($i=0;$i<$this->lx;$i+=10) {
          $color=imagecolorallocate($image,intval(rand(160,224)),intval(rand(160,224)),intval(rand(160,224)));
          imageline($image,$i,0,$i,$this->ly,$color);
        }
        for ($i=0;$i<$this->ly;$i+=10) {
          $color=imagecolorallocate($image,intval(rand(160,224)),intval(rand(160,224)),intval(rand(160,224)));
          imageline($image,0,$i,$this->lx,$i,$color);
        }
      }
      // private text to read
      for ($i=0,$x=5; $i<$this->long;$i++) {
        $r=intval(rand(0,128));
        $g=intval(rand(0,128));
        $b=intval(rand(0,128));
        $color = ImageColorAllocate($image, $r,$g,$b);
        $shadow= ImageColorAllocate($image, $r+128, $g+128, $b+128);
        $size=intval(rand(12,17));
        $angle=intval(rand(-30,30));
        $text=strtoupper(substr($private_key,$i,1));
        ImageTTFText($image,$size,$angle,$x+2,26,$shadow,$this->font_file,$text);
        ImageTTFText($image,$size,$angle,$x,24,$color,$this->font_file,$text);
        $x+=$size+2;
      }
      if ($this->imagetype=="jpg")
        imagejpeg($image, $this->get_filename(), 100);
      else
        imagepng($image, $this->get_filename());
      ImageDestroy($image);
    }

    function display_captcha($noise=true) {
      $this->make_captcha($noise);
      $res="<input type=\"hidden\" name=\"public_key\" value=\"".$this->public_key."\" />\n";
            $res.="<img align=\"middle\" src=\"".$this->get_filename()."\" border=\"0\" alt=\"\" />\n";
      return $res;
    }
  }
// ----------------------------------------------------------------

// v.1.3
function write_log($text,$reason="add")
{
  GLOBAL $CURUSER, $LOG_ACTIVE, $TABLE_PREFIX;

  if ($LOG_ACTIVE)
    {
     $text = sqlesc($text);
     $reason=sqlesc($reason);
     do_sqlquery("INSERT INTO {$TABLE_PREFIX}logs (added, txt,type,user) VALUES(UNIX_TIMESTAMP(), $text, $reason,'".$CURUSER["username"]."')") or sqlerr(__FILE__, __LINE__);
  }
}



//AJAX Poll System Hack Start - 10:51 PM 3/21/2007
class poll
{
  var $ID;
  var $pollerTitle;
  var $table_prefix;
  
  
  function poll()
  {
    $this->ID = "";
    $this->pollerTitle = "";
    $this->table_prefix="btit_";
  }
      
  function setId($id)
  {
    $this->ID = $id;
  }
  
  function getDataById($id)
  {
    $res = mysql_query("select * from {$this->table_prefix}poller where ID='$id'");
    if($inf = mysql_fetch_array($res)){
      $this->ID = $inf["ID"];
      $this->pollerTitle = $inf["pollerTitle"];
      $this->active = $inf["active"];
    }    
    
  }
  
  /* This method returns poller options as an associative array */
  
  function getOptionsAsArray()
  {
    $retArray = array();
    $res = mysql_query("select * from {$this->table_prefix}poller_option where pollerID='".$this->ID."' order by pollerOrder");
    while($inf = mysql_fetch_array($res)){
      $retArray[$inf["ID"]] = array($inf["optionText"],$inf["pollerOrder"]);
    }  
    return $retArray;
    
  }
  
  /* This method returns number of votes as an associative array */
  function getVotesAsArray()
  {
    $retArray = array();
    $res = mysql_query("select v.optionID,count(v.ID) as countVotes from {$this->table_prefix}poller_vote v,{$this->table_prefix}poller_option o where v.optionID = o.ID and o.pollerID = '".$this->ID."' group by v.optionID");
    while($inf = mysql_fetch_array($res)){
      $retArray[$inf["optionID"]] = $inf["countVotes"];    
      
    }    
    return $retArray;
  }  
  
  /* Create new poller and return ID of new poller */
  
  function createNewPoller($pollerTitle,$userid,$active)
  {
    global $db;

    if ("$active" == "yes")
      {
        mysql_query("UPDATE {$this->table_prefix}poller SET active='no', endDate=UNIX_TIMESTAMP() WHERE poller.active='yes'");
        $res = mysql_query("insert into {$this->table_prefix}poller(pollerTitle,starterID,active,startDate)values('$pollerTitle','$userid','yes',UNIX_TIMESTAMP())") or die(mysql_error());
      }
    elseif  ("$active" == "no")
        $res = mysql_query("insert into {$this->table_prefix}poller(pollerTitle,endDate,starterID,active,startDate)values('$pollerTitle',UNIX_TIMESTAMP(),'$userid','no',UNIX_TIMESTAMP())") or die(mysql_error());

    $this->ID = mysql_insert_id();
    return $this->ID;
  }
  
  /* Add poller options */
  
  function addPollerOption($optionText,$pollerOrder)
  {
    mysql_query("insert into {$this->table_prefix}poller_option(pollerID,optionText,pollerOrder)values('".$this->ID."','".$optionText."','".$pollerOrder."')") or die(mysql_error());
    return mysql_insert_id();    
  }
  
  /* Delete a poll, options in the poll and votes */
  function deletePoll($pollId)
  {
    mysql_query("delete from {$this->table_prefix}poller where ID='$pollId'");
    $res = mysql_query("select * from {$this->table_prefix}poller_option where pollerID='".$pollId."'");
    while($inf = mysql_fetch_array($res)){
      mysql_query("delete from {$this->table_prefix}poller_vote where optionID='".$inf["ID"]."'");
      mysql_query("delete from {$this->table_prefix}poller_option where ID='".$inf["ID"]."'");
    }    
  }
  /* Updating poll title */
  function setPollerTitle($pollerTitle)
  {
    mysql_query("update {$this->table_prefix}poller set pollerTitle='$pollerTitle' where ID='".$this->ID."'");
  }

  function setPollerActive($pollerActive)
  {
    if ("$pollerActive" == "yes")
    mysql_query("UPDATE {$this->table_prefix}poller SET endDate=UNIX_TIMESTAMP(), active='no' WHERE poller.active='yes'");
    mysql_query("UPDATE {$this->table_prefix}poller SET endDate='0', active='$pollerActive' WHERE ID='".$this->ID."'");
  }
  
  /* Update option label */
  function setOptionData($newText,$order,$optionId)
  {
    mysql_query("update {$this->table_prefix}poller_option set optionText='".$newText."',pollerOrder='$order' where ID='".$optionId."'");    
  }
  
  /* Get position of the last option, i.e. to append a new option at the bottom of the list */
  
  function getMaxOptionOrder()
  {
    $res = mysql_query("select max(pollerOrder) as maxOrder from {$this->table_prefix}poller_option where pollerID='".$this->ID."'") or die(mysql_error());
    if($inf = mysql_fetch_array($res)){
      return $inf["maxOrder"];
    }
    return 0;    
  }
  
  /* Return order of poller options as array */
}

function DateFormat( $seconds) 
{ 
   $years = 0;
   $months = 0;
   $weeks = 0;
   $days = 0;
   $hours = 0;
   $minutes = 0;

   while ($seconds>31536000) {
      $years++; 
      $seconds -= 31536000; }

   while ($seconds>2419200) {
      $months++; 
      $seconds -= 2419200; }

   while ($seconds>604800) {
     $weeks++; 
     $seconds -= 604800; }

   while ($seconds>86400) {
      $days++; 
      $seconds -= 86400; }

   while ($seconds>3600) {
      $hours++; 
      $seconds -= 3600; }

   while ($seconds>60) {
      $minutes++; 
      $seconds -= 60; }

$year="";
if ($years>0)
 {
  if ($years==1)
   $ys = "".YEAR."";
  elseif ($years==0 || $years>1)
   $ys = "".YEARS."";
  $year = "".$years." ".$ys.", ";
 }

$month="";
if ($months>0)
 {
  if ($months==1)
   $ms = "".MONTH."";
  elseif ($months==0 || $months>1)
   $ms = "".MONTHS."";
  $month = "".$months." ".$ms.", ";
 }

$week="";
if ($weeks>0)
 {
  if ($weeks==1)
   $ws = "".WEEK."";
  elseif ($weeks==0 || $weeks>1)
   $ws = "".WEEKS."";
  $week = "".$weeks." ".$ws.", ";
 }

$day="";
if ($days>0)
 {
  if ($days==1)
   $ds = "".DAY."";
  elseif ($days==0 || $days>1)
   $ds = "".DAYS."";
  $day = "".$days." ".$ds.", ";
 }

$hour="";
if ($hours>0)
 {
  if ($hours==1)
   $hs = "".HOUR."";
  elseif ($hours==0 || $hours>1)
   $hs = "".HOURS."";
  $hour = "".$hours." ".$hs.", ";
 }

$minute="";
if ($minutes>0)
 {
  if ($minutes==1)
   $is = "".MINUTE."";
  elseif ($minutes==0 || $minutes>1)
   $is = "".MINUTES."";
  $minute = "".$minutes." ".$is." ".WORD_AND." ";
 }

$second="";
if ($seconds>0)
 {
  if ($seconds==1)
   $ss = "".SECOND."";
  elseif ($seconds==0 || $seconds>1)
   $ss = "".SECONDS."";
  $second = "".$seconds." ".$ss."";
 }

   $timestamp = "".$year."".$month."".$week."".$day."".$hour."".$minute."".$second.""; 

   return $timestamp; 
}
//AJAX Poll System Hack Stop

function smf_passgen($username, $pwd) {

$passhash = sha1(strtolower($username) . $pwd);
$salt=substr(md5(rand()), 0, 4);

return array($passhash,$salt);

}

function set_smf_cookie($id, $passhash, $salt)
{

global $THIS_BASEPATH;

$language2=$language;
require($THIS_BASEPATH.'/smf/Settings.php');
require($THIS_BASEPATH.'/smf/SSI.php');
require($THIS_BASEPATH.'/smf/Sources/Subs-Auth.php');
$language=$language2;

setLoginCookie(189216000, $id, sha1($passhash . $salt));

}

if ( !function_exists('htmlspecialchars_decode') )
{
   function htmlspecialchars_decode($text)
   {
       return strtr($text, array_flip(get_html_translation_table(HTML_SPECIALCHARS)));
   }
}

// EOF
?>