<?php

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
   
    if ($GLOBALS["FORUMLINK"]=="smf")
    {
        $language2=$language;
        require(dirname(__FILE__)."/smf/Settings.php");
        $language=$language2;
        $smf_pass = sha1(strtolower($user) . $pwd);
    }
    $res = do_sqlquery("SELECT u.id, u.random, u.password".(($GLOBALS["FORUMLINK"]=="smf") ? ", u.smf_fid, s.passwd, s.passwordSalt" : "")." FROM {$TABLE_PREFIX}users u ".(($GLOBALS["FORUMLINK"]=="smf") ? "LEFT JOIN {$db_prefix}members s ON u.smf_fid=s.ID_MEMBER" : "" )." WHERE u.username ='".AddSlashes($user)."'")
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
        if ($GLOBALS["FORUMLINK"]=="smf" && $smf_pass==$row["passwd"])
            set_smf_cookie($row["smf_fid"], $row["passwd"], $row["passwordSalt"]);
        elseif ($GLOBALS["FORUMLINK"]=="smf" && $row["password"]==$row["passwd"])
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
