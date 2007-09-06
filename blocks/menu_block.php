<?php
global $CURUSER;

   block_begin(BLOCK_MENU);

   print("<table class=\"lista\" width=\"100%\" cellspacing=\"0\">\n<tr><td class=\"blocklist\" align=\"center\"><a href=\"index.php\" target=\"_blank\">".$language["MNU_INDEX"]."</a></td></tr>\n");

   if ($CURUSER["view_torrents"]=="yes")
      {
      print("<tr><td class=\"blocklist\" align=\"center\"><a href=\"index.php?page=torrents\">".$language["MNU_TORRENT"]."</a></td></tr>\n");
      print("<tr><td class=\"blocklist\" align=\"center\"><a href=\"index.php?page=extra-stats\">".$language["MNU_STATS"]."</a></td></tr>\n");
      }
   if ($CURUSER["can_upload"]=="yes")
      print("<tr><td class=\"blocklist\" align=\"center\"><a href=\"index.php?page=upload\">".$language["MNU_UPLOAD"]."</a></td></tr>\n");
   if ($CURUSER["view_users"]=="yes")
      print("<tr><td class=\"blocklist\" align=\"center\"><a href=\"index.php?page=users\">".$language["MNU_MEMBERS"]."</a></td></tr>\n");
   if ($CURUSER["view_news"]=="yes")
      print("<tr><td class=\"blocklist\" align=\"center\"><a href=\"index.php?page=viewnews\">".$language["MNU_NEWS"]."</a></td></tr>\n");
   if ($CURUSER["view_forum"]=="yes")
      {
        if ($GLOBALS["FORUMLINK"]=="" || $GLOBALS["FORUMLINK"]=="internal")
           print("<tr><td class=\"blocklist\" align=\"center\"><a href=\"index.php?page=forum\">".$language["MNU_FORUM"]."</a></td></tr>\n");
        else
            print("<tr><td class=\"blocklist\" align=\"center\"><a href=\"".$GLOBALS["FORUMLINK"]."\" target=\"_blank\">".$language["MNU_FORUM"]."</a></td></tr>\n");
      }
   if ($CURUSER["uid"]==1 || !$CURUSER)
      print("<tr><td class=\"blocklist\" align=\"center\"><a href=\"index.php?page=login\">".$language["LOGIN"]."</a></td></tr>\n</table>\n");
   else
       print("<tr><td class=\"blocklist\" align=\"center\"><a href=\"logout.php\">".$language["LOGOUT"]."</a></td></tr>\n</table>\n");

   block_end();
?>