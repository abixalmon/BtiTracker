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
				print("<li><span><a class=\"fly\" href=\"#url\">".$language["MNU_TORRENT"]."</a></span><ul><li><a href=\"index.php?page=torrents&search=&category=0&active=0\"><span>All</span></a></li><li><a href=\"index.php?page=torrents&search=&category=0&active=1\"><span>Active Only</span></a></li><li><a href=\"index.php?page=torrents&search=&category=0&active=2\"><span>Dead Only</span></a></ul></li></li>");?>

				
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
    }?>
<?php print("<li><a class=\"fly\" href=\"#url\">My Panel</a></span><ul><li><a href=\"index.php?page=usercp&amp;uid=".$CURUSER["uid"]."\">My Home</a></li><li><a href=\"index.php?page=usercp&uid=".$CURUSER["uid"]."&do=pm&action=list&what=inbox\">PM inbox</a></li><li><a href=\"index.php?page=usercp&uid=".$CURUSER["uid"]."&do=pm&action=list&what=outbox\">PM outbox</li><li><a href=\"index.php?page=usercp&uid=".$CURUSER["uid"]."&do=pm&action=edit&uid=".$CURUSER["uid"]."&what=new\">New PM</li><li><a href=\"index.php?page=usercp&do=user&action=change&uid=".$CURUSER["uid"]."\">Change Profile</li><li><a href=\"index.php?page=usercp&do=pwd&action=change&uid=".$CURUSER["uid"]."\">Change Password</li><li><a href=\"index.php?page=usercp&do=pid_c&action=change&uid=".$CURUSER["uid"]."\">Change PID</a></ul></li></li>\n");?>
				
				<?php if ($CURUSER["admin_access"]=="yes") 
						print("<li><a class=\"fly\" href=\"#url\">".$language["ADMIN_ACCESS"]."</a>\n");?><!--[if gte IE 7]><!--></a><!--<![endif]-->
				<!--[if lte IE 6]><table><tr><td><![endif]-->
					<ul class="level3">
           <li><a href="#url"> </a></li>
						<li><a href="#url"> </a></li>						
						<li><a href="#url"> </a></li>
						<?php	print("<li><a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."\">".$language["MNU_ADMINCP"]."</a><li><a class=\"fly\" href=\"#url\">Tracker's Settings </a></span><ul><li><a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&do=config&action=read\">Tracker's Settings</a></li><li><a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&do=banip&action=read\">Ban IP's</a></li><li><a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&do=language&action=read\">Languages Settings</li><li><a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&do=style&action=read\">Styles Settings</li></a></ul></li></li>\n");?>
						<?php print("<li><a class=\"fly\" href=\"#url\">Content Settings </a></span><ul><li><a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&do=category&action=read\">Categories Settings</a></li><li><a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&do=poller&action=read\">Poll Settings</a></li><li><a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&do=badwords&action=read\">Censored Words Settings</li><li><a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&do=blocks&action=read\">Block Settings</a></ul></li></li>\n");?>
						<?php print("<li><a class=\"fly\" href=\"#url\">User's Tools</a></span><ul><li><a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&do=groups&action=read\">Users Group Settings</a></li><li><a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&do=masspm&action=write\">Mass Private Mail</a></li><li><a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&do=pruneu\">Prune Users</li><li><a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&do=searchdiff\">Search Diff</a></ul></li></li>\n");?>
						<?php print("<li><a class=\"fly\" href=\"#url\">Torrent's Tools</a></span><ul><li><a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&do=prunet\">Prune Torrents</a></ul></li></li>\n");?>
						<?php print("<li><a class=\"fly\" href=\"#url\">Forum's Settings</a></span><ul><li><a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&do=forum&action=read\">Forum's Settings</a></ul></li></li>\n");?>
						<?php print("<li><a class=\"fly\" href=\"#url\">Others Tools</a></span><ul><li><a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&do=dbutil\">Database Utilities</a></li><li><a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&do=mysql_stats\">MySql Statistics</a></li><li><a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&do=logview\">View Site Log</a></ul></li></li>\n");?>
						<?php print("<li><a class=\"fly\" href=\"#url\">Modules</a></span><ul><li><a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&do=module_config&action=manage\">Modules Settings</a></ul></li></li>\n");?>
						<?php print("<li><a class=\"fly\" href=\"#url\">Hacks</a></span><ul><li><a href=\"index.php?page=admin&amp;user=".$CURUSER["uid"]."&amp;code=".$CURUSER["random"]."&do=hacks&action=read\">Hacks Settings</a></ul></li></li>\n");?>
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
