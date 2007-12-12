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

define("IN_BTIT",true);


$THIS_BASEPATH=dirname(__FILE__);
require("$THIS_BASEPATH/include/functions.php");

dbconn();



// get user's style
$resheet=mysql_query("SELECT * FROM {$TABLE_PREFIX}style where id=".$CURUSER["style"]."") or die(mysql_error());
if (!$resheet)
   {

   $STYLEPATH="$THIS_BASEPATH/style/xbtit_default";
   $STYLEURL="$BASEURL/style/xbtit_default";
   $style="$BASEURL/style/xbtit_default/main.css";
   }
else
    {
        $resstyle=mysql_fetch_array($resheet);
        $STYLEPATH="$THIS_BASEPATH/".$resstyle["style_url"];
        $style="$BASEURL/".$resstyle["style_url"]."/main.css";
        $STYLEURL="$BASEURL/".$resstyle["style_url"];
    }


$idlang=intval($_GET["language"]);

// getting user language
if ($idlang==0)
   $reslang=mysql_query("SELECT * FROM {$TABLE_PREFIX}language WHERE id=".$CURUSER["language"]) or die(mysql_error());
else
   $reslang=mysql_query("SELECT * FROM {$TABLE_PREFIX}language WHERE id=$idlang") or die(mysql_error());

if (!$reslang)
   {
   $USERLANG="$THIS_BASEPATH/language/english";
   }
else
    {
        $rlang=mysql_fetch_array($reslang);
        $USERLANG="$THIS_BASEPATH/".$rlang["language_url"];
    }

if (!file_exists($USERLANG))
    {
    die("Error!<br />\nMissing Language!<br />\n");
}


require_once(load_language("lang_main.php"));

if (!empty($language["charset"]))
   $GLOBALS["charset"]=$language["charset"];

if (isset($_GET['action']) && $_GET['action'])
            $action=$_GET['action'];
else $action = '';;


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php echo (!empty($language["rtl"])?"<html dir=\"".$language["rtl"]."\">\n":"<html>\n"); ?>
  <head>
  <title>Search User</title>
  <meta http-equiv="content-type" content="text/html; charset=<?php echo $GLOBALS["charset"] ?>/>" />
  <link rel="stylesheet" href="<?php echo $style; ?>" type="text/css" />
  </head>
  <body>
<?php

if ($action!="find")
   {
?>
<form action="searchusers.php?action=find" name="users" method="post">
<div align="center">
  <table class="lista">
  <tr>
     <td class="header"><?php echo $language["USER_NAME"];?>:</td>
     <td class="lista"><input type="text" name="user" size="40" maxlength="40" /></td>
     <td class="lista"><input type="submit" name="confirm" value="Search" /></td>
  </tr>
  </table>
</div>
</form>
<?php
}
else
{
  $res=mysql_query("SELECT username FROM {$TABLE_PREFIX}users WHERE id>1 AND username LIKE '%".mysql_escape_string($_POST["user"])."%' ORDER BY username") or die(mysql_error());
  if (!$res or mysql_num_rows($res)==0)
     {
         print("<center>".$language["NO_USERS_FOUND"]."!<br />");
         print("<a href=searchusers.php>".$language["RETRY"]."</a></center>");
     }
  else {
?>
<script type="text/javascript">

function SendIT(){
    window.opener.document.forms['edit'].elements['receiver'].value = document.forms['result'].elements['name'].options[document.forms['result'].elements['name'].options.selectedIndex].value;
    window.close();
}
</script>

<div align="center">
  <form action="searchusers.php?action=find" name="result" method="post">
  <table class="lista">
  <tr>
     <td class="header"><?php print($language["USER_NAME"]);?>:</td>
<?php
     print("\n<td class=\"lista\">
     <select name=\"name\" size=\"1\">");
     while($result=mysql_fetch_array($res))
         print("\n<option value=\"".$result["username"]."\">".$result["username"]."</option>");
     print("\n</select>\n</td>");
     print("\n<td class=\"lista\"><input type=\"button\" name=\"confirm\" onclick=\"javascript:SendIT();\" value=\"".$language["FRM_CONFIRM"]."\" /></td>");
?>
  </tr>
  </table>
  </form>
</div>
<?php
   }
}
print("\n<br />\n<div align=\"center\"><a href=\"javascript: window.close()\">".$language["CLOSE"]."</a></div>");
print("</body>\n</html>\n");
?>