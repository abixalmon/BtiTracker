<?php
/////////////////////////////////////////////////////////////////////////////////////
// xbtit - Bittorrent tracker/frontend
//
// Copyright (C) 2004 - 2011  Btiteam
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

  /*################################################################
  #
  #         Ajax MySQL shoutbox for btit
  #         Version  1.0
  #         Author : miskotes
  #         Created: 11/07/2007
  #         Contact: miskotes [at] yahoo.co.uk
  #         Website: YU-Corner.com
  #         Credits: linuxuser.at, plasticshore.com
  #
  ################################################################*/

# emulate register_globals on
if (!ini_get('register_globals')) {
        extract($_POST, EXTR_SKIP);
}
$name = $n; # name from the form
$text = $c; # comment from the form
$uid = (int)$u;  # userid from the form


session_name("xbtit");
session_start();

include("../include/settings.php");
include("../include/common.php");

mysql_select_db($database, mysql_connect($dbhost,$dbuser,$dbpass));

$secsui_res=mysql_query("SELECT * FROM `{$TABLE_PREFIX}settings` WHERE `key` LIKE('secsui_%')");
while($secsui_arr=mysql_fetch_assoc($secsui_res))
{
        $secsui[$secsui_arr["key"]]=$secsui_arr["value"];
}

if($secsui["secsui_cookie_type"]==1)
{
    $cookie_id=(int)0+$_COOKIE["uid"];
    $cookie_hash=$_COOKIE["pass"];
}
elseif($secsui["secsui_cookie_type"]==2)
{
    $cookie_name=((isset($secsui["secsui_cookie_name"]) && !empty($secsui["secsui_cookie_name"]))?$secsui["secsui_cookie_name"]:"xbtitLoginCookie");
    $cookie_array=unserialize($_COOKIE[$cookie_name]);
    $cookie_id=(int)0+$cookie_array["id"];
    $cookie_hash=$cookie_array["hash"];
    unset($cookie_array);
}
elseif($secsui["secsui_cookie_type"]==3)
{
    $cookie_array=unserialize($_SESSION["login_cookie"]);
    $cookie_id=(int)0+$cookie_array["id"];
    $cookie_hash=$cookie_array["hash"];
    unset($cookie_array);
}
else
{
    $cookie_id=1;
    $cookie_hash="";
}
if($cookie_id!=$uid)
{
    // select first owner (default id_level=8) from users table
    $ra=mysql_fetch_assoc(mysql_query("SELECT `id` FROM `{$TABLE_PREFIX}users` WHERE `id_level`=8 ORDER BY `id` LIMIT 1"));
    $admin_pm_id=$ra['id'];

    $res=mysql_query("SELECT `username`, `password`, `random`, `salt` FROM `{$TABLE_PREFIX}users` WHERE `id`=".$cookie_id);
    $row=mysql_fetch_assoc($res);

    if($secsui["secsui_cookie_type"]==1)
    {
        $user_hash=md5($row["random"].$row["password"].$row["random"]);
    }
    elseif($secsui["secsui_cookie_type"]==2  || $secsui["secsui_cookie_type"]==3)
    {
        $cookie_items=explode(",", $secsui["secsui_cookie_items"]);
        $cookie_string="";

        foreach($cookie_items as $ci_value)
        {
            $ci_exp=explode("-",$ci_value);
            if($ci_exp[0]==8)
            {
                $ci_exp2=explode("[+]", $ci_exp[1]);
                if($ci_exp2[0]==1)
                {
                    $ip_parts=explode(".", getip());

                    if($ci_exp2[1]==1)
                        $cookie_string.=$ip_parts[0]."-";
                    if($ci_exp2[1]==2)
                        $cookie_string.=$ip_parts[1]."-";
                    if($ci_exp2[1]==3)
                        $cookie_string.=$ip_parts[2]."-";
                    if($ci_exp2[1]==4)
                        $cookie_string.=$ip_parts[3]."-";
                    if($ci_exp2[1]==5)
                        $cookie_string.=$ip_parts[0].".".$ip_parts[1]."-";
                    if($ci_exp2[1]==6)
                        $cookie_string.=$ip_parts[1].".".$ip_parts[2]."-";
                    if($ci_exp2[1]==7)
                        $cookie_string.=$ip_parts[2].".".$ip_parts[3]."-";
                    if($ci_exp2[1]==8)
                        $cookie_string.=$ip_parts[0].".".$ip_parts[2]."-";
                    if($ci_exp2[1]==9)
                        $cookie_string.=$ip_parts[0].".".$ip_parts[3]."-";
                    if($ci_exp2[1]==10)
                        $cookie_string.=$ip_parts[1].".".$ip_parts[3]."-";
                    if($ci_exp2[1]==11)
                        $cookie_string.=$ip_parts[0].".".$ip_parts[1].".".$ip_parts[2]."-";
                    if($ci_exp2[1]==12)
                        $cookie_string.=$ip_parts[1].".".$ip_parts[2].".".$ip_parts[3]."-";
                    if($ci_exp2[1]==13)
                        $cookie_string.=$ip_parts[0].".".$ip_parts[1].".".$ip_parts[2].".".$ip_parts[3]."-";

                    unset($ci_exp2);
                }
            }
            else
            {
                if($ci_exp[0]==1 && $ci_exp[1]==1)
                {
                    $cookie_string.=$cookie_id."-";
                }
                if($ci_exp[0]==2 && $ci_exp[1]==1)
                {
                    $cookie_string.=$row["password"]."-";
                }
                if($ci_exp[0]==3 && $ci_exp[1]==1)
                {
                    $cookie_string.=$row["random"]."-";
                }
                if($ci_exp[0]==4 && $ci_exp[1]==1)
                {
                    $cookie_string.=strtolower($row["username"])."-";
                }
                if($ci_exp[0]==5 && $ci_exp[1]==1)
                {
                    $cookie_string.=$row["salt"]."-";
                }
                if($ci_exp[0]==6 && $ci_exp[1]==1)
                {
                    $cookie_string.=$_SERVER["HTTP_USER_AGENT"]."-";
                }
                if($ci_exp[0]==7 && $ci_exp[1]==1)
                {
                    $cookie_string.=$_SERVER["HTTP_ACCEPT_LANGUAGE"]."-";
                }
            }
            unset($ci_exp);
        }
        $user_hash=sha1(trim($cookie_string, "-"));
    }
    if($cookie_hash!=$user_hash)
    {
        $ip=getip();
        $name="Hacker [$ip]";
        $uid=1;
        $res=mysql_query("SELECT `id`, `username` FROM `{$TABLE_PREFIX}users` WHERE `cip`='$ip' ORDER BY `id` ASC");
        if(@mysql_num_rows($res)>0)
        {
            $subject="Shoutbox hack attempt!";
            $msg="Someone with the IP Address $ip hacked the shoutbox on ".date('l jS F Y \a\\t g:ia', time()).", here is a list of potential members to check:\n\n";
            while($row=mysql_fetch_assoc($res))
            {
                $msg.="[url=$BASEURL/index.php?page=userdetails&id=".$row["id"]."]".$row["username"]."[/url]\n";
            }
            $row1=mysql_fetch_assoc(mysql_query("SELECT `value` FROM `{$TABLE_PREFIX}settings` WHERE `key`='forum'"));
            $FORUMLINK=$row1["value"];
            send_pm(0,$admin_pm_id,sqlesc($subject),sqlesc($msg));
        }
    }
    else
    {
        $name=$row["username"];
        $uid=$cookie_id;
    }
    $text="[color=red][b]I am a hacker who deserves to be banned![/b][/color] :axe:";  
}


