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

switch ($action)
{
    case 'post':
           $idlangue=intval(0+$_POST["language"]);
           $idstyle=intval(0+$_POST["style"]);
           $email=AddSlashes($_POST["email"]);
           $avatar=htmlspecialchars(AddSlashes($_POST["avatar"]));
           $idflag=intval(0+$_POST["flag"]);
           $timezone=intval($_POST["timezone"]);

           // Password confirmation required to update user record
           (isset($_POST["passconf"])) ? $password=md5($_POST["passconf"]) : $password="";
                      
           $res=mysql_query("SELECT password FROM {$TABLE_PREFIX}users WHERE id=".$CURUSER["uid"]);
           if(mysql_num_rows($res)>0)
               $user=mysql_fetch_assoc($res);           

           if(!isset($user) || $password=="" || $user["password"]!=$password)
           {
               stderr($language["ERROR"], $language["ERR_PASS_WRONG"]);
               stdfoot();
               exit();
           }
           // Password confirmation required to update user record


           // check avatar image extension if someone have better idea ;)
           if ($avatar && $avatar!="" && !in_array(substr($avatar,strlen($avatar)-4),array(".gif",".jpg",".bmp",".png")))
              {
                stderr($language["ERROR"], $language["ERR_AVATAR_EXT"]);
                stdfoot();
                exit;
              }

           if ($email=="")
          {
            err_msg($language["ERROR"],$language["ERR_NO_EMAIL"]);
            stdfoot();
            exit;
          }
           else
               {
               // Reverify Mail Hack by Petr1fied - Start --->
               if ($VALIDATION=="user") {
                   // Send a verification e-mail to the e-mail address they want to change it to
                   if (($email!="")&&($email!=$CURUSER["email"])) {
                       $id=$CURUSER["uid"];
                       // Generate a random number between 10000 and 99999
                       $floor = 100000;
                       $ceiling = 999999;
                       srand((double)microtime()*1000000);
                       $random = rand($floor, $ceiling);

                       // Update the members record with the random number and store the email they want to change to
                       do_sqlquery("UPDATE {$TABLE_PREFIX}users SET random='".$random."', temp_email='".$email."' WHERE id='".$id."'");

                       // Send the verification email
                       @ini_set("sendmail_from","");
                       if (mysql_errno()==0)
                          mail($email,$language["EMAIL_VERIFY"],$language["EMAIL_VERIFY_MSG"]."\n\n".$BASEURL."/index.php?page=usercp&do=verify&action=changemail&newmail=".$email."&uid=".$id."&random=".$random."","From: ".$SITENAME." <".$SITEEMAIL.">") OR stderr($language["ERROR"],$language["EMAIL_FAILED"]);
                       }
               }
               $set=array();

               if ($VALIDATION!="user") {
                   if ($email!="")
                   $set[]="email='$email'";
                }
                // <--- Reverify Mail Hack by Petr1fied - End
               if ($idlangue>0)
                  $set[]="language=$idlangue";
               if ($idstyle>0)
                  $set[]="style=$idstyle";
               if ($idflag>0)
                  $set[]="flag=$idflag";

               $set[]="time_offset='$timezone'";
               $set[]="avatar='$avatar'";
               $set[]="topicsperpage=".intval(0+$_POST["topicsperpage"]);
               $set[]="postsperpage=".intval(0+$_POST["postsperpage"]);
               $set[]="torrentsperpage=".intval(0+$_POST["torrentsperpage"]);

               $updateset=implode(",",$set);

               // Reverify Mail Hack by Petr1fied - Start --->
               // If they've tried to change their e-mail, give them a message telling them as much
               if (($email!="")&&($VALIDATION=="user")&&($email!=$CURUSER["email"]))
                  {
                  success_msg($language["EMAIL_VERIFY_BLOCK"], "".$language["EMAIL_VERIFY_SENT1"]." ".$email." ".$language["EMAIL_VERIFY_SENT2"]."<a href=\"".$BASEURL."\">".$language["MNU_INDEX"]."</a>");
                  stdfoot(true,false);
                  }
               elseif ($updateset!="")
               // <--- Reverify Mail Hack by Petr1fied - End
                  {
                  do_sqlquery("UPDATE {$TABLE_PREFIX}users SET $updateset WHERE id='".$uid."'") or die(mysql_error());

                  success_msg($language["SUCCESS"], $language["INF_CHANGED"]."<br /><a href=\"index.php?page=usercp&amp;uid=".$uid."\">".$language["BCK_USERCP"]."</a>");
                  stdfoot(true,false);
                  }
              }
    break;

    case '':
    case 'change':
    default:
      $usercptpl->set("AVATAR",false,true);
      $usercptpl->set("USER_VALIDATION",false,true);
      $usercptpl->set("INTERNAL_FORUM",false,true);
      $profiletpl=array();
      $profiletpl["frm_action"]="index.php?page=usercp&amp;do=user&amp;action=post&amp;uid=".$uid."";
      $profiletpl["username"]=$CURUSER["username"];

      //avatar
      if ($CURUSER["avatar"] && $CURUSER["avatar"]!="")
        {
          $usercptpl->set("AVATAR",true,true);
          $profiletpl["avatar"]=unesc($CURUSER["avatar"]);
        }

      $profiletpl["avatar_field"]=unesc($CURUSER["avatar"]);
      $profiletpl["email"]=unesc($CURUSER["email"]);

      //Reverify Mail Hack by Petr1fied - Start
      if ($VALIDATION=="user")
        {
          //Display a message informing users that they will have
          //to verify their e-mail address if they attempt to change it
          $usercptpl->set("USER_VALIDATION",true,true);
        }
      //Reverify Mail Hack by Petr1fied - End

      //language list
      $lres=language_list();
      $langtpl=array();
        foreach($lres as $langue)
          {
             $langtpl["language_combo"].="\n<option ";
         if ($langue["id"]==$CURUSER["language"])
        $langtpl["language_combo"].="selected=\"selected\" ";
         $langtpl["language_combo"].="value=\"".$langue["id"]."\">".unesc($langue["language"])."</option>";
         $langtpl["language_combo"].=($option);
           }
        unset($lres);
      $usercptpl->set("lang",$langtpl);

      //style list
      $sres=style_list();
      $styletpl=array();
        foreach($sres as $style)
          {
        $styletpl["style_combo"].="\n<option ";
          if ($style["id"]==$CURUSER["style"])
        $styletpl["style_combo"].="selected=\"selected\" ";
        $styletpl["style_combo"].="value=\"".$style["id"]."\">".unesc($style["style"])."</option>";
        $styletpl["style_combo"].=($option);
          }
        unset($sres);
      $usercptpl->set("style",$styletpl);

      //flag list
      $fres=flag_list();
      $flagtpl=array();
        foreach($fres as $flag)
          {
        $flagtpl["flag_combo"].="\n<option ";
          if ($flag["id"]==$CURUSER["flag"])
        $flagtpl["flag_combo"].="selected=\"selected\" ";
        $flagtpl["flag_combo"].="value=\"".$flag["id"]."\">".unesc($flag["name"])."</option>";
        $flagtpl["flag_combo"].=($option);
          }
        unset($fres);
      $usercptpl->set("flag",$flagtpl);

      //timezone list
      $tres=timezone_list();
      $tztpl=array();
        foreach($tres as $timezone)
          {
        $tztpl["tz_combo"].="\n<option ";
          if ($timezone["difference"]==$CURUSER["time_offset"])
        $tztpl["tz_combo"].="selected=\"selected\" ";
        $tztpl["tz_combo"].="value=\"".$timezone["difference"]."\">".unesc($timezone["timezone"])."</option>";
        $tztpl["tz_combo"].=($option);
          }
        unset($tres);
      $usercptpl->set("tz",$tztpl);

      if ($FORUMLINK=="" || $FORUMLINK=="internal")
        {
          $usercptpl->set("INTERNAL_FORUM",true,true);
          $profiletpl["topicsperpage"]=$CURUSER["topicsperpage"];
          $profiletpl["postsperpage"]=$CURUSER["postsperpage"];
        }

      $profiletpl["torrentsperpage"]=$CURUSER["torrentsperpage"];
      $profiletpl["frm_cancel"]="index.php?page=usercp&amp;uid=".$uid."";
      $usercptpl->set("profile",$profiletpl);
    break;
}
?>