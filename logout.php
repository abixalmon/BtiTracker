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


$THIS_BASEPATH=dirname(__FILE__);

require("include/functions.php");

logoutcookie();

dbconn();

if ($GLOBALS["FORUMLINK"]=="smf")
  {
    $language2=$language;
    require($THIS_BASEPATH.'/smf/SSI.php');
    require($THIS_BASEPATH.'/smf/Settings.php');
    require($THIS_BASEPATH.'/smf/Sources/Subs-Auth.php');
    $language=$language2;

    setLoginCookie(-3600, 0);
}

header("Location: index.php");

?>