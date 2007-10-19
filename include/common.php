<?php

require_once(dirname(__FILE__)."/config.php");

if (!function_exists("bcsub"))
    {
    function bcsub($first_num, $second_num)
    {
        $fn=intval(0+$first_num);
        $sn=intval(0+$second_num);
        return $fn-$sn;
    }

}


function send_mail($rec_email,$subject,$message, $IsHtml=false)
{
  global $THIS_BASEPATH, $btit_settings;

  include("$THIS_BASEPATH/phpmailer/class.phpmailer.php");
  $mail=new PHPMailer();

  if ($btit_settings["mail_type"]=='php')
    {
      $mail->IsMail();                                   // send via mail
      $mail->From     = $btit_settings["email"];
      $mail->FromName = $btit_settings["name"];
      $mail->AddAddress($rec_email);
      $mail->AddReplyTo($btit_settings["email"],$btit_settings["name"]);

      $mail->WordWrap = 50;                              // set word wrap
      $mail->IsHTML($IsHtml);

      $mail->Subject  =  $subject;
      $mail->Body     =  $message;
    }
  else
    {
      $mail->IsSMTP();                                   // send via SMTP
      $mail->Host     = $btit_settings["smtp_server"];   // SMTP servers
      $mail->Port     = $btit_settings["smtp_port"];
      $mail->SMTPAuth = true;     // turn on SMTP authentication
      $mail->Username = $btit_settings["smtp_username"];  // SMTP username
      $mail->Password = $btit_settings["smtp_password"]; // SMTP password

      $mail->From     = $btit_settings["email"];
      $mail->FromName = $btit_settings["name"];
      $mail->AddAddress($rec_email);
      $mail->AddReplyTo($btit_settings["email"],$btit_settings["name"]);

      $mail->WordWrap = 50;                              // set word wrap
      $mail->IsHTML($IsHtml);

      $mail->Subject  =  $subject;
      $mail->Body     =  $message;
  }

  return $mail->Send();

}


function get_remote_file($http_url,$mode="r")
  {

  $stream="";

  // for first thing we will try with cURL
  if (function_exists("curl_init"))
    {
     $fp=curl_init();
     curl_setopt($fp, CURLOPT_URL, $http_url);
     curl_setopt($fp, CURLOPT_RETURNTRANSFER, true);
     $stream=curl_exec($fp);
     curl_close($fp);

     return $stream;
  }
  // then with fsockopen

  $purl=parse_url($http_url);
  $port=isset($purl["port"])?$purl["port"]:"80";
  $path=isset($purl["path"])?$purl["path"]:"/scrape.php";
  $an=($purl["scheme"]!="http"?$purl["scheme"]."://":"").$purl["host"];
  $query=isset($purl["query"])?"?".$purl["query"]:"";
  $fp=@fsockopen($an,$port,$errno,$errstr, 60);

  if ($fp)
    {
      fputs($fp,"GET $path$query HTTP/1.0\r\nHost: www.google.com\r\nConnection: close\r\n\r\n");
      while (!feof($fp))
         $stream .= fgets($fp, 4096);
      @fclose($fp);

      if (substr($stream,9,3)=="404")
        {
          // last chance we try slowest fopen
          $fp=@fopen($http_url,$mode);
          if (!$fp)
            return false;

          while (!feof($fp))
              $stream.=fread($fp,4096);

          @fclose($fp);

      }

      return $stream;
  }
}

function do_sqlquery($qrystr,$display_error=false)
{
  global $num_queries;

  $ret=mysql_query($qrystr);
  if (mysql_errno()!=0 && $display_error)
      stderr("MySQL query error!","<br />\nError: ".mysql_error()."<br />\nQuery: ".$qrystr."<br />\n");

  $num_queries+=1;

  return $ret;

}

function write_cached_version($page, $content="")
  {
  global $CACHE_DURATION;

  // no cache
  if ($CACHE_DURATION==0)
        return false;

  $cache_dir=realpath(dirname(__FILE__)."/..")."/cache/";
  $cache_ext=".txt";
  $cache_file=$cache_dir.md5($page).$cache_ext;

  if ($content=="")
      $content=ob_get_contents();

  // write cache file
  if(@is_writable($cache_dir))
    {
      $fp=@fopen($cache_file,"w");
      if ($fp)
        {
        @fputs($fp,$content);
        @fclose($fp);
      }
  }
  ob_end_flush();

}

