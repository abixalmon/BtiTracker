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

if (!defined("IN_ACP"))
      die("non direct access!");



switch($action)
    {

    case 'tables':
        if (isset($_POST["doit"]) && isset($_POST["tname"]))
          {
            $table_action=$_POST["doit"];
            $tables=implode(",",$_POST["tname"]);
            switch ($table_action)
               {
                case 'Repair':
                    $dbres=do_sqlquery("REPAIR TABLE $tables");
                    break;
                case 'Analyze':
                    $dbres=do_sqlquery("ANALYZE TABLE $tables");
                    break;
                case 'Optimize':
                    $dbres=do_sqlquery("OPTIMIZE TABLE $tables");
                    break;
                case 'Check':
                    $dbres=do_sqlquery("CHECK TABLE $tables");
                    break;
                case 'Delete':
                    $dbres=do_sqlquery("DROP TABLE $tables");
                    header("Location: index.php?page=admin&user=".$CURUSER["uid"]."&code=".$CURUSER["random"]."&do=dbutil&action=status");
                    exit();
                    break;
             }
             $t=array();
             while ($tstatus=mysql_fetch_array($dbres))
                  {
                     $t[$i]["table"]=$tstatus['Table'];
                     $t[$i]["operation"]=$tstatus['Op'];
                     $t[$i]["info"]=$tstatus['Msg_type'];
                     $t[$i]["status"]=$tstatus['Msg_text'];
                     $i++;
             }
              $admintpl->set("language",$language);
              $admintpl->set("results",$t);
              $admintpl->set("db_status",false,true);
              $admintpl->set("table_result",true,true);

        }
         else
            header("Location: index.php?page=admin&user=".$CURUSER["uid"]."&code=".$CURUSER["random"]."&do=dbutil&action=status");
        break;


    case 'status':
    default:
        $dbstatus=do_sqlquery("SHOW TABLE STATUS");
        if (mysql_num_rows($dbstatus)>0)
            {
              $admintpl->set("frm_action","index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&amp;do=dbutil&amp;action=tables");
              $i=0;
              $bytes=0;
              $records=0;
              $overhead=0;
              $tables=array();
              // display current status for tables
              while ($tstatus=mysql_fetch_array($dbstatus))
                  {
                  $tables[$i]["name"]=$tstatus['Name'];
                  $tables[$i]["rows"]=$tstatus['Rows'];
                  $tables[$i]["length"]=makesize($tstatus['Data_length']+$tstatus['Index_length']);
                  $tables[$i]["overhead"]=($tstatus['Data_free']==0?"-":makesize($tstatus['Data_free']));
                  $i++;
                  $bytes+=$tstatus['Data_length']+$tstatus['Index_length'];
                  $records+=$tstatus['Rows'];
                  $overhead+=$tstatus['Data_free'];
                }
                $admintpl->set("language",$language);
                $admintpl->set("tables",$tables);
                $admintpl->set("db_status",true,true);
                $admintpl->set("table_count",$i);
                $admintpl->set("table_bytes",makesize($bytes));
                $admintpl->set("table_records",$records);
                $admintpl->set("table_overhead",makesize($overhead));
                unset($tables);
                unset($bytes);
                unset($records);
                unset($overhead);
            }
        break;

}


?>