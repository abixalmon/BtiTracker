<?php

// english installation file //

$install_lang["charset"]                = "ISO-8859-1";
$install_lang["lang_rtl"]               = FALSE;
$install_lang["step"]                   = "PASSO:";
$install_lang["welcome_header"]         = "Benvenuto";
$install_lang["welcome"]                = "Benvenuto nell'installazione del nuovo XBTI-Tracker.";
$install_lang["installer_language"]     = "Linguaggio:";
$install_lang["installer_language_set"] = "Abilita questo linguaggio";
$install_lang["start"]                  = "Avvio";
$install_lang["next"]                   = "Prossimo";
$install_lang["back"]                   = "Ritorna";
$install_lang["requirements_check"]     = "Controlli Richiesti";
$install_lang["reqcheck"]               = "Req.Controlli";
$install_lang["settings"]               = "Settaggi";
$install_lang["system_req"]             = "<p>".$GLOBALS["btit-tracker"]."&nbsp;".$GLOBALS["current_btit_version"]." richiede PHP 4.1.2 o superiore ed un database MYSQL.</p>";
$install_lang["list_chmod"]             = "<p>Prima di continuare, ti preghiamo di controllare che tutti i file che hai inviato (nel server ftp o locale), siano provvisti dei necessari permessi di lettura e scrittura (0777 è sufficiente).</p>";
$install_lang["view_log"]               = "Puoi vedere il log di tutti i  cambiamenti";
$install_lang["here"]                   = "qui";
$install_lang["settingup"]              = "Setta il tuo tracker";
$install_lang["settingup_info"]         = "Settaggi Base";
$install_lang["sitename"]               = "Nome del sito";
$install_lang["sitename_input"]         = "XBTI-Tracker";
$install_lang["siteurl"]                = "Site-url";
$install_lang["siteurl_info"]           = "Senza riportare l'ultimo slash";
$install_lang["mysql_settings"]         = "Settaggi MySQL<br />\nCrea l'utente mysql ed il database, inserisci qui i dettagli:";
$install_lang["mysql_settings_info"]    = "Settaggi Database.";
$install_lang["mysql_settings_server"]  = "MySQL Server (localhost funziona per molti server)";
$install_lang["mysql_settings_username"] = "MySQL Utente";
$install_lang["mysql_settings_password"] = "MySQL Password";
$install_lang["mysql_settings_database"] = "MySQL Database";
$install_lang["mysql_settings_prefix"]  = "MySQL Prefisso Tabella";
$install_lang["cache_folder"]           = "Cartella Cache";
$install_lang["torrents_folder"]        = "Cartella Torrent";
$install_lang["badwords_file"]          = "badwords.txt";
$install_lang["chat.php"]               = "chat.php";
$install_lang["write_succes"]           = "<span style=\"color:#00FF00; font-weight: bold;\">è scrivibile!</span>";
$install_lang["write_fail"]             = "<span style=\"color:#FF0000; font-weight: bold;\">NON è scrivibile!</span> (0777)";
$install_lang["write_file_not_found"]   = "<span style=\"color:#FF0000; font-weight: bold;\">NON trovato!</span>";
$install_lang["mysqlcheck"]             = "Controllo Connessione MySQL";
$install_lang["mysqlcheck_step"]        = "Controllo MySQL";
$install_lang["mysql_succes"]           = "<span style=\"color:#00FF00; font-weight: bold;\">Connesso al database con successo!</span>";
$install_lang["mysql_fail"]             = "<span style=\"color:#FF0000; font-weight: bold;\">ERRORE, la connessione non puo essere stabilita!</span>";
$install_lang["back_to_settings"]       = "Ritorna indietro e riempi le informazioni necessarie.";
$install_lang["saved"]                  = "salvato";
$install_lang["file_not_writeable"]     = "Il file <b>./include/settings.php</b> non puo essere scritto.";
$install_lang["file_not_exists"]        = "Il file <b>./include/settings.php</b> non esiste.";
$install_lang["not_continue_settings"]  = "Non puoi continuare con l'installazione senza averli resi leggibili/scrivibili.";
$install_lang["not_continue_settings2"] = "Non puoi continuare con questo file.";
$install_lang["settings.php"]           = "./include/settings.php";
$install_lang["can_continue"]           = "Puoi continuare e cambiarlo dopo.";
$install_lang["mysql_import"]           = "Importo MySQL";
$install_lang["mysql_import_step"]      = "Imp. SQL.";
$install_lang["create_owner_account"]   = "Sto creando l'acconto Owner";
$install_lang["create_owner_account_step"] = "Owner Fatto";
$install_lang["database_saved"]         = "Il db.sql è stato importato nel tuo database.";
$install_lang["create_owner_account_info"] = "Qui puoi creare l'acconto owner.";
$install_lang["username"]               = "Utente";
$install_lang["password"]               = "Password";
$install_lang["password2"]              = "Ripeti password";
$install_lang["email"]                  = "Email";
$install_lang["email2"]                 = "Ripeti email";
$install_lang["is_succes"]              = "fatto.";
$install_lang["no_leave_blank"]         = "Non lasciare nulla vuoto.";
$install_lang["not_valid_email"]        = "Questa email non è valida.";
$install_lang["pass_not_same_username"] = "La Password non puo' essere uguale dell'utente.";
$install_lang["email_not_same"]         = "L'indirizzo email non corrisponde.";
$install_lang["pass_not_same"]          = "La Passwords non corrisponde.";
$install_lang["site_config"]            = "Settaggi Tracker";
$install_lang["site_config_step"]       = "Sett. Tracker";
$install_lang["default_lang"]           = "Linguaggio di Default";
$install_lang["default_style"]          = "Style di Default";
$install_lang["torrents_dir"]           = "Cartella Torrent";
$install_lang["validation"]             = "Tipo di Convalida";
$install_lang["more_settings"]          = "*&nbsp;&nbsp;&nbsp;Altri settaggi sono nel <u>Pannello di Amministrazione</u> quando l'installazione sarà completata.";
$install_lang["tracker_saved"]          = "I settaggi sono salvati.";
$install_lang["finished"]               = "Rounding up the Installation";
$install_lang["finished_step"]          = "Rounding up";
$install_lang["succes_install1"]        = "L'installazione è completata!";
$install_lang["succes_install2a"]       = "<p>Hai installato con successo ".$GLOBALS["btit-tracker"]." ".$GLOBALS["current_btit_version"]." nel tuo server.</p><p>L'installazione è stata conclusa con successo, per prevenire che l'installer possa essere riutilizzato, ti consigliamo di rimuovere anche <b>l'install.php</b> per una maggiore protezione.</p>";
$install_lang["succes_install2b"]       = "<p>Hai installato con successo ".$GLOBALS["btit-tracker"]." ".$GLOBALS["current_btit_version"]." nel tuo server.</p><p>Noi ti consigliamo di chiudere l'installer. Lo puoi fare cambiando <b>install.unlock</b> in <b>install.lock</b> oppure cancellando questo file<b>install.php</b> </p>";
$install_lang["succes_install3"]        = "<p>Noi del BTITeam speriamo che usufruirai di questo prodotto, e che ci rivedremo di nuovo nel nostro <a href=\"http://www.btiteam.org/smf/index.php\" target=\"_blank\">forum</a>.</p>";
$install_lang["go_to_tracker"]          = "Vai al tuo tracker";
$install_lang["forum_type"]             = "Tipo Forum";
$install_lang["forum_internal"]         = "XBTI-Tracker Forum Interno";
$install_lang["forum_smf"]              = "Simple Machines Forum";
$install_lang["forum_other"]            = "Forum Esterno non integrato - Digita l'url qui -->";
$install_lang["smf_download_a"]         = "<strong>Se usi Simple Machines Forum:</strong><br /><br/ >Scarica l'ultima versione di Simple Machines Forum <a target='_new' href='http://www.simplemachines.org/download/'>qui</a> ed invia il contenuto dell'archivio nella cartella \"smf\" , e <a target='_new' href='smf/install.php'>premi qui</a> per installare.*<br /><strong>(Usa le stesse credenziali che hai usato per la procedura d'installazione).<br /><br /><font color='#FF0000'>Una volta installato</font></strong> fai CHMOD per il file di lingua di SMF Inglese(<strong>";
$install_lang["smf_download_b"]         = "</strong>) a 777 e premi <strong>Prossimo</strong> per continuare con l'installazione di XBTI-Tracker<br /><br /><strong>* Ambo i collegamenti si apriranno in una nuova finestra/tabella per prevenire di perdere la pagina di installazione di XBTI-Tracker.</strong></p>";
$install_lang["smf_err_1"]              = "Non trovo Simple Machines Forum nella cartella\"smf\", installa prima di procedere.<br /><br />Premi <a href=\"javascript: history.go(-1);\">qui</a> per tornare alla pagina precedente.";
$install_lang["smf_err_2"]              = "Non trovo Simple Machines Forum nel database, installa prima di procedere.<br /><br />Premi <a href=\"javascript: history.go(-1);\">qui</a> per tornare alla pagina precedente.";
$install_lang["smf_err_3a"]             = "Non posso scrivere nel file SMF English language (<strong>";
$install_lang["smf_err_3b"]             = "</strong>) fai  CHMOD 777 prima di procedere.<br /><br />Premi <a href=\"javascript: history.go(-1);\">qui</a> per tornare alla pagina precedente.";
$install_lang["allow_url_fopen"]        = "php.ini value for \"allow_url_fopen\" (best is ON)";
$install_lang["allow_url_fopen_ON"]        = "<span style=\"color:#00FF00; font-weight: bold;\">ON</span>";
$install_lang["allow_url_fopen_OFF"]        = "<span style=\"color:#FF0000; font-weight: bold;\">OFF</span>";
?>