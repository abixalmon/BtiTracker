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

if ($moderate_user)
  {
    $admin_menu=array(
    0=>array(
            "title"=>$language["ACP_USERS_TOOLS"],
            "menu"=>array(0=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=masspm&amp;action=write" ,
                    "description"=>$language["ACP_MASSPM"]),
                          1=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=pruneu" ,
                    "description"=>$language["ACP_PRUNE_USERS"]),
                          2=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=searchdiff" ,
                    "description"=>$language["ACP_SEARCH_DIFF"])
                    )
            ),
    );

}
else
  {
    $admin_menu=array(
    0=>array(
            "title"=>$language["ACP_TRACKER_SETTINGS"],
            "menu"=>array(0=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=config&amp;action=read" ,
                    "description"=>$language["ACP_TRACKER_SETTINGS"]),
                          1=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=banip&amp;action=read" ,
                    "description"=>$language["ACP_BAN_IP"]),

                          2=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=language&amp;action=read" ,
                    "description"=>$language["ACP_LANGUAGES"]),
                          3=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=style&amp;action=read" ,
                    "description"=>$language["ACP_STYLES"])  
                                 )),
    1=>array(
            "title"=>$language["ACP_FRONTEND"],
            "menu"=>array(0=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=category&amp;action=read" ,
                    "description"=>$language["ACP_CATEGORIES"]),
                          1=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=poller&amp;action=read" ,
                    "description"=>$language["ACP_POLLS"]),
                          2=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=badwords&amp;action=read" ,
                    "description"=>$language["ACP_CENSURED"]),
                          3=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=blocks&amp;action=read" ,
                    "description"=>$language["ACP_BLOCKS"])
                    )
            ),
    2=>array(
            "title"=>$language["ACP_USERS_TOOLS"],
            "menu"=>array(0=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=groups&amp;action=read" ,
                    "description"=>$language["ACP_USER_GROUP"]),
                          1=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=masspm&amp;action=write" ,
                    "description"=>$language["ACP_MASSPM"]),
                          2=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=pruneu" ,
                    "description"=>$language["ACP_PRUNE_USERS"]),
                          3=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=searchdiff" ,
                    "description"=>$language["ACP_SEARCH_DIFF"])
                    )
            ),

    3=>array(
            "title"=>$language["ACP_TORRENTS_TOOLS"],
            "menu"=>array(0=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=prunet" ,
                    "description"=>$language["ACP_PRUNE_TORRENTS"]))
            ),

    4=>array(
            "title"=>$language["ACP_FORUM"],
            "menu"=>array(0=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=forum&amp;action=read" ,
                    "description"=>$language["ACP_FORUM"])
                    )
            ),

    5=>array(
            "title"=>$language["ACP_OTHER_TOOLS"],
            "menu"=>array(0=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=dbutil" ,
                    "description"=>$language["ACP_DBUTILS"]),
                          1=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=mysql_stats" ,
                    "description"=>$language["ACP_MYSQL_STATS"]),
                          2=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=logview" ,
                    "description"=>$language["ACP_SITE_LOG"])
                    )
            ),
            
    6=>array(
            "title"=>$language["ACP_MODULES"],
            "menu"=>array(0=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=module_config&amp;action=manage" ,
                    "description"=>$language["ACP_MODULES_CONFIG"])
                    )
            ),

    7=>array(
            "title"=>$language["ACP_HACKS"],
            "menu"=>array(0=>array(
                    "url"=>"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=hacks&amp;action=read" ,
                    "description"=>$language["ACP_HACKS_CONFIG"])
                    )
            ),

    );
}
?>