# some weird conversion of the data inputed
$name = str_replace("\'","'",$name);
$name = str_replace("'","\'",$name);
$text = str_replace("\'","'",$text);
$text = str_replace("'","\'",$text);
$text = str_replace("---"," - - ",$text);

$name = str_replace("---"," - - ",$name);

# the message is cut of after 500 letters
if (strlen($text) > 500) { $text = substr($text,0,500); }

# to allow for linebreaks a space is inserted every 50 letters
//$text = preg_replace("/([^\s]{50})/","$1 ",$text);


/*
# the name is shortened to 30 letters
if (strlen($name) > 30) {
    $name = substr($name, 0,30); 
}
*/

require_once("conn.php");

# only if a name and a message have been provided the information is added to the db
if ($name != '' && $text != '' && $uid !='') {
    addData($name,$text,$uid); # adds new data to the database
    getID(50); # some database maintenance
}

# adds new data to the database
function addData($name,$text,$uid) {
  include("../include/settings.php");   # getting table prefix
  include("../include/config.php");
  $now = time();
    $sql = "INSERT INTO {$TABLE_PREFIX}chat (time,name,text,uid) VALUES ('".$now."','".$name."','".$text."','".$uid."')";
    $conn = getDBConnection();
    if($GLOBALS['charset']=="UTF-8" && function_exists('mysql_set_charset'))
        mysql_set_charset('utf8',$conn);

    $results = mysql_query($sql, $conn);
    if (!$results || empty($results)) {
        # echo 'There was an error creating the entry';
        end;
    }
}

# returns the id of a message at a certain position
function getID($position) {
  include("../include/settings.php");   # getting table prefix
  
    $sql =  "SELECT * FROM {$TABLE_PREFIX}chat ORDER BY id DESC LIMIT ".$position.",1";
    $conn = getDBConnection(); 
    $results = mysql_query($sql, $conn);
    if (!$results || empty($results)) {
        # echo 'There was an error creating the entry';
        end;
    }
    while ($row = mysql_fetch_array($results)) {
        $id = $row[0]; # the result is converted from the db setup (see conn.php)
    }
    if ($id) {
        deleteEntries($id); # deletes all message prior to a certain id
    }
}

# deletes all message prior to a certain id
function deleteEntries($id) {
  include("../include/settings.php");   # getting table prefix
  
    $sql =  "DELETE FROM {$TABLE_PREFIX}chat WHERE id < ".$id;
    $conn = getDBConnection();
    $results = mysql_query($sql, $conn);
    if (!$results || empty($results)) {
        # echo 'There was an error deletig the entries';
        end;
    }
}
exit; # exits the script
?>