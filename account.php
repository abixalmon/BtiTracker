<?php

if (!defined("IN_BTIT"))
      die("non direct access!");

require_once(load_language("lang_account.php"));

if (!isset($_POST["language"])) $_POST["language"] = 0;
$idlang=intval($_POST["language"]);


if (isset($_GET["uid"])) $id=intval($_GET["uid"]);
 else $id="";
if (isset($_GET["returnto"])) $link=urldecode($_GET["returnto"]);
 else $link="";
if (isset($_GET["act"])) $act=$_GET["act"];
 else $act="signup";
if (isset($_GET["language"])) $idlangue=intval($_GET["language"]);
 else $idlangue="";
if (isset($_GET["style"])) $idstyle=intval($_GET["style"]);
 else $idstyle="";
if (isset($_GET["flag"])) $idflag=intval($_GET["flag"]);
 else $idflag="";

if (isset($_POST["uid"]) && isset($_POST["act"]))
  {
if (isset($_POST["uid"])) $id=intval($_POST["uid"]);
 else $id="";
if (isset($_POST["returnto"])) $link=urldecode($_POST["returnto"]);
 else $link="";
if (isset($_POST["act"])) $act=$_POST["act"];
 else $act="";
  }


// already logged?
if ($act=="signup" && isset($CURUSER["uid"]) && $CURUSER["uid"]!=1) {
        $url="index.php";
        redirect($url);
}


$res=do_sqlquery("SELECT count(*) FROM {$TABLE_PREFIX}users WHERE id>1",true);
$nusers=mysql_fetch_row($res);
$numusers=$nusers[0];

if ($act=="signup" && $MAX_USERS!=0 && $numusers>=$MAX_USERS)
   {
   stderr($language["ERROR"],$language["REACHED_MAX_USERS"]);
}

if ($act=="confirm") {

      global $FORUMLINK, $db_prefix;

      $random=intval($_GET["confirm"]);
      $random2=rand(10000, 60000);
      $res=do_sqlquery("UPDATE {$TABLE_PREFIX}users SET id_level=3".(($FORUMLINK=="smf") ? ", random=$random2" : "")." WHERE id_level=2 AND random=$random",true);
      if (!$res)
         die("ERROR: " . mysql_error() . "\n");
      else {
          if($FORUMLINK=="smf")
              do_sqlquery("UPDATE {$db_prefix}members SET ID_GROUP=13 WHERE ID_MEMBER=".$CURUSER["smf_fid"]);  
          success_msg($language["ACCOUNT_CREATED"],$language["ACCOUNT_CONGRATULATIONS"]);
          stdfoot();
          exit;
          }
}

if ($_POST["conferma"]) {
    if ($act=="signup") {
       $ret=aggiungiutente();
       if ($ret==0)
          {
          if ($VALIDATION=="user")
             {
               success_msg($language["ACCOUNT_CREATED"],$language["EMAIL_SENT"]);
               stdfoot();
               exit();
             }
          else if ($VALIDATION=="none")
               {
               success_msg($language["ACCOUNT_CREATED"],$language["ACCOUNT_CONGRATULATIONS"]);
               stdfoot();
               exit();
               }
          else
              {
               success_msg($language["ACCOUNT_CREATED"],$language["WAIT_ADMIN_VALID"]);
               stdfoot();
               exit();
              }
          }
       elseif ($ret==-1)
         stderr($language["ERROR"],$language["ERR_MISSING_DATA"]);
       elseif ($ret==-3)
         stderr($language["ERROR"],$language["ERR_NO_EMAIL"]);
       elseif ($ret==-7)
         stderr($language["ERROR"],"<font color=\"black\">".$language["ERR_NO_SPACE"]."<strong><font color=\"red\">".preg_replace('/\ /', '_', mysql_escape_string($_POST["user"]))."</strong></font></font><br />");
       elseif ($ret==-8)
         stderr($language["ERROR"],$language["ERR_SPECIAL_CHAR"]);
       elseif ($ret==-9)
         stderr($language["ERROR"],$language["ERR_PASS_LENGTH"]);
       else
        stderr($language["ERROR"],$language["ERR_USER_ALREADY_EXISTS"]);
       }
}
else {
    $tpl_account=new bTemplate();
    tabella($act);
}



