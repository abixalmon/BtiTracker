<?php

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