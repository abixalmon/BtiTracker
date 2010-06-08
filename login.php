<?php
/////////////////////////////////////////////////////////////////////////////////////
// xbtit - Bittorrent tracker/frontend
//
// Copyright (C) 2004 - 2007  Btiteam
//
//    This file is part of xbtit.
//
// Redistribution and use in source and binary forms, with or without modification,
// are permitted provided that the following conditions are met:
//
//   1. Redistributions of source code must retain the above copyright notice,
//      this list of conditions and the following disclaimer.
//   2. Redistributions in binary form must reproduce the above copyright notice,
//      this list of conditions and the following disclaimer in the documentation
//      and/or other materials provided with the distribution.
//   3. The name of the author may not be used to endorse or promote products
//      derived from this software without specific prior written permission.
//
// THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR IMPLIED
// WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
// MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
// IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
// SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED
// TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
// PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
// LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
// NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE,
// EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
//
////////////////////////////////////////////////////////////////////////////////////

if (!defined("IN_BTIT"))
      die("non direct access!");


require_once(load_language("lang_login.php"));

function login() {
 
   global $language, $logintpl;

    $logintpl->set("language",$language);
    $language["INSERT_USERNAME"]=AddSlashes($language["INSERT_USERNAME"]);
    $language["INSERT_PASSWORD"]=AddSlashes($language["INSERT_PASSWORD"]);

    $login=array();
    $login["action"]="index.php?page=login&amp;returnto=".urlencode("index.php")."";
    $login["username"]=$user;
    $login["create"]="index.php?page=signup";
    $login["recover"]="index.php?page=recover";
    $logintpl->set("login",$login);
}


$logintpl=new bTemplate();


if (!$CURUSER || $CURUSER["uid"]==1) {


if (isset($_POST["uid"]) && $_POST["uid"])
  $user=$_POST["uid"];
else $user='';
if (isset($_POST["pwd"]) && $_POST["pwd"])
  $pwd=$_POST["pwd"];
else $pwd='';

if (isset($_POST["uid"]) && isset($_POST["pwd"]))
  {
    if ($FORUMLINK=="smf")
        $smf_pass = sha1(strtolower($user) . $pwd);
        $res = do_sqlquery("SELECT u.id, u.random, u.password".(($FORUMLINK=="smf") ? ", u.smf_fid, s.passwd, s.passwordSalt" : "")." FROM {$TABLE_PREFIX}users u ".(($FORUMLINK=="smf") ? "LEFT JOIN {$db_prefix}members s ON u.smf_fid=s.ID_MEMBER" : "" )." WHERE u.username ='".AddSlashes($user)."'",true);
        $row = mysql_fetch_array($res);

    if (!$row)
        {
          $logintpl->set("FALSE_USER",true,true);
          $logintpl->set("FALSE_PASSWORD",false,true);
          $logintpl->set("login_username_incorrect",$language["ERR_USERNAME_INCORRECT"]);
          login();
        }
    elseif (md5($row["random"].$row["password"].$row["random"]) != md5($row["random"].md5($pwd).$row["random"]))
        {
          $logintpl->set("FALSE_USER",false,true);
          $logintpl->set("FALSE_PASSWORD",true,true);
          $logintpl->set("login_password_incorrect",$language["ERR_PASSWORD_INCORRECT"]);
          login();
        }
    else
      {
       
        logincookie($row["id"],md5($row["random"].$row["password"].$row["random"]));
        if ($FORUMLINK=="smf" && $smf_pass==$row["passwd"])
            set_smf_cookie($row["smf_fid"], $row["passwd"], $row["passwordSalt"]);
        elseif ($FORUMLINK=="smf" && $row["password"]==$row["passwd"])
        {
            $salt=substr(md5(rand()), 0, 4);
            @mysql_query("UPDATE {$db_prefix}members SET passwd='$smf_pass', passwordSalt='$salt' WHERE ID_MEMBER=".$row["smf_fid"]);
            set_smf_cookie($row["smf_fid"], $smf_pass, $salt);
        }
        if (isset($_GET["returnto"]))
           $url=urldecode($_GET["returnto"]);
        else
            $url="index.php";
        redirect($url);
        die();
      }
  }

else
  {
    $logintpl->set("FALSE_USER",false,true);
    $logintpl->set("FALSE_PASSWORD",false,true);
    login();
  }






}
else {

  if (isset($_GET["returnto"]))
     $url=urldecode($_GET["returnto"]);
  else
      $url="index.php";
  redirect($url);
  die();
}
?>