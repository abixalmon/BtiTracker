<?php

if (file_exists("install.unlock") && file_exists("install.php"))
   {
   if (dirname($_SERVER["PHP_SELF"])=="/" || dirname($_SERVER["PHP_SELF"])=="\\")
      header("Location: http://".$_SERVER["HTTP_HOST"]."/install.php");
   else
      header("Location: http://".$_SERVER["HTTP_HOST"].dirname($_SERVER["PHP_SELF"])."/install.php");
   exit;
}

define("IN_BTIT",true);


$THIS_BASEPATH=dirname(__FILE__);

include("$THIS_BASEPATH/btemplate/bTemplate.php");

require("$THIS_BASEPATH/include/functions.php");

$time_start = get_microtime();

//require_once ("$THIS_BASEPATH/include/config.php");

dbconn(true);


// get user's style
$resheet=do_sqlquery("SELECT * FROM {$TABLE_PREFIX}style where id=".$CURUSER["style"]."");
if (!$resheet)
   {

   $STYLEPATH="$THIS_BASEPATH/style/btit";
   $STYLEURL="$BASEURL/style/btit";
}
else
    {
        $resstyle=mysql_fetch_array($resheet);
        $STYLEPATH="$THIS_BASEPATH/".$resstyle["style_url"];
        $STYLEURL="$BASEURL/".$resstyle["style_url"];
}

$style_css=load_css("main.css");


$idlang=intval($_GET["language"]);

$pageID=(isset($_GET["page"])?$_GET["page"]:"");

$no_columns=(isset($_GET["nocolumns"]) && intval($_GET["nocolumns"])==1?true:false);

// getting user language
if ($idlang==0)
   $reslang=do_sqlquery("SELECT * FROM {$TABLE_PREFIX}language WHERE id=".$CURUSER["language"]);
else
   $reslang=do_sqlquery("SELECT * FROM {$TABLE_PREFIX}language WHERE id=$idlang");

if (!$reslang)
   {
   $USERLANG="$THIS_BASEPATH/language/english";
   }
else
    {
        $rlang=mysql_fetch_array($reslang);
        $USERLANG="$THIS_BASEPATH/".$rlang["language_url"];
    }



clearstatcache();

/*
if (!file_exists($USERLANG))
    {
    err_msg("Error!","Missing Language!");
    print_version();
    print("</body>\n</html>\n");
    die;
}
*/

session_start();


check_online(session_id(), ($pageID==""?"index":$pageID));

require(load_language("lang_main.php"));


$tpl=new bTemplate();
$tpl->set("main_title","Index");
$tpl->set("main_charset",$GLOBALS["charset"]);
$tpl->set("main_css","$style_css");


require_once("$THIS_BASEPATH/include/blocks.php");



$morescript="
  <script type=\"text/javascript\" src=\"$BASEURL/jscript/ajax.js\"></script>\n
  <script type=\"text/javascript\" src=\"$BASEURL/jscript/ajax-poller.js\"></script>\n
  <script type=\"text/javascript\" src=\"$BASEURL/jscript/animatedcollapse.js\"></script>\n
  <script language=\"Javascript\" type=\"text/javascript\">

  <!--

  var newwindow;
  function popdetails(url)
  {
    newwindow=window.open(url,'popdetails','height=500,width=500,resizable=yes,scrollbars=yes,status=yes');
    if (window.focus) {newwindow.focus()}
  }

  function poppeer(url)
  {
    newwindow=window.open(url,'poppeers','height=400,width=650,resizable=yes,scrollbars=yes');
    if (window.focus) {newwindow.focus()}
  }

  // -->
  </script>";

$logo="<div align=\"center\" style=\"margin-top:0px;\"><img src=\"$STYLEURL/images/logo.gif\" border=\"0\" alt=\"\" /></div>";
$slideIt="<span style=\"align:right;\"><a href=\"javascript:collapse2.slideit()\"><img src=\"$STYLEURL/images/slide.png\" border=\"0\" alt=\"\" /></a></span>";
$header.="<div>".main_menu()."</div>";