function tabella($action,$dati=array()) {

   global $idflag,$link, $idlangue, $idstyle, $CURUSER,$USE_IMAGECODE, $TABLE_PREFIX, $language, $tpl_account,$THIS_BASEPATH;


   if ($action=="signup")
     {
          $dati["username"]="";
          $dati["email"]="";

     }

   // avoid error with js
   $language["DIF_PASSWORDS"]=AddSlashes($language["DIF_PASSWORDS"]);
   $language["INSERT_PASSWORD"]=AddSlashes($language["INSERT_PASSWORD"]);
   $language["USER_PWD_AGAIN"]=AddSlashes($language["USER_PWD_AGAIN"]);
   $language["INSERT_USERNAME"]=AddSlashes($language["INSERT_USERNAME"]);
   $language["ERR_NO_EMAIL"]=AddSlashes($language["ERR_NO_EMAIL"]);
   $language["ERR_NO_EMAIL_AGAIN"]=AddSlashes($language["ERR_NO_EMAIL_AGAIN"]);
   $language["DIF_EMAIL"]=AddSlashes($language["DIF_EMAIL"]);

   $tpl_account->set("language",$language);
   $tpl_account->set("account_action",$action);
   $tpl_account->set("account_form_actionlink",htmlspecialchars("index.php?page=signup&act=$action&returnto=$link"));
   $tpl_account->set("account_uid",$dati["id"]);
   $tpl_account->set("account_returnto",urlencode($link));
   $tpl_account->set("account_IDlanguage",$idlang);
   $tpl_account->set("account_IDstyle",$idstyle);
   $tpl_account->set("account_IDcountry",$idflag);
   $tpl_account->set("account_username",$dati["username"]);
   $tpl_account->set("dati",$dati);
   $tpl_account->set("DEL",$action=="delete",true);
   $tpl_account->set("DISPLAY_FULL",$action=="signup",true);

   if ($action=="del")
      $tpl_account->set("account_from_delete_confirm","<input type=\"submit\" name=\"elimina\" value=\"".$language["FRM_DELETE"]."\" />&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" name=\"elimina\" value=\"".$language["FRM_CANCEL"]."\" />");
   else
      $tpl_account->set("account_from_delete_confirm","<input type=\"submit\" name=\"conferma\" value=\"".$language["FRM_CONFIRM"]."\" />&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"reset\" name=\"annulla\" value=\"".$language["FRM_CANCEL"]."\" />");
   
  $lres=language_list();

   $option="\n<select name=\"language\" size=\"1\">";
   foreach($lres as $langue)
     {
       $option.="\n<option ";
       if ($langue["id"]==$dati["language"])
          $option.="\"selected\" ";
       $option.="value=\"".$langue["id"]."\">".$langue["language"]."</option>";
     }
   $option.="\n</select>";

   $tpl_account->set("account_combo_language",$option);

   $sres=style_list();
   $option="\n<select name=\"style\" size=\"1\">";
   foreach($sres as $style)
     {
       $option.="\n<option ";
       if ($style["id"]==$dati["style"])
          $option.="\"selected\" ";
       $option.="value=\"".$style["id"]."\">".$style["style"]."</option>";
     }
   $option.="\n</select>";

   $tpl_account->set("account_combo_style",$option);

   $fres=flag_list();
   $option="\n<select name=\"flag\" size=\"1\">\n<option value='0'>---</option>";

   $thisip = $_SERVER["REMOTE_ADDR"];
   $remotedns = gethostbyaddr($thisip);

   if ($remotedns != $thisip)
       {
       $remotedns = strtoupper($remotedns);
       preg_match('/^(.+)\.([A-Z]{2,3})$/', $remotedns, $tldm);
       if (isset($tldm[2]))
              $remotedns = mysql_escape_string($tldm[2]);
     }

   foreach($fres as $flag)
    {
        $option.="\n<option ";
            if ($flag["id"]==$dati["flag"] || ($flag["domain"]==$remotedns && $action=="signup"))
              $option.="\"selected\" ";
            $option.="value=\"".$flag["id"]."\">".$flag["name"]."</option>";
    }
   $option.="\n</select>";

   $tpl_account->set("account_combo_country",$option);

   $zone=date('Z',time());
   $daylight=date('I',time())*3600;
   $os=$zone-$daylight;
   if($os!=0){ $timeoff=$os/3600; } else { $timeoff=0; }

   if(!$CURUSER || $CURUSER["uid"]==1)
      $dati["time_offset"]=$timeoff;

   $tres=timezone_list();
   $option="<select name=\"timezone\">";
   foreach($tres as $timezone)
     {
       $option.="\n<option ";
       if ($timezone["difference"]==$dati["time_offset"])
          $option.="selected=\"selected\" ";
       $option.="value=\"".$timezone["difference"]."\">".unesc($timezone["timezone"])."</option>";
     }
   $option.="\n</select>";

   $tpl_account->set("account_combo_timezone",$option);

// -----------------------------
// Captcha hack
// -----------------------------
// if set to use secure code: try to display imagecode
if ($USE_IMAGECODE && $action!="mod")
  {
   if (extension_loaded('gd'))
     {
       $arr = gd_info();
       if ($arr['FreeType Support']==1)
        {
         $p=new ocr_captcha();

         $tpl_account->set("CAPTCHA",true,true);

         $tpl_account->set("account_captcha",$p->display_captcha(true));

         $private=$p->generate_private();
      }
     else
       {
         include("$THIS_BASEPATH/include/security_code.php");
         $scode_index = rand(0, count($security_code) - 1);
         $scode="<input type=\"hidden\" name=\"security_index\" value=\"$scode_index\" />\n";
         $scode.=$security_code[$scode_index]["question"];
         $tpl_account->set("scode_question",$scode);
         $tpl_account->set("CAPTCHA",false,true);
       }
     }
     else
       {
         include("$THIS_BASEPATH/include/security_code.php");
         $scode_index = rand(0, count($security_code) - 1);
         $scode="<input type=\"hidden\" name=\"security_index\" value=\"$scode_index\" />\n";
         $scode.=$security_code[$scode_index]["question"];
         $tpl_account->set("scode_question",$scode);
         $tpl_account->set("CAPTCHA",false,true);
       }
   }
elseif ($action!="mod")
   {
       include("$THIS_BASEPATH/include/security_code.php");
       $scode_index = rand(0, count($security_code) - 1);
       $scode="<input type=\"hidden\" name=\"security_index\" value=\"$scode_index\" />\n";
       $scode.=$security_code[$scode_index]["question"];
       $tpl_account->set("scode_question",$scode);
       // we will request simple operation to user
       $tpl_account->set("CAPTCHA",false,true);
  }
// -----------------------------
// Captcha hack
// -----------------------------
}

