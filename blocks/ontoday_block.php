<?
global $CURUSER;
if (!$CURUSER || $CURUSER["view_users"]=="no")
   {
    // do nothing
   }
else
    {
//block_begin("Online Today");
print("\n<table class=\"lista\" width=\"100%\">\n");
print("<tr><td class=\"blocklist\" align=\"center\">");
$u_online=array();
     //$group=array();
         $timeout=time()-(60*1); // 1 minute
     $u_online=get_result("SELECT * FROM {$TABLE_PREFIX}users SET lastconnect=NOW() WHERE id IN (SELECT user_id FROM {$TABLE_PREFIX}online ol WHERE ol.lastaction<$timeout AND ol.user_id>1)");

     $total_online=count($u_online);
     $uo=array();
         $counter = 0;
     foreach($u_online as $id=>$users_online)
        {
if ($users_online["user_id"]>1)
                $uo[]="<a href=\"index.php?page=userdetails&amp;id=".$users_online["user_id"]."\" title=\"".unesc(ucfirst($users_online["location"]))."\">".
                       unesc($users_online["prefixcolor"]).unesc($users_online["user_name"]).unesc($users_online["suffixcolor"])."</a>";
print("Users online today:<br> ".implode(", ",$uo)."\n");                                            
$counter++;
}
print("<br />Total Users: ".$counter);
print("</td></tr>");

//block_end();
print("</table>\n");
} // end if user can view
?>