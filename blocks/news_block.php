<?php
require_once ("include/blocks.php");
if (!isset($CURUSER)) global $CURUSER;
if (!$CURUSER || $CURUSER["view_news"]=="no")
   {
       //err_msg(ERROR,NOT_AUTH_VIEW_NEWS."!");
       //stdfoot();
       //exit;
       // modified 1.2
       // do nothing - the exit terminate the script, not really good
}
else{
    global $USERLANG,$THIS_BASEPATH,$BASEURL;
    $limit=$GLOBALS["block_newslimit"];
    $root_path=realpath(dirname(__FILE__)."/../");
    require("$root_path/viewnews.php");
    echo $viewnewstpl->fetch(load_template("viewnews.tpl"));
}
?>