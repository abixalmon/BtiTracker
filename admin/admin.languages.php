<?php

if (!defined("IN_BTIT"))
      die("non direct access!");

if (!defined("IN_ACP"))
      die("non direct access!");


switch ($action)
{
    case 'add':
      $lres=language_list();
      foreach ($lres as $l)
        $newl[]=$l["language_url"];
      $dir = @opendir("$THIS_BASEPATH/language");
      $lc="\n<select name=\"new_lang_url\" size=\"1\">";
      $lc.="\n<option value=\"\">".$language["SELECT"]."</option>";
      while($file = @readdir($dir))
      {
        if(is_dir("$THIS_BASEPATH/language/$file") && $file!="." && $file!=".." && substr($file, 0, 8) != 'install_')
          {
            if (!in_array("language/$file",$newl))
             $lc.="\n<option value=\"$file\">$file</option>";
          }
      }
      @closedir($dir);
      unset($newl);
      unset($lres);  
      $lc.="\n</select>";

      $admintpl->set("language_add",true,true);
      $admintpl->set("language",$language);
      $admintpl->set("lang_combo",$lc);
      $admintpl->set("frm_action","index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=language&amp;action=save");
      break;

    case 'save':
      if ($_POST["confirm"]==$language["FRM_CONFIRM"])
        if ($_POST["new_language"]!="" &&$_POST["new_lang_url"]!="")
            do_sqlquery("INSERT INTO {$TABLE_PREFIX}language (language, language_url) VALUES (".sqlesc($_POST["new_language"]).", ".sqlesc("language/".$_POST["new_lang_url"]).")",true);
        else
            stderr($language["ERROR"],$language["ALL_FIELDS_REQUIRED"]);
      // we don't break, so we read the new inserted row ;)
        
    case '':
    case 'read':
    default:
      $lres=language_list();
      for ($i=0;$i<count($lres);$i++)
        {
        $res = do_sqlquery("SELECT COUNT(*) FROM {$TABLE_PREFIX}users WHERE language = " . $lres[$i]["id"],true);
        $lres[$i]["language_users"]=mysql_result($res,0,0);
        $lres[$i]["language"]=unesc($lres[$i]["language"]);
        $lres[$i]["language_url"]=unesc($lres[$i]["language_url"]);
        }
      $admintpl->set("language_add",false,true);
      $admintpl->set("language",$language);
      $admintpl->set("languages",$lres);
      $admintpl->set("lang_add_new","<a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=language&amp;action=add\">".$language["LANGUAGE_ADD"]."</a>");

      unset($lres);
      mysql_free_result($res);
}

?>