<?php

if (!defined("IN_BTIT"))
      die("non direct access!");

if (!defined("IN_ACP"))
      die("non direct access!");


include(load_language("lang_usercp.php"));

error_reporting(E_ALL);

switch ($action)
  {

     case 'delete':
        if (isset($_GET["sure"]) && $_GET["sure"]=="1")
        {
            $uid=isset($_GET["uid"])?intval($_GET["uid"]):0;
            do_sqlquery("DELETE FROM {$TABLE_PREFIX}users WHERE id=$uid",true);
            if($GLOBALS["FORUMLINK"]=="smf")
            {
                $language2=$language;
                $basedir=substr(str_replace("\\", "/", dirname(__FILE__)), 0, strrpos(str_replace("\\", "/", dirname(__FILE__)), '/'));
                include($basedir."/smf/Settings.php");
                $language=$language2;
                $smf_fid=isset($_GET["smf_fid"])?intval($_GET["smf_fid"]):0;
                
                do_sqlquery("DELETE FROM {$db_prefix}members WHERE ID_MEMBER=$smf_fid",true);
            }  

            redirect((isset($_GET["returnto"])?urldecode($_GET["returnto"]):"index.php"));
            die();
        }
        else
         {
            $uid=isset($_GET["uid"])?intval($_GET["uid"]):0;
            $curu=get_result("SELECT u.*,ul.level FROM {$TABLE_PREFIX}users u INNER JOIN {$TABLE_PREFIX}users_level ul ON ul.id=u.id_level WHERE u.id=$uid LIMIT 1");
            if (count($curu)>0)
              {
              $profile=array();
              $profile["username"]=unesc($curu[0]["username"]);
              $profile["last_ip"]=unesc($curu[0]["cip"]);
              $profile["level"]=unesc($curu[0]["level"]);
              $profile["joined"]=unesc($curu[0]["joined"]);
              $profile["lastaccess"]=unesc($curu[0]["lastconnect"]);
              $profile["downloaded"]=makesize($curu[0]["downloaded"]);
              $profile["uploaded"]=makesize($curu[0]["uploaded"]);
              $profile["return"]="document.location.href='".(isset($_GET["returnto"])?urldecode($_GET["returnto"]):"index.php")."'";
              $profile["confirm_delete"]="document.location.href='index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=users&amp;action=delete&amp;uid=$uid&amp;smf_fid=".intval($_GET["smf_fid"])."&amp;sure=1&amp;returnto=".(isset($_GET["returnto"])?htmlspecialchars($_GET["returnto"]):"index.php")."'";
              $admintpl->set("user",$profile);
              $admintpl->set("edit_user",false,true);
              $admintpl->set("language",$language);
              $block_title=$language["ACCOUNT_EDIT"];

          }
        }
       break;
        
     case 'edit':
        $uid=isset($_GET["uid"])?intval($_GET["uid"]):0;
        $curu=get_result("SELECT * FROM {$TABLE_PREFIX}users WHERE id=$uid LIMIT 1");
        if (count($curu)>0)
          {
          $profile=array();
          $profile["username"]=unesc($curu[0]["username"]);
          $profile["email"]=unesc($curu[0]["email"]);
          $profile["avatar_field"]=unesc($curu[0]["avatar"]);
          //rank list
          $rres=rank_list();
          $ranktpl="";
          foreach($rres as $rank)
            {
               $ranktpl.="\n<option ";
               if ($rank["id"]==$curu[0]["id_level"])
                    $ranktpl.="selected=\"selected\" ";
               $ranktpl.="value=\"".$rank["level"]."\">".unesc($rank["level"])."</option>";
          }
          unset($rres);

          $admintpl->set("rank_combo",$ranktpl);

          //language list
          $lres=language_list();
          $langtpl="";
          foreach($lres as $langue)
            {
               $langtpl.="\n<option ";
               if ($langue["id"]==$curu[0]["language"])
                    $langtpl.="selected=\"selected\" ";
               $langtpl.="value=\"".$langue["id"]."\">".unesc($langue["language"])."</option>";
          }
          unset($lres);

          $admintpl->set("language_combo",$langtpl);

          //style list
          $sres=style_list();
          $styletpl="";
          foreach($sres as $style)
            {
              $styletpl.="\n<option ";
              if ($style["id"]==$curu[0]["style"])
                    $styletpl.="selected=\"selected\" ";
              $styletpl.="value=\"".$style["id"]."\">".unesc($style["style"])."</option>";
          }
          unset($sres);

          $admintpl->set("style_combo",$styletpl);

          //flag list
          $fres=flag_list();
          $flagtpl="";
          foreach($fres as $flag)
            {
              $flagtpl.="\n<option ";
              if ($flag["id"]==$curu[0]["flag"])
                    $flagtpl.="selected=\"selected\" ";
              $flagtpl.="value=\"".$flag["id"]."\">".unesc($flag["name"])."</option>";
            }
          unset($fres);
          $admintpl->set("flag_combo",$flagtpl);

          //timezone list
          $tres=timezone_list();
          $tztpl="";
          foreach($tres as $timezone)
            {
              $tztpl.="\n<option ";
              if ($timezone["difference"]==$curu[0]["time_offset"])
                    $tztpl.="selected=\"selected\" ";
              $tztpl.="value=\"".$timezone["difference"]."\">".unesc($timezone["timezone"])."</option>";
            }
          unset($tres);
          $admintpl->set("tz_combo",$tztpl);

          if ($FORUMLINK=="" || $FORUMLINK=="internal")
            {
              $admintpl->set("INTERNAL_FORUM",true,true);
              $profile["topicsperpage"]=$curu[0]["topicsperpage"];
              $profile["postsperpage"]=$curu[0]["postsperpage"];
            }

          $profile["torrentsperpage"]=$curu[0]["torrentsperpage"];
          $profile["frm_cancel"]="index.php?page=usercp&amp;uid=".$uid."";

          if ($curu[0]["avatar"]!="")
              $profile["avatar"]=$curu[0]["avatar"];
          else
              $profile["avatar"]="$STYLEURL/images/default_avatar.gif";

          $avatar_size=GetImageSize(htmlspecialchars($profile["avatar"]));
          $profile["avatar"]="<img ".($avatar_size[0]>80?"width=\"80\"":"")." src=\"".htmlspecialchars($profile["avatar"])."\" alt=\"\" />";
          $profile["uploaded"]=$curu[0]["uploaded"];
          $profile["downloaded"]=$curu[0]["downloaded"];
          $profile["down"]=makesize($curu[0]["downloaded"]);
          $profile["up"]=makesize($curu[0]["uploaded"]);
          $profile["ratio"]=($curu[0]["downloaded"]>0?$curu[0]["uploaded"]/$curu[0]["downloaded"]:"");
          $profile["frm_action"]="index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=users&amp;action=save&amp;uid=$uid";

          $admintpl->set("profile",$profile);
          $admintpl->set("edit_user",true,true);
          $admintpl->set("language",$language);
          $block_title=$language["ACCOUNT_EDIT"];
        }

       break;

     case 'save':
       if ($_POST["confirm"]==$language["FRM_CONFIRM"])
         {

         $uid=isset($_GET["uid"])?intval($_GET["uid"]):0;

         $idlangue=intval(0+$_POST["language"]);
         $idstyle=intval(0+$_POST["style"]);
         $idflag=intval(0+$_POST["flag"]);
         $email=AddSlashes($_POST["email"]);
         $avatar=unesc($_POST["avatar"]);

         // new level of the user
         $rlev=mysql_query("SELECT id,id_level FROM {$TABLE_PREFIX}users_level WHERE level='".mysql_escape_string(unesc($_POST["level"]))."'");
         $reslev=mysql_fetch_assoc($rlev);
         if ($CURUSER["id_level"] >= $reslev["id_level"])
             $level=intval($reslev["id"]);
         else
             $level=0;

         // check avatar image extension if someone have better idea ;)
         if ($avatar && $avatar!="" && !in_array(substr($avatar,strlen($avatar)-4),array(".gif",".jpg",".bmp",".png")))
            stderr($language["ERROR"], $language["ERR_AVATAR_EXT"]);
         $set=array();
         if ($idlangue>0)
             $set[]="language=$idlangue";
         if ($idstyle>0)
            $set[]="style=$idstyle";
         if ($idflag>0)
            $set[]="flag=$idflag";
         if ($level>0)
         {
             if($GLOBALS["FORUMLINK"]=="smf")
             {
                 $forlev=$level+10;
                 $language2=$language;
                 $basedir=substr(str_replace("\\", "/", dirname(__FILE__)), 0, strrpos(str_replace("\\", "/", dirname(__FILE__)), '/'));
                 include($basedir."/smf/Settings.php");
                 $language=$language2;
                
                 $sql_query=mysql_query("SELECT smf_fid AS fid FROM {$TABLE_PREFIX}users WHERE id=$uid");
                 $smf=mysql_fetch_assoc($sql_query);
                 if($smf["fid"]>0)
                     do_sqlquery("UPDATE {$db_prefix}members SET ID_GROUP=$forlev WHERE ID_MEMBER=".$smf["fid"],true);
             }  
             $set[]="id_level='$level'";
         }

         $set[]="time_offset=".sqlesc(intval($_POST["timezone"]));
         $set[]="email='$email'";
         $set[]="avatar=".sqlesc(htmlspecialchars($avatar));
         $set[]="topicsperpage=".intval(0+$_POST["topicsperpage"]);
         $set[]="postsperpage=".intval(0+$_POST["postsperpage"]);
         $set[]="torrentsperpage=".intval(0+$_POST["torrentsperpage"]);
         $set[]="torrentsperpage=".intval(0+$_POST["torrentsperpage"]);
         $set[]="uploaded=".(float)($_POST["uploaded"]);
         $set[]="downloaded=".(float)($_POST["downloaded"]);

         $updateset=implode(",",$set);

         if ($updateset!="")
           {
            do_sqlquery("UPDATE {$TABLE_PREFIX}users SET $updateset WHERE id='".$uid."'",true);

            success_msg($language["SUCCESS"], $language["INF_CHANGED"]."<br /><a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."\">".$language["MNU_ADMINCP"]."</a>");
            stdfoot(true,false);
            die();
         }
       }
       redirect("index.php?page=admin&user=".$CURUSER["uid"]."&code=".$CURUSER["random"]);
       exit();
       break;



}
?>