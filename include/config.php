<?php

require(dirname(__FILE__)."/settings.php");

// cache interval for cfg read again (it's mysql based now, we don't want overload only for cfg read!)
$reload_cfg_interval=60;


function get_cached_config($qrystr, $cachetime=0)
{
  global $dbhost, $dbuser, $dbpass, $database;

  $cache_dir=realpath(dirname(__FILE__)."/..")."/cache/";
  $cache_ext=".txt";
  $cache_file=$cache_dir.md5($qrystr).$cache_ext;

  if ($cachetime>0)
  {
  if (file_exists($cache_file) && (time()-$cachetime) < filemtime($cache_file))
     return unserialize(file_get_contents($cache_file));
  }

  mysql_connect($dbhost, $dbuser, $dbpass) or die(mysql_error());
  mysql_select_db($database) or die(mysql_error());
  $mr=mysql_query($qrystr) or die(mysql_error());
  while ($mz=mysql_fetch_assoc($mr))
        {
        if ($mz["value"]=="true")
            $return[$mz["key"]]= true;
        elseif ($mz["value"]=="false")
            $return[$mz["key"]]= false;
        elseif (is_numeric($mz["value"]))
            $return[$mz["key"]]= max(0,$mz["value"]);
        else
            $return[$mz["key"]]= unesc($mz["value"]);
       }
  unset($mz);
  mysql_free_result($mr);

  mysql_close();

  if ($cachetime>0)
  {
    $fp=fopen($cache_file,"w");
    fputs($fp,serialize($return));
    fclose($fp);
  }

  return $return;

}


// default settings
function apply_default_settings()
    {

    global $btit_settings;

    if (!isset($btit_settings["max_announce"]) || $btit_settings["max_announce"]=="") $btit_settings["max_announce"]=1800;
    if (!isset($btit_settings["min_announce"]) || $btit_settings["min_announce"]=="") $btit_settings["min_announce"]=300;
    if (!isset($btit_settings["max_peers_per_announce"]) || $btit_settings["max_peers_per_announce"]=="") $btit_settings["max_peers_per_announce"]=50;
    if (!isset($btit_settings["dynamic"]) || $btit_settings["dynamic"]=="") $btit_settings["dynamic"]=false;
    if (!isset($btit_settings["nat"]) || $btit_settings["nat"]=="") $btit_settings["nat"]=false;
    if (!isset($btit_settings["persist"]) || $btit_settings["persist"]=="") $btit_settings["persist"]=false;
    if (!isset($btit_settings["allow_override_ip"]) || $btit_settings["allow_override_ip"]=="") $btit_settings["allow_override_ip"]=false;
    if (!isset($btit_settings["countbyte"]) || $btit_settings["countbyte"]=="") $btit_settings["countbyte"]=true;
    if (!isset($btit_settings["peercaching"]) || $btit_settings["peercaching"]=="") $btit_settings["peercaching"]=true;
    if (!isset($btit_settings["maxpid_seeds"]) || $btit_settings["maxpid_seeds"]=="") $btit_settings["maxpid_seeds"]=3;
    if (!isset($btit_settings["maxpid_leech"]) || $btit_settings["maxpid_leech"]=="") $btit_settings["maxpid_leech"]=2;
    if (!isset($btit_settings["name"]) || $btit_settings["name"]=="") $btit_settings["name"]="BtiTracker Test Site";
    if (!isset($btit_settings["url"]) || $btit_settings["url"]=="") $btit_settings["url"]="http://localhost";
    if (!isset($btit_settings["announce"]) || $btit_settings["announce"]=="") $btit_settings["announce"]=serialize(array("http://localhost/announce.php"));
    if (!isset($btit_settings["email"]) || $btit_settings["email"]=="") $btit_settings["email"]="tracker@localhost";
    if (!isset($btit_settings["torrentdir"]) || $btit_settings["torrentdir"]=="") $btit_settings["torrentdir"]="torrents";
    if (!isset($btit_settings["validation"]) || $btit_settings["validation"]=="") $btit_settings["validation"]="user";

    if (!isset($btit_settings["imagecode"]) || $btit_settings["imagecode"]=="") $btit_settings["imagecode"]=true;
    if (!isset($btit_settings["sanity_update"]) || $btit_settings["sanity_update"]=="") $btit_settings["sanity_update"]=300;
    if (!isset($btit_settings["external_update"]) || $btit_settings["external_update"]=="") $btit_settings["external_update"]=0;
    if (!isset($btit_settings["forum"]) || $btit_settings["forum"]=="") $btit_settings["forum"]="";
    if (!isset($btit_settings["external"]) || $btit_settings["external"]=="") $btit_settings["external"]=true;
    if (!isset($btit_settings["gzip"]) || $btit_settings["gzip"]=="") $btit_settings["gzip"]=true;
    if (!isset($btit_settings["debug"]) || $btit_settings["debug"]=="") $btit_settings["debug"]=true;
    if (!isset($btit_settings["disable_dht"]) || $btit_settings["disable_dht"]=="") $btit_settings["disable_dht"]=false;
    if (!isset($btit_settings["livestat"]) || $btit_settings["livestat"]=="") $btit_settings["livestat"]=true;
    if (!isset($btit_settings["logactive"]) || $btit_settings["logactive"]=="") $btit_settings["logactive"]=true;
    if (!isset($btit_settings["loghistory"]) || $btit_settings["loghistory"]=="") $btit_settings["loghistory"]=false;

    if (!isset($btit_settings["default_language"]) || $btit_settings["default_language"]=="") $btit_settings["default_language"]=1;
    if (!isset($btit_settings["default_charset"]) || $btit_settings["default_charset"]=="") $btit_settings["default_charset"]="ISO-8859-1";
    if (!isset($btit_settings["default_style"]) || $btit_settings["default_style"]=="") $btit_settings["default_style"]=1;
    if (!isset($btit_settings["max_users"]) || $btit_settings["max_users"]=="") $btit_settings["max_users"]=0;
    if (!isset($btit_settings["max_torrents_per_page"]) || $btit_settings["max_torrents_per_page"]=="") $btit_settings["max_torrents_per_page"]=15;
    if (!isset($btit_settings["p_announce"]) || $btit_settings["p_announce"]=="") $btit_settings["p_announce"]=true;
    if (!isset($btit_settings["p_scrape"]) || $btit_settings["p_scrape"]=="") $btit_settings["p_scrape"]=false;
    if (!isset($btit_settings["show_uploader"]) || $btit_settings["show_uploader"]=="") $btit_settings["show_uploader"]=true;
    if (!isset($btit_settings["newslimit"]) || $btit_settings["newslimit"]=="") $btit_settings["newslimit"]=3;
    if (!isset($btit_settings["forumlimit"]) || $btit_settings["forumlimit"]=="") $btit_settings["forumlimit"]=5;
    if (!isset($btit_settings["last10limit"]) || $btit_settings["last10limit"]=="") $btit_settings["last10limit"]=5;
    if (!isset($btit_settings["mostpoplimit"]) || $btit_settings["mostpoplimit"]=="") $btit_settings["mostpoplimit"]=5;
    if (!isset($btit_settings["clocktype"]) || $btit_settings["clocktype"]=="") $btit_settings["clocktype"]=true;
    if (!isset($btit_settings["usepopup"]) || $btit_settings["usepopup"]=="") $btit_settings["usepopup"]=false;
    if (!isset($btit_settings["xbtt_use"]) || $btit_settings["xbtt_use"]=="") $btit_settings["xbtt_use"]=false;
    if (!isset($btit_settings["xbtt_url"]) || $btit_settings["xbtt_url"]=="") $btit_settings["xbtt_url"]="";
    if (!isset($btit_settings["cache_duration"]) || $btit_settings["cache_duration"]=="") $btit_settings["cache_duration"]=0;
    if (!isset($btit_settings["mail_type"]) || $btit_settings["mail_type"]=="") $btit_settings["mail_type"]="php";

    if (!isset($btit_settings["ajax_poller"]) || $btit_settings["ajax_poller"]=="") $btit_settings["ajax_poller"]=true;

}