function aggiungiutente() {

global $SITENAME,$SITEEMAIL,$BASEURL,$VALIDATION,$USERLANG,$USE_IMAGECODE, $TABLE_PREFIX, $XBTT_USE, $language,$THIS_BASEPATH, $FORUMLINK, $db_prefix;

$utente=mysql_escape_string($_POST["user"]);
$pwd=mysql_escape_string($_POST["pwd"]);
$pwd1=mysql_escape_string($_POST["pwd1"]);
$email=mysql_escape_string($_POST["email"]);
$idlangue=intval($_POST["language"]);
$idstyle=intval($_POST["style"]);
$idflag=intval($_POST["flag"]);
$timezone=intval($_POST["timezone"]);

if (strtoupper($utente) == strtoupper("Guest")) {
        err_msg($language["ERROR"],$language["ERR_GUEST_EXISTS"]);
        stdfoot();
        exit;
        }

if ($pwd != $pwd1) {
    err_msg($language["ERROR"],$language["DIF_PASSWORDS"]);
    stdfoot();
    exit;
    }

if ($VALIDATION=="none")
   $idlevel=3;
else
   $idlevel=2;
# Create Random number
$floor = 100000;
$ceiling = 999999;
srand((double)microtime()*1000000);
$random = rand($floor, $ceiling);

if ($utente=="" || $pwd=="" || $email=="") {
   return -1;
   exit;
}

$res=do_sqlquery("SELECT email FROM {$TABLE_PREFIX}users WHERE email='$email'");
if (mysql_num_rows($res)>0)
   {
   return -2;
   exit;
}
// valid email check - by vibes
$regex = "^[_+a-z0-9-]+(\.[_+a-z0-9-]+)*"
                ."@[a-z0-9-]+(\.[a-z0-9-]{1,})*"
                ."\.([a-z]{2,}){1}$";
if(!eregi($regex,$email))
   {
   return -3;
   exit;
}
// valid email check end

// duplicate username
$res=do_sqlquery("SELECT username FROM {$TABLE_PREFIX}users WHERE username='$utente'");
if (mysql_num_rows($res)>0)
   {
   return -4;
   exit;
}
// duplicate username

if (strpos(mysql_escape_string($utente), " ")==true)
   {
   return -7;
   exit;
}
if ($USE_IMAGECODE)
{
  if (extension_loaded('gd'))
    {
     $arr = gd_info();
     if ($arr['FreeType Support']==1)
      {
        $public=$_POST['public_key'];
        $private=$_POST['private_key'];

          $p=new ocr_captcha();

          if ($p->check_captcha($public,$private) != true)
              {
              err_msg($language["ERROR"],$language["ERR_IMAGE_CODE"]);
              stdfoot();
              exit;
          }
       }
       else
         {
           include("$THIS_BASEPATH/include/security_code.php");
           $scode_index=intval($_POST["security_index"]);
           if ($security_code[$scode_index]["answer"]!=$_POST["scode_answer"])
              {
              err_msg($language["ERROR"],$language["ERR_IMAGE_CODE"]);
              stdfoot();
              exit;
            }
         }
    }
     else
       {
         include("$THIS_BASEPATH/include/security_code.php");
         $scode_index=intval($_POST["security_index"]);
         if ($security_code[$scode_index]["answer"]!=$_POST["scode_answer"])
            {
            err_msg($language["ERROR"],$language["ERR_IMAGE_CODE"]);
            stdfoot();
            exit;
          }
       }
}
else
  {
    include("$THIS_BASEPATH/include/security_code.php");
    $scode_index=intval($_POST["security_index"]);
    if ($security_code[$scode_index]["answer"]!=$_POST["scode_answer"])
       {
       err_msg($language["ERROR"],$language["ERR_IMAGE_CODE"]);
       stdfoot();
       exit;
     }
  }

$bannedchar=array("\\", "/", ":", "*", "?", "\"", "@", "$", "'", "`", ",", ";", ".", "<", ">", "!", "£", "%", "^", "&", "(", ")", "+", "=", "#", "~");
if (straipos(mysql_escape_string($utente), $bannedchar)==true)
   {
   return -8;
   exit;
}

if(strlen(mysql_real_escape_string($pwd))<4)
   {
   return -9;
   exit;
}

$pid=md5(uniqid(rand(),true));
do_sqlquery("INSERT INTO {$TABLE_PREFIX}users (username, password, random, id_level, email, style, language, flag, joined, lastconnect, pid, time_offset) VALUES ('$utente', '" . md5($pwd) . "', $random, $idlevel, '$email', $idstyle, $idlangue, $idflag, NOW(), NOW(),'$pid', '".$timezone."')",true);

$newuid=mysql_insert_id();

if ($FORUMLINK=="smf")
{
    $smfpass=smf_passgen($utente, $pwd);
    $flevel=$idlevel+10;

    do_sqlquery("INSERT INTO {$db_prefix}members (memberName, dateRegistered, ID_GROUP, realName, passwd, emailAddress, memberIP, memberIP2, is_activated, passwordSalt) VALUES ('$utente', UNIX_TIMESTAMP(), $flevel, '$utente', '$smfpass[0]', '$email', '".getip()."', '".getip()."', 1, '$smfpass[1]')");
    $fid=mysql_insert_id();
    do_sqlquery("UPDATE `{$db_prefix}settings` SET `value` = $fid WHERE `variable` = 'latestMember'");
    do_sqlquery("UPDATE `{$db_prefix}settings` SET `value` = '$utente' WHERE `variable` = 'latestRealName'");
    do_sqlquery("UPDATE `{$db_prefix}settings` SET `value` = UNIX_TIMESTAMP() WHERE `variable` = 'memberlist_updated'");
    do_sqlquery("UPDATE {$TABLE_PREFIX}users SET smf_fid=$fid WHERE id=$newuid");
}

// xbt
if ($XBTT_USE)
   {
   $resin=do_sqlquery("INSERT INTO xbt_users (uid, name, torrent_pass) VALUES ($newuid, '$utente','$pid')");
   }
if ($VALIDATION=="user")
   {
   ini_set("sendmail_from","");
   if (mysql_errno()==0)
     {
      send_mail($email,$language["ACCOUNT_CONFIRM"],$language["ACCOUNT_MSG"]."\n\n".$BASEURL."/index.php?page=account&act=confirm&confirm=$random&language=$idlangue");
      write_log("Signup new user $utente ($email)","add");
      }
   else
       die(mysql_error());
   }

return mysql_errno();
}

?>