<?php


if (!defined("IN_BTIT"))
      die("non direct access!");

if (!defined("IN_ACP"))
      die("non direct access!");



function blocks_combo($current_block="")
   {
      global $THIS_BASEPATH, $language;

      $dir = @opendir("$THIS_BASEPATH/blocks/");
      $ret="\n<select name=\"block_name\" size=\"1\">\n<option value=\"\" ".($current_block==""?"selected=\"selected\"":"").">".$language["SELECT"]."</option>";
      while($file = @readdir($dir))
        {
        if (@is_file("$THIS_BASEPATH/blocks/" . $file) && $file!="index.php")
          {
            $content=str_replace(array("_block",".php"),"",$file);
            $ret.="\n<option value=\"$content\" ".($current_block==$content?"selected=\"selected\"":"").">$file</option>";
        }
      }
      @closedir($dir);
      $ret.="\n</select>";

      return $ret;
}

function read_blocks()
    {
    global $TABLE_PREFIX,$language,$admintpl, $CURUSER, $USERLANG, $STYLEPATH;

    require_once(load_language("lang_blocks.php"));

      $br=get_result("SELECT * FROM {$TABLE_PREFIX}blocks ORDER BY sortid",true);
      $tops=array();
      $lefts=array();
      $centers=array();
      $rights=array();
      $bottom=array();
      $t=0;
      $l=0;
      $c=0;
      $r=0;
      $b=0;

      $rlevel=mysql_query("SELECT DISTINCT id_level, predef_level, level FROM {$TABLE_PREFIX}users_level ORDER BY id_level");
      $alevel=array();
      while($reslevel=mysql_fetch_assoc($rlevel))
          $alevel[]=$reslevel;

      foreach($br as $id=>$blk)
        {
        switch($blk["position"])
          {
          case 't':
                $tops[$t]["pos"]="\n<select name=\"position_".$blk["blockid"]."\" size=\"1\">";
                $tops[$t]["pos"].="\n<option selected=\"selected\" value=\"t\">".$language["TOP"]."</option>";
                $tops[$t]["pos"].="\n<option value=\"l\">".$language["LEFT"]."</option>";
                $tops[$t]["pos"].="\n<option value=\"c\">".$language["CENTER"]."</option>";
                $tops[$t]["pos"].="\n<option value=\"r\">".$language["RIGHT"]."</option>";
                $tops[$t]["pos"].="\n<option value=\"b\">".$language["BOTTOM"]."</option>";
                $tops[$t]["pos"].="\n</select>";

                $tops[$t]["combo_min_view"]="\n<select name=\"minclassview_".$blk["blockid"]."\" size=\"1\">";
                foreach($alevel as $level)
                    $tops[$t]["combo_min_view"].="\n<option value=\"".$level["id_level"].($blk["minclassview"] == $level["id_level"] ? "\" selected=\"selected\">" : "\">").$level["level"]."</option>";
                $tops[$t]["combo_min_view"].="\n</select>";

                $tops[$t]["combo_max_view"]="\n<select name=\"maxclassview_".$blk["blockid"]."\" size=\"1\">";
                foreach($alevel as $level)
                    $tops[$t]["combo_max_view"].="\n<option value=\"".$level["id_level"].($blk["maxclassview"] == $level["id_level"] ? "\" selected=\"selected\">" : "\">").$level["level"]."</option>";
                $tops[$t]["combo_max_view"].="\n</select>";

                $tops[$t]["combo"]="\n<select name=\"sort_".$blk["blockid"]."\" size=\"1\">";
                for ($i=0;$i<count($br);$i++)
                    $tops[$t]["combo"].="\n<option value=\"$i\" ".($i==$blk["sortid"]?"selected=\"selected\"":"").">$i</option>";
                $tops[$t]["combo"].="\n</select>";
                $tops[$t]["status"]=$blk["status"];
                $tops[$t]["title"]=$language[$blk["title"]].
                    "&nbsp;&nbsp;<a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=blocks&amp;action=edit&amp;id=".$blk["blockid"]."\">".
                    image_or_link("$STYLEPATH/images/edit.png","",$language["EDIT"])."</a>";
                $tops[$t]["check"]="<input name=\"status_".$blk["blockid"]."\" type=\"checkbox\" ".($blk["status"]=="1"?"checked=\"checked\"":"")." />";
                $t++;
                break;

          case 'l':
                $lefts[$l]["pos"]="\n<select name=\"position_".$blk["blockid"]."\" size=\"1\">";
                $lefts[$l]["pos"].="\n<option value=\"t\">".$language["TOP"]."</option>";
                $lefts[$l]["pos"].="\n<option selected=\"selected\" value=\"l\">".$language["LEFT"]."</option>";
                $lefts[$l]["pos"].="\n<option value=\"c\">".$language["CENTER"]."</option>";
                $lefts[$l]["pos"].="\n<option value=\"r\">".$language["RIGHT"]."</option>";
                $lefts[$l]["pos"].="\n<option value=\"b\">".$language["BOTTOM"]."</option>";
                $lefts[$l]["pos"].="\n</select>";

                $lefts[$l]["combo_min_view"]="\n<select name=\"minclassview_".$blk["blockid"]."\" size=\"1\">";
                foreach($alevel as $level)
                    $lefts[$l]["combo_min_view"].="\n<option value=\"".$level["id_level"].($blk["minclassview"] == $level["id_level"] ? "\" selected=\"selected\">" : "\">").$level["level"]."</option>";
                $lefts[$l]["combo_min_view"].="\n</select>";

                $lefts[$l]["combo_max_view"]="\n<select name=\"maxclassview_".$blk["blockid"]."\" size=\"1\">";
                foreach($alevel as $level)
                    $lefts[$l]["combo_max_view"].="\n<option value=\"".$level["id_level"].($blk["maxclassview"] == $level["id_level"] ? "\" selected=\"selected\">" : "\">").$level["level"]."</option>";
                $lefts[$l]["combo_max_view"].="\n</select>";


                $lefts[$l]["combo"]="\n<select name=\"sort_".$blk["blockid"]."\" size=\"1\">";
                for ($i=0;$i<count($br);$i++)
                    $lefts[$l]["combo"].="\n<option value=\"$i\" ".($i==$blk["sortid"]?"selected=\"selected\"":"").">$i</option>";
                $lefts[$l]["combo"].="\n</select>";
                $lefts[$l]["status"]=$blk["status"];
                $lefts[$l]["title"]=$language[$blk["title"]].
                    "&nbsp;&nbsp;<a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=blocks&amp;action=edit&amp;id=".$blk["blockid"]."\">".
                    image_or_link("$STYLEPATH/images/edit.png","",$language["EDIT"])."</a>";
                $lefts[$l]["check"]="<input name=\"status_".$blk["blockid"]."\" type=\"checkbox\" ".($blk["status"]=="1"?"checked=\"checked\"":"")." />";
                $l++;
                break;

          case 'c':
                $centers[$c]["pos"]="\n<select name=\"position_".$blk["blockid"]."\" size=\"1\">";
                $centers[$c]["pos"].="\n<option value=\"t\">".$language["TOP"]."</option>";
                $centers[$c]["pos"].="\n<option value=\"l\">".$language["LEFT"]."</option>";
                $centers[$c]["pos"].="\n<option selected=\"selected\" value=\"c\">".$language["CENTER"]."</option>";
                $centers[$c]["pos"].="\n<option value=\"r\">".$language["RIGHT"]."</option>";
                $centers[$c]["pos"].="\n<option value=\"b\">".$language["BOTTOM"]."</option>";
                $centers[$c]["pos"].="\n</select>";


                $centers[$c]["combo_min_view"]="\n<select name=\"minclassview_".$blk["blockid"]."\" size=\"1\">";
                foreach($alevel as $level)
                    $centers[$c]["combo_min_view"].="\n<option value=\"".$level["id_level"].($blk["minclassview"] == $level["id_level"] ? "\" selected=\"selected\">" : "\">").$level["level"]."</option>";
                $centers[$c]["combo_min_view"].="\n</select>";

                $centers[$c]["combo_max_view"]="\n<select name=\"maxclassview_".$blk["blockid"]."\" size=\"1\">";
                foreach($alevel as $level)
                    $centers[$c]["combo_max_view"].="\n<option value=\"".$level["id_level"].($blk["maxclassview"] == $level["id_level"] ? "\" selected=\"selected\">" : "\">").$level["level"]."</option>";
                $centers[$c]["combo_max_view"].="\n</select>";


                $centers[$c]["combo"]="\n<select name=\"sort_".$blk["blockid"]."\" size=\"1\">";
                for ($i=0;$i<count($br);$i++)
                    $centers[$c]["combo"].="\n<option value=\"$i\" ".($i==$blk["sortid"]?"selected=\"selected\"":"").">$i</option>";
                $centers[$c]["combo"].="\n</select>";
                $centers[$c]["status"]=$blk["status"];
                $centers[$c]["title"]=$language[$blk["title"]].
                    "&nbsp;&nbsp;<a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=blocks&amp;action=edit&amp;id=".$blk["blockid"]."\">".
                    image_or_link("$STYLEPATH/images/edit.png","",$language["EDIT"])."</a>";
                $centers[$c]["check"]="<input name=\"status_".$blk["blockid"]."\" type=\"checkbox\" ".($blk["status"]=="1"?"checked=\"checked\"":"")." />";
                $c++;
                break;

          case 'r':
                $rights[$r]["pos"]="\n<select name=\"position_".$blk["blockid"]."\" size=\"1\">";
                $rights[$r]["pos"].="\n<option value=\"t\">".$language["TOP"]."</option>";
                $rights[$r]["pos"].="\n<option value=\"l\">".$language["LEFT"]."</option>";
                $rights[$r]["pos"].="\n<option value=\"c\">".$language["CENTER"]."</option>";
                $rights[$r]["pos"].="\n<option selected=\"selected\" value=\"r\">".$language["RIGHT"]."</option>";
                $rights[$r]["pos"].="\n<option value=\"b\">".$language["BOTTOM"]."</option>";
                $rights[$r]["pos"].="\n</select>";

                $rights[$r]["combo_min_view"]="\n<select name=\"minclassview_".$blk["blockid"]."\" size=\"1\">";
                foreach($alevel as $level)
                    $rights[$r]["combo_min_view"].="\n<option value=\"".$level["id_level"].($blk["minclassview"] == $level["id_level"] ? "\" selected=\"selected\">" : "\">").$level["level"]."</option>";
                $rights[$r]["combo_min_view"].="\n</select>";

                $rights[$r]["combo_max_view"]="\n<select name=\"maxclassview_".$blk["blockid"]."\" size=\"1\">";
                foreach($alevel as $level)
                    $rights[$r]["combo_max_view"].="\n<option value=\"".$level["id_level"].($blk["maxclassview"] == $level["id_level"] ? "\" selected=\"selected\">" : "\">").$level["level"]."</option>";
                $rights[$r]["combo_max_view"].="\n</select>";


                $rights[$r]["combo"]="\n<select name=\"sort_".$blk["blockid"]."\" size=\"1\">";
                for ($i=0;$i<count($br);$i++)
                    $rights[$r]["combo"].="\n<option value=\"$i\" ".($i==$blk["sortid"]?"selected=\"selected\"":"").">$i</option>";
                $rights[$r]["combo"].="\n</select>";
                $rights[$r]["status"]=$blk["status"];
                $rights[$r]["title"]=$language[$blk["title"]].
                    "&nbsp;&nbsp;<a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=blocks&amp;action=edit&amp;id=".$blk["blockid"]."\">".
                    image_or_link("$STYLEPATH/images/edit.png","",$language["EDIT"])."</a>";
                $rights[$r]["check"]="<input name=\"status_".$blk["blockid"]."\" type=\"checkbox\" ".($blk["status"]=="1"?"checked=\"checked\"":"")." />";
                $r++;
                break;

          case 'b':
                $bottom[$b]["pos"]="\n<select name=\"position_".$blk["blockid"]."\" size=\"1\">";
                $bottom[$b]["pos"].="\n<option value=\"t\">".$language["TOP"]."</option>";
                $bottom[$b]["pos"].="\n<option value=\"l\">".$language["LEFT"]."</option>";
                $bottom[$b]["pos"].="\n<option value=\"c\">".$language["CENTER"]."</option>";
                $bottom[$b]["pos"].="\n<option value=\"r\">".$language["RIGHT"]."</option>";
                $bottom[$b]["pos"].="\n<option selected=\"selected\" value=\"b\">".$language["BOTTOM"]."</option>";
                $bottom[$b]["pos"].="\n</select>";

                $bottom[$b]["combo_min_view"]="\n<select name=\"minclassview_".$blk["blockid"]."\" size=\"1\">";
                foreach($alevel as $level)
                    $bottom[$b]["combo_min_view"].="\n<option value=\"".$level["id_level"].($blk["minclassview"] == $level["id_level"] ? "\" selected=\"selected\">" : "\">").$level["level"]."</option>";
                $bottom[$b]["combo_min_view"].="\n</select>";

                $bottom[$b]["combo_max_view"]="\n<select name=\"maxclassview_".$blk["blockid"]."\" size=\"1\">";
                foreach($alevel as $level)
                    $bottom[$b]["combo_max_view"].="\n<option value=\"".$level["id_level"].($blk["maxclassview"] == $level["id_level"] ? "\" selected=\"selected\">" : "\">").$level["level"]."</option>";
                $bottom[$b]["combo_max_view"].="\n</select>";


                $bottom[$b]["combo"]="\n<select name=\"sort_".$blk["blockid"]."\" size=\"1\">";
                for ($i=0;$i<count($br);$i++)
                    $bottom[$b]["combo"].="\n<option value=\"$i\" ".($i==$blk["sortid"]?"selected=\"selected\"":"").">$i</option>";
                $bottom[$b]["combo"].="\n</select>";
                $bottom[$b]["status"]=$blk["status"];
                $bottom[$b]["title"]=$language[$blk["title"]].
                    "&nbsp;&nbsp;<a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=blocks&amp;action=edit&amp;id=".$blk["blockid"]."\">".
                    image_or_link("$STYLEPATH/images/edit.png","",$language["EDIT"])."</a>";
                $bottom[$b]["check"]="<input name=\"status_".$blk["blockid"]."\" type=\"checkbox\" ".($blk["status"]=="1"?"checked=\"checked\"":"")." />";
                $b++;
                break;
          }
      }
      unset($br);
      $admintpl->set("frm_action","index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=blocks&amp;action=save");
      $admintpl->set("top_blocks",$t>0,true);
      $admintpl->set("left_blocks",$l>0,true);
      $admintpl->set("center_blocks",$c>0,true);
      $admintpl->set("right_blocks",$r>0,true);
      $admintpl->set("bottom_blocks",$b>0,true);
      $admintpl->set("tops",$tops);
      $admintpl->set("lefts",$lefts);
      $admintpl->set("centers",$centers);
      $admintpl->set("rights",$rights);
      $admintpl->set("bottoms",$bottom);
      $admintpl->set("language",$language);
      $admintpl->set("edit_block",false,true);
      $admintpl->set("add_new_block","index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=blocks&amp;action=edit");

}

