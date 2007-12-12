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


if (!defined("IN_BTIT"))
      die("non direct access!");

if (!defined("IN_ACP"))
      die("non direct access!");


function read_styles()
    {
        global $TABLE_PREFIX, $language, $CURUSER, $admintpl, $STYLEPATH;

        $sres=style_list();
        for ($i=0;$i<count($sres);$i++)
           {
            $res = do_sqlquery("SELECT COUNT(*) FROM {$TABLE_PREFIX}users WHERE style = " . $sres[$i]["id"],true);
            $sres[$i]["style_users"]=mysql_result($res,0,0);
            $sres[$i]["style"]=unesc($sres[$i]["style"]);
            $sres[$i]["style_url"]=unesc($sres[$i]["style_url"]);
            $sres[$i]["edit"]="<a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=style&amp;action=edit&amp;id=".$sres[$i]["id"]."\">".image_or_link("$STYLEPATH/images/edit.png","",$language["EDIT"])."</a>";
            $sres[$i]["delete"]="<a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=style&amp;action=delete&amp;id=".$sres[$i]["id"]."\" onclick=\"return confirm('".AddSlashes($language["DELETE_CONFIRM"])."')\">".image_or_link("$STYLEPATH/images/delete.png","",$language["DELETE"])."</a>";
        }
        $admintpl->set("style_add",false,true);
        $admintpl->set("language",$language);
        $admintpl->set("styles",$sres);
        $admintpl->set("style_add_new","<a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=style&amp;action=add\">".$language["STYLE_ADD"]."</a>");
        unset($sres);
        mysql_free_result($res);

}


function styles_combo($all=false,$selected="")
  {
      global $THIS_BASEPATH, $language;
      if (!$all)
        {
            $sr=style_list();
            foreach ($sr as $s)
              $news[]=$s["style_url"];
       }
      $dir = @opendir("$THIS_BASEPATH/style");
      $lc="\n<select name=\"style_url\" size=\"1\">";
      if ($selected=="")
            $lc.="\n<option value=\"\">".$language["SELECT"]."</option>";

      while($file = @readdir($dir))
      {
        if(is_dir("$THIS_BASEPATH/style/$file") && $file!="." && $file!=".." && file_exists("$THIS_BASEPATH/style/$file/index.php"))
          {
            if ((!$all && !in_array("style/$file",$news)) || $all)
              $lc.="\n<option value=\"$file\" ".($selected=="style/$file"?"selected=\"selected\"":"").">$file</option>";
          }
      }
      @closedir($dir);
      $lc.="</select>";
      return $lc;

}

switch($action)
  {

    case 'save':
      if ($_POST["confirm"]==$language["FRM_CONFIRM"])
        {
        if ($_POST["style_name"]!="" && $_POST["style_url"]!="")
          {
            if ($_GET["mode"]=='new')
              do_sqlquery("INSERT INTO {$TABLE_PREFIX}style (style, style_url) VALUES (".sqlesc($_POST["style_name"]).",".sqlesc("style/".$_POST["style_url"]).")",true);
            else
              do_sqlquery("UPDATE {$TABLE_PREFIX}style SET style=".sqlesc($_POST["style_name"]).",style_url=".sqlesc("style/".$_POST["style_url"])." WHERE id=".max(0,$_GET["id"]),true);
          }
        else
            stderr($language["ERROR"],$language["ALL_FIELDS_REQUIRED"]);
      }
      read_styles();
      break;

    case 'add':
      $admintpl->set("style_add",true,true);
      $admintpl->set("language",$language);
      $admintpl->set("style_name","");
      $admintpl->set("frm_action","index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=style&amp;action=save&amp;mode=new");
      $admintpl->set("style_combo",styles_combo());
      break;

    case 'edit':
      if (isset($_GET["id"]))
        {
          // we should get only 1 style, selected with radio ...
          $id=max(0,$_GET["id"]);
          $sres=get_result("SELECT style,style_url FROM {$TABLE_PREFIX}style WHERE id=$id",true);
          $admintpl->set("style_add",true,true);
          $admintpl->set("language",$language);
          $admintpl->set("style_name",$sres[0]["style"]);
          $admintpl->set("frm_action","index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=style&amp;action=save&amp;mode=edit&amp;id=$id");
          $admintpl->set("style_combo",styles_combo(true,$sres[0]["style_url"]));
        }
      break;

    case 'delete':
      if (isset($_GET["id"]))
        {
         // we should get only 1 style, selected with radio ...
         $id=max(0,$_GET["id"]);
         // update the deleted user's style to default one
         do_sqlquery("UPDATE {$TABLE_PREFIX}users SET style='".$btit_settings["default_style"]."' WHERE style=$id",true);
         // delete style from database
         do_sqlquery("DELETE FROM {$TABLE_PREFIX}style WHERE id=$id",true);
         read_styles();
        }
      break;

    case '':
    case'read':
    default:
        read_styles();

}

?>