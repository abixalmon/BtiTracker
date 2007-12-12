<?php
/////////////////////////////////////////////////////////////////////////
// xBtit - Bittorrent tracker/frontend
//
// Copyright (C) 2004 - 2007  Btiteam
//
//    This file is part of xBtit.
//
//    xBtit is free software: you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation, either version 3 of the License, or
//    (at your option) any later version.
//
//    xBtit is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//    You should have received a copy of the GNU General Public License
//    along with xBtit.  If not, see <http://www.gnu.org/licenses/>.
//
/////////////////////////////////////////////////////////////////////////


function do_sanity() {

         global $PRIVATE_ANNOUNCE, $TORRENTSDIR, $CURRENTPATH,$LIVESTATS,$LOG_HISTORY, $TABLE_PREFIX;

         // SANITY FOR TORRENTS
         $results = do_sqlquery("SELECT info_hash, seeds, leechers, dlbytes, filename FROM {$TABLE_PREFIX}files WHERE external='no'");
         $i = 0;
         while ($row = mysql_fetch_row($results))
         {
             list($hash, $seeders, $leechers, $bytes, $filename) = $row;

         $timeout=time()-(intval($GLOBALS["report_interval"]*2));

         // for testing purpose -- begin
         $resupd=do_sqlquery("SELECT * FROM {$TABLE_PREFIX}peers where lastupdate < ".$timeout ." AND infohash='$hash'");
         if (mysql_num_rows($resupd)>0)
            {
            while ($resupdate = mysql_fetch_array($resupd))
              {
                  $uploaded=max(0,$resupdate["uploaded"]);
                  $downloaded=max(0,$resupdate["downloaded"]);
                  $pid=$resupdate["pid"];
                  $ip=$resupdate["ip"];
                  // update user->peer stats only if not livestat
                  if (!$LIVESTATS)
                    {
                      if ($PRIVATE_ANNOUNCE)
                         quickQuery("UPDATE {$TABLE_PREFIX}users SET uploaded=uploaded+$uploaded, downloaded=downloaded+$downloaded WHERE pid='$pid' AND id>1 LIMIT 1");
                      else // ip
                          quickQuery("UPDATE {$TABLE_PREFIX}users SET uploaded=uploaded+$uploaded, downloaded=downloaded+$downloaded WHERE cip='$ip' AND id>1 LIMIT 1");
                     }

                  // update dead peer to non active in history table
                  if ($LOG_HISTORY)
                     {
                          $resuser=do_sqlquery("SELECT id FROM {$TABLE_PREFIX}users WHERE ".($PRIVATE_ANNOUNCE?"pid='$pid'":"cip='$ip'")." ORDER BY lastconnect DESC LIMIT 1");
                          $curu=@mysql_fetch_row($resuser);
                          quickquery("UPDATE {$TABLE_PREFIX}history SET active='no' WHERE uid=$curu[0] AND infohash='$hash'");
                     }

            }
         }
         // for testing purpose -- end

            quickQuery("DELETE FROM {$TABLE_PREFIX}peers where lastupdate < ".$timeout." AND infohash='$hash'");
            quickQuery("UPDATE {$TABLE_PREFIX}files SET lastcycle='".time()."' WHERE info_hash='$hash'");

             $results2 = do_sqlquery("SELECT status, COUNT(status) from {$TABLE_PREFIX}peers WHERE infohash='$hash' GROUP BY status");
             $counts = array();
             while ($row = mysql_fetch_row($results2))
                 $counts[$row[0]] = 0+$row[1];

             quickQuery("UPDATE {$TABLE_PREFIX}files SET leechers=".(isset($counts["leecher"])?$counts["leecher"]:0).",seeds=".(isset($counts["seeder"])?$counts["seeder"]:0)." WHERE info_hash=\"$hash\"");
             if ($bytes < 0)
             {
                 quickQuery("UPDATE {$TABLE_PREFIX}files SET dlbytes=0 WHERE info_hash=\"$hash\"");
             }

         }
         // END TORRENT'S SANITY

         //  optimize peers table
         quickQuery("OPTIMIZE TABLE {$TABLE_PREFIX}peers");

         // delete readposts when topic don't exist or deleted  *** should be done by delete, just in case
         quickQuery("DELETE readposts FROM {$TABLE_PREFIX}readposts LEFT JOIN topics ON readposts.topicid = topics.id WHERE topics.id IS NULL");
         
         // delete readposts when users was deleted *** should be done by delete, just in case
         quickQuery("DELETE readposts FROM {$TABLE_PREFIX}readposts LEFT JOIN users ON readposts.userid = users.id WHERE users.id IS NULL");
         
         // deleting orphan image in torrent's folder (if image code is enabled)
         $tordir=realpath("$CURRENTPATH/../$TORRENTSDIR");
         if ($dir = @opendir($tordir."/"));
           {
            while(false !== ($file = @readdir($dir)))
               {
                   if ($ext = substr(strrchr($file, "."), 1)=="png")
                       unlink("$tordir/$file");
               }
            @closedir($dir);
         }

}
?>