function position_combo($current="l")
  {
    global $language;
    $ret="\n<select name=\"block_position\" size=\"1\">";
    $ret.="\n<option value=\"t\" ".($current=="t"?"selected=\"selected\"":"").">".$language["TOP"]."</option>";
    $ret.="\n<option value=\"l\" ".($current=="l"?"selected=\"selected\"":"").">".$language["LEFT"]."</option>";
    $ret.="\n<option value=\"c\" ".($current=="c"?"selected=\"selected\"":"").">".$language["CENTER"]."</option>";
    $ret.="\n<option value=\"r\" ".($current=="r"?"selected=\"selected\"":"").">".$language["RIGHT"]."</option>";
    $ret.="\n<option value=\"b\" ".($current=="b"?"selected=\"selected\"":"").">".$language["BOTTOM"]."</option>";
    $ret.="\n</select>";

    return $ret;

}

switch ($action)
    {

      case 'confirm':
        if ($_POST["confirm"]==$language["FRM_CONFIRM"])
          {
            $id=(isset($_GET["id"])?intval($_GET["id"]):0);
            $block_name=sqlesc($_POST["block_name"]);
            $block_position=sqlesc($_POST["block_position"]);
            $block_title=sqlesc($_POST["block_title"]);
            $block_cache=isset($_POST["use_cache"])?"'yes'":"'no'";
            $block_minview=sqlesc(intval($_POST["minclassview"]));
            $block_maxview=sqlesc(intval($_POST["maxclassview"]));
            if ($block_name=="''")
                stderr($language["ERROR"],$language["ERR_BLOCK_NAME"]);
            if ($id>0) // update existing block
              {
              do_sqlquery("UPDATE {$TABLE_PREFIX}blocks SET content=$block_name, position=$block_position, title=$block_title, cache=$block_cache, minclassview=$block_minview, maxclassview=$block_maxview WHERE blockid=$id",true);
            }
            else
              {
              do_sqlquery("INSERT INTO {$TABLE_PREFIX}blocks SET content=$block_name, position=$block_position, title=$block_title, cache=$block_cache, status=0, minclassview=$block_minview, maxclassview=$block_maxview",true);
            }
        }
        read_blocks();
        break;

      case 'edit':

        $rlevel=mysql_query("SELECT DISTINCT id_level, predef_level, level FROM {$TABLE_PREFIX}users_level ORDER BY id_level");
        $alevel=array();
        while($reslevel=mysql_fetch_assoc($rlevel))
            $alevel[]=$reslevel;

        $id=(isset($_GET["id"])?intval($_GET["id"]):0);
        if ($id>0)
          {
            $cb=get_result("SELECT * FROM {$TABLE_PREFIX}blocks WHERE blockid=$id",true);
            if (count($cb)>0)
              {
              $admintpl->set("combo_blocks_name",blocks_combo($cb[0]["content"]));
              $admintpl->set("combo_position",position_combo($cb[0]["position"]));
              $admintpl->set("block_title",$cb[0]["title"]);
              $admintpl->set("block_cache",($cb[0]["cache"]=="yes"?"checked=\"checked\"":""));

              $combo_min_view="\n<select name=\"minclassview\" size=\"1\">";
              foreach($alevel as $level)
                  $combo_min_view.="\n<option value=\"".$level["id_level"].($cb[0]["minclassview"] == $level["id_level"] ? "\" selected=\"selected\">" : "\">").$level["level"]."</option>";
              $combo_min_view.="\n</select>";

              $combo_max_view="\n<select name=\"maxclassview\" size=\"1\">";
              foreach($alevel as $level)
                  $combo_max_view.="\n<option value=\"".$level["id_level"].($cb[0]["maxclassview"] == $level["id_level"] ? "\" selected=\"selected\">" : "\">").$level["level"]."</option>";
              $combo_max_view.="\n</select>";
            }
            else
                stderr($language["ERROR"],$language["BLOCK_BAD_ID"]);
        }
        else
          {
            $admintpl->set("combo_blocks_name",blocks_combo());
            $admintpl->set("combo_position",position_combo());
            $admintpl->set("block_title","");
            $admintpl->set("block_cache","");

            $combo_min_view="\n<select name=\"minclassview\" size=\"1\">";
            foreach($alevel as $level)
                $combo_min_view.="\n<option value=\"".$level["id_level"].($level["id_level"]==1 ? "\" selected=\"selected\">" : "\">").$level["level"]."</option>";
            $combo_min_view.="\n</select>";

            $combo_max_view="\n<select name=\"maxclassview\" size=\"1\">";
            foreach($alevel as $level)
                $combo_max_view.="\n<option value=\"".$level["id_level"].($level["id_level"]==8 ? "\" selected=\"selected\">" : "\">").$level["level"]."</option>";
            $combo_max_view.="\n</select>";
        }

        $admintpl->set("combo_min_view",$combo_min_view);
        $admintpl->set("combo_max_view",$combo_max_view);
        $admintpl->set("frm_action","index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=blocks&amp;action=confirm&amp;id=$id");
        $admintpl->set("language",$language);
        $admintpl->set("edit_block",true,true);
        break;

      case 'save':
        if ($_POST["confirm"]==$language["FRM_CONFIRM"])
          {
            $br=get_result("SELECT * FROM {$TABLE_PREFIX}blocks",true);
            foreach($br as $id=>$block)
               {
                  $active=(isset($_POST["status_".$block["blockid"]])?1:0);
                  $position=sqlesc($_POST["position_".$block["blockid"]]);
                  $sort=max(0,$_POST["sort_".$block["blockid"]]);
                  $block_minview=sqlesc(intval($_POST["minclassview_".$block["blockid"]]));
                  $block_maxview=sqlesc(intval($_POST["maxclassview_".$block["blockid"]]));
                  $id=$block["blockid"];
                  do_sqlquery("UPDATE {$TABLE_PREFIX}blocks SET position=$position, sortid=$sort, status=$active, minclassview=$block_minview, maxclassview=$block_maxview WHERE blockid=$id",true);
            }
        }            
        // don't break, we read the new block's position ;)

      case '':
      case 'read':
      default:
        read_blocks();

}

?>