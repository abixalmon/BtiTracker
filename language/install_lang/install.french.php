<?php

// french installation file //

$install_lang["charset"]                = "ISO-8859-1";
$install_lang["lang_rtl"]               = FALSE;
$install_lang["step"]                   = "&Eacute;TAPE : ";
$install_lang["welcome_header"]         = "Bienvenue";
$install_lang["welcome"]                = "Bienvenue dans l'installation guid&eacute;e de xbtit.";
$install_lang["installer_language"]     = "Langue :";
$install_lang["installer_language_set"] = "Activer cette langue";
$install_lang["start"]                  = "D&eacute;marrer";
$install_lang["next"]                   = "Suivant";
$install_lang["back"]                   = "Retour";
$install_lang["requirements_check"]     = "N&eacute;cessaires requis";
$install_lang["reqcheck"]               = "N&eacute;c.requis";
$install_lang["settings"]               = "Param&egrave;tres";
$install_lang["system_req"]             = "<p>".$GLOBALS["btit-tracker"]."&nbsp;".$GLOBALS["current_btit_version"]." a besoin de PHP 4.1.2 ou plus et d'une base de donn&eacute;es MYSQL.</p>";
$install_lang["list_chmod"]             = "<p>Avant d'aller plus loin, veuillez v&eacute;rifier que tous les fichiers ont bien &eacute;t&eacute; envoy&eacute;s et que les fichiers suivants ont bien les permissions requises pour autoriser le script &agrave; &eacute;crire (0777 devrait suffire).</p>";
$install_lang["view_log"]               = "Les changements peuvent &ecirc;tre lus ";
$install_lang["here"]                   = "&agrave; cette adresse";
$install_lang["settingup"]              = "Configuration de votre tracker";
$install_lang["settingup_info"]         = "Configuration de base de donn&eacute;es";
$install_lang["sitename"]               = "Nom du site ";
$install_lang["sitename_input"]         = "xbtit";
$install_lang["siteurl"]                = "Adresse du site ";
$install_lang["siteurl_info"]           = "Sans le <b>/</b> final.";
$install_lang["mysql_settings"]         = "Configuration MySQL";
$install_lang["mysql_settings_info"]    = "Configuration de la base de donn&eacute;es.";
$install_lang["mysql_settings_server"]  = "Serveur MySQL ";
$install_lang["mysql_settings_username"] = "Nom d'utilisateur MySQL ";
$install_lang["mysql_settings_password"] = "Mot de passe MySQL ";
$install_lang["mysql_settings_database"] = "Base de donn&eacute;es MySQL ";
$install_lang["mysql_settings_prefix"]  = "Pr&eacute;fixe pour la table MySQL ";
$install_lang["cache_folder"]           = "Dossier 'Cache'";
$install_lang["torrents_folder"]        = "Dossier 'Torrents'";
$install_lang["badwords_file"]          = "badwords.txt";
$install_lang["chat.php"]               = "chat.php";
$install_lang["write_succes"]           = "<span style=\"color:#00FF00; font-weight: bold;\">peut &ecirc;tre &eacute;crit !</span>";
$install_lang["write_fail"]             = "<span style=\"color:#FF0000; font-weight: bold;\">ne peut &ecirc;tre &eacute;crit !</span> (0777)";
$install_lang["write_file_not_found"]   = "<span style=\"color:#FF0000; font-weight: bold;\">introuvable !</span>";
$install_lang["mysqlcheck"]             = "V&eacute;rification de connexion ";
$install_lang["mysqlcheck_step"]        = "V&eacute;rif. MySQL";
$install_lang["mysql_succes"]           = "<span style=\"color:#00FF00; font-weight: bold;\">Connexion &agrave; la base r&eacute;ussie avec succ&egrave;s !</span>";
$install_lang["mysql_fail"]             = "<span style=\"color:#FF0000; font-weight: bold;\">&Egrave;chec, la connexion n'a pas pu &ecirc;tre &eacute;tablie !</span>";
$install_lang["back_to_settings"]       = "Retournez remplir les informations n&eacute;cessaires.";
$install_lang["saved"]                  = "sauvegard&eacute;e";
$install_lang["file_not_writeable"]     = "Le fichier <b>./include/settings.php</b> ne peut &ecirc;tre &eacute;crit.";
$install_lang["file_not_exists"]        = "le fichier <b>./include/settings.php</b> n'existe pas.";
$install_lang["not_continue_settings"]  = "Vous ne pouvez pas continuer l'installation si l'on ne peut pas &eacute;crire le fichier.";
$install_lang["not_continue_settings2"] = "Impossible de continuer avec ce fichier.";
$install_lang["settings.php"]           = "./include/settings.php";
$install_lang["can_continue"]           = "Vous pouvez continuer et changer ceci plus tard.";
$install_lang["mysql_import"]           = "Importation de MySQL";
$install_lang["mysql_import_step"]      = "Imp. SQL";
$install_lang["create_owner_account"]   = "Cr&eacute;ation du compte 'Propri&eacute;taire'";
$install_lang["create_owner_account_step"] = "Cr&eacute;ation de 'Propri&eacute;taire'";
$install_lang["database_saved"]         = "Le fichier database.sql a &eacute;t&eacute; import&eacute; vers votre base de donn&eacute;es.";
$install_lang["create_owner_account_info"] = "Ici vous pouvez cr&eacute;er le compte 'Propri&eacute;taire'.";
$install_lang["username"]               = "Pseudonyme ";
$install_lang["password"]               = "Mot de passe ";
$install_lang["password2"]              = "R&eacute;&eacute;crire le mot de passe ";
$install_lang["email"]                  = "Courriel ";
$install_lang["email2"]                 = "R&eacute;&eacute;crire le courriel ";
$install_lang["is_succes"]              = "effectu&eacute;.";
$install_lang["no_leave_blank"]         = "Ne rien laisser vide.";
$install_lang["not_valid_email"]        = "Ce n'est pas une adresse courriel valide.";
$install_lang["pass_not_same_username"] = "Le mot de passe ne peut �tre identique au pseudonyme.";
$install_lang["email_not_same"]         = "Les adresses courriel ne correspondent pas.";
$install_lang["pass_not_same"]          = "Les mots de passe ne correspondent pas.";
$install_lang["site_config"]            = "Configuration du tracker";
$install_lang["site_config_step"]       = "Config. Tracker";
$install_lang["default_lang"]           = "Langue par d&eacute;faut ";
$install_lang["default_style"]          = "Style par d&eacute;faut ";
$install_lang["torrents_dir"]           = "R&eacute;pertoire Torrents ";
$install_lang["validation"]             = "Mode de validation ";
$install_lang["more_settings"]          = "*&nbsp;&nbsp;&nbsp;Plus de configurations disponibles dans les <u>Outils Admin</u> quand l'installation sera compl&egrave;te.";
$install_lang["tracker_saved"]          = "La configuration est sauvegard&eacute;e.";
$install_lang["finished"]               = "R&eacute;sum&eacute; d'installation";
$install_lang["finished_step"]          = "R&eacute;sum&eacute;";
$install_lang["succes_install1"]        = "L'installation est termin&eacute;e !";
$install_lang["succes_install2a"]       = "<p>Vous avez install&eacute; avec succ&egrave;s ".$GLOBALS["btit-tracker"]." ".$GLOBALS["current_btit_version"]." comme tracker.</p><p>L'installation a &eacute;t&eacute; bloqu&eacute; avec succ&egrave;s et le fichier <b>install.php</b> supprim&eacute; afin de pr&eacute;venir toute utilisation ult&eacute;rieure.</p>";
$install_lang["succes_install2b"]       = "<p>Vous avez install&eacute; avec succ&egrave;s ".$GLOBALS["btit-tracker"]." ".$GLOBALS["current_btit_version"]." comme tracker.</p><p>Nous vous conseillons de bloquer l'installation. Vous pouvez faire cela en renommant le fichier <b>install.unlock</b> en <b>install.lock</b> ou en supprimant le fichier <b>install.php</b>.</p>";
$install_lang["succes_install3"]        = "<p>L'&eacute;quipe BTIT vous souhaite une agr&eacute;able utilisation du produit, et esp&egrave;re vous voir bientot sur le <a href=\"http://www.btiteam.org/smf/index.php\" target=\"_blank\">forum</a>.</p>";
$install_lang["go_to_tracker"]          = "Aller au Tracker";
$install_lang["forum_type"]             = "Type de Forum ";
$install_lang["forum_internal"]         = "Forum interne xbtit";
$install_lang["forum_smf"]              = "Simple Machines Forum";
$install_lang["forum_other"]            = "Forum externe non int&eacute;gr&eacute; - Entrer l'adresse url ici -->";
$install_lang["smf_download_a"]         = "<strong>Si vous utilisez Simple Machines Forum:</strong><br /><br/ >Veuillez t&eacute;l&eacute;charger la derni&egrave;re version de Simple Machines Forum <a target='_new' href='http://www.simplemachines.org/download/'>ici</a> et uploadez le contenu de l'archive dans le dossier \"smf\" et <a target='_new' href='smf/install.php'>cliquez ici</a> pour l'installer.*<br /><strong>(Utilisez s'il vous pla&icirc;t la m&ecirc;me base de donn&eacute;es que vous avez utilis&eacute; lors de cette proc&eacute;dure d'installation).<br /><br /><font color='#FF0000'>Une fois install&eacute;</font></strong> veuillez faire un CHMOD aux fichiers de language de SMF (<strong>";
$install_lang["smf_download_b"]         = "</strong>) &agrave; 777 et cliquez <strong>Suivant</strong> pour continuer l'installation de xbtit.<br /><br /><strong>* Les liens s'ouvriront dans de nouvelles fen&ecirc;tres afin d'&eacute;viter la perte de l'installation de xbtit.</strong></p>";
$install_lang["smf_err_1"]              = "Impossible de trouver Simple Machines Forum dans le dossier \"smf\", veuillez l'install&eacute; avant de continuer.<br /><br />Cliquez <a href=\"javascript: history.go(-1);\">ici</a> pour revenir &agrave; la page pr&eacute;c&eacute;dente.";
$install_lang["smf_err_2"]              = "Impossible de trouver Simple Machines Forum dans la base de donn&eacute;es, veuillez l'install&eacute; avant de continuer.<br /><br />Cliquez <a href=\"javascript: history.go(-1);\">ici</a> pour revenir &agrave; la page pr&eacute;c&eacute;dente.";
$install_lang["smf_err_3a"]             = "Impossible d'&eacute;crire le fichier de language de SMF (<strong>";
$install_lang["smf_err_3b"]             = "</strong>) veuillez faire un CHMOD &agrave; 777 avant de continuer.<br /><br />Cliquez <a href=\"javascript: history.go(-1);\">ici</a> pour revenir &agrave; la page pr&eacute;c&eacute;dente.";
$install_lang["allow_url_fopen"]        = "Valeur de php.ini pour \"allow_url_fopen\" (recommand&eacute;: ON)";
$install_lang["allow_url_fopen_ON"]     = "<span style=\"color:#00FF00; font-weight: bold;\">ON</span>";
$install_lang["allow_url_fopen_OFF"]    = "<span style=\"color:#FF0000; font-weight: bold;\">OFF</span>";
$install_lang["succes_upgrade1"]        = "La mise &agrave jour est compl&egrave;te!";
$install_lang["succes_upgrade2a"]       = "<p>Vous avez mis &agrave jour avec succ&egrave;s ".$GLOBALS["btit-tracker"]." ".$GLOBALS["current_btit_version"]." comme tracker.</p><p>La mise &agrave; jour a &eacute;t&eacute; bloqu&eacute; avec succ&egrave;s mais nous vous conseillons de supprim&eacute; les fichiers <b>upgrade.php+install.php</b> pour une protection accrue.</p>";
$install_lang["succes_upgrade2b"]       = "<p>Vous avez mis &agrave jour avec succ&egrave;s ".$GLOBALS["btit-tracker"]." ".$GLOBALS["current_btit_version"]." comme tracker.</p><p>Nous vous conseillons de bloquer la mise &agrave; jour. Vous pouvez faire cela en renommant le fichier <b>install.unlock</b> en <b>install.lock</b> ou en supprimant les fichiers <b>upgrade.php+install.php</b>.</p>";
$install_lang["succes_upgrade3"]        = "<p>L'&eacute;quipe BTIT vous souhaite une agr&eacute;able utilisation du produit, et esp&egrave;re vous voir bientot sur le <a href=\"http://www.btiteam.org/smf/index.php\" target=\"_blank\">forum</a>.</p>";
$install_lang['error_mysql_database']   = "L'installation n'est pas en mesure d'acc&eacute;der &agrave; la base de donn&eacute;es &quot;<i>%s</i>&quot;. Chez certains h&eacute;bergeurs, vous devez cr&eacute;er la base de donn&eacute;es via le panneau d'administration avant que xBtit puisse l'utiliser. Certains ajoutent aussi des pr&eacute;fixes - comme votre pseudo - dans le nom des bases de donn&eacute;es.";
$install_lang['error_message_click']    = "Cliquez icie";
$install_lang['error_message_try_again']= "pour r&eacute;essayez";

