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
    $res = do_sqlquery("SELECT u.id, u.random, u.password".(($FORUMLINK=="smf") ? ", u.smf_fid, s.passwd, s.passwordSalt" : "")." FROM {$TABLE_PREFIX}users u ".(($FORUMLINK=="smf") ? "LEFT JOIN {$db_prefix}members s ON u.smf_fid=s.ID_MEMBER" : "" )." WHERE u.username ='".AddSlashes($user)."'")
        or die(mysql_error());
    $row = mysql_fetch_array($res);

    if (!$row)
        {
          $logintpl->set("FALSE_USER",true,true);
          $logintpl->set("FALSE_PASSWORD",false,true);
          $logintpl->set("login_username_incorrent",$language["ERR_USERNAME_INCORRECT"]);
          login();
        }
    elseif (md5($row["random"].$row["password"].$row["random"]) != md5($row["random"].md5($pwd).$row["random"]))
        {
          $logintpl->set("FALSE_USER",false,true);
          $logintpl->set("FALSE_PASSWORD",true,true);
          $logintpl->set("login_password_incorrent",$language["ERR_PASSWORD_INCORRECT"]);
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