<?php
global $CURUSER;
if (!$CURUSER || $CURUSER["view_torrents"]=="no")
   {
    // do nothing
   }
else
    {

  global $BASEURL, $STYLEPATH, $XBTT_USE,$btit_settings;

  block_begin(TOP_TORRENTS);

  if ($XBTT_USE)
     $sql = "SELECT f.info_hash as hash, f.seeds+ifnull(x.seeders,0) as seeds , f.leechers + ifnull(x.leechers,0) as leechers, dlbytes AS dwned, format(f.finished+ifnull(x.completed,0),0) as finished, filename, url, info, UNIX_TIMESTAMP(data) AS added, c.image, c.name AS cname, category AS catid, size, external, uploader FROM {$TABLE_PREFIX}files as f LEFT JOIN xbt_files x ON f.bin_hash=x.info_hash LEFT JOIN {$TABLE_PREFIX}categories as c ON c.id = f.category WHERE f.leechers + ifnull(x.leechers,0) + f.seeds+ifnull(x.seeders,0) > 0  ORDER BY finished DESC LIMIT " .  $GLOBALS["block_mostpoplimit"];
  else
     $sql = "SELECT info_hash as hash, seeds, leechers, dlbytes AS dwned, format(finished,0) as finished, filename, url, info, UNIX_TIMESTAMP(data) AS added, c.image, c.name AS cname, category AS catid, size, external, uploader FROM {$TABLE_PREFIX}files as f LEFT JOIN {$TABLE_PREFIX}categories as c ON c.id = f.category WHERE leechers + seeds > 0 ORDER BY finished DESC LIMIT " .  $GLOBALS["block_mostpoplimit"];

     $row = do_sqlquery($sql,true);
  ?>
  <table cellpadding="4" cellspacing="1" width="100%">
  <tr>
    <td colspan="2" align="center" class="header">&nbsp;<?php echo $language["TORRENT_FILE"]; ?>&nbsp;</td>
    <td align="center" class="header">&nbsp;<?php echo $language["CATEGORY"]; ?>&nbsp;</td>
<?php
if (max(0,$CURUSER["WT"])>0)
    print("<td align=\"center\" class=\"header\">&nbsp".$language["WT"]."&nbsp;</td>");
?>
    <td align="center" class="header">&nbsp;<?php echo $language["ADDED"]; ?>&nbsp;</td>
    <td align="center" class="header">&nbsp;<?php echo $language["SIZE"]; ?>&nbsp;</td>
    <td align="center" class="header">&nbsp;<?php echo $language["SHORT_S"]; ?>&nbsp;</td>
    <td align="center" class="header">&nbsp;<?php echo $language["SHORT_L"]; ?>&nbsp;</td>
    <td align="center" class="header">&nbsp;<?php echo $language["SHORT_C"]; ?>&nbsp;</td>
  </tr>
  <?php

  if ($row)
  {
      while ($data=mysql_fetch_array($row))
      {
      echo "<tr>\n";

          if ( strlen($data["hash"]) > 0 )
          {
             echo "\t<td align=\"center\" class=\"lista\" nowrap=\"nowrap\">";

      echo "<a href=\"download.php?id=".$data["hash"]."&amp;f=" . rawurlencode($data["filename"]) . ".torrent\"><img src='images/torrent.gif' border='0' alt='".$language["DOWNLOAD_TORRENT"]."' title='".$language["DOWNLOAD_TORRENT"] ."' /></a>";


     echo "</td>";

     $data["filename"]=unesc($data["filename"]);
     $filename=cut_string($data["filename"],intval($btit_settings["cut_name"]));

     if ($GLOBALS["usepopup"])
        echo "\t<td width=\"60%\" class=\"lista\"><a href=\"javascript:popdetails('details.php?id=" . $data['hash'] . "');\" title=\"" . $language["VIEW_DETAILS"] . ": " . $data["filename"] . "\">" . $filename . "</a>".($data["external"]=="no"?"":" (<span style=\"color:red\">EXT</span>)")."</td>";
     else
        echo "\t<td width=\"60%\" class=\"lista\"><a href=\"details.php?id=" . $data['hash'] . "\" title=\"" . $language["VIEW_DETAILS"] . ": " . $data["filename"] . "\">" . $filename . "</a>".($data["external"]=="no"?"":" (<span style=\"color:red\">EXT</span>)")."</td>";

     echo "\t<td align=\"center\" class=\"lista\"><a href=\"index.php?page=torrents&amp;category=$data[catid]\">" . image_or_link( ($data["image"] == "" ? "" : "images/categories/" . $data["image"]), "", $data["cname"]) . "</a></td>";

    //waitingtime
    // only if current user is limited by WT
    if (max(0,$CURUSER["WT"])>0)
        {
          $wait=0;
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

           echo "\t<td align=\"center\" class=\"lista\">".$wait." h</td>";

    }
    //end waitingtime
           include("include/offset.php");
           echo "\t<td nowrap=\"nowrap\" class=\"lista\" align=\"center\">" . date("d/m/Y", $data["added"]-$offset) . "</td>";
           echo "\t<td nowrap=\"nowrap\" class=\"lista\" align=\"center\">" . makesize($data["size"]) . "</td>";

           if ( $data["external"] == "no" )
            {
              if ($GLOBALS["usepopup"])
                {
                echo "\t<td align=\"center\" class=\"".linkcolor($data["seeds"])."\"><a href=\"javascript:poppeer('index.php?page=peers&amp;id=".$data["hash"]."');\" title=\"".PEERS_DETAILS."\">" . $data["seeds"] . "</a></td>\n";
                echo "\t<td align=\"center\" class=\"".linkcolor($data["leechers"])."\"><a href=\"javascript:poppeer('index.php?page=peers&amp;id=".$data["hash"]."');\" title=\"".PEERS_DETAILS."\">" .$data["leechers"] . "</a></td>\n";
                if ($data["finished"]>0)
                   echo "\t<td align=\"center\" class=\"lista\"><a href=\"javascript:poppeer('torrent_history.php?id=".$data["hash"]."');\" title=\"History - ".$data["filename"]."\">" . $data["finished"] . "</a></td>";
                else
                    echo "\t<td align=\"center\" class=\"lista\">---</td>";

                }
              else
                {
                echo "\t<td align=\"center\" class=\"".linkcolor($data["seeds"])."\"><a href=\"index.php?page=peers&amp;id=".$data["hash"]."\" title=\"".PEERS_DETAILS."\">" . $data["seeds"] . "</a></td>\n";
                echo "\t<td align=\"center\" class=\"".linkcolor($data["leechers"])."\"><a href=\"index.php?page=peers&amp;id=".$data["hash"]."\" title=\"".PEERS_DETAILS."\">" .$data["leechers"] . "</a></td>\n";
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
    echo "<tr><td class=\"lista\" colspan=\"9\" align=\"center\">" . NO_TORRENTS . "</td></tr>";
  }

  print("</table>");

  block_end();

} // end if user can view
?>