function get_cached_version($page)
  {

  global $CACHE_DURATION;

  // no cache
  if ($CACHE_DURATION==0)
        return false;

  $cache_dir=realpath(dirname(__FILE__)."/..")."/cache/";
  $cache_ext=".txt";
  $cache_file=$cache_dir.md5($page).$cache_ext;

  if (file_exists($cache_file) && (time()-$CACHE_DURATION) < filemtime($cache_file))
     return file_get_contents($cache_file);
  else
    {
    ob_start();
    return false;
  }

}


function get_result($qrystr,$display_error=false,$cachetime=0)
{
  $cache_dir=realpath(dirname(__FILE__)."/..")."/cache/";
  $cache_ext=".txt";
  $cache_file=$cache_dir.md5($qrystr).$cache_ext;

  if ($cachetime>0)
  {
      if (file_exists($cache_file) && (time()-$cachetime) < filemtime($cache_file))
         return unserialize(file_get_contents($cache_file));
  }

  $return=array();
  $mr=do_sqlquery($qrystr,$display_error);
  while ($mz=mysql_fetch_assoc($mr))
        $return[]=$mz;

  unset($mz);
  mysql_free_result($mr);

  if ($cachetime>0)
    {
      if(@is_writable($cache_dir))
        {
        $fp=fopen($cache_file,"w");
        fputs($fp,serialize($return));
        fclose($fp);
      }
  }

  return $return;

}

// Reports an error to the client in $message.
// Any other output will confuse the client, so please don't do that.
function show_error($message, $log=false)
{
  if ($log)
      error_log("BtiTracker: ERROR ($message)");

  echo "d14:failure reason".strlen($message).":$message"."e";
  exit(0);
}


function verifyHash($input)
{
    if (strlen($input) === 40 && preg_match('/^[0-9a-f]+$/', $input))
        return true;
    else
        return false;
}

/**** validip/getip courtesy of manolete <manolete@myway.com> ****/

// IP Validation
function validip($ip)
{
    if (!empty($ip) && $ip==long2ip(ip2long($ip)))
    {
        // reserved IANA IPv4 addresses
        // http://www.iana.org/assignments/ipv4-address-space
        $reserved_ips = array (
                array('0.0.0.0','2.255.255.255'),
                array('10.0.0.0','10.255.255.255'),
                array('127.0.0.0','127.255.255.255'),
                array('169.254.0.0','169.254.255.255'),
                array('172.16.0.0','172.31.255.255'),
                array('192.0.2.0','192.0.2.255'),
                array('192.168.0.0','192.168.255.255'),
                array('255.255.255.0','255.255.255.255')
        );

        foreach ($reserved_ips as $r)
        {
                $min = ip2long($r[0]);
                $max = ip2long($r[1]);
                if ((ip2long($ip) >= $min) && (ip2long($ip) <= $max)) return false;
        }
        return true;
    }
    else return false;
}

// Patched function to detect REAL IP address if it's valid
function getip()
{
  
  if (getenv('HTTP_CLIENT_IP') && long2ip(ip2long(getenv('HTTP_CLIENT_IP')))==getenv('HTTP_CLIENT_IP') && validip(getenv('HTTP_CLIENT_IP')))
  {
        $ip = getenv('HTTP_CLIENT_IP');
  }
  elseif (getenv('HTTP_X_FORWARDED_FOR') && long2ip(ip2long(getenv('HTTP_X_FORWARDED_FOR')))==getenv('HTTP_X_FORWARDED_FOR') && validip(getenv('HTTP_X_FORWARDED_FOR')))
  {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
  }
  elseif (getenv('HTTP_X_FORWARDED') && long2ip(ip2long(getenv('HTTP_X_FORWARDED')))==getenv('HTTP_X_FORWARDED') && validip(getenv('HTTP_X_FORWARDED')))
  {
        $ip = getenv('HTTP_X_FORWARDED');
  }
  elseif (getenv('HTTP_FORWARDED_FOR') && long2ip(ip2long(getenv('HTTP_FORWARDED_FOR')))==getenv('HTTP_FORWARDED_FOR') && validip(getenv('HTTP_FORWARDED_FOR')))
  {
        $ip = getenv('HTTP_FORWARDED_FOR');
  }
  elseif (getenv('HTTP_FORWARDED') && long2ip(ip2long(getenv('HTTP_FORWARDED')))==getenv('HTTP_FORWARDED') && validip(getenv('HTTP_FORWARDED')))
  {
        $ip = getenv('HTTP_FORWARDED');
  }
  else {
      $ip = long2ip(ip2long($_SERVER['REMOTE_ADDR']));
  }
  return($ip);

}


