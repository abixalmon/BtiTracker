<?php
global $users, $torrents, $seeds, $leechers, $percent;
// $language["rtl"]="rtl"; // if your language is  right to left then uncomment this line
// $language["charset"]="ISO-8859-1"; // uncomment this line with specific language charset if different than tracker's one
$language["ACCOUNT_CONFIRM"]="Account bevestiging op de $SITENAME site.";
$language["ACCOUNT_CONGRATULATIONS"]="Gefeliciteerd, uw account is nu gevalideerd!<br />Nu kunt u <a href=index.php?page=login>inloggen</a> op de site en gebruik maken van uw account.";
$language["ACCOUNT_CREATE"]="Account Aanmaken";
$language["ACCOUNT_DELETE"]="Account Verwijderen";
$language["ACCOUNT_DETAILS"]="account details";
$language["ACCOUNT_EDIT"]="Verander account";
$language["ACCOUNT_MGMT"]="Account Beheer";
$language["ACCOUNT_MSG"]="Hallo,\n\nDeze email is verstuurd omdat iemand een account heeft aangemaakt op onze site met behulp van dit email adres.\nAls u dit niet gedaan heeft kunt u deze email negeren, heeft u dit wel gedaan vragen wij u om uw account te activeren \n\nVriendelijke groeten van de staff.";
$language["ACTION"]="Actie";
$language["ACTIVATED"]="Actief";
$language["ACTIVE"]="Status";
$language["ACTIVE_ONLY"]="Alleen actief";
$language["ADD"]="Toevoegen";
$language["ADDED"]="Toegevoegd";
$language["ADMIN_CPANEL"]="Admin Beheer Menu";
$language["ADMINCP_NOTES"]="Hier kunt u alles beheren betrekkende uw site...";
$language["ALL"]="Alles";
$language["ALL_SHOUT"]="Alle Shouts";
$language["ANNOUNCE_URL"]="Tracker announce url:";
$language["ANONYMOUS"]="Anoniem";
$language["ANSWER"]="Beantwoorden";
$language["AUTHOR"]="Autheur";
$language["AVATAR_URL"]="Avatar (url): ";
$language["AVERAGE"]="Gemiddeld";
$language["BACK"]="Terug";
$language["BAD_ID"]="Fout ID!";
$language["BCK_USERCP"]="Terug naar Gebruikers Menu";
$language["BLOCK"]="Blok";
$language["BODY"]="Body";
$language["BOTTOM"]="beneden";
$language["BY"]="Door";
$language["CANT_DELETE_ADMIN"]="Het is onmogelijk een andere Admin te verwijderen!";
$language["CANT_DELETE_NEWS"]="U bent niet bevoegd om nieuws te verwijderen!";
$language["CANT_DELETE_TORRENT"]="U bent niet bevoegd om deze torrent te verwijderen!...";
$language["CANT_DELETE_USER"]="U bent niet bevoegd om gebruikers te verwijderen!";
$language["CANT_DO_QUERY"]="Kan SQL query niet uitvoeren - ";
$language["CANT_EDIT_TORR"]="U bent niet bevoegd om deze torrent te wijzigen!";
$language["CANT_FIND_TORRENT"]="Kan torrentbestand niet vinden!";
$language["CANT_READ_LANGUAGE"]="Kan het taalbestand niet lezen!";
$language["CANT_SAVE_CONFIG"]="Kan de instellingen niet opslaan naar config.php";
$language["CANT_SAVE_LANGUAGE"]="Kan het taalbestand niet opslaan";
$language["CANT_WRITE_CONFIG"]="Waarschuwing: kon config.php niet schrijven!";
$language["CATCHUP"]="Alles Markeren als Gelezen";
$language["CATEGORY"]="Cat.";
$language["CATEGORY_FULL"]="Categorie";
$language["CENTER"]="Midden";
$language["CHANGE_PID"]="Verander PID";
$language["CHARACTERS"]="karakters";
$language["CHOOSE"]="Kies";
$language["CHOOSE_ONE"]="kies &eacute;&eacute;n";
$language["CLICK_HERE"]="klik hier";
$language["CLOSE"]="sluiten";
$language["COMMENT"]="Comm.";
$language["COMMENT_1"]="Commentaar";
$language["COMMENT_PREVIEW"]="Commentaar Vertoning";
$language["COMMENTS"]="Commentaren";
$language["CONFIG_SAVED"]="Gefeliciteerd, nieuwe instellingen zijn opgeslagen.";
$language["COUNTRY"]="Land";
$language["CURRENT_DETAILS"]="Huidige Details";
$language["DATABASE_ERROR"]="Database fout.";
$language["DATE"]="Datum";
$language["DB_ERROR_REQUEST"]="Database fout. Kan verzoek niet inwilligen.";
$language["DB_SETTINGS"]="Database instellingen";
$language["DEAD_ONLY"]="Alleen dode";
$language["DELETE"]="Verwijderen";
$language["DELETE_ALL_READED"]="Verwijder alle gelezen";
$language["DELETE_CONFIRM"]="Weet u zeker dat u dit wilt verwijderen?";
$language["DELETE_TORRENT"]="Verwijder Torrent";
$language["DELFAILED"]="Verwijderen Mislukt";
$language["DESCRIPTION"]="Beschrijving";
$language["DONT_NEED_CHANGE"]="U hoeft deze instellingen niet te veranderen!";
$language["DOWN"]="Dl";
$language["DOWNLOAD"]="Download";
$language["DOWNLOAD_TORRENT"]="Download Torrent";
$language["DOWNLOADED"]="Gedownload";
$language["EDIT"]="Veranderen";
$language["EDIT_LANGUAGE"]="Verander Taal";
$language["EDIT_POST"]="Verander Post";
$language["EDIT_TORRENT"]="Verander Torrent";
$language["EMAIL"]="Email";
$language["EMAIL_SENT"]="Een email is verstuurd naar het aangegeven email adres<br />klik op de bijgevoegde link om uw account te bevestigen.";
$language["EMAIL_VERIFY"]="email account update bij $SITENAME";
$language["EMAIL_VERIFY_BLOCK"]="Bevestigingsmail verstuurd";
$language["EMAIL_VERIFY_MSG"]="Hallo,\n\nDeze email is verstuurd omdat u een ander email adres wilt gaan gebruiken, klik op de onderstaande link om de verandering te bevestigen.\n\nVriendelijke groeten van de staff.";
$language["EMAIL_VERIFY_SENT1"]="<br /><center>Een bevestigingsmail is verstuurd naar:<br /><br /><strong><font color=\"red\">";
$language["EMAIL_VERIFY_SENT2"]="</font></strong><br /><br />U moet op de bijgevoegde link klikken in de email<br />om uw email adres te veranderen. De email zou binnen 10 minuten binnen moeten zijn<br />(meestal gelijk) alhoewel sommige providers de email kunnen herkennen als spam<br />dus controleer uw spam map wanneer u de email niet kunt vinden.<br /><br />";
$language["ERR_500"]="HTTP/1.0 500 Toegang alleen voor bevoegden!";
$language["ERR_AVATAR_EXT"]="Sorry alleen gif,jpg,bmp of png toegestaan";
$language["ERR_BAD_LAST_POST"]="";
$language["ERR_BAD_NEWS_ID"]="Fout nieuws ID!";
$language["ERR_BODY_EMPTY"]="Tekstvak kan niet leeg zijn!";
$language["ERR_CANT_CONNECT"]="Kan geen verbinding maken tot MySQL server";
$language["ERR_CANT_OPEN_DB"]="Kan database niet openen";
$language["ERR_DB_ERR"]="Database fout. Neem contact met de beheerder op.";
$language["ERR_DELETE_POST"]="Verwijder Post. Sanity Controle: U staat op het punt een post te verwijderen. Klik";
$language["ERR_DELETE_TOPIC"]="Verwijder topic. Sanity Controle: U staat op het punt een topic te verwijderen. Klik";
$language["ERR_EMAIL_ALREADY_EXISTS"]="Het email adres is al in gebruik!";
$language["ERR_EMAIL_NOT_FOUND_1"]="Het email adres";
$language["ERR_EMAIL_NOT_FOUND_2"]="is niet gevonden in onze database.";
$language["ERR_ENTER_NEW_TITLE"]="U moet een nieuwe titel toevoegen!";
$language["ERR_FORUM_NOT_FOUND"]="Forum niet gevonden";
$language["ERR_FORUM_UNKW_ACT"]="Forum Fout: Onbekende Actie";
$language["ERR_GUEST_EXISTS"]="'Gast' is een verboden gebruikersnaam. U kunt zich niet registreren als 'Guest'";
$language["ERR_IMAGE_CODE"]="De beveiligingscode komt niet overeen";
$language["ERR_INS_TITLE_NEWS"]="U moet zowel titel ALS nieuws toevoegen";
$language["ERR_INV_NUM_FIELD"]="Ongeldig nummeriek(e) veld(en) van cli&euml;nt";
$language["ERR_INVALID_CLIENT_EVENT"]="Ongeldige actie= van cli&euml;nt.";
$language["ERR_INVALID_INFO_BT_CLIENT"]="Ongelidge informaite ontvangen van BitTorrent cli&euml;nt";
$language["ERR_INVALID_IP_NUMB"]="Ongelid IP adres. Moet beginnen met decimaal (hostnamen niet toegestaan)";
$language["ERR_LEVEL"]="Sorry, uw level ";
$language["ERR_LEVEL_CANT_POST"]="U heeft geen bevoegdheid om een post te plaatsen in dit forum.";
$language["ERR_LEVEL_CANT_VIEW"]="U heeft geen bevoegdheid om dit topic te lezen.";
$language["ERR_MISSING_DATA"]="Data missend!";
$language["ERR_MUST_BE_LOGGED_SHOUT"]="U moet ingelogd zijn om een shout te plaatsen...";
$language["ERR_NO_BODY"]="Tekstvak leeg";
$language["ERR_NO_NEWS_ID"]="Nieuws ID niet gevonden!";
$language["ERR_NO_POST_WITH_ID"]="Geen post met ID ";
$language["ERR_NO_SPACE"]="Uw gebruikersnaam mag geen spatie bevatten, vervang dit door een underscore (_) ed:<br /><br />";
$language["ERR_NO_TOPIC_ID"]="Geen Topic ID terug gekregen";
$language["ERR_NO_TOPIC_POST_ID"]="Geen topic verbonden met post ID";
$language["ERR_NOT_AUTH"]="U bent niet bevoegd!";
$language["ERR_NOT_FOUND"]="Niet gevonden...";
$language["ERR_NOT_PERMITED"]="Niet toegestaan";
$language["ERR_PASS_LENGTH"]="<font color=\"black\">Uw wachtwoord moet minimaal 4 karakters lang zijn.</font>";
$language["ERR_PASSWORD_INCORRECT"]="Wachtwoord incorrect";
$language["ERR_PERM_DENIED"]="Toegang geweigerd";
$language["ERR_PID_NOT_FOUND"]="Download torrent aub opnieuw. Het PID systeem is actief en pid was niet gevonden in de torrent.";
$language["ERR_RETR_DATA"]="Fout tijdens data terughalen!";
$language["ERR_SEND_EMAIL"]="Niet mogelijk om email te verzenden. Neem aub contact op met de beheerder.";
$language["ERR_SERVER_LOAD"]="De server lading is op het moment erg hoog. Bezig met vernieuwen, een moment aub...";
$language["ERR_SPECIAL_CHAR"]="<font color=\"black\">Uw gebruikersnaam mag geen speciale karakters bevatten:<br /><br /><font color=\"red\"><strong>* ? < > @ $ & % etc.</strong></font></font><br />";
$language["ERR_SQL_ERR"]="SQL Fout";
$language["ERR_SUBJECT"]="U moet een onderwerp invullen.";
$language["ERR_TOPIC_ID_NA"]="Topic ID is niet beschikbaar";
$language["ERR_TOPIC_LOCKED"]="Topic is gesloten";
$language["ERR_TORRENT_IN_BROWSER"]="Dit bestand is voor BitTorrent cli&euml;nten.";
$language["ERR_UPDATE_USER"]="Niet mogelijk om gebruikersdata te updaten. Neem aub contact op met de beheerder.";
$language["ERR_USER_ALREADY_EXISTS"]="Er bestaat al een gebruiker met deze gebruikersnaam!";
$language["ERR_USER_NOT_FOUND"]="Sorry, Gebruiker niet gevonden";
$language["ERR_USER_NOT_USER"]="U bent niet bevoegd om iemand anders zijn gebruikers menu binnen te treden!";
$language["ERR_USERNAME_INCORRECT"]="Gebruikersnaam Incorrect";
$language["ERROR"]="Fout";
$language["ERROR_ID"]="Fout ID";
$language["FACOLTATIVE"]="optioneel";
$language["FILE"]="Bestand";
$language["FILE_CONTENTS"]="Inhoud Bestand";
$language["FILE_NAME"]="Bestand Naam";
$language["FIND_USER"]="Vind Gebruiker";
$language["FINISHED"]="Afgerond";
$language["FORUM"]="Forum";
$language["FORUM_ERROR"]="Forum Fout";
$language["FORUM_INFO"]="Forum Info";
$language["FORUM_MIN_CREATE"]="Minimum Aanmaak Level";
$language["FORUM_MIN_READ"]="Minimum Lees Level";
$language["FORUM_SEARCH"]="Forums Zoeken";
$language["FORUM_N_TOPICS"]="Aantal Topics";
$language["FORUM_N_POSTS"]="Aantal Posts";
$language["FRM_DELETE"]="Verwijderen";
$language["FRM_LOGIN"]="Inloggen";
$language["FRM_PREVIEW"]="Vertoning";
$language["FRM_REFRESH"]="Vernieuwen";
$language["FRM_RESET"]="Herstellen";
$language["FRM_SEND"]="Verstuur";
$language["FRM_CONFIRM"]="Bevestigen";
$language["FRM_CANCEL"]="Stoppen";
$language["FRM_CLEAN"]="Schoonmaken";
$language["GLOBAL_SERVER_LOAD"]="Totale Server Lading (Alle websites op huidige server)";
$language["GO"]="Ga";
$language["GROUP"]="Groep";
$language["GUEST"]="Gast";
$language["GUESTS"]="Gasten";
$language["HERE"]="hier";
$language["HISTORY"]="Geschiedenis";
$language["HOME"]="Begin";
$language["IF_YOU_ARE_SURE"]="als u zeker bent.";
$language["IM_SURE"]="Ik weet het zeker";
$language["IN"]="in";
$language["INF_CHANGED"]="Informatie Verancerd!";
$language["INFINITE"]="Oneindig";
$language["INFO_HASH"]="Info Hash";
$language["INS_NEW_PWD"]="Vul NIEUW wachtwoord in!";
$language["INS_OLD_PWD"]="Vul OUD wachtwoord in!";
$language["INSERT_DATA"]="Vul alle belangrijke data in voor de upload.";
$language["INSERT_NEW_FORUM"]="Nieuw Forum Toevoegen";
$language["INVALID_ID"]="Het is geen geldig ID. Sorry!";
$language["INVALID_INFO_HASH"]="Ongeldig info hash waarde.";
$language["INVALID_PID"]="Ongeldig PID";
$language["INVALID_TORRENT"]="Tracker fout: ongeldige torrent";
$language["KEYWORDS"]="Sleutelwoorden";
$language["LAST_EXTERNAL"]="Laatste Externe Torrent Update is gedaan op ";
$language["LAST_NEWS"]="Laatste Nieuws";
$language["LAST_POST_BY"]="Laatste post door";
$language["LAST_SANITY"]="Laatste Sanity Controle is gedaan op ";
$language["LAST_TORRENTS"]="Laatste Torrents";
$language["LAST_UPDATE"]="Laatste Update";
$language["LASTPOST"]="Laatste post";
$language["LEECHERS"]="leechers";
$language["LEFT"]="over";
$language["LOGIN"]="Inloggen";
$language["LOGOUT"]="Uitloggen";
$language["MAILBOX"]="Mailbox";
$language["MANAGE_NEWS"]="Beheer Nieuws";
$language["MEMBER"]="Gebruiker";
$language["MEMBERS"]="Gebruikers";
$language["MEMBERS_LIST"]="Gebruikerslijst";
$language["MINIMUM_100_DOWN"]="(met minimum van 100 MB gedownload)";
$language["MINIMUM_5_LEECH"]="met minimum van 5 leechers, dode torrents niet inbegrepen";
$language["MINIMUM_5_SEED"]="met minimum van 5 seeders";
$language["MKTOR_INVALID_HASH"]="makeTorrent: Ontving een ongeldige hash";
$language["MNU_ADMINCP"]="Admin Beheer Menu";
$language["MNU_FORUM"]="Forum";
$language["MNU_INDEX"]="Hoofdmenu";
$language["MNU_MEMBERS"]="Leden";
$language["MNU_NEWS"]="Nieuws";
$language["MNU_STATS"]="Extra Statistieken";
$language["MNU_TORRENT"]="Torrents";
$language["MNU_UCP_CHANGEPWD"]="Verander Wachtwoord";
$language["MNU_UCP_HOME"]="Begin Gebruikers Menu";
$language["MNU_UCP_IN"]="Uw PM postvak-in";
$language["MNU_UCP_INFO"]="Verander Profiel";
$language["MNU_UCP_NEWPM"]="Nieuw PM";
$language["MNU_UCP_OUT"]="Uw PM postvak-uit";
$language["MNU_UCP_PM"]="Uw PM postvak";
$language["MNU_UPLOAD"]="Uploaden";
$language["MORE_SMILES"]="Meer Emoticons";
$language["MORE_THAN"]="Meer dan ";
$language["MORE_THAN_2"]="artikelen gevonden, weergeven van eerste";
$language["NA"]="Niet Beschikbaar";
$language["NAME"]="Naam";
$language["NEED_COOKIES"]="Let Op: U moet cookies aan hebben staan om in te loggen.";
$language["NEW_COMMENT"]="Voeg uw commentaar toe...";
$language["NEW_COMMENT_T"]="Nieuw Commentaar";
$language["NEWS"]="het nieuws";
$language["NEWS_DESCRIPTION"]="Nieuws:";
$language["NEWS_INSERT"]="Voeg uw nieuws toe";
$language["NEWS_PANEL"]="Nieuws Beheer";
$language["NEWS_TITLE"]="Titel:";
$language["NEXT"]="Volgende";
$language["NO"]="Nee";
$language["NO_BANNED_IPS"]="Er zijn geen gebande IPs";
$language["NO_COMMENTS"]="Geen Commmentaar...";
$language["NO_FORUMS"]="Geen Forum Gevonden!";
$language["NO_MAIL"]="U heeft geen nieuwe mail.";
$language["NO_MESSAGES"]="Geen PM gevonden..";
$language["NO_NEWS"]="Geen nieuws";
$language["NO_PEERS"]="Geen Peers";
$language["NO_RECORDS"]="Sorry, lijst is leeg...";
$language["NO_TOPIC"]="Geen topics gevonden";
$language["NO_TORR_UP_USER"]="Geen torrents geupload door deze gebruiker.";
$language["NO_TORRENTS"]="Geen torrents hier...";
$language["NO_USERS_FOUND"]="Geen gebruikers gevonden!";
$language["NOBODY_ONLINE"]="Niemand online";
$language["NONE"]="Geen";
$language["NOT_ADMIN_CP_ACCESS"]="U heeft geen bevoegdheid om het Admin Beheer Menu binnen te treden!";
$language["NOT_ALLOW_DOWN"]="is niet toegestaan te downloaden van";
$language["NOT_AUTH_DOWNLOAD"]="U heeft geen bevoegdheid om te downloaden. Sorry...";
$language["NOT_AUTH_VIEW_NEWS"]="U heeft geen bevoegdheid om het nieuws te lezen!";
$language["NOT_AUTHORIZED"]="U bent niet bevoegd te kijken van ";
$language["NOT_AUTHORIZED_UPLOAD"]="U heeft geen bevoegdheid om te uploaden!";
$language["NOT_AVAILABLE"]="Niet Beschikbaar";
$language["NOT_MAIL_IN_URL"]="Dis is niet hetzelfde email adres als in de link";
$language["NOT_POSS_RESET_PID"]="Het is niet mogelijk om uw PID te resetten!<br />Neem contact met een beheerder op...";
$language["NOW_LOGIN"]="U wordt nu verzocht in te loggen";
$language["NUMBER_SHORT"]="Aant.";
$language["OLD_PWD"]="Oud Wachtwoord";
$language["ONLY_REG_COMMENT"]="Alleen geregistreerde gebruikers kunnen een commentaartje achterlaten!";
$language["OPT_DB_RES"]="Optimaliseren van database resultaten";
$language["OPTION"]="Optie";
$language["PASS_RESET_CONF"]="wachtwoord vernieuw bevestiging";
$language["PEER_CLIENT"]="Cli&euml;nt";
$language["PEER_COUNTRY"]="Land";
$language["PEER_ID"]="Peer ID";
$language["PEER_LIST"]="Peer Lijst";
$language["PEER_PORT"]="Port";
$language["PEER_PROGRESS"]="Voortgang";
$language["PEER_STATUS"]="Status";
$language["PEERS"]="peers";
$language["PEERS_DETAILS"]="Klik hier om de volledige peer details te bekijken";
$language["PICTURE"]="Afbeelding";
$language["PID"]="PID";
$language["PLEASE_WAIT"]="Een ogenblik geduld alstublieft...";
$language["PM"]="PM";
$language["POSITION"]="Positie";
$language["POST_REPLY"]="Verstuur Reactie";
$language["POSTED_BY"]="Geschreven door";
$language["POSTED_DATE"]="Geschreven op";
$language["POSTS"]="Posts";
$language["POSTS_PER_DAY"]="%s reacties per dag";
$language["POSTS_PER_PAGE"]="reacties per pagina";
$language["PREVIOUS"]="Vorige";
$language["PRIVATE_MSG"]="Priv&eacute; Bericht";
$language["PWD_CHANGED"]="Wachtwoord veranderd!";
$language["QUESTION"]="Vraag";
$language["QUICK_JUMP"]="Snelmenu";
$language["QUOTE"]="Quote:";
$language["RANK"]="Rank";
$language["RATIO"]="Deelverhouding";
$language["REACHED_MAX_USERS"]="Maximum aantal gebruikers bereikt";
$language["READED"]="Lees";
$language["RECEIVER"]="Ontvanger";
$language["RECOVER_DESC"]="Gebruik het onderstaande formulier om uw wachtwoord te vernieuwen en uw account details terug gestuurd te krijgen.<br />(U moet antwoorden op deze bevestigingsmail.)";
$language["RECOVER_PWD"]="Herkrijg Wachtwoord";
$language["RECOVER_TITLE"]="Herkrijg verloren gebruikersnaam of wachtwoord";
$language["REDIRECT"]="Wanneer uw browser geen javascript heeft aanstaan, klik";
$language["REDOWNLOAD_TORR_FROM"]="Download torrent opnieuw van";
$language["REGISTERED"]="Geregistreerd";
$language["REGISTERED_EMAIL"]="Geregistreerd email";
$language["REMOVE"]="Verwijder";
$language["REPLIES"]="Reacties";
$language["REPLY"]="Beantwoord";
$language["RESULT"]="Resultaat";
$language["RETRY"]="Opnieuw";
$language["RETURN_TORRENTS"]="Terug naar de torrent lijst";
$language["REVERIFY_CONGRATS1"]="<center><br />Gefeliciteerd, uw email is bevestigd en succesvol veranderd<br /><br /><strong>Van: <font color=\"red\">";
$language["REVERIFY_CONGRATS2"]="</strong></font><br /><strong>Naar: <font color=\"red\">";
$language["REVERIFY_CONGRATS3"]="</strong></font><br /><br />";
$language["REVERIFY_FAILURE"]="<center><br /><strong><font color=\"red\"><u>Sorry, maar deze link is niet geldig</u></strong></font><br /><br />Een willekeurig nummer is aangemaakt elke keer wanneer u tracht uw email te veranderen dus<br />als u dit bericht ziet heeft u waarschijnelijk meerdere keren getracht uw email<br />te veranderen en gebruikt u een oude link.<br /><br /><strong>Wacht aub tot u zeker weet dat u niet uw nieuwe bevestigingsmail<br />heeft ontvangen voordat u opnieuw probeert uw email te veranderen.</strong><br /><br />";
$language["REVERIFY_MSG"]="Als u getracht heeft uw email adres te veranderen zult u een bevestigingsmail ontvangen op het nieuwe email adres.<br /><br /><font color=\"red\"><strong>Uw email adres zal niet worden veranderd voordat u de email heeft bevestigd.</strong></font>";
$language["RIGHT"]="rechts";
$language["SEARCH"]="Zoeken";
$language["SEEDERS"]="seeders";
$language["SEEN"]="Gezien";
$language["SELECT"]="Selecteer...";
$language["SENDER"]="Verstuurder";
$language["SENT_ERROR"]="Verstuur Fout";
$language["SHORT_C"]="C"; //Shortname for Completed
$language["SHORT_L"]="L"; //Shortname for Leechers
$language["SHORT_S"]="S"; //Shortname for Seeders
$language["SHOUTBOX"]="ShoutBox";
$language["SIZE"]="Grootte";
$language["SORRY"]="Sorry";
$language["SORTID"]="Sorteerid";
$language["SPEED"]="Snelheid";
$language["STICKY"]="Sticky";
$language["SUB_CATEGORY"]="Deel Categorie";
$language["SUBJECT"]="Onderwerp";
$language["SUBJECT_MAX_CHAR"]="Onderwerp is gelimiteerd tot ";
$language["SUC_POST_SUC_EDIT"]="Post is met succes gewijzigd.";
$language["SUC_SEND_EMAIL"]="Een bevestigingsmail is verstuurd naar";
$language["SUC_SEND_EMAIL_2"]="Wacht aub een paar minuten om uw email binnen te krijgen.";
$language["SUCCESS"]="Succes";
$language["SUMADD_BUG"]="Tracker fout roept summaryAdd op";
$language["TABLE_NAME"]="Tabel Naam";
$language["TIMEZONE"]="Tijdzone";
$language["TITLE"]="Titel";
$language["TOP"]="top";
$language["TOP_10_ACTIVE"]="10 Torrents Meest Actief";
$language["TOP_10_BEST_SEED"]="10 Torrents Beste Seeders";
$language["TOP_10_BSPEED"]="10 Torrents Beste Snelheid";
$language["TOP_10_DOWNLOAD"]="Top 10 Downloaders";
$language["TOP_10_SHARE"]="Top 10 Beste Delers";
$language["TOP_10_UPLOAD"]="Top 10 Uploaders";
$language["TOP_10_WORST"]="Top 10 Slechtste Delers";
$language["TOP_10_WORST_SEED"]="10 Torrents met Slechtste Seeders";
$language["TOP_10_WSPEED"]="10 Torrents met Slechtste Snelheid";
$language["TOP_TORRENTS"]="Meest Populaire Torrents";
$language["TOPIC"]="Topic";
$language["TOPICS"]="Topics";
$language["TOPICS_PER_PAGE"]="Topics per pagina";
$language["TORR_PEER_DETAILS"]="Torrent peers details";
$language["TORRENT"]="Torrent";
$language["TORRENT_ANONYMOUS"]="Verstuur als anoniem";
$language["TORRENT_CHECK"]="Sta de tracker to om bruikbare informatie uit het torrentbestand te halen.";
$language["TORRENT_DETAIL"]="Torrent details";
$language["TORRENT_FILE"]="Torrent Bestand";
$language["TORRENT_SEARCH"]="Zoek Torrents";
$language["TORRENT_STATUS"]="Status";
$language["TORRENT_UPDATE"]="Updaten, een moment geduld aub...";
$language["TORRENTS"]="torrents";
$language["TORRENTS_PER_PAGE"]="Torrents per pagina";
$language["TRACK_DB_ERR"]="Tracker/database fout. Details staan in het log.";
$language["TRACKER_INFO"]="$users gebruikers, zoekend $torrents torrents ($seeds seeders, $leechers leechers, $percent%)";
$language["TRACKER_LOAD"]="Tracker Lading";
$language["TRACKER_SETTINGS"]="Tracker Instellingen";
$language["TRACKER_STATS"]="Tracker Statistieken";
$language["TRACKING"]="zoeken";
$language["TRAFFIC"]="Verkeer";
$language["UCP_NOTE_1"]="Hier kunt u uw postvak-in beheren, PM schrijven naar andere gebruikers,";
$language["UCP_NOTE_2"]="Beheer en verander uw instellingen, etc...";
$language["UNAUTH_IP"]="Onbevoegd IP adres.";
$language["UNKNOWN"]="onbekend";
$language["UPDATE"]="Update";
$language["UPFAILED"]="Upload Mislukt";
$language["UPLOAD_IMAGE"]="Upload Afbeelding";
$language["UPLOAD_LANGUAGE_FILE"]="Upload Taalbestand";
$language["UPLOADED"]="Geupload";
$language["UPLOADER"]="Uploader";
$language["UPLOADS"]="Uploads";
$language["URL"]="Link";
$language["USER_CP"]="Mijn Menu";
$language["USER_CP_1"]="Gebruiker Menu";
$language["USER_DETAILS"]="Gebruiker Details";
$language["USER_EMAIL"]="Geldig email";
$language["USER_ID"]="Gebruiker ID";
$language["USER_JOINED"]="Aangemeld op";
$language["USER_LASTACCESS"]="Laatst gezien";
$language["USER_LEVEL"]="Level";
$language["USER_LOCAL_TIME"]="Gebruiker zijn/haar locale tijd";
$language["USER_NAME"]="Gebruiker";
$language["USER_PASS_RECOVER"]="Wachtwoord/Gebruiker herkrijgen";
$language["USER_PWD"]="Wachtwoord";
$language["USERS_SEARCH"]="Gebruikers Zoeken";
$language["VIEW_DETAILS"]="Bekijk details";
$language["VIEW_TOPIC"]="Bekijk Topic";
$language["VIEW_UNREAD"]="Bekijk Ongelezen";
$language["VIEWS"]="Gezien";
$language["VISITOR"]="Bezoeker";
$language["VISITORS"]="Bezoekers";
$language["WAIT_ADMIN_VALID"]="U moet wachten op validatie van een admin...";
$language["WARNING"]="Waarschuwing!";
$language["WELCOME"]="Welkom";
$language["WELCOME_ADMINCP"]="Welkom bij het Admin Beheer Menu";
$language["WELCOME_BACK"]="Welkom terug";
$language["WELCOME_UCP"]="Welkom bij uw Gebruikers Menu";
$language["WORD_AND"]="en";
$language["WORD_NEW"]="Nieuw";
$language["WROTE"]="schreef";
$language["WT"]="WT";
$language["X_TIMES"]="keer";
$language["YES"]="Ja";
$language["LAST_IP"]="Laatste IP";
$language["FIRST_UNREAD"]="Ga naar de eerste ongelezen post";
$language["MODULE_UNACTIVE"]="De benodigde module is niet actief!";
$language["MODULE_NOT_PRESENT"]="De benodigde module bestaat niet!";
$language["MODULE_LOAD_ERROR"]="De benodigde module schijnt verkeerd te zijn!";
?>