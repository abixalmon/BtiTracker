<?php
          $uid = intval($CURUSER["uid"]);
          $res=do_sqlquery("SELECT u.lip,u.username,u.downloaded,u.uploaded, UNIX_TIMESTAMP(u.joined) as joined, u.flag, c.name, c.flagpic FROM {$TABLE_PREFIX}users u LEFT JOIN {$TABLE_PREFIX}countries c ON u.flag=c.id WHERE u.id=$uid",true);
          $row = mysql_fetch_array($res);

          if (max(0,$row["downloaded"])>0)
           {
             $sr = $row["uploaded"]/$row["downloaded"];
             if ($sr >= 4)
               $s = "images/smilies/thumbsup.gif";
             else if ($sr >= 2)
               $s = "images/smilies/grin.gif";
             else if ($sr >= 1)
               $s = "images/smilies/smile1.gif";
             else if ($sr >= 0.5)
               $s = "images/smilies/noexpression.gif";
             else if ($sr >= 0.25)
               $s = "images/smilies/sad.gif";
             else
               $s = "images/smilies/thumbsdown.gif";
            $ratio=number_format($sr,2)."&nbsp;&nbsp;<img src=\"$s\" alt=\"\" />";
           }
          else
             $ratio="oo";

        $ucptpl=array();
        $ucptpl["username"]=unesc($CURUSER["username"]);
        $ucptpl["avatar"]=htmlspecialchars($CURUSER["avatar"]);
        $ucptpl["email"]=htmlspecialchars($CURUSER["email"]);
        $ucptpl["lastip"]=long2ip($row["lip"]);
        $ucptpl["userlevel"]=unesc($CURUSER["level"]);
        $ucptpl["userjoin"]=($CURUSER["joined"]==0 ? "N/A" : get_date_time($CURUSER["joined"]));
        $ucptpl["lastaccess"]=($CURUSER["lastconnect"]==0 ? "N/A" : get_date_time($CURUSER["lastconnect"]));
        $ucptpl["country"]=($row["flag"]==0 ? "":unesc($row['name']))."&nbsp;&nbsp;<img src=\"images/flag/".(!$row["flagpic"] || $row["flagpic"]==""?"unknown.gif":$row["flagpic"])."\" alt='".($row["flag"]==0 ? "unknow":unesc($row['name']))."' />";
        $ucptpl["download"]=makesize($row["downloaded"]);
        $ucptpl["upload"]=makesize($row["uploaded"]);
        $ucptpl["ratio"]=$ratio;
        $usercptpl->set("ucp",$ucptpl);
        $usercptpl->set("AVATAR",$CURUSER["avatar"] && $CURUSER["avatar"]!="",true);
        $usercptpl->set("CAN_EDIT",$CURUSER["edit_users"]=="yes" || $CURUSER["admin_access"]=="yes",true);

        // Only show if forum is internal
        if ( $GLOBALS["FORUMLINK"] == '' || $GLOBALS["FORUMLINK"] == 'internal' )
           {
           $sql = do_sqlquery("SELECT count(*) FROM {$TABLE_PREFIX}posts p INNER JOIN {$TABLE_PREFIX}users u ON p.userid = u.id WHERE u.id = " . $CURUSER["uid"]);
           $ssql=mysql_fetch_array($sql);
           $posts = $ssql[0];
           unset($ssql);
           $memberdays = max(1, round( ( time() - $row['joined'] ) / 86400 ));
           $posts_per_day = number_format(round($posts / $memberdays,2),2);
           $usercptpl->set("INTERNAL_FORUM",true,true);
           $usercptpl->set("posts",$posts."&nbsp;[" . sprintf($language["POSTS_PER_DAY"], $posts_per_day) . "]");
        }
        elseif ($GLOBALS["FORUMLINK"]=="smf")
           {
           $forum=mysql_fetch_assoc(mysql_query("SELECT dateRegistered, posts FROM {$db_prefix}members WHERE ID_MEMBER=".$CURUSER["smf_fid"]));
           $memberdays = max(1, round( ( time() - $forum["dateRegistered"] ) / 86400 ));
           $posts_per_day = number_format(round($forum["posts"] / $memberdays,2),2);
           $usercptpl->set("INTERNAL_FORUM",true,true);
           $usercptpl->set("posts",$forum["posts"]."&nbsp;[" . sprintf($language["POSTS_PER_DAY"], $posts_per_day) . "]");
           unset($forum);
        }
        if ($XBTT_USE)
           {
            $tseeds="f.seeds+ifnull(x.seeders,0)";
            $tleechs="f.leechers+ifnull(x.leechers,0)";
            $tcompletes="f.finished+ifnull(x.completed,0)";
            $ttables="{$TABLE_PREFIX}files f INNER JOIN xbt_files x ON x.info_hash=f.bin_hash";
           }
        else
            {
            $tseeds="f.seeds";
            $tleechs="f.leechers";
            $tcompletes="f.finished";
            $ttables="{$TABLE_PREFIX}files f";
            }

        $resuploaded = do_sqlquery("SELECT count(*) FROM {$TABLE_PREFIX}files WHERE uploader=$uid ORDER BY data DESC",true);
        $ruploaded=mysql_fetch_row($resuploaded);
        $numtorrent=$ruploaded[0];
        unset($ruploaded);
        if ($numtorrent>0)
           {
           list($pagertop, $pagerbottom, $limit) = pager(($utorrents==0?15:$utorrents), $numtorrent, "index.php?page=usercp&amp;uid=$uid&amp;");

           $resuploaded = do_sqlquery("SELECT f.filename, UNIX_TIMESTAMP(f.data) as added, f.size, $tseeds as seeds, $tleechs as leechers, $tcompletes as finished, f.info_hash as hash FROM $ttables WHERE uploader=$uid ORDER BY data DESC $limit", true);
        }
        if ($resuploaded && mysql_num_rows($resuploaded)>0)
           {
               include("include/offset.php");
               $usercptpl->set("RESULTS",true,true);
               $uptortpl=array();
               $i=0;
               while ($rest=mysql_fetch_assoc($resuploaded))
                       {
                        $uptortpl[$i]["filename"]=cut_string(unesc($rest["filename"]),intval($btit_settings["cut_name"]));
                        $uptortpl[$i]["added"]=date("d/m/Y",$rest["added"]-$offset);
                        $uptortpl[$i]["size"]=makesize($rest["size"]);
                        $uptortpl[$i]["seedcolor"]=linkcolor($rest["seeds"]);
                        $uptortpl[$i]["seeds"]=$rest[seeds];
                        $uptortpl[$i]["leechcolor"]=linkcolor($rest["leechers"]);
                        $uptortpl[$i]["leechers"]=$rest[leechers];
                        $uptortpl[$i]["completed"]=($rest["finished"]>0?$rest["finished"]:"---");
                        $uptortpl[$i]["editlink"]="index.php?page=edit&amp;info_hash=".$rest["hash"]."&amp;returnto=".urlencode("index.php?page=torrents")."";
                        $uptortpl[$i]["dellink"]="index.php?page=delete&amp;info_hash=".$rest["hash"]."&amp;returnto=".urlencode("index.php?page=torrents")."";
                        $uptortpl[$i]["editimg"]=image_or_link("$STYLEPATH/images/edit.png","",$language["EDIT"]);
                        $uptortpl[$i]["delimg"]=image_or_link("$STYLEPATH/images/delete.png","",$language["DELETE"]);
                        $i++;
                     }
                   $usercptpl->set("uptor",$uptortpl);
          }
        else
            {
              $usercptpl->set("RESULTS",false,true);
       }

?>