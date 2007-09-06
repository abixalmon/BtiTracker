<?php

if (!defined("IN_BTIT"))
      die("non direct access!");

if (!defined("IN_ACP"))
      die("non direct access!");



switch ($action)
    {

    case 'delete':

        if ($_GET['ip']=="")
            err_msg(ERROR,INVALID_ID);
        //delete the ip from db
        $id = max(0,$_GET['ip']);
        do_sqlquery("DELETE FROM {$TABLE_PREFIX}bannedip WHERE id=".$id,true);
        success_msg($language["SUCCESS"],$language["BAN_DELETED"]);
        stdfoot(true,false);
        break;

    case 'write':
        if ($_POST['firstip']=="" || $_POST['lastip']=="")
            stderr($language["ERROR"],$language["BAN_NO_IP_WRITE"]);
        else
         {
            //ban the ip for real
            $firstip = $_POST["firstip"];
            $lastip = $_POST["lastip"];
            $comment = $_POST["comment"];
            $firstip = sprintf("%u", ip2long($firstip));
            $lastip = sprintf("%u", ip2long($lastip));
            if ($firstip == -1 || $lastip == -1)
                 err_msg($language["ERROR"],$language["BAN_IP_ERROR"]);
            else{
                 $comment = sqlesc($comment);
                 $added = sqlesc(time());
                 do_sqlquery("INSERT INTO {$TABLE_PREFIX}bannedip (added, addedby, first, last, comment) VALUES($added, $CURUSER[uid], $firstip, $lastip, '$comment')",true);
            }
          }
    // don't break, so now we read directly ;)

    case '':
    case 'read':
    default:
        $banned=array();
        $getbanned = do_sqlquery("SELECT b.*, u.username FROM {$TABLE_PREFIX}bannedip b LEFT JOIN {$TABLE_PREFIX}users u ON u.id=b.addedby ORDER BY b.added DESC",true);
        $rowsbanned = @mysql_num_rows($getbanned);
        $admintpl->set("frm_action","index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=banip&amp;action=write");
        $i=0;
        if ($rowsbanned>0)
        {
           $admintpl->set("no_records",false,true);

           while ($arr=mysql_fetch_assoc($getbanned))
              {
              $banned[$i]["first_ip"] = long2ip($arr["first"]);
              $banned[$i]["last_ip"] = long2ip($arr["last"]);
              $banned[$i]["date"] = get_date_time($arr['added']);
              $banned[$i]["comments"] = htmlspecialchars(unesc($arr["comment"]));
              $banned[$i]["by"] = "<a href=\"index.php?page=userdetails&amp;id=".$arr["addedby"]."\">".unesc($arr["username"])."</a>";
              $banned[$i]["remove"] = "<a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=banip&amp;action=delete&amp;ip=$arr[id]\" onclick=\"return confirm('". str_replace("'","\'",$language["DELETE_CONFIRM"])."')\">".image_or_link("$STYLEPATH/images/delete.png","",$language["DELETE"])."</a>";
           $i++;
           }

        }
        else
           $admintpl->set("no_records",true,true);

        $admintpl->set("bannedip",$banned);
        $admintpl->set("language",$language);
    }

?>