$tpl->set("main_jscript",$morescript);
if (!$no_columns && $pageID1='admin') {
  $tpl->set("main_left",side_menu());
  $tpl->set("main_right",right_menu());
}
$tpl->set("tracker_logo",$logo);

$tpl->set("main_slideIt",$slideIt);

$tpl->set("main_header",$header.$err_msg_install);

$tpl->set("more_css","");

// assign main content
switch ($pageID) {

    case 'admin':
        require("$THIS_BASEPATH/admin/admin.index.php");
        $tpl->set("main_title","Index->Admin");
        // the main_content for current template is setting within admin/index.php
        break;

// shouthistory
    case 'allshout':
        ob_start();
        require("$THIS_BASEPATH/ajaxchat/getHistoryChatData.php");
        $tpl->set("main_title","Index->Shout History");
        $out=ob_get_contents();
        ob_end_clean();
        $tpl->set("main_content",set_block($language["SHOUTBOX"]." ".$language["HISTORY"],"left",$out));
        break;
/*
    case 'allshout':
        require("$THIS_BASEPATH/allshout.php");
        $tpl->set("main_content",set_block($language["SHOUTBOX"]." ".$language["HISTORY"],"center",$tpl_shout->fetch(load_template("shoutbox_history.tpl")),($GLOBALS["usepopup"]?false:true)));
        $tpl->set("main_title","Index->All Shout");
        break;
*/
    case 'comment':
        require("$THIS_BASEPATH/comment.php");
        $tpl->set("main_content",set_block($language["COMMENTS"],"center",$tpl_comment->fetch(load_template("comment.tpl")),false));
        $tpl->set("main_title","Index->Torrent->Comment");
        break;

    case 'delete':
        require("$THIS_BASEPATH/delete.php");
        $tpl->set("main_content",set_block($language["DELETE_TORRENT"],"center",$torrenttpl->fetch(load_template("torrent.delete.tpl"))));
        $tpl->set("main_title","Index->Torrent->Delete");
        break;

    case 'edit':
        require("$THIS_BASEPATH/edit.php");
        $tpl->set("main_content",set_block($language["EDIT_TORRENT"],"center",$torrenttpl->fetch(load_template("torrent.edit.tpl"))));
        $tpl->set("main_title","Index->Torrent->Edit");
        break;

    case 'extra-stats':
        require("$THIS_BASEPATH/extra-stats.php");
        $tpl->set("main_content",set_block($language["MNU_STATS"],"center",$out));
        $tpl->set("main_title","Index->Statistics");
        break;

    case 'forum':
        require("$THIS_BASEPATH/forum/forum.index.php");
        $tpl->set("main_title","Index->Forum");
        break;

    case 'history':
        require("$THIS_BASEPATH/torrent_history.php");
        $tpl->set("main_content",set_block($language["MNU_TORRENT"],"center",$historytpl->fetch(load_template("torrent_history.tpl"))));
        $tpl->set("main_title","Index->Torrent->History");
        break;

    case 'login':
        require("$THIS_BASEPATH/login.php");
        $tpl->set("main_content",set_block($language["LOGIN"],"center",$logintpl->fetch(load_template("login.tpl"))));
        $tpl->set("main_title","Index->Login");
        break;

    case 'moresmiles':
        require("$THIS_BASEPATH/moresmiles.php");
        $tpl->set("main_content",set_block($language["MORE_SMILES"],"center",$moresmiles_tpl->fetch(load_template("moresmiles.tpl"))));
        $tpl->set("main_title","More Smilies");
        break;

   case 'news':
        require("$THIS_BASEPATH/news.php");
        $tpl->set("main_content",set_block($language["MANAGE_NEWS"],"center",$newstpl->fetch(load_template("news.tpl"))));
        $tpl->set("main_title","Index->News");
        break;

    case 'peers':
        require("$THIS_BASEPATH/peers.php");
        $tpl->set("main_content",set_block($language["MNU_TORRENT"],"center",$peerstpl->fetch(load_template("peers.tpl"))));
        $tpl->set("main_title","Index->Torrent->Peers");
        break;


    case 'recover':
        require("$THIS_BASEPATH/recover.php");
        $tpl->set("main_content",set_block($language["RECOVER_PWD"],"center",$recovertpl->fetch(load_template("recover.tpl"))));
        $tpl->set("main_title","Index->Recover");
        break;

    case 'account':
    case 'signup':
        require("$THIS_BASEPATH/account.php");
        $tpl->set("more_css","<link rel=\"stylesheet\" type=\"text/css\" href=\"$BASEURL/jscript/passwdcheck.css\" />");
        $tpl->set("main_content",set_block($language["ACCOUNT_CREATE"],"center",$tpl_account->fetch(load_template("account.tpl"))));
        $tpl->set("main_title","Index->Signup");
        break;


    case 'torrents':
        require("$THIS_BASEPATH/torrents.php");
        $tpl->set("main_content",set_block($language["MNU_TORRENT"],"center",$torrenttpl->fetch(load_template("torrent.list.tpl"))));
        $tpl->set("main_title","Index->Torrents");
        break;

    case 'torrent-details':
        require("$THIS_BASEPATH/details.php");
        $tpl->set("main_content",set_block($language["TORRENT_DETAIL"],"center",$torrenttpl->fetch(load_template("torrent.details.tpl")),($GLOBALS["usepopup"]?false:true)));
        $tpl->set("main_title","Index->Torrent->Details");
        break;

    case 'users':
        require("$THIS_BASEPATH/users.php");
        $tpl->set("main_content",set_block($language["MEMBERS_LIST"],"center",$userstpl->fetch(load_template("users.tpl"))));
        $tpl->set("main_title","Index->Users");
        break;


    case 'usercp':
        require("$THIS_BASEPATH/user/usercp.index.php");
        // the main_content for current template is setting within users/index.php
        $tpl->set("main_title","Index->My Panel");
        break;

    case 'upload':
        require("$THIS_BASEPATH/upload.php");
        $tpl->set("main_content",set_block($language["MNU_UPLOAD"],"center",$uploadtpl->fetch(load_template("$tplfile.tpl"))));
        $tpl->set("main_title","Index->Torrent->Upload");
        break;

    case 'userdetails':
        require("$THIS_BASEPATH/userdetails.php");
        $tpl->set("main_content",set_block($language["USER_DETAILS"],"center",$userdetailtpl->fetch(load_template("userdetails.tpl"))));
        $tpl->set("main_title","Index->Users->Details");
        break;

    case 'viewnews':
        require("$THIS_BASEPATH/viewnews.php");
        $tpl->set("main_content",set_block($language["LAST_NEWS"],"center",$viewnewstpl->fetch(load_template("viewnews.tpl"))));
        $tpl->set("main_title","Index->News");
        break;

    
    case 'index':
    case '':
    default:
        $tpl->set("main_content",center_menu());
        break;
}



// controll if client can handle gzip
if ($GZIP_ENABLED)
    {
     if (stristr($_SERVER["HTTP_ACCEPT_ENCODING"],"gzip") && extension_loaded('zlib') && ini_get("zlib.output_compression") == 0)
         {
         if (ini_get('output_handler')!='ob_gzhandler')
             {
             ob_start("ob_gzhandler");
             $gzip='enabled';
             }
         else
             {
             ob_start();
             $gzip='enabled';
             }
     }
     else
         {
         ob_start();
         $gzip='disabled';
         }
}
else
    $gzip='disabled';




// fetch page with right template
switch ($pageID) {

    // for admin page we will display page with header and only left column (for menu)
    case 'admin':
        stdfoot(false,false,true);
        break;

    // if popup enabled then we display the page without header and no columns, else full page
    case 'comment':
    case 'torrent-details':
    case 'peers':
        stdfoot(($GLOBALS["usepopup"]?false:true));
        break;

    // we display the page without header and no columns
    case 'allshout':
    case 'moresmiles':
        stdfoot(false);
        break;

    // full page
    default:
        stdfoot();
        break;
}




?>