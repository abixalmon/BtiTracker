<?php


class update_hacks
      {

      var $file=array();
      var $errors=array();
      var $hack_path;

      function update_hacks()
            {
            // reset all var
            $this->file=array();
            $this->errors=array();
            $this->hack_path="";
      }

      // open the xml input file and return the full stream
      function open_hack($filename_and_folder)
            {
            // will open the main file which will contain the informations for insert hack.
            if (!file_exists($filename_and_folder))
                {
                $this->errors[]["message"]="File \"$filename_and_folder\" seems to be missed!";
                return false;
             }

            if (!is_readable($filename_and_folder))
                {
                $this->errors[]["message"]="File \"$filename_and_folder\" seems to be not readable";
                return false;
             }

             $fp=@fopen($filename_and_folder,"r");

             if (!$fp)
                {
                $this->errors[]["message"]="Error reading file \"$filename_and_folder\"";
                return false;
             }

             $full_file="";
             while (!feof($fp))
                  $full_file.=fread($fp,4096);

             $this->hack_path=dirname($filename_and_folder);
            
             return $full_file;
      }


      // private
      // used by hack_to_array, find the content in $text between <$tag> and </$tag>
      function get_tag_value($text, $tag) {
          $StartTag = "<$tag";
          $EndTag = "</$tag";
          
          $StartPosTemp = strpos($text, $StartTag);
          $StartPos = strpos($text, '>', $StartPosTemp);
          $StartPos = $StartPos + 1;
          
          $EndPos = strpos($text, $EndTag);
          
          if($EndPos > $StartPos) {
              $text   = substr ($text, $StartPos, ($EndPos - $StartPos));
          } else {
              $text = '';
          }
          
          $text = str_replace('<![CDATA[', '', $text);
          $text = str_replace(']]>', '', $text);
          
          return $text;
      }

      // read xml type stream and create valid array for applying hack
      // Array
      // (
      //    [0] => Array
      //        (
      //            [title]   => title of the hack
      //            [version] => version number of the hack
      //            [author]  => author's name
      //            [file]    => Array
      //                (
      //                    [0] => Array
      //                        (
      //                            [name] => full file path to modify
      //                            [operations] => Array
      //                                (
      //                                    [0] => Array
      //                                        (
      //                                            [search] => text to search (optional if copy)
      //                                            [action] => add/remove/copy/sql
      //                                            [data]   => text to insert or replace or file name (for copy)
      //                                            [where] => after/before/path if copy (optional if remove)
      //                                        )
      //                                )
      //                        )
      //                )
      //        )
      // )
      function hack_to_array($string) {
          $hacks = explode ('<hack>', $string);
          $i = 0;
          $hack=array();
          array_shift($hacks);
          foreach($hacks as $h)
            {
              $hack[$i]['title']    = $this->get_tag_value($h, 'title');
              $hack[$i]['version']  = $this->get_tag_value($h, 'version');
              $hack[$i]['author']  = $this->get_tag_value($h, 'author');
              // split all files on this hack
              $hack_file     = explode('<file>', $h);
              array_shift($hack_file);
              $x=0;
              foreach($hack_file as $hf)
                 {
                   $hack[$i]['file'][$x]['name']=$this->get_tag_value($hf, 'name');
                   // split all operations on this file
                   $hack_operations=explode('<operation>',$hf);
                   array_shift($hack_operations);
                   $j=0;
                   foreach($hack_operations as $op)
                     {
                        $hack[$i]['file'][$x]['operations'][$j]['search']     =$this->get_tag_value($op, 'search');
                        $hack[$i]['file'][$x]['operations'][$j]['action']     =$this->get_tag_value($op, 'action');
                        $hack[$i]['file'][$x]['operations'][$j]['data']       =$this->get_tag_value($op, 'data');
                        $hack[$i]['file'][$x]['operations'][$j]['where']      =$this->get_tag_value($op, 'where');
                        $j++;
                   }
                   $x++;
              }
              $i++;
          }
          unset($hacks);
          return $hack;
      }

      // private:
      // try to open input file and if success return file's stream
      function open_read_file($file_to_hack)
         {
            // globals var
            global $THIS_BASEPATH,$CURRENT_FOLDER;

            $DEFAULT_ROOT=$THIS_BASEPATH;
            $DEFAULT_STYLE_PATH="$THIS_BASEPATH/style/btit";
            $DEFAULT_LANGUAGE_PATH="$THIS_BASEPATH/language/english";

         // file exists?
           if (!file_exists($file_to_hack))
             {
               $this->errors[]["message"]="File $file_to_hack don't exists!";
               return false;
           }
           // i can read it?
           if (!is_readable($file_to_hack))
             {
              // try to make it readable...
              $ok=@chmod($file_to_hack,0744);
           }
           else $ok=true;

           // all done return file resource id
           if ($ok)
             {
             $stream="";
             $fp=fopen($file_to_hack,"r");
             if (!$fp)
               {
               $this->errors[]["message"]="Error opening File ($file_to_hack)!";
               return false;
             }
             else
               {
                 while(!feof($fp))
                      $stream.=fread($fp,4096);
                 fclose($fp);
             }
             return $stream;
           }
           else
             {
             $this->errors[]["message"]="File $file_to_hack is not readable!";
             return false;
           }

      }


      // private, used only to set errors in sql action when it fail
      function db_error(){
        global $j;

        $this->errors[]["message"]=mysql_error();
        $this->file[$j]["status"]="Failed!";

      }

      // this function will apply_hack,
      // if $test = true: just test all files and position
      // to check if there is no errors.
      // else will apply definitivly the hack
      // on error put string in $errors array at the
      // end test if any errors was found and return
      // true if no errors or false if some errors.
      function install_hack($hack_array,$test=false)
         {
            // globals var
            global $THIS_BASEPATH,$CURRENT_FOLDER,$dbhost, $dbuser, $dbpass, $database, $TABLE_PREFIX;

            $DEFAULT_ROOT=$THIS_BASEPATH;
            $DEFAULT_STYLE_PATH="$THIS_BASEPATH/style/btit";
            $DEFAULT_LANGUAGE_PATH="$THIS_BASEPATH/language/english";

            // reset errors array;
            unset($this->errors);
            $hacks=count($hack_array);
            // we have at least 1 hack listed
            if ($hacks)
              {
              for($i=0;$i<$hacks;$i++)
                {
                $files=count($hack_array[$i]["file"]);
                // we have at least 1 file to touch
                if ($files)
                  {
                  for ($j=0;$j<$files;$j++)
                    {
                    eval("\$this->file[\$j][\"name\"]=".$hack_array[$i]["file"][$j]["name"].";");
                    if (strtoupper($this->file[$j]["name"])!="DATABASE")
                      {
                        $file_content=$this->open_read_file($this->file[$j]["name"]);
                        if (!$file_content)
                          continue; // file not found, we don't make other operations on this file...
                    }
                    $operations=count($hack_array[$i]["file"][$j]["operations"]);
                    // we have at least 1 operation to do on this file
                    if ($operations)
                      {
                      for ($k=0;$k<$operations;$k++)
                        {
                        $action=str_replace("\"","",$hack_array[$i]["file"][$j]["operations"][$k]["action"]);
                        // what is the task?
                        unset($new_file_path);
                        unset($new_file_name);
                        switch($action)
                          {
                          case 'sql':
                              $this->file[$j]["status"]="OK";
                              require_once(dirname(__FILE__)."/settings.php");
                              @mysql_connect($dbhost,$dbuser,$dbpass) or die("Error connecting to $dbhost!");
                              @mysql_select_db($database) or ($this->db_error());
                              // if we just test then that's all, else we will run the query
                              if (!$test)
                                 @mysql_query(mysql_escape_string(str_replace("{\$db_prefix}","$TABLE_PREFIX",$hack_array[$i]["file"][$j]["operations"][$k]["data"]))) or ($this->db_error());
                            break;

                          case 'copy':
                            eval("\$new_file_path=".$hack_array[$i]["file"][$j]["operations"][$k]["where"].";");
                            eval("\$new_file_name=".$hack_array[$i]["file"][$j]["operations"][$k]["data"].";");
                            if (!is_writable($new_file_path))
                              {
                                if (!@chmod($new_file_path,0777))
                                  {
                                   $this->errors[]["message"]="Error: $new_file_path is not writable";
                                   $this->file[$j]["status"]="Failed";
                                }
                                else
                                   $this->file[$j]["status"]="OK";

                                @chmod($new_file_path,0766);
                            }
                            else
                               $this->file[$j]["status"]="OK";
                            break;

                          // when add or remove we must search
                          case 'add':
                          case 'remove':
                          case 'replace':
                            // we will put a comment before and after each change
                            $begin_hack_str="\n// begin modification\n";
                            $begin_hack_str.="// hack: ".$hack_array[$i]["title"]."\n";
                            $begin_hack_str.="//operation #$k\n";
                            $end_hack_str="\n// end modification\n";
                            $end_hack_str.="// hack: ".$hack_array[$i]["title"]."\n";
                            $end_hack_str.="//operation #$k\n";
                            $file_content=str_replace("\r\n","\n", $file_content); // convert file containt into unix style
                            $string_to_search=str_replace("\r\n","\n", $hack_array[$i]["file"][$j]["operations"][$k]["search"]);
                            $pos=strpos($file_content,$string_to_search);
                            // we find the position
                            if ($pos!==false)
                              {
                                if ($action=="add")
                                  {
                                    // we must find if before or after
                                    $where=$hack_array[$i]["file"][$j]["operations"][$k]["where"];
                                    $newpos=($where=="before"?$pos:$pos+strlen($string_to_search)+1);
                                    $file_content=substr($file_content,0,$newpos).$begin_hack_str.str_replace("\r\n","\n", $hack_array[$i]["file"][$j]["operations"][$k]["data"]).$end_hack_str.substr($file_content,$newpos);
                                }
                                elseif ($action=="replace")
                                  {
                                    $newpos=$pos+strlen($hack_array[$i]["file"][$j]["operations"][$k]["search"]);
                                    $file_content=substr($file_content,0,$pos).$begin_hack_str.str_replace("\r\n","\n", $hack_array[$i]["file"][$j]["operations"][$k]["data"]).$end_hack_str.substr($file_content,$newpos);
                                }
                                else
                                  { // we're removing...
                                    $newpos=$pos+strlen($hack_array[$i]["file"][$j]["operations"][$k]["search"]);
                                    $file_content=substr($file_content,0,$pos).$begin_hack_str."*** REMOVED ***\n".substr($file_content,$newpos);
                                }
                            }
                            else // we don't find the searched text
                              $this->errors[]["message"]="Sorry <br />\n\"".nl2br(htmlspecialchars($string_to_search))."\"<br />\nto search was not found in file: ".$this->file[$j]["name"].".";
                            break;
                        } // end switch action
                      } // end for operations
                    } // end if operations
                    else
                      $this->errors[]["message"]="Sorry no operations defined.";
                    
                    // it's not a test, we must save the current file
                    // we will make a new folder in hacks/backups/before_hack_name
                    // and we will copy files here
                    // then we will write the new file.
                    if (!$test && (strtoupper($this->file[$j]["name"])!="DATABASE"))
                       {
                         if ($this->save_original($this->file[$j]["name"]));
                         // we have saved the originale
                           {
                           // we need to copy the file somewhere?
                           if (isset($new_file_name) && isset($new_file_path))
                             {
                             // destination is writable
                             if ($this->file[$j]["status"]=="OK")
                               {
                                if (@copy($this->file[$j]["name"],"$new_file_path/$new_file_name"))
                                  $this->file[$j]["status"]="OK";
                                else
                                  {
                                    $this->file[$j]["status"]="Failed";
                                    $this->errors[]["message"]="Error: copying ".$this->file[$j]["name"]." in new position $new_file_path/$new_file_name";
                                }
                             }
                           }
                           else  // "normal" operation
                             {
                               if ($this->write_new_file($this->file[$j]["name"],$file_content))
                                 $this->file[$j]["status"]="OK";
                               else
                                 $this->file[$j]["status"]="Failed";
                           }
                         }
                    }
                    elseif (strtoupper($this->file[$j]["name"])!="DATABASE")
                     {
                       if ($this->write_new_file($this->file[$j]["name"],$file_content,true))
                         $this->file[$j]["status"]="OK";
                       else
                         $this->file[$j]["status"]="Failed";
                    }
                   // end test control :)

                  } // end for files    
                } // end if files
                else
                  $this->errors[]["message"]="Sorry no files defined.";
              } // end for hacks
            } //end if hacks
            else
              $this->errors[]["message"]="Sorry no hack defined.";

            // ok, we've do nothing but seems ok.
            // finally we check if all was gone as should
            if (isset($this->errors))
              if (($this->errors)>0)
                  return false;
              else
                  return true;    
            else
              return true;
      }

      // this function will remove hack,
      // if $test = true: just test all files and position
      // to check if there is no errors.
      // else will remove definitivly the hack
      // on error put string in $errors array at the
      // end test if any errors was found and return
      // true if no errors or false if some errors.
      function uninstall_hack($hack_array,$test=false)
         {
            // globals var
            global $THIS_BASEPATH,$CURRENT_FOLDER,$dbhost, $dbuser, $dbpass, $database;

            $DEFAULT_ROOT=$THIS_BASEPATH;
            $DEFAULT_STYLE_PATH="$THIS_BASEPATH/style/btit";
            $DEFAULT_LANGUAGE_PATH="$THIS_BASEPATH/language/english";

            // reset errors array;
            unset($this->errors);
            $hacks=count($hack_array);
            // we have at least 1 hack listed
            if ($hacks)
              {
              for($i=0;$i<$hacks;$i++)
                {
                $files=count($hack_array[$i]["file"]);
                // we have at least 1 file to touch
                if ($files)
                  {
                  for ($j=0;$j<$files;$j++)
                    {
                    eval("\$this->file[\$j][\"name\"]=".$hack_array[$i]["file"][$j]["name"].";");
                    if (strtoupper($this->file[$j]["name"])!="DATABASE")
                      {
                        $file_content=$this->open_read_file($this->file[$j]["name"]);
                        if (!$file_content)
                          continue; // file not found, we don't make other operations on this file...
                    }
                    $operations=count($hack_array[$i]["file"][$j]["operations"]);
                    // we have at least 1 operation to do on this file
                    if ($operations)
                      {
                      for ($k=0;$k<$operations;$k++)
                        {
                        $action=str_replace("\"","",$hack_array[$i]["file"][$j]["operations"][$k]["action"]);
                        // what is the task?
                        unset($new_file_path);
                        unset($new_file_name);
                        switch($action)
                          {
                          case 'sql':
                              // on uninstall we will do nothing (don't remove the fields)
                              $this->file[$j]["status"]="OK";
                            break;

                          case 'copy':
                              // atm we do nothing, dunno if we must remove added files...?
                              $this->file[$j]["status"]="OK";
                            break;

                          // when add or remove we must search
                          case 'add':
                          case 'remove':
                          case 'replace':
                            $begin_hack_str="\n// begin modification\n";
                            $begin_hack_str.="// hack: ".$hack_array[$i]["title"]."\n";
                            $begin_hack_str.="//operation #$k\n";
                            $end_hack_str="\n// end modification\n";
                            $end_hack_str.="// hack: ".$hack_array[$i]["title"]."\n";
                            $end_hack_str.="//operation #$k\n";
                            // removing the hack, so we search what has be added or replacement
                            $string_to_search=$begin_hack_str.($action=="remove"?"*** REMOVED ***\n":str_replace("\r\n","\n", $hack_array[$i]["file"][$j]["operations"][$k]["data"]).$end_hack_str);
                            $file_content=str_replace("\r\n","\n", $file_content); // convert file containt into unix style
                            $pos=strpos($file_content, $string_to_search);
                            // we find the position
                            if ($pos!==false)
                              {
                                $newpos=$pos+strlen($string_to_search);
                                if ($action=="add")
                                  {
                                  // then when uninstalling we remove ;)
                                    $file_content=substr($file_content,0,$pos).substr($file_content,$newpos);
                                }
                                elseif ($action=="replace")
                                  {
                                  // we replace the replacement with original
                                    $file_content=substr($file_content,0,$pos).str_replace("\r\n","\n", $hack_array[$i]["file"][$j]["operations"][$k]["search"]).substr($file_content,$newpos);
                                }
                                else
                                  { // we put back again the removed string
                                    $file_content=substr($file_content,0,$pos).str_replace("\r\n","\n", $hack_array[$i]["file"][$j]["operations"][$k]["search"]).substr($file_content,$newpos);
                                }
                            }
                            else // we don't find the searched text
                              $this->errors[]["message"]="Sorry <br />\n\"".nl2br(htmlspecialchars($string_to_search))."\"<br />\nto search was not found in file: ".$this->file[$j]["name"].".";
                            break;
                        } // end switch action
                      } // end for operations
                    } // end if operations
                    else
                      $this->errors[]["message"]="Sorry no operations defined.";

                    // from this point all operations are the same as when we have installed the hack...
                    
                    // it's not a test, we must save the current file
                    // we will make a new folder in hacks/backups/before_hack_name
                    // and we will copy files here
                    // then we will write the new file.
                    if (!$test && (strtoupper($this->file[$j]["name"])!="DATABASE"))
                       {
                         if ($this->save_original($this->file[$j]["name"]));
                         // we have saved the originale
                           {
                           // we need to copy the file somewhere?
                           if (isset($new_file_name) && isset($new_file_path))
                             {
                             // destination is writable
                             if ($this->file[$j]["status"]=="OK")
                               {
                                if (@copy($this->file[$j]["name"],"$new_file_path/$new_file_name"))
                                  $this->file[$j]["status"]="OK";
                                else
                                  {
                                    $this->file[$j]["status"]="Failed";
                                    $this->errors[]["message"]="Error: copying ".$this->file[$j]["name"]." in new position $new_file_path/$new_file_name";
                                }
                             }
                           }
                           else  // "normal" operation
                             {
                               if ($this->write_new_file($this->file[$j]["name"],$file_content))
                                 $this->file[$j]["status"]="OK";
                               else
                                 $this->file[$j]["status"]="Failed";
                           }
                         }
                    }
                    elseif (strtoupper($this->file[$j]["name"])!="DATABASE")
                     {
                       if ($this->write_new_file($this->file[$j]["name"],$file_content,true))
                         $this->file[$j]["status"]="OK";
                       else
                         $this->file[$j]["status"]="Failed";
                    }
                   // end test control :)

                  } // end for files    
                } // end if files
                else
                  $this->errors[]["message"]="Sorry no files defined.";
              } // end for hacks
            } //end if hacks
            else
              $this->errors[]["message"]="Sorry no hack defined.";

            // ok, we've do nothing but seems ok.
            // finally we check if all was gone as should
            if (isset($this->errors))
              if (($this->errors)>0)
                  return false;
              else
                  return true;    
            else
              return true;
      }

      // private
      // will write the input $new_content in $file_with_path
      // return false in case of error
      function write_new_file($file_with_path, $new_content, $for_test=false)
        {

        // globals var
        global $THIS_BASEPATH,$CURRENT_FOLDER;

        // is the file writable?
        if (!is_writable($file_with_path))
          {
          if (!@chmod($file_with_path,0777))
            {
              $this->errors[]["message"]="unable to write new content in $file_with_path!";
              return false;
          }
        }
        // it's only for testing, but seems to be writable
        if ($for_test)
          return true;
        // we will write the new content in the truncated file
        $fp=fopen($file_with_path,"w");
        if (!$fp)
          {
            $this->errors[]["message"]="unable to open $file_with_path!";
            return false;
        }
        if (fwrite($fp,$new_content)===false)
          {
            $this->errors[]["message"]="unable to write new content in $file_with_path!";
            @fclose($fp);
            return false;
        }
        @fclose($fp);
        return true;
        @chmod($file_with_path,0766);
      }

      // private
      // will save the input $file_with_path in a new
      // create folder called "backup" in folder which the
      // script has origin, or use the already existing one
      function save_original($file_with_path)
        {
        // globals var
        global $THIS_BASEPATH,$CURRENT_FOLDER;

        // just in case is not set....
        if ($this->hack_path=="")
          $this->hack_path="$THIS_BASEPATH/hacks";

        // if there is no backup folder
        if (!file_exists($this->hack_path."/backup"))
          {
          // we can create it?
          if (!is_writable($this->hack_path))
            {
            if (!@chmod($this->hack_path,0777))
              {
                $this->errors[]["message"]="unable to write in $this->hack_path's folder!";
                return false;
            }
            else
              if (@mkdir($this->hack_path."/backup",0777))
              {
                $this->errors[]["message"]="unable to create backup folder!";
                return false;
            }
          }
          else
            {
            if (!@mkdir($this->hack_path."/backup",0777))
              {
                $this->errors[]["message"]="unable to create backup folder!";
                return false;
            }
          }
        }
        // the backup folder exist or has been created
        if (!is_writable($this->hack_path."/backup"))
          {
          if (!@chmod($this->hack_path."/backup",0777))
            {
              $this->errors[]["message"]="unable to write in $this->hack_path/backup's folder!";
              return false;
          }
        }
        // ok, all writable we copy the original file in the backup position
        $fname=basename($file_with_path).".".date("d-m-Y_H-i-s");
        if (!@copy($file_with_path,$this->hack_path."/backup/$fname"))
          {
            $this->errors[]["message"]="unable to copy $file_with_path in $this->hack_path/backup/$fname!";
            return false;
        }

        @chmod($this->hack_path."/backup",0766);
        @chmod($this->hack_path."/backup/$fname",0766);

        // all gone fine
        return true;
      }



}

?>