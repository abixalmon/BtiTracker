<?php
global $CURUSER;
if (!$CURUSER || $CURUSER["view_torrents"]=="no")
   {
    // do nothing
   }
else
    {
   global $SITENAME, $XBTT_USE;

   block_begin(BLOCK_INFO);
   if ($XBTT_USE)
      $res=do_sqlquery("select count(*) as tot, sum(f.seeds)+sum(ifnull(x.seeders,0)) as seeds, sum(f.leechers)+sum(ifnull(x.leechers,0)) as leechs  FROM {$TABLE_PREFIX}files f LEFT JOIN xbt_files x ON f.bin_hash=x.info_hash");
   else
       $res=do_sqlquery("select count(*) as tot, sum(seeds) as seeds, sum(leechers) as leechs  FROM {$TABLE_PREFIX}files");

   if ($res)
      {
      $row=mysql_fetch_array($res);
      $torrents=$row["tot"];
      $seeds=0+$row["seeds"];
      $leechers=0+$row["leechs"];
      }
   else {
      $seeds=0;
      $leechers=0;
      $torrents=0;
      }

   $res=do_sqlquery("select count(*) as tot FROM {$TABLE_PREFIX}users where id>1");
   if ($res)
      {
      $row=mysql_fetch_array($res);
      $users=$row["tot"];
      }
   else
       $users=0;
      if ($leechers>0)
         $percent=number_format(($seeds/$leechers)*100,0);
      else
          $percent=number_format($seeds*100,0);

   $peers=$seeds+$leechers;

   $res=do_sqlquery("select sum(downloaded) as dled, sum(uploaded) as upld FROM {$TABLE_PREFIX}users");
   $row=mysql_fetch_array($res);
   $dled=0+$row["dled"];
   $upld=0+$row["upld"];
   $traffic=makesize($dled+$upld);

//   print("<tr><td class=\"blocklist\" align=\"center\">\n");
   print("<table width=\"100%\" class=\"lista\" cellspacing=\"0\">\n");
   print("<tr>\n<td colspan=\"2\" align=\"center\" class=\"lista\"><u>".unesc($SITENAME)."</u></td></tr>\n");
   print("<tr><td align=\"left\" class=\"lista\">".$language["MEMBERS"].":</td><td align=\"right\" class=\"lista\">$users</td></tr>\n");
   print("<tr><td align=\"left\" class=\"lista\">".$language["TORRENTS"].":</td><td align=\"right\" class=\"lista\">$torrents</td></tr>\n");
   print("<tr><td align=\"left\" class=\"lista\">".$language["SEEDERS"].":</td><td align=\"right\" class=\"lista\">$seeds</td></tr>\n");
   print("<tr><td align=\"left\" class=\"lista\">".$language["LEECHERS"].":</td><td align=\"right\" class=\"lista\">$leechers</td></tr>\n");
   print("<tr><td align=\"left\" class=\"lista\">".$language["PEERS"].":</td><td align=\"right\" class=\"lista\">$peers</td></tr>\n");
   print("<tr><td align=\"left\" class=\"lista\">".$language["SEEDERS"]."/".$language["LEECHERS"].":</td><td align=\"right\" class=\"lista\">$percent%</td></tr>\n");
   print("<tr><td align=\"left\" class=\"lista\">".$language["TRAFFIC"].":</td><td align=\"right\" class=\"lista\">$traffic</td></tr>\n");
   print("</table>\n");
//   print("</table>\n</td></tr>");
//   block_end();

} // end if user can view
?>