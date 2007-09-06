<?php


if (!defined("IN_BTIT"))
      die("non direct access!");


/*#########################

$parentform=$_GET["form"];
$parentarea=$_GET["text"];

#########################*/

$parentform=chatForm;
$parentarea=chatbarText;

$count=0;
$i=0;
reset($smilies);
reset($privatesmilies);

$all_smiles=array();

foreach($smilies as $code=>$url) {
    switch($i)
       {
        case 0:
            $all_smiles[$count]["first_col"]="<a href=\"javascript: SmileIT('".str_replace("'","\'",$code)."',window.opener.document.forms.$parentform.$parentarea);\"><img border=\"0\" src=\"images/smilies/".$url."\" alt=\"$url\" /></a>";
            $all_smiles[$count]["second_col"]="";
            $all_smiles[$count]["third_col"]="";
            $i++;
            break;
        case 1:
            $all_smiles[$count]["second_col"]="<a href=\"javascript: SmileIT('".str_replace("'","\'",$code)."',window.opener.document.forms.$parentform.$parentarea);\"><img border=\"0\" src=\"images/smilies/".$url."\" alt=\"$url\" /></a>";
            $i++;
            break;
        case 2:
            $all_smiles[$count]["third_col"]="<a href=\"javascript: SmileIT('".str_replace("'","\'",$code)."',window.opener.document.forms.$parentform.$parentarea);\"><img border=\"0\" src=\"images/smilies/".$url."\" alt=\"$url\" /></a>";
            $count++;
            $i=0;
            break;
    }
}

foreach($privatesmilies as $code=>$url) {
    switch($i)
       {
        case 0:
            $all_smiles[$count]["first_col"]="<a href=\"javascript: SmileIT('".str_replace("'","\'",$code)."',window.opener.document.forms.$parentform.$parentarea);\"><img border=\"0\" src=\"images/smilies/".$url."\" alt=\"$url\" /></a>";
            $all_smiles[$count]["second_col"]="";
            $all_smiles[$count]["third_col"]="";
            $i++;
            break;
        case 1:
            $all_smiles[$count]["second_col"]="<a href=\"javascript: SmileIT('".str_replace("'","\'",$code)."',window.opener.document.forms.$parentform.$parentarea);\"><img border=\"0\" src=\"images/smilies/".$url."\" alt=\"$url\" /></a>";
            $i++;
            break;
        case 2:
            $all_smiles[$count]["third_col"]="<a href=\"javascript: SmileIT('".str_replace("'","\'",$code)."',window.opener.document.forms.$parentform.$parentarea);\"><img border=\"0\" src=\"images/smilies/".$url."\" alt=\"$url\" /></a>";
            $count++;
            $i=0;
            break;
    }
}

$moresmiles_tpl=new bTemplate();
$moresmiles_tpl->set("language",$language);
$moresmiles_tpl->set("smiles",$all_smiles);


?>