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

// english installation file //

$install_lang["charset"]                = "ISO-8859-1";
$install_lang["lang_rtl"]               = FALSE;
$install_lang["step"]                   = "STEP:";
$install_lang["welcome_header"]         = "Welcome";
$install_lang["welcome"]                = "Welcome to the installation for the new XBTI-Tracker.";
$install_lang["installer_language"]     = "Language:";
$install_lang["installer_language_set"] = "Enable this language";
$install_lang["start"]                  = "Start";
$install_lang["next"]                   = "Next";
$install_lang["back"]                   = "Back";
$install_lang["requirements_check"]     = "Requirements Check";
$install_lang["reqcheck"]               = "Req.Check";
$install_lang["settings"]               = "Settings";
$install_lang["system_req"]             = "<p>".$GLOBALS["btit-tracker"]."&nbsp;".$GLOBALS["current_btit_version"]." requires PHP 4.1.2 or better and an MYSQL database.</p>";
$install_lang["list_chmod"]             = "<p>Before we go any further, please ensure that all the files have been uploaded, and that the following files have suitable permissions to allow this script to write to it (0777 should be sufficient).</p>";
$install_lang["view_log"]               = "You can view full changelog";
$install_lang["here"]                   = "here";
$install_lang["settingup"]              = "Setting up your tracker";
$install_lang["settingup_info"]         = "Basic Settings";
$install_lang["sitename"]               = "Sitename";
$install_lang["sitename_input"]         = "XBTI-Tracker";
$install_lang["siteurl"]                = "Site-url";
$install_lang["siteurl_info"]           = "Without trailing slash";
$install_lang["mysql_settings"]         = "MySQL Settings<br />\nCreate a MySQL user and database, input the details here";
$install_lang["mysql_settings_info"]    = "Database Settings.";
$install_lang["mysql_settings_server"]  = "MySQL Server (localhost works ok for most servers)";
$install_lang["mysql_settings_username"] = "MySQL Username";
$install_lang["mysql_settings_password"] = "MySQL Password";
$install_lang["mysql_settings_database"] = "MySQL Database";
$install_lang["mysql_settings_prefix"]  = "MySQL Table Prefix";
$install_lang["cache_folder"]           = "Cache Folder";
$install_lang["torrents_folder"]        = "Torrents Folder";
$install_lang["badwords_file"]          = "badwords.txt";
$install_lang["chat.php"]               = "chat.php";
$install_lang["write_succes"]           = "<span style=\"color:#00FF00; font-weight: bold;\">is writable!</span>";
$install_lang["write_fail"]             = "<span style=\"color:#FF0000; font-weight: bold;\">NOT writable!</span> (0777)";
$install_lang["write_file_not_found"]   = "<span style=\"color:#FF0000; font-weight: bold;\">NOT FOUND!</span>";
$install_lang["mysqlcheck"]             = "MySQL Connection Check";
$install_lang["mysqlcheck_step"]        = "MySQL Check";
$install_lang["mysql_succes"]           = "<span style=\"color:#00FF00; font-weight: bold;\">Succesfully connected to the database!</span>";
$install_lang["mysql_fail"]             = "<span style=\"color:#FF0000; font-weight: bold;\">Failed, the connection couldn't be astablished!</span>";
$install_lang["back_to_settings"]       = "Go back and fill in the neccesary info.";
$install_lang["saved"]                  = "saved";
$install_lang["file_not_writeable"]     = "The file <b>./include/settings.php</b> is not writeable.";
$install_lang["file_not_exists"]        = "The file <b>./include/settings.php</b> doesn't exists.";
$install_lang["not_continue_settings"]  = "You can not continue with the install without this file being writable.";
$install_lang["not_continue_settings2"] = "You can not continue with this file.";
$install_lang["settings.php"]           = "./include/settings.php";
$install_lang["can_continue"]           = "You can continue and change this later.";
$install_lang["mysql_import"]           = "MySQL Import";
$install_lang["mysql_import_step"]      = "SQL Imp.";
$install_lang["create_owner_account"]   = "Creating Owner Account";
$install_lang["create_owner_account_step"] = "Create Owner";
$install_lang["database_saved"]         = "The database.sql has been imported to your database.";
$install_lang["create_owner_account_info"] = "Here you can create the owner account.";
$install_lang["username"]               = "Username";
$install_lang["password"]               = "Password";
$install_lang["password2"]              = "Repeat password";
$install_lang["email"]                  = "Email";
$install_lang["email2"]                 = "Repeat email";
$install_lang["is_succes"]              = "is done.";
$install_lang["no_leave_blank"]         = "Don't leave anything blank.";
$install_lang["not_valid_email"]        = "This is not a valid email adress.";
$install_lang["pass_not_same_username"] = "Password cannot be the same as username.";
$install_lang["email_not_same"]         = "Email adresses don't match.";
$install_lang["pass_not_same"]          = "Passwords don't match.";
$install_lang["site_config"]            = "Tracker Settings";
$install_lang["site_config_step"]       = "Tracker Sett.";
$install_lang["default_lang"]           = "Default Language";
$install_lang["default_style"]          = "Default Style";
$install_lang["torrents_dir"]           = "Torrents Dir";
$install_lang["validation"]             = "Validation Mode";
$install_lang["more_settings"]          = "*&nbsp;&nbsp;&nbsp;More settings in the <u>Admin Panel</u> when the installation is completed.";
$install_lang["tracker_saved"]          = "The settings are saved.";
$install_lang["finished"]               = "Rounding up the Installation";
$install_lang["finished_step"]          = "Rounding up";
$install_lang["succes_install1"]        = "The installation is completed!";
$install_lang["succes_install2a"]       = "<p>You succesfully installed ".$GLOBALS["btit-tracker"]." ".$GLOBALS["current_btit_version"].".</p><p>The installation has been successfully locked and <b>install.php</b> deleted to prevent being used again.</p>";
$install_lang["succes_install2b"]       = "<p>You succesfully installed ".$GLOBALS["btit-tracker"]." ".$GLOBALS["current_btit_version"].".</p><p>We advise you to lock the installation. You can do this by changing <b>install.unlock</b> to <b>install.lock</b> and to delete this <b>install.php</b> file.</p>";
$install_lang["succes_install3"]        = "<p>We of BTITeam hope you enjoy use of this product and that we will see you again on our <a href=\"http://www.btiteam.org/smf/index.php\" target=\"_blank\">forum</a>.</p>";
$install_lang["go_to_tracker"]          = "Go to your tracker";
$install_lang["forum_type"]             = "Forum Type";
$install_lang["forum_internal"]         = "XBTI-Tracker Internal Forum";
$install_lang["forum_smf"]              = "Simple Machines Forum";
$install_lang["forum_other"]            = "Unintegrated External Forum - Enter url here -->";
$install_lang["smf_download_a"]         = "<strong>If using Simple Machines Forum:</strong><br /><br/ >Please download the latest version of Simple Machines Forum <a target='_new' href='http://www.simplemachines.org/download/'>here</a> and upload the contents of the archive to the \"smf\" folder and <a target='_new' href='smf/install.php'>click here</a> to install it.*<br /><strong>(Please use the same database credentials you used for this installation procedure).<br /><br /><font color='#FF0000'>Once installed</font></strong> please CHMOD the SMF English language file (<strong>";
$install_lang["smf_download_b"]         = "</strong>) to 777 and click <strong>Next</strong> to continue with the XBTI-Tracker installation.<br /><br /><strong>* Both links will open into a new window/tab to prevent losing your place on the XBTI-Tracker installation.</strong></p>";
$install_lang["smf_err_1"]              = "Can't find Simple Machines Forum in the \"smf\" folder, please install it before proceeding.<br /><br />Click <a href=\"javascript: history.go(-1);\">here</a> to return to the previous page.";
$install_lang["smf_err_2"]              = "Can't find Simple Machines Forum in the database, please install it before proceeding.<br /><br />Click <a href=\"javascript: history.go(-1);\">here</a> to return to the previous page.";
$install_lang["smf_err_3a"]             = "Unable to write to the SMF English language file (<strong>";
$install_lang["smf_err_3b"]             = "</strong>) please CHMOD to 777 before proceeding.<br /><br />Click <a href=\"javascript: history.go(-1);\">here</a> to return to the previous page.";
$install_lang["allow_url_fopen"]        = "php.ini value for \"allow_url_fopen\" (best is ON)";
$install_lang["allow_url_fopen_ON"]        = "<span style=\"color:#00FF00; font-weight: bold;\">ON</span>";
$install_lang["allow_url_fopen_OFF"]        = "<span style=\"color:#FF0000; font-weight: bold;\">OFF</span>";
$install_lang["succes_upgrade1"]        = "The upgrade is completed!";
$install_lang["succes_upgrade2a"]       = "<p>You succesfully upgraded ".$GLOBALS["btit-tracker"]." ".$GLOBALS["current_btit_version"]." on your tracker.</p><p>The upgrade has been successfully locked to prevent being used again but we advise you to also delete <b>upgrade.php+install.php</b> for extra protection.</p>";
$install_lang["succes_upgrade2b"]       = "<p>You succesfully upgraded ".$GLOBALS["btit-tracker"]." ".$GLOBALS["current_btit_version"]." on your tracker.</p><p>We advise you to lock the installation. You can do this by changing <b>install.unlock</b> to <b>install.lock</b> or to delete this <b>upgrade.php+install.php</b> file.</p>";
$install_lang["succes_upgrade3"]        = "<p>We of BTITeam hope you enjoy use of this product and that we will see you again on our <a href=\"http://www.btiteam.org/smf/index.php\" target=\"_blank\">forum</a>.</p>";
$install_lang['error_mysql_database']   = 'The installer was unable to access the &quot;<i>%s</i>&quot; database.  With some hosts, you have to create the database in your administration panel before xBtit can use it.  Some also add prefixes - like your username - to your database names.';
$install_lang['error_message_click']    = 'Click here';
$install_lang['error_message_try_again']= 'to try again';
?>