apply_default_settings();

$btit_settings=get_cached_config("SELECT `key`,`value` FROM {$TABLE_PREFIX}settings",$reload_cfg_interval);




/* Tracker Configuration
 *
 *  This file provides configuration informatino for
 *  the tracker. The user-editable variables are at the top. It is
 *  recommended that you do not change the database settings
 *  unless you know what you are doing.
 */

//Maximum reannounce interval.
$GLOBALS["report_interval"] = $btit_settings["max_announce"];
//Minimum reannounce interval. Optional.
$GLOBALS["min_interval"] = $btit_settings["min_announce"];
//Number of peers to send in one request.
$GLOBALS["maxpeers"] = $btit_settings["max_peers_per_announce"];
//If set to true, then the tracker will accept any and all
//torrents given to it. Not recommended, but available if you need it.
$GLOBALS["dynamic_torrents"] = $btit_settings["dynamic"];
// If set to true, NAT checking will be performed.
// This may cause trouble with some providers, so it's
// off by default.
$GLOBALS["NAT"] = $btit_settings["nat"];
// Persistent connections: true or false.
// Check with your webmaster to see if you're allowed to use these.
// not recommended, only if you get very higher loads, but use at you own risk.
$GLOBALS["persist"] = $btit_settings["persist"];
// Allow users to override ip= ?
// Enable this if you know people have a legit reason to use
// this function. Leave disabled otherwise.
$GLOBALS["ip_override"] = $btit_settings["allow_override_ip"];
// For heavily loaded trackers, set this to false. It will stop count the number
// of downloaded bytes and the speed of the torrent, but will significantly reduce
// the load.
$GLOBALS["countbytes"] = $btit_settings["countbyte"];
// Table caches!
// Lowers the load on all systems, but takes up more disk space.
// You win some, you lose some. But since the load is the big problem,
// grab this.
//
// Warning! Enable this BEFORE making torrents, or else run makecache.php
// immediately, or else you'll be in deep trouble. The tables will lose
// sync and the database will be in a somewhat "stale" state.
$GLOBALS["peercaching"] = $btit_settings["peercaching"];
//Max num. of seeders with same PID.
$GLOBALS["maxseeds"] = $btit_settings["maxpid_seeds"];
//Max num. of leechers with same PID.
$GLOBALS["maxleech"] = $btit_settings["maxpid_leech"];

