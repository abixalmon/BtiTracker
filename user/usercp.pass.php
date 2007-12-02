<?php
switch ($action)
{
    case 'post':
            if ($_POST["old_pwd"]=="")
          {
        err_msg($language["ERROR"],$language["INS_OLD_PWD"]);
        stdfoot();
        exit;
          }
            elseif ($_POST["new_pwd"]=="")
          {
        err_msg($language["ERROR"],$language["INS_NEW_PWD"]);
        stdfoot();
        exit;
          }
            elseif ($_POST["new_pwd"]!=$_POST["new_pwd1"])
          {
        err_msg($language["ERROR"],$language["DIF_PASSWORDS"]);
        stdfoot();
        exit;
          }
            else
                {
                $respwd = do_sqlquery("SELECT * FROM {$TABLE_PREFIX}users WHERE id=$uid AND password='".md5($_POST["old_pwd"])."' AND username=".sqlesc($CURUSER["username"])."");
                if (!$respwd || mysql_num_rows($respwd)==0)
                   err_msg($language["ERROR"],$language["ERR_RETR_DATA"]);
                else {
                    $arr=mysql_fetch_assoc($respwd);
                    do_sqlquery("UPDATE {$TABLE_PREFIX}users SET password='".md5($_POST["new_pwd"])."' WHERE id=$uid AND password='".md5($_POST["old_pwd"])."' AND username=".sqlesc($CURUSER["username"])."") or die(mysql_error());
                    
                if($GLOBALS["FORUMLINK"]=="smf")
                {
                    $passhash=smf_passgen($CURUSER["username"], $_POST["new_pwd"]);
                    do_sqlquery("UPDATE {$db_prefix}members SET passwd='$passhash[0]', passwordSalt='$passhash[1]' WHERE ID_MEMBER=".$arr["smf_fid"]) or die(mysql_error());
                }
                    
            success_msg($language["PWD_CHANGED"], "".$language["NOW_LOGIN"]."<br /><a href=\"index.php?page=login\">Go</a>");
            stdfoot(true,false);
                    }
                }
    break;

    case '':
    case 'change':
    default:
    $pwdtpl=array();
    $pwdtpl["frm_action"]="index.php?page=usercp&amp;do=pwd&amp;action=post&amp;uid=".$uid."";
    $pwdtpl["frm_cancel"]="index.php?page=usercp&amp;uid=".$uid."";
    $usercptpl->set("pwd",$pwdtpl);
    break;
}
?>