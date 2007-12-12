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


function image_combo($current_image="")
   {
      global $STYLEPATH, $language, $STYLEURL;

      $dir = @opendir("$STYLEPATH/images/categories/");
      $ret="\n<select name=\"image\" size=\"1\" onchange=\"update_cat(this.options[selectedIndex].value,'$STYLEURL/images/categories/spacer.gif');\">\n<option value=\"\">".$language["SELECT"]."</option>";
      while($file = @readdir($dir))
      {
        if( !@is_dir("$STYLEPATH/images/categories/" . $file) )
        {
          $img_size = @getimagesize("$STYLEPATH/images/categories/" . $file);
          // IT'S AN IMAGE ;)
          if( $img_size[0] && $img_size[1] )
          {
            $ret.="\n<option value=\"$file\" ".($current_image==$file?"selected=\"selected\"":"").">$file</option>";
          }
        }
      }
      @closedir($dir);
      $ret.="\n</select>";

      return $ret;
}


function category_read()
   {
   global $admintpl,$language,$STYLEURL,$CURUSER,$STYLEPATH;

     $admintpl->set("category_add",false,true);
     $admintpl->set("language",$language);

     $cres=genrelist();
     for ($i=0;$i<count($cres);$i++)
       {
         $cres[$i]["name"]=unesc($cres[$i]["name"]);
         $cres[$i]["image"]="<img src=\"$STYLEURL/images/categories/".$cres[$i]["image"]."\" alt=\"\" border=\"0\" />";
         $cres[$i]["edit"]="<a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=category&amp;action=edit&amp;id=".$cres[$i]["id"]."\">".image_or_link("$STYLEPATH/images/edit.png","",$language["EDIT"])."</a>";
         $cres[$i]["delete"]="<a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=category&amp;action=delete&amp;id=".$cres[$i]["id"]."\" onclick=\"return confirm('".AddSlashes($language["DELETE_CONFIRM"])."')\">".image_or_link("$STYLEPATH/images/delete.png","",$language["DELETE"])."</a>";


     }
     $admintpl->set("categories",$cres);
     $admintpl->set("category_add_new","<a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=category&amp;action=add\">".$language["CATEGORY_ADD"]."</a>");

     unset($cres);
          
}


switch ($action)
  {
   case 'save':
      if ($_POST["confirm"]==$language["FRM_CONFIRM"])
        {
        if ($_POST["category_name"]!="" && $_POST["sort_index"]!="")
          {
            if ($_GET["mode"]=='new')
              do_sqlquery("INSERT INTO {$TABLE_PREFIX}categories (name, sort_index, sub, image) VALUES (".sqlesc($_POST["category_name"]).",".max(0,$_POST["sort_index"]).",".max(0,$_POST["sub_category"]).",".sqlesc($_POST["image"]).")",true);
            else
              do_sqlquery("UPDATE {$TABLE_PREFIX}categories SET name=".sqlesc($_POST["category_name"]).",sort_index=".max(0,$_POST["sort_index"]).", sub=".max(0,$_POST["sub_category"]).", image=".sqlesc($_POST["image"])." WHERE id=".max(0,$_GET["id"]),true);
          }
        else
            stderr($language["ERROR"],$language["ALL_FIELDS_REQUIRED"]);
      }
      category_read();
      break;

    case 'add':
        $admintpl->set("category_add",true,true);
        $admintpl->set("language",$language);
        $admintpl->set("category_name","");
        $admintpl->set("frm_action","index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=category&amp;action=save&amp;mode=new");
        $admintpl->set("image_combo",image_combo());
        $admintpl->set("subcat_combo",sub_categories());
        $admintpl->set("category_sort","");
        $admintpl->set("category_image","$STYLEURL/images/categories/spacer.gif");
        $admintpl->set("image_url","$STYLEURL/images/categories/");
        break;

    case 'edit':
        if (isset($_GET["id"]))
          {
            // we should get only 1 style, selected with radio ...
            $id=max(0,$_GET["id"]);
            $cres=get_result("SELECT * FROM {$TABLE_PREFIX}categories WHERE id=$id",true);
            $admintpl->set("category_add",true,true);
            $admintpl->set("language",$language);
            $admintpl->set("category_name",$cres[0]["name"]);
            $admintpl->set("frm_action","index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=category&amp;action=save&amp;mode=edit&amp;id=$id");
            $admintpl->set("image_combo",image_combo($cres[0]["image"]));
            $admintpl->set("subcat_combo",sub_categories($cres[0]["sub"]));
            $admintpl->set("category_sort",$cres[0]["sort_index"]);
            $admintpl->set("category_image","$STYLEURL/images/categories/".$cres[0]["image"]);
            $admintpl->set("image_url","$STYLEURL/images/categories/");
          }
        break;

    case 'delete':
        if (isset($_GET["id"]))
          {
           // we should get only 1 style, selected with radio ...
           $id=max(0,$_GET["id"]);
           // delete style from database
           do_sqlquery("DELETE FROM {$TABLE_PREFIX}categories WHERE id=$id",true);
           category_read();
          }
        break;

            
    case '':
    case 'read':
    default:
      category_read();
}

?>