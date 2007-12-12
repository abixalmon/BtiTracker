<?php
global $CURUSER;
if (!$CURUSER || $CURUSER["view_users"]=="no")
   {
    // do nothing
   }
else
    {
    //lastest member

     block_begin ("Latest Member");
     $a = @mysql_fetch_assoc(do_sqlquery("SELECT id,username FROM {$TABLE_PREFIX}users WHERE
     id_level<>1 AND id_level<>2 ORDER BY id DESC LIMIT 1"));
     if($a){
      if ($CURUSER["view_users"]=="yes")
      $latestuser = "<a href=\"index.php?page=userdetails&amp;id=" . $a["id"] . "\">" . $a["username"] . "</a>";
     else
     $latestuser = $a['username'];
     echo " <div align=\"center\"><table border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" > <tr><td class=\"blocklist\" align=\"center\">".$language["WELCOME_LASTUSER"]."<br /><b>$latestuser</b>!</td></tr></table></div>\n";
     }
     block_end("");

} // end if user can view

//end
?>