function hex2bin ($input, $assume_safe=true)
{
    if ($assume_safe !== true && ! ((strlen($input) % 2) === 0 || preg_match ('/^[0-9a-f]+$/i', $input)))
        return "";
    return pack('H*', $input );
}

// Runs a query with no regard for the result
function quickQuery($query)
{
    $results = do_sqlquery($query);
    if (!is_bool($results))
        mysql_free_result($results);
    else
        return $results;
    return true;
}

#========================================
#getAgent function by deliopoulos
#========================================
function StdDecodePeerId($id_data, $id_name){
  $version_str = "";
  for ($i=0; $i<=strlen($id_data); $i++){
    $c = $id_data[$i];
    if ($id_name=="BitTornado" || $id_name=="ABC") {
      if ($c!='-' && ctype_digit($c)) $version_str .= "$c.";
      elseif ($c!='-' && ctype_alpha($c)) $version_str .= (ord($c)-55).".";
      else break;
    }
    elseif($id_name=="BitComet"||$id_name=="BitBuddy"||$id_name=="Lphant"||$id_name=="BitPump"||$id_name=="BitTorrent Plus! v2") {
      if ($c != '-' && ctype_alnum($c)){
        $version_str .= "$c";
        if($i==0) $version_str = intval($version_str) .".";
      }
      else{
        $version_str .= ".";
        break;
      }
    }
    else {
      if ($c != '-' && ctype_alnum($c)) $version_str .= "$c.";
      else break;
    }
  }
  $version_str = substr($version_str,0,strlen($version_str)-1);
  return "$id_name $version_str";
}
function MainlineDecodePeerId($id_data, $id_name){
  $version_str = "";
  for ($i=0; $i<=strlen($id_data); $i++){
    $c = $id_data[$i];
    if ($c != '-' && ctype_alnum($c)) $version_str .= "$c.";
  }
  $version_str = substr($version_str,0,strlen($version_str)-1);
    return "$id_name $version_str";
}
function DecodeVersionString ($ver_data, $id_name){
    $version_str = "";
    $version_str .= intval(ord($ver_data[0]) + 0).".";
    $version_str .= intval(ord($ver_data[1])/10 + 0);
    $version_str .= intval(ord($ver_data[1])%10 + 0);
    return "$id_name $version_str";
}
function getagent($httpagent, $peer_id="") {
  if($peer_id!="") $peer_id=hex2bin($peer_id);
  if(substr($peer_id,0,3)=='-AX') return StdDecodePeerId(substr($peer_id,4,4),"BitPump"); # AnalogX BitPump
  if(substr($peer_id,0,3)=='-BB') return StdDecodePeerId(substr($peer_id,3,5),"BitBuddy"); # BitBuddy
  if(substr($peer_id,0,3)=='-BC') return StdDecodePeerId(substr($peer_id,4,4),"BitComet"); # BitComet
  if(substr($peer_id,0,3)=='-BS') return StdDecodePeerId(substr($peer_id,3,7),"BTSlave"); # BTSlave
  if(substr($peer_id,0,3)=='-BX') return StdDecodePeerId(substr($peer_id,3,7),"BittorrentX"); # BittorrentX
  if(substr($peer_id,0,3)=='-CT') return "Ctorrent $peer_id[3].$peer_id[4].$peer_id[6]"; # CTorrent
  if(substr($peer_id,0,3)=='-KT') return StdDecodePeerId(substr($peer_id,3,7),"KTorrent"); # KTorrent
  if(substr($peer_id,0,3)=='-LT') return StdDecodePeerId(substr($peer_id,3,7),"libtorrent"); # libtorrent
  if(substr($peer_id,0,3)=='-LP') return StdDecodePeerId(substr($peer_id,4,4),"Lphant"); # Lphant
  if(substr($peer_id,0,3)=='-MP') return StdDecodePeerId(substr($peer_id,3,7),"MooPolice"); # MooPolice
  if(substr($peer_id,0,3)=='-MT') return StdDecodePeerId(substr($peer_id,3,7),"Moonlight"); # MoonlightTorrent
  if(substr($peer_id,0,3)=='-PO') return StdDecodePeerId(substr($peer_id,3,7),"PO Client"); #unidentified clients with versions
  if(substr($peer_id,0,3)=='-QT') return StdDecodePeerId(substr($peer_id,3,7),"Qt 4 Torrent"); # Qt 4 Torrent
  if(substr($peer_id,0,3)=='-RT') return StdDecodePeerId(substr($peer_id,3,7),"Retriever"); # Retriever
  if(substr($peer_id,0,3)=='-S2') return StdDecodePeerId(substr($peer_id,3,7),"S2 Client"); #unidentified clients with versions
  if(substr($peer_id,0,3)=='-SB') return StdDecodePeerId(substr($peer_id,3,7),"Swiftbit"); # Swiftbit
  if(substr($peer_id,0,3)=='-SN') return StdDecodePeerId(substr($peer_id,3,7),"ShareNet"); # ShareNet
  if(substr($peer_id,0,3)=='-SS') return StdDecodePeerId(substr($peer_id,3,7),"SwarmScope"); # SwarmScope
  if(substr($peer_id,0,3)=='-SZ') return StdDecodePeerId(substr($peer_id,3,7),"Shareaza"); # Shareaza
  if(preg_match("/^RAZA ([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $httpagent, $matches)) return "Shareaza $matches[1]";
  if(substr($peer_id,0,3)=='-TN') return StdDecodePeerId(substr($peer_id,3,7),"Torrent.NET"); # Torrent.NET
  if(substr($peer_id,0,3)=='-TR') return StdDecodePeerId(substr($peer_id,3,7),"Transmission"); # Transmission
  if(substr($peer_id,0,3)=='-TS') return StdDecodePeerId(substr($peer_id,3,7),"TorrentStorm"); # Torrentstorm
  if(substr($peer_id,0,3)=='-UR') return StdDecodePeerId(substr($peer_id,3,7),"UR Client"); # unidentified clients with versions
  if(substr($peer_id,0,3)=='-UT') return StdDecodePeerId(substr($peer_id,3,7),"uTorrent"); # uTorrent
  if(substr($peer_id,0,3)=='-XT') return StdDecodePeerId(substr($peer_id,3,7),"XanTorrent"); # XanTorrent
  if(substr($peer_id,0,3)=='-ZT') return StdDecodePeerId(substr($peer_id,3,7),"ZipTorrent"); # ZipTorrent
  if(substr($peer_id,0,3)=='-bk') return StdDecodePeerId(substr($peer_id,3,7),"BitKitten"); # BitKitten
  if(substr($peer_id,0,3)=='-lt') return StdDecodePeerId(substr($peer_id,3,7),"libTorrent"); # libTorrent
  if(substr($peer_id,0,3)=='-pX') return StdDecodePeerId(substr($peer_id,3,7),"pHoeniX"); # pHoeniX
  if(substr($peer_id,0,2)=='BG') return StdDecodePeerId(substr($peer_id,2,4),"BTGetit"); # BTGetit
  if(substr($peer_id,2,2)=='BM') return DecodeVersionString(substr($peer_id,0,2),"BitMagnet"); # BitMagnet
  if(substr($peer_id,0,2)=='OP') return StdDecodePeerId(substr($peer_id,2,4),"Opera"); # Opera
  if(substr($peer_id,0,4)=='270-') return "GreedBT 2.7.0"; # GreedBT
  if(substr($peer_id,0,4)=='271-') return "GreedBT 2.7.1"; # GreedBT 2.7.1
  if(substr($peer_id,0,4)=='346-') return "TorrentTopia"; # TorrentTopia
  if(substr($peer_id,0,3)=='-AR') return "Arctic Torrent"; # Arctic (no way to know the version)
  if(substr($peer_id,0,3)=='-G3') return "G3 Torrent"; # G3 Torrent
  if(substr($peer_id,0,6)=='BTDWV-') return "Deadman Walking"; # Deadman Walking
  if(substr($peer_id,5,7)=='Azureus') return "Azureus 2.0.3.2"; # Azureus 2.0.3.2
  if(substr($peer_id,0,8)=='PRC.P---') return "BitTorrent Plus! II"; # BitTorrent Plus! II
  if(substr($peer_id,0,8)=='P87.P---') return "BitTorrent Plus!"; # BitTorrent Plus!
  if(substr($peer_id,0,4)=='Plus') return StdDecodePeerId(substr($peer_id,4,5),"BitTorrent Plus! v2"); # BitTorrent Plus! v2 (not 100% sure on this one)
  if(substr($peer_id,0,8)=='S587Plus') return "BitTorrent Plus!"; # BitTorrent Plus!
  if(substr($peer_id,0,7)=='martini') return "Martini Man"; # Martini Man
  if(substr($peer_id,4,6)=='btfans') return "SimpleBT"; # SimpleBT
  if(substr($peer_id,3,9)=='SimpleBT?') return "SimpleBT"; # SimpleBT
  if(ereg("MFC_Tear_Sample", $httpagent)) return "SimpleBT";
  if(substr($peer_id,0,5)=='btuga') return "BTugaXP"; # BTugaXP
  if(substr($peer_id,0,5)=='BTuga') return "BTuga"; # BTugaXP
  if(substr($peer_id,0,5)=='oernu') return "BTugaXP"; # BTugaXP
  if(substr($peer_id,0,10)=='DansClient') return "XanTorrent"; # XanTorrent
  if(substr($peer_id,0,16)=='Deadman Walking-') return "Deadman"; # Deadman client
  if(substr($peer_id,0,8)=='XTORR302') return "TorrenTres 0.0.2"; # TorrenTres
  if(substr($peer_id,0,7)=='turbobt') return "TurboBT ".(substr($peer_id,7,5)); # TurboBT
  if(substr($peer_id,0,7)=='a00---0') return "Swarmy"; # Swarmy
  if(substr($peer_id,0,7)=='a02---0') return "Swarmy"; # Swarmy
  if(substr($peer_id,0,7)=='T00---0') return "Teeweety"; # Teeweety
  if(substr($peer_id,0,7)=='rubytor') return "Ruby Torrent v".ord($peer_id[7]); # Ruby Torrent
  if(substr($peer_id,0,5)=='Mbrst') return MainlineDecodePeerId(substr($peer_id,5,5),"burst!"); # burst!
  if(substr($peer_id,0,4)=='btpd') return "BT Protocol Daemon ".(substr($peer_id,5,3)); # BT Protocol Daemon
  if(substr($peer_id,0,8)=='XBT022--') return "BitTorrent Lite"; # BitTorrent Lite based on XBT code
  if(substr($peer_id,0,3)=='XBT') return StdDecodePeerId(substr($peer_id,3,3), "XBT"); # XBT Client
  if(substr($peer_id,0,4)=='-BOW') return StdDecodePeerId(substr($peer_id,4,5),"Bits on Wheels"); # Bits on Wheels
  if(substr($peer_id,1,2)=='ML') return MainlineDecodePeerId(substr($peer_id,3,5),"MLDonkey"); # MLDonkey
  if($peer_id[0]=='A') return StdDecodePeerId(substr($peer_id,1,9),"ABC"); # ABC
  if($peer_id[0]=='R') return StdDecodePeerId(substr($peer_id,1,5),"Tribler"); # Tribler
  if($peer_id[0]=='M'){
    if(preg_match("/^Python/", $httpagent, $matches)) return "Spoofing BT Client"; # Spoofing BT Client
    return MainlineDecodePeerId(substr($peer_id,1,7),"Mainline"); # Mainline BitTorrent with version
  }
  if($peer_id[0]=='O') return StdDecodePeerId(substr($peer_id,1,9),"Osprey Permaseed"); # Osprey Permaseed
  if($peer_id[0]=='S'){
    if(preg_match("/^BitTorrent\/3.4.2/", $httpagent, $matches)) return "Spoofing BT Client"; # Spoofing BT Client
    return StdDecodePeerId(substr($peer_id,1,9),"Shad0w"); # Shadow's client
  }
  if($peer_id[0]=='T'){
    if(preg_match("/^Python/", $httpagent, $matches)) return "Spoofing BT Client"; # Spoofing BT Client
    return StdDecodePeerId(substr($peer_id,1,9),"BitTornado"); # BitTornado
  }
  if($peer_id[0]=='U') return StdDecodePeerId(substr($peer_id,1,9),"UPnP"); # UPnP NAT Bit Torrent
  # Azureus / Localhost
  if(substr($peer_id,0,3)=='-AZ') {
    if(preg_match("/^Localhost ([0-9]+\.[0-9]+\.[0-9]+)/", $httpagent, $matches)) return "Localhost $matches[1]";
    if(preg_match("/^BitTorrent\/3.4.2/", $httpagent, $matches)) return "Spoofing BT Client"; # Spoofing BT Client
    if(preg_match("/^Python/", $httpagent, $matches)) return "Spoofing BT Client"; # Spoofing BT Client
    return StdDecodePeerId(substr($peer_id,3,7),"Azureus");
  }
  if(ereg("Azureus", $peer_id)) return "Azureus 2.0.3.2";
  # BitComet/BitLord/BitVampire/Modded FUTB BitComet
  if(substr($peer_id,0,4)=='exbc' || substr($peer_id,1,3)=='UTB'){
    if(substr($peer_id,0,4)=='FUTB') return DecodeVersionString(substr($peer_id,4,2),"BitComet Mod1");
    elseif(substr($peer_id,0,4)=='xUTB') return DecodeVersionString(substr($peer_id,4,2),"BitComet Mod2");
    elseif(substr($peer_id,6,4)=='LORD') return DecodeVersionString(substr($peer_id,4,2),"BitLord");
    elseif(substr($peer_id,6,3)=='---' && DecodeVersionString(substr($peer_id,4,2),"BitComet")=='BitComet 0.54') return "BitVampire";
    else return DecodeVersionString(substr($peer_id,4,2),"BitComet");
  }
  # Rufus
  if(substr($peer_id,2,2)=='RS'){
    for ($i=0; $i<=strlen(substr($peer_id,4,9)); $i++){
      $c = $peer_id[$i+4];
      if (ctype_alnum($c) || $c == chr(0)) $rufus_chk = true;
      else break;
    }
    if ($rufus_chk) return DecodeVersionString(substr($peer_id,0,2),"Rufus"); # Rufus
  }
  # BitSpirit
  if(substr($peer_id,14,6)=='HTTPBT' || substr($peer_id,16,4)=='UDP0') {
    if(substr($peer_id,2,2)=='BS') {
      if($peer_id[1]==chr(0)) return "BitSpirit v1";
      if($peer_id[1]== chr(2)) return "BitSpirit v2";
    }
        return "BitSpirit";
  }
  #BitSpirit
  if(substr($peer_id,2,2)=='BS') {
    if($peer_id[1]==chr(0)) return "BitSpirit v1";
    if($peer_id[1]==chr(2)) return "BitSpirit v2";
    return "BitSpirit";
  }
  # eXeem beta
  if(substr($peer_id,0,3)=='-eX') {
    $version_str = "";
    $version_str .= intval($peer_id[3],16).".";
    $version_str .= intval($peer_id[4],16);
    return "eXeem $version_str";
  }
  if(substr($peer_id,0,2)=='eX') return "eXeem"; # eXeem beta .21
  if(substr($peer_id,0,12)==(chr(0)*12) && $peer_id[12]==chr(97) && $peer_id[13]==chr(97)) return "Experimental 3.2.1b2"; # Experimental 3.2.1b2
  if(substr($peer_id,0,12)==(chr(0)*12) && $peer_id[12]==chr(0) && $peer_id[13]==chr(0)) return "Experimental 3.1"; # Experimental 3.1
  //if(substr($peer_id,0,12)==(chr(0)*12)) return "Mainline (obsolete)"; # Mainline BitTorrent (obsolete)
  //return "$httpagent [$peer_id]";
  return "Unknown client";
}
#========================================
#getAgent function by deliopoulos
#========================================


?>