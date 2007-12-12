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
      

$parentform=$_GET["form"];
$parentarea=$_GET["text"];


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