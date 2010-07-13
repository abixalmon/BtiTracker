<?php
/////////////////////////////////////////////////////////////////////////////////////
// xbtit - Bittorrent tracker/frontend
//
// Copyright (C) 2004 - 2007  Btiteam
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
// You can see there are plenty of extra spaces for more menu links,
// you will need to simply create a language setting for your links and then insert them into
// the menu, as I have below. (TreetopClimber)
//
////////////////////////////////////////////////////////////////////////////////////
global $CURUSER, $FORUMLINK, $db_prefix,$XBTT_USE;

$langue=language_list();

?>

<div id="menu">
	<ul class="level1">
		 
<?php

if ($CURUSER["view_torrents"]=="yes")		
		print("<li class=\"level1-li\"><a class=\"level1-a drop\" href=\"#url\">".$language['TORRENT_MENU']."</a>\n");?><!--[if gte IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
			<ul class="level2">
<?php if ($CURUSER["view_torrents"]=="yes")
				print("<li><a href=\"index.php?page=extra-stats\">".$language["MNU_STATS"]."</a></li>\n");
				print("<li><a href=\"index.php?page=torrents\">".$language["MNU_TORRENT"]."</a></li>\n");?>

				
<?php if ($CURUSER["can_upload"]=="yes")				
				print("<li><a class=\"fly\" href=\"#url\">".$language['UPLOAD_LINK']."</a>\n");?><!--[if gte IE 7]><!--></a><!--<![endif]-->
						<!--[if lte IE 6]><table><tr><td><![endif]-->
							<ul class="level4">
							<?php if ($CURUSER["can_upload"]=="yes")
								print("<li><a href=\"index.php?page=upload\">".$language["MNU_UPLOAD"]."</a></li>\n");?>
								<li><a href="#url"> </a></li>
							  <li><a href="#url"> </a></li>
								
				        <li><a class="fly" href="#url"> <!--[if gte IE 7]><!--></a><!--<![endif]-->
				          <!--[if lte IE 6]><table><tr><td><![endif]-->
					          <ul class="level3">
						          <li><a href="#url"> </a></li>
						          <li><a href="#url"> </a></li>
						          <li><a href="#url"> </a></li>
						          <li><a href="#url"> </a><b></b></li>
					          </ul>
				          <!--[if lte IE 6]></td></tr></table></a><![endif]-->
				        </li>
				
								<li><a href="#url"> </a><b></b></li>
							</ul>
						<!--[if lte IE 6]></td></tr></table></a><![endif]-->
						</li>
				
				<li><a href="#url"> </a><b></b></li>
			</ul>
		<!--[if lte IE 6]></td></tr></table></a><![endif]-->
		</li>
		
		<li class="level1-li"><a class="level1-a drop" href="#url"> </a><!--[if gte IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
			<ul class="level2">
				
				<li><a class="fly" href="#url"> <!--[if gte IE 7]><!--></a><!--<![endif]-->
				<!--[if lte IE 6]><table><tr><td><![endif]-->
					<ul class="level3">
						<li><a href="#url"> </a></li>
						<li><a href="#url"> </a></li>
						<li><a href="#url"> </a></li>
					</ul>
				<!--[if lte IE 6]></td></tr></table></a><![endif]-->
				</li>
				
				<li><a href="#url"> </a></li>
				
				<li><a class="fly" href="#url"> <!--[if gte IE 7]><!--></a><!--<![endif]-->
				<!--[if lte IE 6]><table><tr><td><![endif]-->
					<ul class="level3">
						<li><a href="#url"> </a></li>
						<li><a href="#url"> </a></li>
						<li><a href="#url"> </a></li>
						<li><a href="#url"> </a><b></b></li>
					</ul>
				<!--[if lte IE 6]></td></tr></table></a><![endif]-->
				</li>
				
				<li><a class="fly" href="#url"> <!--[if gte IE 7]><!--></a><!--<![endif]-->
				<!--[if lte IE 6]><table><tr><td><![endif]-->
					<ul class="level3">
						<li><a href="#url"> </a></li>
						<li><a href="#url"> </a></li>
						<li><a href="#url"> </a><b></b></li>
					</ul>
				<!--[if lte IE 6]></td></tr></table></a><![endif]-->
				</li>
				
			</ul>
		<!--[if lte IE 6]></td></tr></table></a><![endif]-->
		</li>
	<li class="level1-li"><a href="index.php"><?php echo $language["MNU_INDEX"]; ?></a></li>	
	<?php
if ($CURUSER["uid"]==1 || !$CURUSER)
       // anonymous=guest
    {
   print("<li class=\"level1-li\"><a href=\"index.php?page=login\">".$language["LOGIN"]."</a></li>\n");
    }
else
    {
    print("<li class=\"level1-li\"><a href=\"logout.php\">".$language["LOGOUT"]."</a></li>\n");
    }		
?>
<?php if ($CURUSER["view_users"]=="yes")
		print("<li class=\"level1-li left\"><a class=\"level1-a drop\" href=\"#url\">".$language['USER_MENU']."</a>\n");?><!--[if gte IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
			<ul class="level2"><?php
			if ($CURUSER["view_forum"]=="yes")
   {
   if ($GLOBALS["FORUMLINK"]=="" || $GLOBALS["FORUMLINK"]=="internal" || $GLOBALS["FORUMLINK"]=="smf")
      print("<li><a href=\"index.php?page=forum\">".$language["MNU_FORUM"]."</a></li>\n");
   elseif ($GLOBALS["FORUMLINK"]=="smf")
       print("<li><a href=\"".$GLOBALS["FORUMLINK"]."\">".$language["MNU_FORUM"]."</a></li>\n");
   else
       print("<li><a href=\"".$GLOBALS["FORUMLINK"]."\">".$language["MNU_FORUM"]."</a></li>\n");
    }
				print("<li><a href=\"index.php?page=usercp&amp;uid=".$CURUSER["uid"]."\">".$language["USER_CP"]."</a></li>\n");?>
				
				<?php if ($CURUSER["admin_access"]=="yes") 
						print("<li><a class=\"fly\" href=\"#url\">".$language["ADMIN_ACCESS"]."</a>\n");?><!--[if gte IE 7]><!--></a><!--<![endif]-->
				<!--[if lte IE 6]><table><tr><td><![endif]-->
					<ul class="level3">
           <li><a href="#url"> </a></li>
						<li><a href="#url"> </a></li>						
						<li><a href="#url"> </a></li>
						<?php	print("<li><a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."\">".$language["MNU_ADMINCP"]."</a><b></b></li>\n");?>
						<li><a class="fly" href="#url"> </a><!--[if gte IE 7]><!--></a><!--<![endif]-->
						<!--[if lte IE 6]><table><tr><td><![endif]-->
							<ul class="level4">
								<li><a href="#url"> </a></li>
								<li><a href="#url"> </a></li>
								<li><a href="#url"> </a></li>
			         <li><a href="#url"> </a><b></b></li> 
							</ul>
						<!--[if lte IE 6]></td></tr></table></a><![endif]-->
						</li>

					</ul>
				<!--[if lte IE 6]></td></tr></table></a><![endif]-->
				</li>
				<?php if ($CURUSER["view_users"]=="yes")
			print("<li><a href=\"index.php?page=users\">".$language["MNU_MEMBERS"]."</a><b></b></li>\n");
				if ($CURUSER["view_news"]=="yes")
		  print("<li><a href=\"index.php?page=viewnews\">".$language['MNU_NEWS']."</a></li>\n");?>
						
		<li><a class="fly" href="#url"> </a> <!--[if gte IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
			<ul class="level2">
			<li><a href="#url"> </a></li>
				<li><a href="#url"> </a></li>
				<li><a href="#url"> </a></li>
				<li><a href="#url"> </a><b></b></li>
			</ul>
		<!--[if lte IE 6]></td></tr></table></a><![endif]-->
		</li>
				
			</ul>
		<!--[if lte IE 6]></td></tr></table></a><![endif]-->
		</li>		
	</ul>
   </div>	
