<?php
/////////////////////////////////////////////////////////////////////////////////////
// xbtit - Bittorrent tracker/frontend
//
// Copyright (C) 2004 - 2012  Btiteam
//
//    This file is part of xbtit.
//
// Redistribution and use in source and binary forms, with or without modification,
// are permitted provided that the following conditions are met:
//
//   1. Redistributions of source code must retain the above copyright notice,
//      this list of conditions and the following disclaimer.
//   2. Redistributions in binary form must reproduce the above copyright notice,
//      this list of conditions and the following disclaimer in the documentation
//      and/or other materials provided with the distribution.
//   3. The name of the author may not be used to endorse or promote products
//      derived from this software without specific prior written permission.
//
// THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR IMPLIED
// WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
// MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
// IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
// SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED
// TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
// PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
// LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
// NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE,
// EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
//
////////////////////////////////////////////////////////////////////////////////////

global $CURUSER,$btit_settings;
if (!$CURUSER || $CURUSER["view_users"]=="no")
   {
    // do nothing
   }
else
    {
    //lastest member

     block_begin ("Latest Member");
     $a = get_result("SELECT id,username FROM {$TABLE_PREFIX}users WHERE
     id_level<>1 AND id_level<>2 ORDER BY id DESC LIMIT 1",true,$btit_settings['cache_duration']);
     if($a){
      $a=$a[0];
      if ($CURUSER["view_users"]=="yes")
      $latestuser = "<a class=\"lastmem\" href=\"index.php?page=userdetails&amp;id=" . $a["id"] . "\">" . $a["username"] . "</a>";
     else
     $latestuser = $a['username'];
     echo " <div align=\"center\"><table border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" > <tr><td class=\"blocklist\" align=\"center\">".$language["WELCOME_LASTUSER"]."<br /><b>$latestuser</b>!</td></tr></table></div>\n";
     }
     block_end("");

} // end if user can view

//end
?>