<?php
global $CURUSER, $user, $USERLANG, $FORUMLINK, $db_prefix;

require_once(load_language("lang_account.php"));

         block_begin("".BLOCK_USER."");

         if (!$CURUSER || $CURUSER["id"]==1)
            {
            // guest-anonymous, login require
            ?>
            <form action="index.php?page=login" name="login" method="post">
            <table class="lista" border="0" align="center" width="100%">
            <tr><td align="right" class="header"><?php echo $language["USER_NAME"]?>:</td><td class="lista"><input type="text" size="9" name="uid" value="<?php $user ?>" maxlength="40" /></td></tr>
            <tr><td align="right" class="header"><?php echo $language["USER_PWD"]?>:</td><td class="lista"><input type="password" size="9" name="pwd" maxlength="40" /></td></tr>
            <tr><td colspan="2"  class="header" align="center"><input type="submit" value="<?php echo $language["FRM_LOGIN"]?>" /></td></tr>
            <tr><td class="header" align="center"><a href="index.php?page=signup"><?php echo $language["ACCOUNT_CREATE"]?></a></td><td class="header" align="center"><a href="index.php?page=recover"><?php echo $language["RECOVER_PWD"]?></a></td></tr>
            </table>
            </form>
            <?php
            }
         else
             {
             // user information
             $style=style_list();
             $langue=language_list();
             print("\n<form name=\"jump\" method=\"post\" action=\"index.php\">\n<table class=\"poller\" width=\"100%\" cellspacing=\"0\">\n<tr><td align=\"center\">".$language["USER_NAME"].":  " .unesc($CURUSER["username"])."</td></tr>\n");
             print("<tr><td align=\"center\">".$language["USER_LEVEL"].": ".$CURUSER["level"]."</td></tr>\n");
             if($FORUMLINK=="smf")
                 $resmail=do_sqlquery("SELECT unreadMessages FROM {$db_prefix}members WHERE ID_MEMBER=".$CURUSER["smf_fid"]);
             else
                 $resmail=do_sqlquery("SELECT COUNT(*) FROM {$TABLE_PREFIX}messages WHERE readed='no' AND receiver=$CURUSER[uid]");
             if ($resmail && mysql_num_rows($resmail)>0)
                {
                 $mail=mysql_fetch_row($resmail);
                 if ($mail[0]>0)
                    print("<tr><td align=\"center\"><a href=\"index.php?page=usercp&amp;uid=".$CURUSER["uid"]."&amp;do=pm&amp;action=list\">".$language["MAILBOX"]."</a> (<font color=\"#FF0000\"><b>$mail[0]</b></font>)</td></tr>\n");
                 else
                     print("<tr><td align=\"center\"><a href=\"index.php?page=usercp&amp;uid=".$CURUSER["uid"]."&amp;do=pm&amp;action=list\">".$language["MAILBOX"]."</a></td></tr>\n");
                }
             else
                 print("<tr><td align=\"center\">".$language["NO_MAIL"]."</td></tr>");
             print("<tr><td align=\"center\">");
             include("include/offset.php");
             print($language["USER_LASTACCESS"].":<br />".date("d/m/Y H:i:s",$CURUSER["lastconnect"]-$offset));
             print("</td></tr>\n<tr><td align=\"center\">");
             print($language["USER_STYLE"].":<br />\n<select name=\"style\" size=\"1\" onchange=\"location=document.jump.style.options[document.jump.style.selectedIndex].value\">");
             foreach($style as $a)
                            {
                            print("<option ");
                            if ($a["id"]==$CURUSER["style"])
                               print("selected=\"selected\"");
                            print(" value=\"account_change.php?style=".$a["id"]."&amp;returnto=".urlencode($_SERVER['REQUEST_URI'])."\">".$a["style"]."</option>");
                            }
             print("</select>");
             print("</td></tr>\n<tr><td align=\"center\">");
             print($language["USER_LANGUE"].":<br />\n<select name=\"langue\" size=\"1\" onchange=\"location=document.jump.langue.options[document.jump.langue.selectedIndex].value\">");
             foreach($langue as $a)
                            {
                            print("<option ");
                            if ($a["id"]==$CURUSER["language"])
                               print("selected=\"selected\"");
                            print(" value=\"account_change.php?langue=".$a["id"]."&amp;returnto=".urlencode($_SERVER['REQUEST_URI'])."\">".$a["language"]."</option>");
                            }
             print("</select>");
             print("</td>\n</tr>\n");
             print("\n<tr><td align=\"center\"><a href=\"index.php?page=usercp&amp;uid=".$CURUSER["uid"]."\">".$language["USER_CP"]."</a></td></tr>\n");
             if ($CURUSER["admin_access"]=="yes")
                print("\n<tr><td align=\"center\"><a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."\">".$language["MNU_ADMINCP"]."</a></td></tr>\n");

             print("</table>\n</form>");
             }
         block_end();
?>