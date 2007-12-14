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


require_once(load_language("lang_recover.php"));

if (isset($_GET["act"])) $act=$_GET["act"];
  else $act="recover";



if ($act == "takerecover")
{
  $email = trim($_POST["email"]);
  if (!$email)
    stderr($language["ERROR"],$language["ERR_NO_EMAIL"]);

  $res = do_sqlquery("SELECT id, email FROM {$TABLE_PREFIX}users WHERE email=".sqlesc($email)." LIMIT 1",true);
  $arr = mysql_fetch_assoc($res) or stderr($language["ERROR"],$language["ERR_EMAIL_NOT_FOUND_1"]." <b>$email</b> ".$language["ERR_EMAIL_NOT_FOUND_2"]);
if ($USE_IMAGECODE)
{
  if (extension_loaded('gd'))
    {
     $arr_gd = gd_info();
     if ($arr_gd['FreeType Support']==1)
      {
        $public=$_POST['public_key'];
        $private=$_POST['private_key'];

          $p=new ocr_captcha();

          if ($p->check_captcha($public,$private) != true)
              {
              stderr($language["ERROR"],$language["ERR_IMAGE_CODE"]);
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

$floor = 100000;
$ceiling = 999999;
srand((double)microtime()*1000000);
$random = rand($floor, $ceiling);

do_sqlquery("UPDATE {$TABLE_PREFIX}users SET random='$random' WHERE id='".$arr["id"]."'") or sqlerr();
if (mysql_affected_rows()==0)
    stderr($language["ERROR"],"".$language["ERR_DB_ERR"].",".$arr["id"].",".$email.",".$random."");

$user_temp_id = $arr["id"];
$user_temp_email = $email;
/*
  $body = PASSWORD_REQUEST_MAIL;
*/
/*
$body=<<<EOD
Someone, hopefully you, requested that the password for the account
associated with this email address ($email) be reset.

The request originated from {$_SERVER["REMOTE_ADDR"]}.

If you did not do this ignore this email. Please do not reply.


Should you wish to confirm this request, please follow this link:

$BASEURL/index.php?page=recover&act=generate&id=$user_temp_id&random=$random


After you do this, your password will be reset and emailed back
to you.

--
$SITENAME
EOD;
*/
$body=sprintf($language["RECOVER_EMAIL_1"],$email,$_SERVER["REMOTE_ADDR"],"$BASEURL/index.php?page=recover&act=generate&id=$user_temp_id&random=$random",$SITENAME);
  send_mail( $arr["email"], "$SITENAME ".$language["PASS_RESET_CONF"], $body) or stderr($language["ERROR"],$language["ERR_SEND_EMAIL"]);
  success_msg($language["SUCCESS"],$language["SUC_SEND_EMAIL"]." <b>$email</b>.\n".$language["SUC_SEND_EMAIL_2"]);
  $tpl->set("main_footer",bottom_menu()."<br />\n");
  $tpl->set("btit_version",print_version());
  echo $tpl->fetch(load_template("main.tpl"));
  die();

}
elseif ($act == "generate")
{

    $id = intval(0 + $_GET["id"]);
    $random = intval($_GET["random"]);

if (!$id || !$random || empty($random) || $random==0)
    stderr($language["ERROR"],$language["ERR_UPDATE_USER"]);

$res = do_sqlquery("SELECT username, email, random".(($GLOBALS["FORUMLINK"]=="smf") ? ", smf_fid" : "")." FROM {$TABLE_PREFIX}users WHERE id = $id");
$arr = mysql_fetch_array($res) or httperr();

if ($random!=$arr["random"])
    stderr($language["ERROR"],$language["ERR_UPDATE_USER"]);

    $email = $arr["email"];

    // generate new password;
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

    $newpassword = "";
    for ($i = 0; $i < 10; $i++)
      $newpassword .= $chars[mt_rand(0, strlen($chars) - 1)];

    do_sqlquery("UPDATE {$TABLE_PREFIX}users SET password='".md5($newpassword)."' WHERE id=$id AND random=$random");

    if (!mysql_affected_rows())
        stderr($language["ERROR"],$language["ERR_UPDATE_USER"]);

    if($GLOBALS["FORUMLINK"]=="smf")
    {
        $passhash=smf_passgen($arr["username"], $newpassword);
        do_sqlquery("UPDATE {$db_prefix}members SET passwd='$passhash[0]', passwordSalt='$passhash[1]' WHERE ID_MEMBER=".$arr["smf_fid"]);
    }
/*
  $body = <<<EOD
As per your request we have generated a new password for your account.

Here is the information we now have on file for this account:

    User name: {$arr["username"]}
    Password:  $newpassword

You may login at $BASEURL/index.php?page=login

--
$SITENAME
EOD;
*/
$body=sprintf($language["RECOVER_EMAIL_2"],$arr["username"],$newpassword,"$BASEURL/index.php?page=login",$SITENAME);

  send_mail($email, "$SITENAME ".$language["ACCOUNT_DETAILS"], $body) or stderr($language["ERROR"],$language["ERR_SEND_EMAIL"]);
  redirect("index.php?page=recover&act=recover_ok&id=$id&random=$random");
  die();
}
elseif ($act=="recover_ok")
{
  $id = intval(0 + $_GET["id"]);
  $random = intval($_GET["random"]);
                          
  if (!$id || !$random || empty($random) || $random==0)
       stderr($language["ERROR"],$language["ERR_UPDATE_USER"]);

  $res = do_sqlquery("SELECT username, email, random".(($GLOBALS["FORUMLINK"]=="smf") ? ", smf_fid" : "")." FROM {$TABLE_PREFIX}users WHERE id = $id");
  $arr = mysql_fetch_array($res) or httperr();

  if ($random!=$arr["random"])
       stderr($language["ERROR"],$language["ERR_UPDATE_USER"]);

  $email = $arr["email"];

  success_msg($language["SUCCESS"],$language["SUC_SEND_EMAIL"]." <b>$email</b>.\n".$language["SUC_SEND_EMAIL_2"]);

  $tpl->set("main_footer",bottom_menu()."<br />\n");
  $tpl->set("btit_version",print_version());
  echo $tpl->fetch(load_template("main.tpl"));
  die();


}
elseif ($act == "recover");
{
    $recovertpl=new bTemplate();
    global $language, $recovertpl;
    $recovertpl->set("language",$language);
    $recover=array();
    $recover["action"]="index.php?page=recover&amp;act=takerecover";
    $recovertpl->set("recover",$recover);

    if ($USE_IMAGECODE)
      {
       if (extension_loaded('gd'))
         {
           $arr = gd_info();
           if ($arr['FreeType Support']==1)
             {
              $p=new ocr_captcha();
              $recovertpl->set("CAPTCHA",true,true);
              $recovertpl->set("recover_captcha",$p->display_captcha(true));
              $private=$p->generate_private();
               }
           else
             {
               include("$THIS_BASEPATH/include/security_code.php");
               $scode_index = rand(0, count($security_code) - 1);
               $scode="<input type=\"hidden\" name=\"security_index\" value=\"$scode_index\" />\n";
               $scode.=$security_code[$scode_index]["question"];
               $recovertpl->set("scode_question",$scode);
               $recovertpl->set("CAPTCHA",false,true);
             }
         }
         else
           {
             include("$THIS_BASEPATH/include/security_code.php");
             $scode_index = rand(0, count($security_code) - 1);
             $scode="<input type=\"hidden\" name=\"security_index\" value=\"$scode_index\" />\n";
             $scode.=$security_code[$scode_index]["question"];
             $recovertpl->set("scode_question",$scode);
             $recovertpl->set("CAPTCHA",false,true);
           }
       }
    else
      {
        include("$THIS_BASEPATH/include/security_code.php");
        $scode_index = rand(0, count($security_code) - 1);
        $scode="<input type=\"hidden\" name=\"security_index\" value=\"$scode_index\" />\n";
        $scode.=$security_code[$scode_index]["question"];
        $recovertpl->set("scode_question",$scode);
        $recovertpl->set("CAPTCHA",false,true);
      }
}

?>