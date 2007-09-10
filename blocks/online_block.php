<?php
global $CURUSER;
if (!$CURUSER || $CURUSER["view_users"]=="no")
   {
    // do nothing
   }
else
    {

     //block_begin("Online Users");
     print("\n<table class=\"lista\" width=\"100%\">\n");

     $u_online=array();
     $group=array();
     $u_online=get_result("SELECT * FROM {$TABLE_PREFIX}online ol",true);

     $total_online=count($u_online);
     $uo=array();
     foreach($u_online as $id=>$users_online)
        {
            $group[unesc(ucfirst($users_online["user_group"]))]++;
            if ($users_online["user_id"]>1)
                $uo[]="<a href=\"index.php?page=userdetails&amp;id=".$users_online["user_id"]."\" title=\"".unesc(ucfirst($users_online["location"]))."\">".
                       unesc($users_online["prefixcolor"]).unesc($users_online["user_name"]).unesc($users_online["suffixcolor"])."</a>";

     }

     print("<tr><td class=\"block\" align=\"center\">".$language["GROUP"]."</td><td class=\"block\" align=\"center\">".$language["NUMBER_SHORT"]."</td></tr>\n");

     foreach($group as $gname=>$gnumber)
        {
          print("<tr>\n");
          print("<td class=\"blocklist\" align=\"left\">$gname</td><td class=\"blocklist\" align=\"right\">$gnumber</td>\n");
          print("</tr>\n");
      }

     print("<tr><td class=\"blocklist\" align=\"left\">Total</td><td class=\"blocklist\" align=\"right\">$total_online</td>\n</tr>\n");
     print("<tr><td colspan=\"2\" class=\"blocklist\">".$language["REGISTERED"].": ".implode(", ",$uo)."</td>\n</tr>\n");


     //print($print. $gueststr . ($guest_num>0 && $regusers>0?" ".$language["WORD_AND"]." ":"") . ($regusers>0?"$regusers ".($regusers>1?$language["MEMBERS"]:$language["MEMBER"])."): ":")") . $users ."\n</td></tr>");
     block_end();
     print("</table>\n");
} // end if user can view
?>