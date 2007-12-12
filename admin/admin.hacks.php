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


switch ($action)
 {

    case 'uninstall_ok':

        if (isset($_POST["confirm"]) && $_POST["confirm"]!=$language["HACK_UNINSTALL"])
          {
          redirect("index.php?page=admin&user=".$CURUSER["uid"]."&code=".$CURUSER["random"]."&do=hacks&action=read");
          die();
        }


        if (isset($_GET["id"]))
            $hack_id=intval($_GET["id"]);
        else
            $hack_id=0;

        $ui_hack=get_result("SELECT folder FROM {$TABLE_PREFIX}hacks WHERE id=$hack_id",true);

        if (count($ui_hack)>0)
          {

            include("$THIS_BASEPATH/include/class.update_hacks.php");

            $hack_folder=unesc($ui_hack[0]["folder"]);

            // used to define the current path (hack path)
            $CURRENT_FOLDER="$THIS_BASEPATH/hacks/$hack_folder";

            // create object
            $newhack=new update_hacks();

            // we open the work definition file
            $hstring=$newhack->open_hack("$THIS_BASEPATH/hacks/$hack_folder/modification.xml");

            // all structure is now in an array
            $new_hack_array=$newhack->hack_to_array($hstring);

            // we will install the hack or we can just test if installation will run fine.
            if ($newhack->uninstall_hack($new_hack_array,true))
              {
               if ($newhack->uninstall_hack($new_hack_array))
                 {
                  do_sqlquery("DELETE FROM {$TABLE_PREFIX}hacks WHERE id=$hack_id",true);
                  success_msg($language["SUCCESS"],$language["HACK_UNINSTALLED_OK"]);
                  stdfoot(true,false);
                  die;
               }
            }
            else
              {
                 stderr($language["ERROR"],join("<br />\n",$newhack->errors));
            }
        }
        else
          stderr($language["ERROR"],$language["HACK_BAD_ID"]);

      break;

    case 'uninstall':

        if (isset($_GET["id"]))
            $hack_id=intval($_GET["id"]);
        else
            $hack_id=0;

        $ui_hack=get_result("SELECT folder FROM {$TABLE_PREFIX}hacks WHERE id=$hack_id",true);

        if (count($ui_hack)>0)
          {

            include("$THIS_BASEPATH/include/class.update_hacks.php");

            $hack_folder=unesc($ui_hack[0]["folder"]);

            // used to define the current path (hack path)
            $CURRENT_FOLDER="$THIS_BASEPATH/hacks/$hack_folder";

            // create object
            $newhack=new update_hacks();

            // we open the work definition file
            $hstring=$newhack->open_hack("$THIS_BASEPATH/hacks/$hack_folder/modification.xml");

            // all structure is now in an array
            $new_hack_array=$newhack->hack_to_array($hstring);

            // we will install the hack or we can just test if installation will run fine.
            if ($newhack->uninstall_hack($new_hack_array,true))
              {
                $admintpl->set("test_result",$newhack->file);
                $admintpl->set("test",true,true);
                $admintpl->set("test_ok",true,true);
            }
            else
              {
                $admintpl->set("test_result",$newhack->errors);
                $admintpl->set("test",true,true);
                $admintpl->set("test_ok",false,true);
            }
            $admintpl->set("language",$language);
            $admintpl->set("hack_folder",$hack_folder);
            $admintpl->set("hack_install",$language["HACK_UNINSTALL"]);
            $admintpl->set("hack_main_link","index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=hacks&amp;action=read");
            $admintpl->set("form_action","index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=hacks&amp;action=uninstall_ok&amp;id=$hack_id");
            $admintpl->set("hack_title_action","<b>".$language["HACK_UNINSTALL"].":&nbsp;".$new_hack_array[0]["title"]."</b>");

        }
        else
          stderr($language["ERROR"],$language["HACK_BAD_ID"]);


      break;

    case 'install':

        if (isset($_POST["confirm"]) && $_POST["confirm"]!=$language["HACK_INSTALL"])
          {
          redirect("index.php?page=admin&user=".$CURUSER["uid"]."&code=".$CURUSER["random"]."&do=hacks&action=read");
          die();
        }

        include("$THIS_BASEPATH/include/class.update_hacks.php");

        if (isset($_POST["add_hack_folder"]))
            $hack_folder=$_POST["add_hack_folder"];


        // used to define the current path (hack path)
        $CURRENT_FOLDER="$THIS_BASEPATH/hacks/$hack_folder";

        // create object
        $newhack=new update_hacks();

        // we open the work definition file
        $hstring=$newhack->open_hack("$THIS_BASEPATH/hacks/$hack_folder/modification.xml");

        // all structure is now in an array
        $new_hack_array=$newhack->hack_to_array($hstring);

        // we will test again, then if ok, we install the hack
        if ($newhack->install_hack($new_hack_array,true))
          {
               if ($newhack->install_hack($new_hack_array))
                 {
                  do_sqlquery("INSERT INTO {$TABLE_PREFIX}hacks SET ".
                    sprintf("title=%s,version=%s,author=%s,added=UNIX_TIMESTAMP(),folder=%s",
                            sqlesc($new_hack_array[0]["title"]),
                            sqlesc($new_hack_array[0]["version"]),
                            sqlesc($new_hack_array[0]["author"]),
                            sqlesc($hack_folder)),true);
                  success_msg($language["SUCCESS"],$language["HACK_INSTALLED_OK"]);
                  stdfoot(true,false);
                  die;

               }
        }
        else
          {
             stderr($language["ERROR"],join("<br />\n",$newhack->errors));
        }

      break;


    case 'test':

        include("$THIS_BASEPATH/include/class.update_hacks.php");

        if (isset($_POST["add_hack_folder"]))
            $hack_folder=$_POST["add_hack_folder"];


        // used to define the current path (hack path)
        $CURRENT_FOLDER="$THIS_BASEPATH/hacks/$hack_folder";

        // create object
        $newhack=new update_hacks();

        // we open the work definition file
        $hstring=$newhack->open_hack("$THIS_BASEPATH/hacks/$hack_folder/modification.xml");

        // all structure is now in an array
        $new_hack_array=$newhack->hack_to_array($hstring);

        // we will install the hack or we can just test if installation will run fine.
        if ($newhack->install_hack($new_hack_array,true))
          {
            $admintpl->set("test_result",$newhack->file);
            $admintpl->set("test",true,true);
            $admintpl->set("test_ok",true,true);
        }
        else
          {
            $admintpl->set("test_result",$newhack->errors);
            $admintpl->set("test",true,true);
            $admintpl->set("test_ok",false,true);
        }
        $admintpl->set("language",$language);
        $admintpl->set("hack_folder",$hack_folder);
        $admintpl->set("hack_install",$language["HACK_INSTALL"]);
        $admintpl->set("hack_main_link","index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=hacks&amp;action=read");
        $admintpl->set("form_action","index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=hacks&amp;action=install");
        $admintpl->set("hack_title_action","<b>".$language["HACK_INSTALL"].":&nbsp;".$new_hack_array[0]["title"]."</b>");

      break;

    case 'read':
    default:
        $admintpl->set("language",$language);
        $hacks = get_result("SELECT * FROM {$TABLE_PREFIX}hacks ORDER BY id",true);
        $installed=array();
        $i=0;
        //die(print_r($hacks));
        foreach($hacks as $id=>$hack)
          {
            $installed[]=unesc($hack["folder"]);
            $hacks[$i]["title"]=unesc($hack["title"]);
            $hacks[$i]["author"]=unesc($hack["author"]);
            $hacks[$i]["version"]=unesc($hack["version"]);
            $hacks[$i]["added"]=date("d M Y",$hack["added"]);
            $hacks[$i]["uninstall"]="index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=hacks&amp;action=uninstall&amp;id=".$hacks[$i]["id"]; // link only
            $i++;
        }
        // drop down
        $dir=opendir("$THIS_BASEPATH/hacks");
        $combo="\n<select name=\"add_hack_folder\" size=\"1\" onchange=\"valid_folder(this.options[selectedIndex].value)\">\n<option value=\"\">".$language["SELECT"]."</option>";
        while($file = @readdir($dir))
          {
          if (is_dir("$THIS_BASEPATH/hacks/$file") && $file!="." && $file!=".." && file_exists("$THIS_BASEPATH/hacks/$file/modification.xml"))
             if (!in_array($file,$installed))
               $combo.="\n<option value=\"$file\">$file</option>";
        }
        @closedir($dir);
        unset($installed);
        $combo.="\n</select>";

        $admintpl->set("form_action","index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=hacks&amp;action=test");
        $admintpl->set("hack_combo",$combo);
        $admintpl->set("no_hacks",count($hacks)==0,true);
        $admintpl->set("hacks",$hacks);
        $admintpl->set("test",false,true);
                
      break;
}


?>