$install_lang["forum_ipb"]              = "Invision Power Board";
$install_lang["ipb_download_a"]         = "<b>If using Invision Power Board:</b><br /><br/ >Please download the latest version of Invision Power Board from your <a target='_new' href='http://www.invisionpower.com/customer/'>Client Area</a> at Invision Power Services, extract the files somewhere on your computer and then upload the contents of the \"upload\" folder to the \"ipb\" folder.<br /><br />Once uploaded please make sure the \"cache\", \"hooks\", \"public\" and \"uploads\" folders are CHMOD'd to 777 recursively, rename \"conf_global.dist.php\" to \"conf_global.php\" and CHMOD that to 777 as well.<br /><br />Once done please <a target='_new' href='ipb/admin/install/index.php'>click here</a> to install it.*<br /><b>(Please use the same database credentials you used for this installation procedure and be sure to enter a database prefix, we suggest using <span style='color:blue;'>ipb_</span> as your prefix).<br /><br /><font color='#FF0000'>Once installed</font></b> please CHMOD the default cached English language file (<b>";
$install_lang["ipb_download_b"]         = "</b>) to 777 and click <b>Next</b> to continue with the xbtitFM installation.<br /><br /><b>* Both links will open into a new window/tab to prevent losing your place on the xbtitFM installation.</b></p>";
$install_lang["ipb_err_1"]              = "Can't find Invision Power Board in the \"ipb\" folder, please install it before proceeding.<br /><br />Click <a href=\"javascript: history.go(-1);\">here</a> to return to the previous page.";
$install_lang["ipb_err_2"]              = "Can't find Invision Power Board in the database, please install it before proceeding.<br /><br />Click <a href=\"javascript: history.go(-1);\">here</a> to return to the previous page.";
$install_lang["ipb_err_3a"]             = "Unable to write to the IPB English language file (<b>";
$install_lang["ipb_err_3b"]             = "</b>) please CHMOD to 777 before proceeding.<br /><br />Click <a href=\"javascript: history.go(-1);\">here</a> to return to the previous page.";
$install_lang["ipb_err_4a"]             = "IPB English language file (<b>";
$install_lang["ipb_err_4b"]             = "</b>) doesn't exist, cannot proceed.<br /><br />Click <a href=\"javascript: history.go(-1);\">here</a> to return to the previous page.";
$install_lang["ipb_err_5"]             = "Unable to write to the IPB Config file (<b>";
$install_lang["ipb_err_6"]             = "Unable to write to the Tracker Config file (<b>";
?>