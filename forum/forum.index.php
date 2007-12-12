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

if (!$CURUSER || $CURUSER["view_forum"]!="yes")
   stderr(ERROR,NOT_AUTHORIZED." ".MNU_FORUM);

if ($btit_settings["forum"]=="smf")
  {
     $FORUMLINK=$BASEURL."/".$btit_settings["forum"];
     $smf_content="";
     $smf_content.="
     <script type=\"text/javascript\" language=\"JavaScript\">

     function autoIframe(frameId){
     var newheight
              try{
                newheight = document.getElementById(frameId).contentWindow.document.body.scrollHeight;
                document.getElementById(frameId).height = newheight + 45;
              }
                catch(err){
                window.status = err.message;
              }
     }


     function autoResize(id){

     var newheight;

     if (!window.opera && !document.mimeType && document.all && document.getElementById){

     newheight=document.getElementById(id).contentWindow.document.body.offsetHeight;

     }else if(document.getElementById){

     newheight=document.getElementById(id).contentWindow.document.body.scrollHeight;

     }

     document.getElementById(id).height= (newheight + 45) + \"px\";

     }

     </script>
     <noscript>".
     err_msg($language["ERROR"], "Resizable window will not work without Javascript.<br />Please enable Javascript or view the forum in a new window <a target='_new' href='$BASEURL/$FORUMLINK'>Here</a>")
     ."</noscript>";
    
     $topic=intval($_GET["topicid"]);
     $action=htmlspecialchars($_GET["action"]);
     $user=intval($_GET["userid"]);

     if ($action=="viewtopic")
       {
       $smf_content.="
          <div align=\"center\">
          <iframe id=\"forum_ifrm\" onload=\"autoIframe('forum_ifrm')\" name=\"Forum\" border=\"0\" frameborder=\"0\" src=\"$FORUMLINK/index.php?topic=$topic\" width=\"98%\">Your browser don't support iframe, then click <a href=\"$FORUMLINK/index.php?topic=$topic\">here</a> to get forum page</iframe>
          </div>";
      }
     elseif (substr($action, 0, 7)=="profile")
       {
       $smf_content.="
          <div align=\"center\">
          <iframe id=\"forum_ifrm\" onload=\"autoIframe('forum_ifrm')\" name=\"Forum\" border=\"0\" frameborder=\"0\" src=\"$FORUMLINK/index.php?action=$action\" width=\"98%\">Your browser don't support iframe, then click <a href=\"$FORUMLINK/index.php?action=$action\">here</a> to get forum page</iframe>
          </div>";
      }
     elseif (substr($action, 0, 2)=="pm")
       {
       $smf_content.="
          <div align=\"center\">
          <iframe id=\"forum_ifrm\" onload=\"autoIframe('forum_ifrm')\" name=\"Forum\" border=\"0\" frameborder=\"0\" src=\"$FORUMLINK/index.php?action=$action\" width=\"98%\">Your browser don't support iframe, then click <a href=\"$FORUMLINK/index.php?action=$action\">here</a> to get forum page</iframe>
          </div>";
      }
      
     else
       {
       $smf_content.="
          <div align=\"center\">
          <iframe id=\"forum_ifrm\" onload=\"autoIframe('forum_ifrm')\" name=\"Forum\" border=\"0\" frameborder=\"0\" src=\"$FORUMLINK/index.php\" width=\"98%\">Your browser don't support iframe, then click <a href=\"$FORUMLINK/index.php\">here</a> to get forum page</iframe>
          </div>";
      }

     $tpl->set("main_content",set_block($block_title,"center",$smf_content));


}
else
  {
    if (isset($_GET["action"])) $action = $_GET["action"];
      else $action = "";


    define("IN_BTIT_FORUM",true);

    function highlight_search($ori_string,$hl_words)
    {
         if (is_array($hl_words))
           {
           foreach ($hl_words as $hl)
             $ori_string=highlight_search($ori_string,$hl);
         }
         $h=strtoupper($ori_string);
         $n=strtoupper($hl_words);
         $pos=strpos($h,$n);
         if ($pos !== false)
             {
            $var=substr($ori_string,0,$pos)."<span class=\"highlight\">".substr($ori_string,$pos,strlen($hl_words))."</span>";
            $var.=substr($ori_string,($pos+strlen($hl_words)));
            $ori_string=$var;
            }
         return $ori_string;
    }



    function forum_pager($rpp, $count, $href, $opts = array()) {

        global $language;

        if($rpp!=0) $pages = ceil($count / $rpp);
        else $pages=1;

        if (!isset($opts["lastpagedefault"]))
            $pagedefault = 1;
        else {
            $pagedefault = floor(($count - 1) / $rpp);
            if ($pagedefault < 1)
                $pagedefault = 1;
        }

        $pagename="pages";

        if (isset($opts["pagename"]))
          {
           $pagename=$opts["pagename"];
           if (isset($_GET[$opts["pagename"]]))
              $page = max(1 ,intval($_GET[$opts["pagename"]]));
           else
              $page = $pagedefault;
          }
        elseif (isset($_GET["pages"])) {
            $page = max(1,intval(0 + $_GET["pages"]));
            if ($page < 0)
                $page = $pagedefault;
        }
        else
            $page = $pagedefault;

        $pager = "";

        if ($pages>1)
          {
            $pager.="\n<form name=\"change_page\" method=\"post\" action=\"index.php\">\n<select class=\"drop_pager\" name=\"pages\" onchange=\"location=document.change_page.pages.options[document.change_page.pages.selectedIndex].value\" size=\"1\">";
            for ($i = 1; $i<=$pages;$i++)
                $pager.="\n<option ".($i==$page?"selected=\"selected\"":"")."value=\"$href$pagename=$i\">$i</option>";
            $pager.="\n</select>";
        }

        $mp = $pages;// - 1;
        $begin=($page > 3?($page<$pages-2?$page-2:$pages-2):1);
        $end=($pages>$begin+2?($begin+2<$pages?$begin+2:$pages):$pages);
        if ($page > 1)
          {
            $pager .= "\n&nbsp;<span class=\"pager\"><a href=\"{$href}$pagename=1\">&nbsp;&laquo;</a></span>";
            $pager .= "\n<span class=\"pager\"><a href=\"{$href}$pagename=".($page-1)."\">&lt;&nbsp;</a></span>";
        }
    //    else
    //        $pager .= "\n<span class=\"pager\">&lt;&nbsp;</span>";

        if ($count) {
            for ($i = $begin; $i <= $end; $i++) {
                if ($i != $page)
                    $pager .= "\n&nbsp;<span class=\"pager\"><a href=\"{$href}$pagename=$i\">$i</a></span>";
                else
                    $pager .= "\n&nbsp;<span class=\"pagercurrent\"><b>$i</b></span>";
            }


            if ($page < $mp && $mp >= 1)
             {
                $pager .= "\n&nbsp;<span class=\"pager\"><a href=\"{$href}$pagename=".($page+1)."\">&nbsp;&gt;</a></span>";
                $pager .= "\n&nbsp;<span class=\"pager\"><a href=\"{$href}$pagename=$pages\">&nbsp;&raquo;</a></span>";
            }
    //        else
    //            $pager .= "\n&nbsp;<span class=\"pager\">&nbsp;&gt;</span>";

            $pagertop = "$pager\n";
            $pagerbottom = str_replace("change_page","change_page1",$pager)."\n</form>";
        }
        else {
            $pagertop = "$pager\n";
            $pagerbottom = str_replace("change_page","change_page1",$pagertop)."\n</form>";
        }

        $start = ($page-1) * $rpp;
        if ($pages<2)
            {
            // only 1 page??? don't need pager ;)
            $pagertop="";
            $pagerbottom="";
        }
        return array($pagertop, $pagerbottom, "LIMIT $start,$rpp");

    }


    $FORUM_PATH=dirname(__FILE__);

    include(load_language("lang_forum.php"));

    $forumtpl=new bTemplate();
    $forumtpl->set("language",$language);

    switch ($action)
        {

        case 'editpost':
          include("$FORUM_PATH/forum.post.php");
          $tpl->set("main_content",set_block($block_title,"center",$forumtpl->fetch(load_template("forum.editpost.tpl"))));
          break;

        case 'catchup':
        case 'deletetopic':
        case 'movetopic':
        case 'setsticky':
        case 'rename':
        case 'setlocked':
        case 'deletepost':
          include("$FORUM_PATH/forum.actions.php");
          break;

        case 'newtopic':
        case 'post':
        case 'quotepost':
        case 'reply':
          include("$FORUM_PATH/forum.post.php");
          $tpl->set("main_content",set_block($block_title,"center",$forumtpl->fetch(load_template("forum.post.tpl"))));
          break;

        case 'search':
          include("$FORUM_PATH/forum.search.php");
          $tpl->set("main_content",set_block($block_title,"center",$forumtpl->fetch(load_template("forum.search.tpl"))));
          break;


        case 'viewforum':
          include("$FORUM_PATH/forum.viewforum.php");
          $tpl->set("main_content",set_block($block_title,"center",$forumtpl->fetch(load_template("forum.viewforum.tpl"))));
          break;

        case 'viewtopic':
          include("$FORUM_PATH/forum.viewtopic.php");
          $tpl->set("main_content",set_block($block_title,"center",$forumtpl->fetch(load_template("forum.viewtopic.tpl"))));
          break;

        case 'viewunread':
          include("$FORUM_PATH/forum.unread.php");
          $tpl->set("main_content",set_block($block_title,"center",$forumtpl->fetch(load_template("forum.unread.tpl"))));
          break;


        case 'index':
        case '':
        default:
          include("$FORUM_PATH/forum.main.php");
          $tpl->set("main_content",set_block($language["FORUM"],"center",$forumtpl->fetch(load_template("forum.main.tpl"))));
          break;



    }
}
?>