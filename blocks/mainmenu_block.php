<table cellpadding="0" cellspacing="0" width="100%">
  <tr>
<?php

   global $CURUSER;

if (!$CURUSER)
   {

       // anonymous=guest
   print("<td class=\"header\" align=\"center\">".$language["WELCOME"]." ".$language["GUEST"]."\n");
   print("<a href=\"login.php\">(".$language["LOGIN"].")</a></td>");
   }
elseif ($CURUSER["uid"]==1)
       // anonymous=guest
    {
   print("<td class=\"header\" align=\"center\">".$language["WELCOME"]." " . $CURUSER["username"] ." \n");
   print("<a href=\"index.php?page=login\">(".$language["LOGIN"].")</a></td>\n");
    }
else
    {
    print("<td class=\"header\" align=\"center\">".$language["WELCOME_BACK"]." " . $CURUSER["username"] ." \n");
    print("<a href=\"logout.php\">(".$language["LOGOUT"].")</a></td>\n");
    }

print("<td class=\"header\" align=\"center\"><a href=\"index.php\">".$language["MNU_INDEX"]."</a></td>\n");

if ($CURUSER["view_torrents"]=="yes")
    {
    print("<td class=\"header\" align=\"center\"><a href=\"index.php?page=torrents\">".$language["MNU_TORRENT"]."</a></td>\n");
    print("<td class=\"header\" align=\"center\"><a href=\"index.php?page=extra-stats\">".$language["MNU_STATS"]."</a></td>\n");
   }
if ($CURUSER["can_upload"]=="yes")
   print("<td class=\"header\" align=\"center\"><a href=\"index.php?page=upload\">".$language["MNU_UPLOAD"]."</a></td>\n");
if ($CURUSER["view_users"]=="yes")
   print("<td class=\"header\" align=\"center\"><a href=\"index.php?page=users\">".$language["MNU_MEMBERS"]."</a></td>\n");
if ($CURUSER["view_news"]=="yes")
   print("<td class=\"header\" align=\"center\"><a href=\"index.php?page=viewnews\">".$language["MNU_NEWS"]."</a></td>\n");
if ($CURUSER["view_forum"]=="yes")
   {
   if ($GLOBALS["FORUMLINK"]=="" || $GLOBALS["FORUMLINK"]=="internal" || $GLOBALS["FORUMLINK"]=="smf")
      print("<td class=\"header\" align=\"center\"><a href=\"index.php?page=forum\">".$language["MNU_FORUM"]."</a></td>\n");
   else
       print("<td class=\"header\" align=\"center\"><a href=\"".$GLOBALS["FORUMLINK"]."\">".$language["MNU_FORUM"]."</a></td>\n");
    }
 
?>
  </tr>
   </table>
     