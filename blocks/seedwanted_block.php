<?php


global $CURUSER, $BASEURL, $STYLEPATH, $XBTT_USE;

if (!$CURUSER || $CURUSER["view_torrents"]=="no")
   {
    // do nothing
   }
else
    {
   $limit=10;
  if ($XBTT_USE)
     $sql = "SELECT f.info_hash as hash, f.seeds+ifnull(x.seeders,0) as seeds , f.leechers + ifnull(x.leechers,0) as leechers, dlbytes AS dwned, format(f.finished+ifnull(x.completed,0),0) as finished, filename, url, info, UNIX_TIMESTAMP(data) AS added, c.image, c.name AS cname, category AS catid, size, external, uploader FROM {$TABLE_PREFIX}files as f LEFT JOIN xbt_files x ON f.bin_hash=x.info_hash LEFT JOIN {$TABLE_PREFIX}categories as c ON c.id = f.category WHERE f.leechers + ifnull(x.leechers,0) > 0 AND f.seeds+ifnull(x.seeders,0) = 0 AND f.external='no' ORDER BY f.leechers + ifnull(x.leechers,0) DESC LIMIT $limit";
  else
     $sql = "SELECT info_hash as hash, seeds, leechers, dlbytes AS dwned, finished, filename, url, info, UNIX_TIMESTAMP(data) AS added, c.image, c.name AS cname, category AS catid, size, external, uploader FROM {$TABLE_PREFIX}files as f LEFT JOIN {$TABLE_PREFIX}categories as c ON c.id = f.category WHERE leechers >0 AND seeds = 0 AND external='no' ORDER BY leechers DESC LIMIT $limit";

   $row = do_sqlquery($sql,true);

   if (mysql_num_rows($row)>0)
     {
       block_begin("Seeder Wanted");

       ?>
       <table cellpadding="4" cellspacing="1" width="100%">
       <tr>
         <td colspan="2" align="center" class="header">&nbsp;<?php echo $language["TORRENT_FILE"]; ?>&nbsp;</td>
         <td align="center" class="header">&nbsp;<?php echo $language["CATEGORY"] ?>&nbsp;</td>
         <?php
         if (max(0,$CURUSER["WT"])>0)
         print("<TD align=\"center\" class=\"header\">".$language["WT"]."</TD>");
         ?>
         <td align="center" class="header">&nbsp;<?php echo $language["ADDED"] ?>&nbsp;</td>
         <td align="center" class="header">&nbsp;<?php echo $language["SIZE"] ?>&nbsp;</td>
         <td align="center" class="header">&nbsp;<?php echo $language["SHORT_S"] ?>&nbsp;</td>
         <td align="center" class="header">&nbsp;<?php echo $language["SHORT_L"] ?>&nbsp;</td>
         <td align="center" class="header">&nbsp;<?php echo $language["SHORT_C"] ?>&nbsp;</td>
       </tr>
       <?php

       if ($row)
       {
           while ($data=mysql_fetch_array($row))
           {
           echo "<tr>\n";

               if ( strlen($data["hash"]) > 0 )
               {
                  echo "\t<td NOWRAP align=\"center\" class=\"lista\">";


           echo "<a href=download.php?id=".$data["hash"]."&f=" . rawurlencode($data["filename"]) . ".torrent><img src='images/torrent.gif' border='0' alt='".$language["DOWNLOAD_TORRENT"]."' title='".$language["DOWNLOAD_TORRENT"]."' /></a>";


         //waitingtime
             if (max(0,$CURUSER["WT"])>0){
             $resuser=do_sqlquery("SELECT * FROM {$TABLE_PREFIX}users WHERE id=".$CURUSER["uid"]);
             $rowuser=mysql_fetch_array($resuser);
             if (max(0,$rowuser['downloaded'])>0) $ratio=number_format($rowuser['uploaded']/$rowuser['downloaded'],2);
             else $ratio=0.0;
             $res2 =do_sqlquery("SELECT * FROM {$TABLE_PREFIX}files WHERE info_hash='".$data["hash"]."'");
             $added=mysql_fetch_array($res2);
             $vz = sql_timestamp_to_unix_timestamp($added["data"]);
             $timer = floor((time() - $vz) / 3600);
             if($ratio<1.0 && $rowuser['id']!=$added["uploader"]){
                 $wait=$CURUSER["WT"];
             }
             $wait -=$timer;
             if ($wait<=0)$wait=0;
             }
         //end waitingtime

                echo "</td>";
                if ($GLOBALS["usepopup"])
                     echo "\t<td width=60% class=\"lista\"><a href=\"javascript:popdetails('details.php?id=" . $data['hash'] . "');\" title=\"" . $language["VIEW_DETAILS"] . ": " . $data["filename"] . "\">" . $data["filename"] . "</a></td>";
                else
                     echo "\t<TD align=\"left\" class=\"lista\"><A HREF=\"details.php?id=".$data["hash"]."\" title=\"".$language["VIEW_DETAILS"].": ".$data["filename"]."\">".$data["filename"]."</A></td>";
                echo "\t<td align=\"center\" class=\"lista\"><a href=\"index.php?page=torrents&category=$data[catid]\">" . image_or_link( ($data["image"] == "" ? "" : "images/categories/" . $data["image"]), "", $data["cname"]) . "</td>";
                if (max(0,$CURUSER["WT"])>0)
                echo "\t<td align=\"center\" class=\"lista\">".$wait." h</td>";
                include("include/offset.php");
                echo "\t<td nowrap=\"nowrap\" class=\"lista\" align='center'>" . date("d/m/Y", $data["added"]-$offset) . "</td>";
                echo "\t<td nowrap=\"nowrap\" align=\"center\" class=\"lista\">" . makesize($data["size"]) . "</td>";

                if ($data["external"]=="no")
                {
                    if ($GLOBALS["usepopup"])
                    {
                        echo "\t<td align=\"center\" class=\"".linkcolor($data["seeds"])."\"><a href=\"javascript:poppeer('peers.php?id=".$data["hash"]."');\" title=\"".$language["PEERS_DETAILS"]."\">" . $data["seeds"] . "</a></td>\n";
                        echo "\t<td align=\"center\" class=\"".linkcolor($data["leechers"])."\"><a href=\"javascript:poppeer('peers.php?id=".$data["hash"]."');\" title=\"".$language["PEERS_DETAILS"]."\">" .$data["leechers"] . "</a></td>\n";
                        if ($data["finished"]>0)
                            echo "\t<td align=\"center\" class=\"lista\"><a href=\"javascript:poppeer('torrent_history.php?id=".$data["hash"]."');\" title=\"History - ".$data["filename"]."\">" . $data["finished"] . "</a></td>";
                        else
                            echo "\t<td align=\"center\" class=\"lista\">---</td>";
                    }
                    else
                    {
                        echo "\t<td align=\"center\" class=\"".linkcolor($data["seeds"])."\"><a href=\"peers.php?id=".$data["hash"]."\" title=\"".$language["PEERS_DETAILS"]."\">" . $data["seeds"] . "</a></td>\n";
                        echo "\t<td align=\"center\" class=\"".linkcolor($data["leechers"])."\"><a href=\"peers.php?id=".$data["hash"]."\" title=\"".$language["PEERS_DETAILS"]."\">" .$data["leechers"] . "</a></td>\n";
                        if ($data["finished"]>0)
                            echo "\t<td align=\"center\" class=\"lista\"><a href=\"torrent_history.php?id=".$data["hash"]."\" title=\"History - ".$data["filename"]."\">" . $data["finished"] . "</a></td>";
                        else
                            echo "\t<td align=\"center\" class=\"lista\">---</td>";
                    }
                }
                else
                {
                    // linkcolor
                    echo "\t<td align=\"center\" class=\"".linkcolor($data["seeds"])."\">" . $data["seeds"] . "</td>";
                    echo "\t<td align=\"center\" class=\"".linkcolor($data["leechers"])."\">" .$data["leechers"] . "</td>";
                    if ($data["finished"]>0)
                        echo "\t<td align=\"center\" class=\"lista\">" . $data["finished"] . "</td>";
                    else
                    echo "\t<td align=\"center\" class=\"lista\">---</td>";
                }
                echo "</tr>\n";
                }
           }
       }
       else
       {
         echo "<tr><td class=\"lista\" colspan=\"9\" align=\"center\">" . $language["NO_TORRENTS"]  . "</td></tr>";
       }

       print("</table>");

       block_end();
    }
    else
      echo "<div align=\"center\">".$language["NO_TORRENTS"]."</div>";
} // end if user can view
?>