<?php
global $CURUSER, $XBTT_USE,$TABLE_PREFIX;
if (!$CURUSER || $CURUSER["view_torrents"]=="no")
   {
    // do nothing
   }
else
    {
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
/*
   $res=do_sqlquery("select sum(seeds) as seeds, sum(leechers) as leechs FROM summary");
   if ($res)
      {
      $row=mysql_fetch_array($res);
      $seeds=0+$row["seeds"];
      $leechers=0+$row["leechs"];
      }
   else {
      $seeds=0;
      $leechers=0;
      }
*/
      if ($leechers>0)
         $percent=number_format(($seeds/$leechers)*100,0);
      else
          $percent=number_format($seeds*100,0);

   $peers=$seeds+$leechers;

   $res=do_sqlquery("select sum(downloaded) as dled, sum(uploaded) as upld FROM {$TABLE_PREFIX}users",true);
   $row=mysql_fetch_assoc($res);
   $dled=0+$row["dled"];
   $upld=0+$row["upld"];
   $traffic=makesize($dled+$upld);
?>
<table class="tool" cellpadding="2" cellspacing="0" width="100%">
<tr>
<td class="lista" style="text-align:center;" align="center"><?php echo $language["BLOCK_INFO"]; ?>:</td>
<td class="lista" style="text-align:center;" align="center"><?php echo $language["MEMBERS"]; ?>:</td><td style="text-align:center;" align="right"><?php echo $users; ?></td>
<td class="lista" style="text-align:center;" align="center"><?php echo $language["TORRENTS"]; ?>:</td><td style="text-align:center;" align="right"><?php echo $torrents; ?></td>
<td class="lista" style="text-align:center;" align="center"><?php echo $language["SEEDERS"]; ?>:</td><td style="text-align:center;" align="right"><?php echo $seeds; ?></td>
<td class="lista" style="text-align:center;" align="center"><?php echo $language["LEECHERS"]; ?>:</td><td style="text-align:center;" align="right"><?php echo $leechers; ?></td>
<td class="lista" style="text-align:center;" align="center"><?php echo $language["PEERS"]; ?>:</td><td style="text-align:center;" align="right"><?php echo $peers; ?></td>
<td class="lista" style="text-align:center;" align="center"><?php echo $language["SEEDERS"]."/".$language["LEECHERS"]; ?>:</td><td style="text-align:center;" align="right"><?php echo $percent."%"; ?></td>
<td class="lista" style="text-align:center;" align="center"><?php echo $language["TRAFFIC"]; ?>:</td><td style="text-align:center;" align="right"><?php echo $traffic; ?></td>
</tr></table>
<?php
} // end if user can view
?>