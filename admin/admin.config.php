<?php

if (!defined("IN_BTIT"))
      die("non direct access!");

if (!defined("IN_ACP"))
      die("non direct access!");

$admintpl->set("config_saved",false,true);

switch ($action)
    {
    case 'write':
      if ($_POST["write"]==$language["FRM_CONFIRM"])
        {
        $btit_settings=array();
        $btit_settings["name"]=$_POST["trackername"];
        $btit_settings["url"]=$_POST["trackerurl"];
//        $btit_settings["announce"]=serialize(explode("\n",$_POST["announceurl"]));
        $btit_settings["announce"]=serialize(explode("\n",$_POST["tracker_announceurl"]));
        $btit_settings["email"]=$_POST["trackeremail"];
        $btit_settings["torrentdir"]=$_POST["torrentdir"];
        $btit_settings["external"]=isset($_POST["exttorrents"])?"true":"false";
        $btit_settings["gzip"]=isset($_POST["gzip_enabled"])?"true":"false";
        $btit_settings["debug"]=isset($_POST["show_debug"])?"true":"false";
        $btit_settings["disable_dht"]=isset($_POST["dht"])?"true":"false";
        $btit_settings["livestat"]=isset($_POST["livestat"])?"true":"false";
        $btit_settings["logactive"]=isset($_POST["logactive"])?"true":"false";
        $btit_settings["loghistory"]=isset($_POST["loghistory"])?"true":"false";
        $btit_settings["p_announce"]=isset($_POST["p_announce"])?"true":"false";
        $btit_settings["p_scrape"]=isset($_POST["p_scrape"])?"true":"false";
        $btit_settings["show_uploader"]=isset($_POST["show_uploader"])?"true":"false";
        $btit_settings["usepopup"]=isset($_POST["usepopup"])?"true":"false";
        $btit_settings["default_language"]=$_POST["default_langue"];
        $btit_settings["default_style"]=$_POST["default_style"];
        $btit_settings["default_charset"]=$_POST["default_charset"];
        $btit_settings["max_users"]=$_POST["maxusers"];
        $btit_settings["max_torrents_per_page"]=$_POST["ntorrents"];
        $btit_settings["sanity_update"]=$_POST["sinterval"];
        $btit_settings["external_update"]=$_POST["uinterval"];
        $btit_settings["max_announce"]=$_POST["rinterval"];
        $btit_settings["min_announce"]=$_POST["mininterval"];
        $btit_settings["max_peers_per_announce"]=$_POST["maxpeers"];
        $btit_settings["dynamic"]=isset($_POST["dynamic"])?"true":"false";
        $btit_settings["nat"]=isset($_POST["nat"])?"true":"false";
        $btit_settings["persist"]=isset($_POST["persist"])?"true":"false";
        $btit_settings["allow_override_ip"]=isset($_POST["override"])?"true":"false";
        $btit_settings["countbyte"]=isset($_POST["countbyte"])?"true":"false";
        $btit_settings["peercaching"]=isset($_POST["peercaching"])?"true":"false";
        $btit_settings["maxpid_seeds"]=$_POST["maxseeds"];
        $btit_settings["maxpid_leech"]=$_POST["maxleech"];
        $btit_settings["validation"]=$_POST["validation"];
        $btit_settings["imagecode"]=isset($_POST["imagecode"])?"true":"false";
        $btit_settings["forum"]=$_POST["f_link"];
        $btit_settings["clocktype"]=$_POST["clocktype"];
        $btit_settings["newslimit"]=$_POST["newslimit"];
        $btit_settings["forumlimit"]=$_POST["forumlimit"];
        $btit_settings["last10limit"]=$_POST["last10limit"];
        $btit_settings["mostpoplimit"]=$_POST["mostpoplimit"];
        $btit_settings["xbtt_use"]=isset($_POST["xbtt_use"])?"true":"false";
        $btit_settings["xbtt_url"]=$_POST["xbtt_url"];
        $btit_settings["cache_duration"]=$_POST["cache_duration"];
        $btit_settings["cut_name"]=intval($_POST["cut_name"]);
        
        $btit_settings["mail_type"]=$_POST["mail_type"];
        if ($btit_settings["mail_type"]=="smtp")
          {
          $btit_settings["smtp_server"]=$_POST["smtp_server"];
          $btit_settings["smtp_port"]=$_POST["smtp_port"];
          $btit_settings["smtp_username"]=$_POST["smtp_username"];
          $btit_settings["smtp_password"]=$_POST["smtp_password"];
        }

        foreach($btit_settings as $key=>$value)
            $values[]="(".sqlesc($key).",".sqlesc($value).")";
        //die(implode(",",$values));
        mysql_query("DELETE FROM {$TABLE_PREFIX }settings") or stderr($language["ERROR"],mysql_error());
        mysql_query("INSERT INTO {$TABLE_PREFIX }settings (`key`,`value`) VALUES ".implode(",",$values).";") or stderr($language["ERROR"],mysql_error());

        unset($values);
        

        $admintpl->set("config_saved",true,true);
        }
        // we don't break, so we will display the new config...

    case 'read':
    case '':
    default:
        $admintpl->set("language",$language);
        // some $btit_settings are stored in database, some other not like in template
        // we will convert and set to correct value in the array.
        $btit_settings["announce"]=implode("\n",unserialize($btit_settings["announce"]));
        $btit_settings["external"]=($btit_settings["external"]=="true"?"checked=\"checked\"":"");
        $btit_settings["gzip"]=($btit_settings["gzip"]=="true"?"checked=\"checked\"":"");
        $btit_settings["debug"]=($btit_settings["debug"]=="true"?"checked=\"checked\"":"");
        $btit_settings["disable_dht"]=($btit_settings["disable_dht"]=="true"?"checked=\"checked\"":"");
        $btit_settings["livestat"]=($btit_settings["livestat"]=="true"?"checked=\"checked\"":"");
        $btit_settings["logactive"]=($btit_settings["logactive"]=="true"?"checked=\"checked\"":"");
        $btit_settings["loghistory"]=($btit_settings["loghistory"]=="true"?"checked=\"checked\"":"");
        $btit_settings["p_announce"]=($btit_settings["p_announce"]=="true"?"checked=\"checked\"":"");
        $btit_settings["p_scrape"]=($btit_settings["p_scrape"]=="true"?"checked=\"checked\"":"");
        $btit_settings["show_uploader"]=($btit_settings["show_uploader"]=="true"?"checked=\"checked\"":"");
        $btit_settings["usepopup"]=($btit_settings["usepopup"]=="true"?"checked=\"checked\"":"");
        $btit_settings["dynamic"]=($btit_settings["dynamic"]=="true"?"checked=\"checked\"":"");
        $btit_settings["nat"]=($btit_settings["nat"]=="true"?"checked=\"checked\"":"");
        $btit_settings["persist"]=($btit_settings["persist"]=="true"?"checked=\"checked\"":"");
        $btit_settings["allow_override_ip"]=($btit_settings["allow_override_ip"]=="true"?"checked=\"checked\"":"");
        $btit_settings["countbyte"]=($btit_settings["countbyte"]=="true"?"checked=\"checked\"":"");
        $btit_settings["peercaching"]=($btit_settings["peercaching"]=="true"?"checked=\"checked\"":"");
        $btit_settings["imagecode"]=($btit_settings["imagecode"]=="true"?"checked=\"checked\"":"");
        $btit_settings["clockanalog"]=($btit_settings["clocktype"]=="true"?"checked=\"checked\"":"");
        $btit_settings["clockdigital"]=($btit_settings["clocktype"]=="false"?"checked=\"checked\"":"");
        $btit_settings["xbtt_use"]=($btit_settings["xbtt_use"]=="true"?"checked=\"checked\"":"");
        // language dropdown
        $lres=language_list();
        $btit_settings["language_combo"]=("\n<select name=\"default_langue\" size=\"1\">");
        foreach($lres as $langue)
          {
            $btit_settings["language_combo"].="\n<option ";
            if ($langue["id"]==$btit_settings["default_language"])
               $btit_settings["language_combo"].="selected=\"selected\" ";
            $btit_settings["language_combo"].="value=\"".$langue["id"]."\">".$langue["language"]."</option>";
            $btit_settings["language_combo"].=($option);
          }
        $btit_settings["language_combo"].=("\n</select>\n");
        unset($lres);
        // charset
        $btit_settings["charset_combo"]="\n<select name=\"default_charset\" size=\"1\">";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="ISO-8859-1"?" selected=\"selected\"":"").">ISO-8859-1</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="ISO-8859-2"?" selected=\"selected\"":"").">ISO-8859-2</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="ISO-8859-3"?" selected=\"selected\"":"").">ISO-8859-3</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="ISO-8859-4"?" selected=\"selected\"":"").">ISO-8859-4</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="ISO-8859-5"?" selected=\"selected\"":"").">ISO-8859-5</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="ISO-8859-6"?" selected=\"selected\"":"").">ISO-8859-6</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="ISO-8859-6-e"?" selected=\"selected\"":"").">ISO-8859-6-e</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="ISO-8859-6-i"?" selected=\"selected\"":"").">ISO-8859-6-i</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="ISO-8859-7"?" selected=\"selected\"":"").">ISO-8859-7</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="ISO-8859-8"?" selected=\"selected\"":"").">ISO-8859-8</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="ISO-8859-8-e"?" selected=\"selected\"":"").">ISO-8859-8-e</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="ISO-8859-8-i"?" selected=\"selected\"":"").">ISO-8859-8-i</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="ISO-8859-9"?" selected=\"selected\"":"").">ISO-8859-9</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="ISO-8859-10"?" selected=\"selected\"":"").">ISO-8859-10</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="ISO-8859-13"?" selected=\"selected\"":"").">ISO-8859-13</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="ISO-8859-14"?" selected=\"selected\"":"").">ISO-8859-14</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="ISO-8859-15"?" selected=\"selected\"":"").">ISO-8859-15</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="UTF-8"?" selected=\"selected\"":"").">UTF-8</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="ISO-2022-JP"?" selected=\"selected\"":"").">ISO-2022-JP</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="EUC-JP"?" selected=\"selected\"":"").">EUC-JP</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="Shift_JIS"?" selected=\"selected\"":"").">Shift_JIS</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="GB2312"?" selected=\"selected\"":"").">GB2312</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="Big5"?" selected=\"selected\"":"").">Big5</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="EUC-KR"?" selected=\"selected\"":"").">EUC-KR</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="windows-1250"?" selected=\"selected\"":"").">windows-1250</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="windows-1251"?" selected=\"selected\"":"").">windows-1251</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="windows-1252"?" selected=\"selected\"":"").">windows-1252</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="windows-1253"?" selected=\"selected\"":"").">windows-1253</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="windows-1254"?" selected=\"selected\"":"").">windows-1254</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="windows-1255"?" selected=\"selected\"":"").">windows-1255</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="windows-1256"?" selected=\"selected\"":"").">windows-1256</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="windows-1257"?" selected=\"selected\"":"").">windows-1257</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="windows-1258"?" selected=\"selected\"":"").">windows-1258</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="KOI8-R"?" selected=\"selected\"":"").">KOI8-R</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="KOI8-U"?" selected=\"selected\"":"").">KOI8-U</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="cp866"?" selected=\"selected\"":"").">cp866</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="cp874"?" selected=\"selected\"":"").">cp874</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="TIS-620"?" selected=\"selected\"":"").">TIS-620</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="VISCII"?" selected=\"selected\"":"").">VISCII</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="VPS"?" selected=\"selected\"":"").">VPS</option>";
        $btit_settings["charset_combo"].="\n<option".($btit_settings["default_charset"]=="TCVN-5712"?" selected=\"selected\"":"").">TCVN-5712</option>";
        $btit_settings["charset_combo"].="\n</select>";
        // style dropdown
        $sres=style_list();
        $btit_settings["style_combo"]="\n<select name=\"default_style\" size=\"1\">";
        foreach($sres as $style)
          {
            $btit_settings["style_combo"].="\n<option ";
            if ($style["id"]==$btit_settings["default_style"])
               $btit_settings["style_combo"].="selected=\"selected\" ";
            $btit_settings["style_combo"].="value=\"".$style["id"]."\">".$style["style"]."</option>";
          }
        $btit_settings["style_combo"].="\n</select>\n";
        unset($sres);
        // validation dropdown
        $btit_settings["validation_combo"]="
                    <select name=\"validation\" size=\"1\">
                    <option value=\"none\"".($btit_settings["validation"]=="none"?" selected=\"selected\"":"").">none</option>
                    <option value=\"user\"".($btit_settings["validation"]=="user"?" selected=\"selected\"":"").">user</option>
                    <option value=\"admin\"".($btit_settings["validation"]=="admin"?" selected=\"selected\"":"").">admin</option>
                    </select>";

        // cut torrent's name
        $btit_settings["cut_name"]=intval($btit_settings["cut_name"]);
        // mailer
        $btit_settings["mail_type_combo"]="\n<option value=\"php\"".($btit_settings["mail_type"]=="php"?"selected=\"selected\"":"").">PHP (default)</option>";
        $btit_settings["mail_type_combo"].="\n<option value=\"smtp\"".($btit_settings["mail_type"]=="smtp"?"selected=\"selected\"":"").">SMTP</option>";

        $btit_settings["smtp_server"]=isset($btit_settings["smtp_server"])?$btit_settings["smtp_server"]:"";
        $btit_settings["smtp_port"]=isset($btit_settings["smtp_port"])?$btit_settings["smtp_port"]:"25";
        $btit_settings["smtp_username"]=isset($btit_settings["smtp_username"])?$btit_settings["smtp_username"]:"";
        $btit_settings["smtp_password"]=isset($btit_settings["smtp_password"])?$btit_settings["smtp_password"]:"";

        $admintpl->set("config",$btit_settings);
        $admintpl->set("frm_action","index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=config&amp;action=write");
        break;
}
?>