<?php
global $CURUSER;

  if (isset($CURUSER) && $CURUSER && $CURUSER["uid"]>1)
  {
  print("<form name=\"jump1\" action=\"index.php\" method=\"post\">\n");
?>
<table class="lista" cellpadding="2" cellspacing="0" width="100%">
<tr>
<?php
$style=style_list();
$langue=language_list();

if ($XBTT_USE)
   {
    $udownloaded="u.downloaded+IFNULL(x.downloaded,0)";
    $uuploaded="u.uploaded+IFNULL(x.uploaded,0)";
    $utables="{$TABLE_PREFIX}users u LEFT JOIN xbt_users x ON x.uid=u.id";
   }
else
    {
    $udownloaded="u.downloaded";
    $uuploaded="u.uploaded";
    $utables="{$TABLE_PREFIX}users u";
    }


$resuser=do_sqlquery("SELECT $udownloaded as downloaded, $uuploaded as uploaded FROM $utables WHERE u.id=".$CURUSER["uid"]);
$rowuser= mysql_fetch_assoc($resuser);
//print("<td class=lista align=center>".WELCOME_BACK." ".$CURUSER['username']." (<a href=logout.php>".LOGOUT."</a>)</td>\n");
print("<td class=\"lista\" align=\"center\">".$language["USER_LEVEL"].": ".$CURUSER["level"]."</td>\n");
print("<td class=\"green\" align=\"center\">&uarr;&nbsp;".makesize($rowuser['uploaded']));
print("</td><td class=\"red\" align=\"center\">&darr;&nbsp;".makesize($rowuser['downloaded']));
print("</td><td class=\"lista\" align=\"center\">(SR ".($rowuser['downloaded']>0?number_format($rowuser['uploaded']/$rowuser['downloaded'],2):"---").")</td>\n");
if ($CURUSER["admin_access"]=="yes")
   print("\n<td align=\"center\" class=\"lista\"><a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."\">".$language["MNU_ADMINCP"]."</a></td>\n");

print("<td class=\"lista\" align=\"center\"><a href=\"index.php?page=usercp&amp;uid=".$CURUSER["uid"]."\">".$language["USER_CP"]."</a></td>\n");

$resmail=do_sqlquery("SELECT COUNT(*) FROM {$TABLE_PREFIX}messages WHERE readed='no' AND receiver=$CURUSER[uid]");
if ($resmail && mysql_num_rows($resmail)>0)
   {
    $mail=mysql_fetch_row($resmail);
    if ($mail[0]>0)
       print("<td class=\"lista\" align=\"center\"><a href=\"index.php?page=usercp&amp;uid=".$CURUSER["uid"]."&amp;do=pm&amp;action=list\">".$language["MAILBOX"]."</a> (<font color=\"#FF0000\"><b>$mail[0]</b></font>)</td>\n");
    else
        print("<td class=\"lista\" align=\"center\"><a href=\"index.php?page=usercp&amp;uid=".$CURUSER["uid"]."&amp;do=pm&amp;action=list\">".$language["MAILBOX"]."</a></td>\n");
   }
else
    print("<td class=\"lista\" align=\"center\"><a href=\"index.php?page=usercp&amp;uid=".$CURUSER["uid"]."&amp;do=pm&amp;action=list\">".$language["MAILBOX"]."</a></td>\n");

print("\n<td class=\"lista\"><select name=\"style\" size=\"1\" onchange=\"location=document.jump1.style.options[document.jump1.style.selectedIndex].value\">");
foreach($style as $a)
               {
               print("<option ");
               if ($a["id"]==$CURUSER["style"])
                  print("selected=\"selected\"");
               print(" value=\"account_change.php?style=".$a["id"]."&amp;returnto=".urlencode($_SERVER['REQUEST_URI'])."\">".$a["style"]."</option>");
               }
print("</select></td>");

print("\n<td class=\"lista\"><select name=\"langue\" size=\"1\" onchange=\"location=document.jump1.langue.options[document.jump1.langue.selectedIndex].value\">");
foreach($langue as $a)
               {
               print("<option ");
               if ($a["id"]==$CURUSER["language"])
                  print("selected=\"selected\"");
               print(" value=\"account_change.php?langue=".$a["id"]."&amp;returnto=".urlencode($_SERVER['REQUEST_URI'])."\">".$a["language"]."</option>");
               }
print("</select></td>");
//print("<td class=lista align=center>".USER_LASTACCESS.": ".date("d/m/Y H:i:s",$CURUSER["lastconnect"])."</td>\n");
?>
</tr>
</table>
</form>
<?php
}
else
    {
    if (!isset($user)) $user = '';
    ?>
    <form action="index.php?page=login" name="login" method="post">
    <table class="lista" border="0" width="100%" cellpadding="2" cellspacing="0">
    <tr>
    <td class="lista" align="left">
      <table border="0" cellpadding="2" cellspacing="0">
      <tr>
      <td align="right" class="lista"><?php echo $language["USER_NAME"]?>:</td>
      <td class="lista"><input type="text" size="15" name="uid" value="<?php $user ?>" maxlength="40" style="font-size:10px" /></td>
      <td align="right" class="lista"><?php echo $language["USER_PWD"]?>:</td>
      <td class="lista"><input type="password" size="15" name="pwd" maxlength="40" style="font-size:10px" /></td>
      <td class="lista" align="center"><input type="submit" value="<?php echo $language["FRM_LOGIN"]?>" style="font-size:10px" /></td>
      </tr>
      </table>
    </td>
    <td class="lista" align="center"><a href="index.php?page=signup"><?php echo $language["ACCOUNT_CREATE"]?></a></td>
    <td class="lista" align="center"><a href="index.php?page=recover"><?php echo $language["RECOVER_PWD"]?></a></td>
    </tr>
    </table>
    </form>
    <?php
}
?>