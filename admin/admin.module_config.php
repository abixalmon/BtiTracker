<?php

if (!defined("IN_BTIT"))
      die("non direct access!");

if (!defined("IN_ACP"))
      die("non direct access!");


switch ($action)
{
    case 'manage':
        $admintpl->set("language",$language);
        $res = do_sqlquery("SELECT * FROM {$TABLE_PREFIX}modules ORDER BY id");
        $modules=array();
        $i=0;

        while ($row_modules=mysql_fetch_array($res))
            {
            if (unesc($row_modules["type"]=='staff'))
                $type = $language["STAFF"];
            elseif (unesc($row_modules["type"]=='misc'))
                $type = $language["MISC"];
            elseif (unesc($row_modules["type"]=='torrent'))
                $type = $language["TORRENT"];
            elseif (unesc($row_modules["type"]=='style'))
                $type = $language["STYLE"];
            $modules[$i]["module_id"]   = unesc($row_modules["id"]);              // id of the module (index)
            $modules[$i]["module_name"] = unesc($row_modules["name"]);            // name of the module (unique)
            $modules[$i]["module_type"] = $type;                                // type of the module
            if ($row_modules["activated"] == 'yes')
                $modules[$i]["module_activated"] = $language["YES"]."&nbsp;&nbsp;->&nbsp;&nbsp;<a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=module_config&amp;action=change_to_no&amp;id=".unesc($row_modules["id"])."\">".$language["DEACTIVATE"]."</a>";    // yes
            else
                $modules[$i]["module_activated"] = $language["NO"]."&nbsp;&nbsp;->&nbsp;&nbsp;<a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=module_config&amp;action=change_to_yes&amp;id=".unesc($row_modules["id"])."\">".$language["ACTIVATE"]."</a>";              // yes
            $modules[$i]["module_date_changed"] = unesc($row_modules["changed"]); // when last switched on or off
            $modules[$i]["module_date_created"] = unesc($row_modules["created"]); // the date created
            $i++;
            }
        $admintpl->set("modules", $modules);
        $active_modules = do_sqlquery("SELECT COUNT(*) FROM {$TABLE_PREFIX}modules WHERE activated='yes'",true);
        $admintpl->set("nr_active_modules", mysql_result($active_modules,0,0));
        $not_active_modules = do_sqlquery("SELECT COUNT(*) FROM {$TABLE_PREFIX}modules WHERE activated='no'",true);
        $admintpl->set("nr_not_active_modules", mysql_result($not_active_modules,0,0));
        $total_modules = do_sqlquery("SELECT COUNT(*) FROM {$TABLE_PREFIX}modules",true);
        $admintpl->set("nr_total_modules", mysql_result($total_modules,0,0));
        $admintpl->set("form_action", "index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=module_config&amp;action=add");
    break; // end of case 'manage'
    
    case 'change_to_yes':
        $id=max(0,$_GET["id"]);
        $admintpl->set("language",$language);
        do_sqlquery("UPDATE {$TABLE_PREFIX}modules SET activated='yes', changed=NOW() WHERE id=$id",true);
        redirect("index.php?page=admin&user=".$CURUSER["uid"]."&code=".$CURUSER["random"]."&do=module_config&action=manage");
        die();
    break;
    
    case 'change_to_no':
        $id=max(0,$_GET["id"]);
        $admintpl->set("language",$language);
        do_sqlquery("UPDATE {$TABLE_PREFIX}modules SET activated='no', changed=NOW() WHERE id=$id",true);
        redirect("index.php?page=admin&user=".$CURUSER["uid"]."&code=".$CURUSER["random"]."&do=module_config&action=manage");
        die();
    break;
    
    case 'add':
        $admintpl->set("language",$language);
        if ($_POST["confirm"]==$language["FRM_CONFIRM"])
            if ($_POST["module_name"]!="")
            {
                do_sqlquery("INSERT INTO {$TABLE_PREFIX}modules (`name`, `type`, `changed`, `created`) VALUES (".sqlesc($_POST["module_name"]).",".sqlesc($_POST["module_type"]).",NOW(), NOW())",true);
                redirect("index.php?page=admin&user=".$CURUSER["uid"]."&code=".$CURUSER["random"]."&do=module_config&action=manage");
                die();
            }
            else
                stderr($language["ERROR"],$language["ALL_FIELDS_REQUIRED"]);
    break; // end of case 'add'

} // end of switch ($action)
?>