/////////// End of User Configuration ///////////
//Tracker's name
$SITENAME=$btit_settings["name"];
//Tracker's Base URL
$BASEURL=$btit_settings["url"];
// tracker's announce urls, can be more than one
$TRACKER_ANNOUNCEURLS=array();
$TRACKER_ANNOUNCEURLS=unserialize($btit_settings["announce"]);
for($i=0;$i<count($TRACKER_ANNOUNCEURLS);$i++)
    $TRACKER_ANNOUNCEURLS[$i]=trim(str_replace(array("\r\n","\r","\n"),"",$TRACKER_ANNOUNCEURLS[$i]));
//Tracker's email (owner email)
$SITEEMAIL=$btit_settings["email"];
//Torrent's DIR
$TORRENTSDIR=$btit_settings["torrentdir"];
//validation type (must be none, user or admin
//none=validate immediatly, user=validate by email, admin=manually validate
$VALIDATION=$btit_settings["validation"];
//Use or not the image code for new users' registration
$USE_IMAGECODE=$btit_settings["imagecode"];
// interval for sanity check (good = 10 minutes)
$clean_interval=$btit_settings["sanity_update"];
// interval for updating external torrents (depending of how many external torrents)
$update_interval=$btit_settings["external_update"];
// forum link or internal (empty = internal) or none
$FORUMLINK=$btit_settings["forum"];
// If you want to allow users to upload external torrents values true/false
$EXTERNAL_TORRENTS=$btit_settings["external"];
// Enable/disable GZIP compression, can save a lot of bandwidth
$GZIP_ENABLED=$btit_settings["gzip"];
// Show/Hide bottom page information on script's generation time and gzip
$PRINT_DEBUG=$btit_settings["debug"];
// Enable/disable DHT network, add private flag to "info" in torrent
$DHT_PRIVATE=$btit_settings["disable_dht"];
// Enable/disable Live Stats (up/down updated every announce) WARNING CAN DO HIGH SERVER LOAD!
$LIVESTATS=$btit_settings["livestat"];
// Enable/disable Site log
$LOG_ACTIVE=$btit_settings["logactive"];
//Enable Basic History (torrents/users)
$LOG_HISTORY=$btit_settings["loghistory"];
// Default language (used for guest)
$DEFAULT_LANGUAGE=$btit_settings["default_language"];
// Default charset (used for guest)
$GLOBALS["charset"]=$btit_settings["default_charset"];
// Default style  (used for guest)
$DEFAULT_STYLE=$btit_settings["default_style"];
// Maximum number of users (0 = no limits)
$MAX_USERS=$btit_settings["max_users"];
//torrents per page
$ntorrents =$btit_settings["max_torrents_per_page"];
//private announce (true/false), if set to true don't allow non register user to download
$PRIVATE_ANNOUNCE =$btit_settings["p_announce"];
//private scrape (true/false), if set to true don't allow non register user to scrape (for stats)
$PRIVATE_SCRAPE =$btit_settings["p_scrape"];
//Show uploaders nick on torrent listing
$SHOW_UPLOADER = $btit_settings["show_uploader"];
$GLOBALS["block_newslimit"] = $btit_settings["newslimit"];
$GLOBALS["block_forumlimit"] = $btit_settings["forumlimit"];
$GLOBALS["block_last10limit"] = $btit_settings["last10limit"];
$GLOBALS["block_mostpoplimit"] =$btit_settings["mostpoplimit"];
$GLOBALS["clocktype"] = $btit_settings["clocktype"];
$GLOBALS["usepopup"] = $btit_settings["usepopup"];
// Is xbtt used as backend?
$XBTT_USE = $btit_settings["xbtt_use"];
// If used as backend, then we should have the "xbt url"
$XBTT_URL = $btit_settings["xbtt_url"];
// this is the interval between which the cache must be updated (if 0 cache is disable)
$CACHE_DURATION=$btit_settings["cache_duration"];

//ajax polling system hack
//if set to false then the default btit polling system will be used
$GLOBALS["ajax_poller"] = true;
//if set to true the script will perform an IP check to see if the IP has already voted
$GLOBALS["ipcheck_poller"] = false;
//number of votes per page listed in admincp
$votesppage =25;


?>