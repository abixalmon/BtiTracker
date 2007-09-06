<?php
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