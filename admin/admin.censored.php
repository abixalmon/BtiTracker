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
    case 'write':
      if ($_POST["write"]==$language["FRM_CONFIRM"])
         {
         if (isset($_POST["badwords"]))
            {
            $f=fopen("badwords.txt","w+");
            @fwrite($f,$_POST["badwords"]);
            fclose($f);
            }
         }


    case '':
    case 'read':
    default:
      $f=@fopen("$THIS_BASEPATH/badwords.txt","r");
      $badwords=@fread($f,filesize("$THIS_BASEPATH/badwords.txt"));
      @fclose($f);

      $admintpl->set("language",$language);
      $admintpl->set("censored_text",$badwords);
      $admintpl->set("frm_action","index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=badwords&amp;action=write");

}

?>