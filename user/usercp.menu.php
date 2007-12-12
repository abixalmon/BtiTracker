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

$usercp_menu=array(
0=>array(
        "title"=>$language["MNU_UCP_HOME"],
        "menu"=>array(0=>array(
                "url"=>"index.php?page=usercp&amp;uid=".$uid."" ,
                "description"=>$language["MNU_UCP_HOME"]))
        ),
1=>array(
        "title"=>$language["MNU_UCP_PM"],
        "menu"=>array(0=>array(
                "url"=>"index.php?page=usercp&amp;uid=".$uid."&amp;do=pm&amp;action=list&amp;what=inbox" ,
                "description"=>$language["MNU_UCP_PM"]),
                      1=>array(
                "url"=>"index.php?page=usercp&amp;uid=".$uid."&amp;do=pm&amp;action=list&amp;what=outbox" ,
                "description"=>$language["MNU_UCP_OUT"]),

                      2=>array(
                "url"=>"index.php?page=usercp&amp;do=pm&amp;action=edit&amp;uid=".$uid."&amp;what=new" ,
                "description"=>$language["MNU_UCP_NEWPM"]), 
                             )),
2=>array(
        "title"=>$language["MNU_UCP_INFO"],
        "menu"=>array(0=>array(
                "url"=>"index.php?page=usercp&amp;do=user&amp;action=change&amp;uid=".$uid."" ,
                "description"=>$language["MNU_UCP_INFO"]),
                      1=>array(
                "url"=>"index.php?page=usercp&amp;do=pwd&amp;action=change&amp;uid=".$uid."" ,
                "description"=>$language["MNU_UCP_CHANGEPWD"]),
                      2=>array(
                "url"=>"index.php?page=usercp&amp;do=pid_c&amp;action=change&amp;uid=".$uid."" ,
                "description"=>$language["CHANGE_PID"]), 
                             )),
);
?>