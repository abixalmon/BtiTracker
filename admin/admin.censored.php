<?php


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