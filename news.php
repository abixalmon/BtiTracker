<?php
require_once(load_language("lang_news.php"));

global $CURUSER, $language, $newstpl;

if (isset($_GET["act"])) $action=$_GET["act"];
else $action ="viewnews";

if ($CURUSER["edit_news"]!="yes")
   {
   err_msg(ERROR,ERR_NOT_AUTH);
   stdfoot();
   exit();
   }

if ($action=="del")
   {
       if ($CURUSER["delete_news"]=="yes")
          {
              do_sqlquery("DELETE FROM {$TABLE_PREFIX}news WHERE id=".$_GET["id"]);
              redirect("index.php");
              exit();
          }
          else
              {
              stderr($language["ERROR"],$language["CANT_DELETE_NEWS"]);
              stdfoot();
              exit();
              }

   }
elseif ($action=="edit")
       {
       if ($CURUSER["edit_news"]=="yes")
          {
              $rnews=do_sqlquery("SELECT * FROM {$TABLE_PREFIX}news WHERE id=".intval($_GET["id"]));
              if (!$rnews)
                 {
                 stderr($language["ERROR"],$language["ERR_BAD_NEWS_ID"]);
                 stdfoot();
                 exit();
                 }
              $row=mysql_fetch_array($rnews);
              if ($row)
                 {
                   $news=unesc($row["news"]);
                   $title=unesc($row["title"]);
                 }
              else
                  {
                   stderr($language["ERROR"],$language["ERR_NO_NEWS_ID"]);
                   stdfoot();
                   exit();
                  }
          }
          else
              {
              stderr($language["ERROR"],$language["CANT_DELETE_NEWS"]);
              stdfoot();
              exit();
              }

global $language, $newstpl;

    $newstpl=new bTemplate();
    $newstpl->set("language",$language);

    $newstpl->set("ADD_EDIT",true,true);
    $newstpl->set("VIEW",false,true);
    $newstpl->set("NO_NEWS",false,true);

    $tplnews=array();
    $tplnews["action"]="index.php?page=news&amp;act=confirm";
    $tplnews["hidden_action"]=$action;
    $tplnews["hidden_id"]=$_GET["id"];
    $tplnews["news_title"]=$title;
    $tplnews["bbcode"]=textbbcode("news","news",$news);
    $newstpl->set("news",$tplnews);

       }


elseif ($action=="add")
{
global $news, $title, $CURUSER, $language, $newstpl;

    $newstpl=new bTemplate();
    $newstpl->set("language",$language);

    $newstpl->set("ADD_EDIT",true,true);
    $newstpl->set("VIEW",false,true);
    $newstpl->set("NO_NEWS",false,true);

    $tplnews=array();
    $tplnews["action"]="index.php?page=news&amp;act=confirm";
    $tplnews["hidden_action"]=$action;
    $tplnews["hidden_id"]=$_GET["id"];
    $tplnews["news_title"]=$title;
    $tplnews["bbcode"]=textbbcode("news","news",$news);
    $newstpl->set("news",$tplnews);

}

elseif ($action=="confirm")
{
if (!isset($_POST["conferma"])) ;
      elseif ($_POST["conferma"] == $language["FRM_CONFIRM"])
         {
         if (isset($_POST["news"]) && isset($_POST["title"]))
            {
              $news=$_POST["news"];
              $uid=$CURUSER["uid"];
              $title=$_POST["title"];
              if ($news=="" || $title=="")
              {
                  err_msg(ERROR,ERR_INS_TITLE_NEWS);
              }
              else
              {
                $news=sqlesc($news);
                $title=sqlesc($title);
                $nid=intval($_POST["id"]);
                $action=$_POST['action'];
                if ($action=="edit")
                   do_sqlquery("UPDATE {$TABLE_PREFIX}news SET news=$news, title=$title WHERE id=$nid") or die(mysql_error());
                else
                    do_sqlquery("INSERT INTO {$TABLE_PREFIX}news (news,title,user_id,date) VALUES ($news, $title, $uid, NOW())") or die(mysql_error());
                redirect("index.php");
                exit();
              }
            }
         }
         elseif ($_POST["conferma"] == $language["FRM_CANCEL"]) {
                redirect("index.php");
                exit();
                }
         else {
              $title="";
              $news="";
         }
}

else
{
global $CURUSER, $CURRENTPATH, $TABLE_PREFIX;

$limit = $GLOBALS['block_newslimit'];
if ($limit>0)
  $limitqry="LIMIT $limit";
$res=do_sqlquery("SELECT {$TABLE_PREFIX}news.id, title, news, UNIX_TIMESTAMP(date) AS news_date, username FROM {$TABLE_PREFIX}news INNER JOIN {$TABLE_PREFIX}users ON {$TABLE_PREFIX}news.user_id={$TABLE_PREFIX}users.id ORDER BY date DESC $limitqry");

    $newstpl=new bTemplate();
    $newstpl->set("language",$language);
    $newstpl->set("ADD_EDIT",false,true);
    $newstpl->set("VIEW",true,true);
    $newstpl->set("NO_NEWS",false,true);

if ($res)

  {
    $news_model=array();
    $i=0;

    $newstpl->set("EDIT_DEL",$CURUSER["edit_news"]=="yes" || $CURUSER["delete_news"]=="yes",true);
    $newstpl->set("EDIT_NEWS",$CURUSER["edit_news"]=="yes",true);
    $newstpl->set("DELETE_NEWS",$CURUSER["delete_news"]=="yes",true);
    include("$CURRENTPATH/offset.php");

         while ($rows=mysql_fetch_array($res)) {
          $news_model[$i]["add"]="index.php?page=news&amp;act=add";
          $news_model[$i]["edit"]="index.php?page=news&amp;act=edit&amp;id=".$rows['id']."";
          $news_model[$i]["delete"]="index.php?page=news&amp;act=del&amp;id=".$rows['id']."";
          $news_model[$i]["username"]=unesc($rows["username"]);
          $news_model[$i]["date"]=date("d/m/Y H:i",$rows["news_date"]-$offset);
          $news_model[$i]["title"]=unesc($rows["title"]);
          $news=format_comment($rows["news"]);
          $news_model[$i]["news"]=$news;
          $i++;
          }

    $newstpl->set("news_model",$news_model);
  }
else
  {
    $newstpl->set("NO_NEWS",true,true);
    $newstpl->set("EDIT_NEWS",$CURUSER["edit_news"]=="yes",true);
    $newstpl->set("news_add","index.php?page=news&amp;act=add");
  }
}


?>