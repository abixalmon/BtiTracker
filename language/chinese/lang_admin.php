<?php
//���ķ���:ziggear 
//����������޸ĺͷ���������Ҫɾ��ע�ͺ�������Ϣ��

// Sidebar Views (���������)
$language['ACP_BAN_IP']='��ֹIP';
$language['ACP_FORUM']='��̳����';
$language['ACP_USER_GROUP']='�û�������';
$language['ACP_STYLES']='��������';
$language['ACP_LANGUAGES']='��������';
$language['ACP_CATEGORIES']='��������';
$language['ACP_TRACKER_SETTINGS']='Tracker����';
$language['ACP_OPTIMIZE_DB']='�������ݿ�';
$language['ACP_CENSORED']='�����������';
$language['ACP_DBUTILS']='���ݿ�����';
$language['ACP_HACKS']='�������';
$language['ACP_HACKS_CONFIG']='�������';
$language['ACP_MODULES']='ģ�����';
$language['ACP_MODULES_CONFIG']='ģ������';
$language['ACP_MASSPM']='վ����Ⱥ��';
$language['ACP_PRUNE_TORRENTS']='��̭��Ч����';
$language['ACP_PRUNE_USERS']='��̭��Ч�û�';
$language['ACP_SITE_LOG']='�鿴վ����־';
$language['ACP_SEARCH_DIFF']='Search Diff.';
$language['ACP_BLOCKS']='����ģ������';
$language['ACP_POLLS']='��������';
$language['ACP_MENU']='�������';
$language['ACP_FRONTEND']='���ݹ���';
$language['ACP_USERS_TOOLS']='�û�����';
$language['ACP_TORRENTS_TOOLS']='���ӹ���';
$language['ACP_OTHER_TOOLS']='��������';
$language['ACP_MYSQL_STATS']='MySql ״̬';
$language['XBTT_BACKEND']='xbtt ѡ��';
$language['XBTT_USE']='ʹ�� <a href="http://xbtt.sourceforge.net/tracker/" target="_blank">xbtt</a> ��Ϊ���÷�����?';
$language['XBTT_URL']='xbtt ��url���� http://localhost:2710';
$language['GENERAL_SETTINGS']='Tracker�����趨';
$language['TRACKER_NAME']='վ������';
$language['TRACKER_BASEURL']='վ��URL (����Ҫurl��β���� /)';
$language['TRACKER_ANNOUNCE']='��վ�� Announce URL(ÿ��һ��url)'.($XBTT_USE?'<br />'."\n".'<span style="color:#FF0000; font-weight: bold;">����ϸ���announce url�Ƿ���ȷ, �������� xbtt ���÷�����...</span>':'');
$language['TRACKER_EMAIL']='Tracker/վ�� email';
$language['TORRENT_FOLDER']='�����ļ���';
$language['ALLOW_EXTERNAL']='�����ⲿ����';
$language['ALLOW_GZIP']='����GZIP';
$language['ALLOW_DEBUG']='��ҳ����ʾ������Ϣ';
$language['ALLOW_DHT']='���� DHT (�����е�˽�б�־)<br />'."\n".'����Ӧ����֮���ϴ���������';
$language['ALLOW_LIVESTATS']='����ʵʱ״̬��� (ע��:���ܻ����ӷ���������!)';
$language['ALLOW_SITELOG']='���û���վ����־ (��¼�û�/���Ӳ���)';
$language['ALLOW_HISTORY']='������ʷ��¼ (�û�/����)';
$language['ALLOW_PRIVATE_ANNOUNCE']='˽��tracker������(PTվ�빴ѡ)';
$language['ALLOW_PRIVATE_SCRAPE']='˽�е�Scrape'; //���岻��ȷ����
$language['SHOW_UPLOADER']='��ʾ�������ǳ�';
$language['USE_POPUP']='ʹ�õ�������ʾ��������/�ϴ���';
$language['DEFAULT_LANGUAGE']='Ĭ������';
$language['DEFAULT_CHARSET']='Ĭ���ַ���<br />'."\n".'(�Ƽ���ΪGB2312)';
$language['DEFAULT_STYLE']='Ĭ�Ϸ��';
$language['MAX_USERS']='����û��� (����, 0 Ϊ����)';
$language['MAX_TORRENTS_PER_PAGE']='ÿҳ��ʾ������';
$language['SPECIFIC_SETTINGS']='Tracker��ϸ�趨';
$language['SETTING_INTERVAL_SANITY']='�����Լ���� (��, 0 Ϊ����)<br />������Ϊ 1800 (30����)';
$language['SETTING_INTERVAL_EXTERNAL']='�ⲿ���¼�� (��, 0 Ϊ����)<br />�����ⲿ���ӵ���������';
$language['SETTING_INTERVAL_MAX_REANNOUNCE']='���reannounce��� (��)';//�˴�������reannounce
$language['SETTING_INTERVAL_MIN_REANNOUNCE']='��Сreannounce��� (��)';
$language['SETTING_MAX_PEERS']='����ϴ��������� (����)';
$language['SETTING_DYNAMIC']='����̬���� (������ʹ��)';
$language['SETTING_NAT_CHECK']='NAT ���';
$language['SETTING_PERSISTENT_DB']='�������ݿ����� (������ʹ��)';//ԭ��Ϊ��Persistent connections (Database, not recommended)��
$language['SETTING_OVERRIDE_IP']='�����û��ض��򵽼���IP';
$language['SETTING_CALCULATE_SPEED']='���������ٶȺ��������ֽ���';
$language['SETTING_PEER_CACHING']='���� (���ܻ���΢���ӷ���������)';
$language['SETTING_SEEDS_PID']='ͬһPID�����е��������������';
$language['SETTING_LEECHERS_PID']='ͬһPID�����е��������������';
$language['SETTING_VALIDATION']='��֤��ʽ';//��Ϊ����֤ģʽ��
$language['SETTING_CAPTCHA']='��ȫע��(ʹ����֤��, ��ҪGD��Freetype��)';
$language['SETTING_FORUM']='��̳����, ������:<br /><li>�� <font color="#FF0000">internal</font> ��������ʹ��tracker�Դ���̳</li><li>�� <font color="#FF0000">smf</font> ���� <a target="_new" href="http://www.simplemachines.org">Simple Machines Forum</a></li><li>���Լ�����̳������� (�ڴ�����url)</li>';
$language['BLOCKS_SETTING']='��ҳ�趨';
$language['SETTING_CLOCK']='ʱ�Ӹ�ʽ';
$language['SETTING_FORUMBLOCK']='��̳ģ����ʾ';//ԭ�ġ�Forum Block Type����Ϊ��ͨ˳����ֱ�롣
$language['SETTING_NUM_NEWS']='��ʾ���¹�����Ŀ (����)';
$language['SETTING_NUM_POSTS']='��ʾ��̳������Ŀ (����)';
$language['SETTING_NUM_LASTTORRENTS']='��ʾ����������Ŀ (����)';
$language['SETTING_NUM_TOPTORRENTS']='��ʾ���ܻ�ӭ������Ŀ (����)';
$language['CLOCK_ANALOG']='ָ��';//"Analog"
$language['CLOCK_DIGITAL']='����';//"Digital"
$language['FORUMBLOCK_POSTS']='���»ظ�';
$language['FORUMBLOCK_TOPICS']='��󱻻ظ�������';
$language['CONFIG_SAVED']='���ö��ѱ���ȷ����!';
$language['CACHE_SITE']='������¼�� (��, 0 Ϊ����)';
$language['ALL_FIELDS_REQUIRED']='������Ϣ����Ҫ����!';
$language['SETTING_CUT_LONG_NAME']='���������Ƴ���x���ַ����г������ (0 Ϊ������)';
$language['MAILER_SETTINGS']='�ʼ��趨';
$language['SETTING_MAIL_TYPE']='�ʼ���������';
$language['SETTING_SMTP_SERVER']='SMTP������';
$language['SETTING_SMTP_PORT']='SMTP�˿�';
$language['SETTING_SMTP_USERNAME']='SMTP�û���';
$language['SETTING_SMTP_PASSWORD']='SMTP����';
$language['SETTING_SMTP_PASSWORD_REPEAT']='�ظ�SMTP����';
$language['XBTT_TABLES_ERROR']='����Ҫ���� xbtt �����ݱ� (����� xbtt ��װָ��) ��������ݿ������� xbtt ������Ϊ���÷�����!';
$language['XBTT_URL_ERROR']='xbtt ���� url �ѱ�ָ��!';

// BAN FORM (��ֹ����)
$language['BAN_NOTE']='�ڽ�ֹIP���, ����Բ鿴�Խ�ֹ��IP�κ������µĽ�ֹIP��.<br />'."\n".'������������ʼIP����ֹIP��IP���.';
$language['BAN_NOIP']='Ŀǰû�н�ֹ��IP��';
$language['BAN_FIRSTIP']='��ʼIP';
$language['BAN_LASTIP']='��ֹIP';
$language['BAN_COMMENTS']='��ע';
$language['BAN_REMOVE']='ɾ��';
$language['BAN_BY']='������';//��ֹIP����
$language['BAN_ADDED']='����';
$language['BAN_INSERT']='�����µĽ�ֹIP��';
$language['BAN_IP_ERROR']='�Ƿ�IP��ַ.';
$language['BAN_NO_IP_WRITE']='��Ǹ, ��û������IP��ַ!';
$language['BAN_DELETED']='��ֹ��IP���Ѵ����ݿ���ɾ��.<br />'."\n".'<br />'."\n".'<a href="index.php?page=admin&amp;user='.$CURUSER['uid'].'&amp;code='.$CURUSER['random'].'&amp;do=banip&amp;action=read">����\"��ֹIP\"���</a>';

// LANGUAGES (����ѡ��)
$language['LANGUAGE_SETTINGS']='��������';
$language['LANGUAGE']='����';
$language['LANGUAGE_ADD']='�������԰�';
$language['LANGUAGE_SAVED']='��ϲ, �����������޸�';

// STYLES (���ѡ��)
$language['STYLE_SETTINGS']='��������';
$language['STYLE_EDIT']='�༭����';
$language['STYLE_ADD']='����������';
$language['STYLE_NAME']='��������';
$language['STYLE_URL']='���� URL';
$language['STYLE_FOLDER']='ѡ������� ';//ԭΪ��Style&rsquo;s folder�� �ڰ�װ����ҳ�棬����Ϊ��ѡ�������"�Ϻ���
$language['STYLE_NOTE']='����������������Թ����������, ��Ҫ��������, ����Ҫ�Ƚ��ļ����ϴ���ftp�ռ��Ӧ��Ŀ¼.';

// CATEGORIES (Ŀ¼����)
$language['CATEGORY_SETTINGS']='��������';
$language['CATEGORY_IMAGE']='����ͼƬ';
$language['CATEGORY_ADD']='����·���';
$language['CATEGORY_SORT_INDEX']='�������';//ԭ�ġ�Sort Index����ֱ��
$language['CATEGORY_FULL']='����';
$language['CATEGORY_EDIT']='�༭����';
$language['CATEGORY_SUB']='�ϼ�����';//��Sub-Category�������ǡ����ࡱ������ʵ�ǡ��ϼ����ࡱ
$language['CATEGORY_NAME']='����';

// CENSORED (���ʻ�)
$language['CENSORED_NOTE']='<b>ÿ�з���һ��</b>����Ҫ���˵Ĵ� (����ʽ��ᱻ *censored* �滻)';
$language['CENSORED_EDIT']='�༭�������';

// BLOCKS (����ģ��)
$language['BLOCKS_SETTINGS']='����ģ������';
$language['ENABLED']='����';
$language['ORDER']='����';//ԭ��Ϊ��Order��
$language['BLOCK_NAME']='ģ������';
$language['BLOCK_POSITION']='λ��';
$language['BLOCK_TITLE']='���Ա�� (����ʾΪ����֮��ı���)';
$language['BLOCK_USE_CACHE']='Ϊ��ģ�黺��?';
$language['ERR_BLOCK_NAME']='������������˵���ѡ��һ�����õ��ļ�!';
$language['BLOCK_ADD_NEW']='������ģ��';
// POLLS (more in lang_polls.php) (�������� ��ϸ������lang_polls.php)
$language['POLLS_SETTINGS']='��������';
$language['POLLID']='����ID';
$language['INSERT_NEW_POLL']='�����µ���';
$language['CANT_FIND_POLL']='�޷��ҵ��˵���';
$language['ADD_NEW_POLL']='�µ���';//��Add Poll��
// GROUPS (�û���)
$language['USER_GROUPS']='�û������� (��������Ա༭)';
$language['VIEW_EDIT_DEL']='�鿴/�޸�/ɾ��';
$language['CANT_DELETE_GROUP']='�� �ȼ�/�û��� �޷�ɾ��!';
$language['GROUP_NAME']='�û�������';
$language['GROUP_VIEW_NEWS']='�鿴����';
$language['GROUP_VIEW_FORUM']='��̳';
$language['GROUP_EDIT_FORUM']='�޸���̳';
$language['GROUP_BASE_LEVEL']='ѡ���û���ģ��';
$language['GROUP_ERR_BASE_SEL']='ѡ���û���ģ�����!';
$language['GROUP_DELETE_NEWS']='ɾ������';
$language['GROUP_PCOLOR']='ǰ׺ɫ (���� ';
$language['GROUP_SCOLOR']='��׺ɫ (���� ';
$language['GROUP_VIEW_TORR']='�鿴����';
$language['GROUP_EDIT_TORR']='�޸�����';
$language['GROUP_VIEW_USERS']='�鿴�û�';
$language['GROUP_DELETE_TORR']='ɾ������';
$language['GROUP_EDIT_USERS']='�޸��û�';
$language['GROUP_DOWNLOAD']='����Ȩ��';
$language['GROUP_DELETE_USERS']='ɾ���û�';
$language['GROUP_DELETE_FORUM']='ɾ����̳';
$language['GROUP_GO_CP']='�ɽ���������';
$language['GROUP_EDIT_NEWS']='�޸Ĺ���';
$language['GROUP_ADD_NEW']='�������û���';
$language['GROUP_UPLOAD']='�ϴ�Ȩ��';
$language['GROUP_WT']='�û����Ŷ�ʱ�� <1';
$language['GROUP_EDIT_GROUP']='�༭�û���Ȩ��';
$language['GROUP_VIEW']='�鿴';
$language['GROUP_EDIT']='�޸�';
$language['GROUP_DELETE']='ɾ��';
$language['INSERT_USER_GROUP']='�������û���';
$language['ERR_CANT_FIND_GROUP']='�޷��ҵ�����!';
$language['GROUP_DELETED']='�û����ѱ�ɾ��!';
// MASS PM (վ����Ⱥ��)
$language['USERS_FOUND']='���ҵ����û�';//"users found"
$language['USERS_PMED']='��Ⱥ�����û�';//��users PMed��
$language['WHO_PM']='Ҫ��վ���ŷ��͸�˭?';
$language['MASS_SENT']='վ������Ⱥ��!';
$language['MASS_PM']='վ����Ⱥ��';
$language['MASS_PM_ERROR']='�����ύ֮ǰ������ҪȺ��������!';
$language['RATIO_ONLY']='������������ʵ��û�';
$language['RATIO_GREAT']='�������ʴ��ڴ�ֵ���û�';
$language['RATIO_LOW']='��������С�ڴ�ֵ���û�';
$language['RATIO_FROM']='������';
$language['RATIO_TO']='�ռ���';
$language['MASSPM_INFO']='��Ϣ';
// PRUNE USERS (��̭�û�)
$language['PRUNE_USERS_PRUNED']='�ѱ���̭���û�';
$language['PRUNE_USERS']='��̭�û�';//��Prune����Ϊ��̭Ӧ�ñȽϺ���
$language['PRUNE_USERS_INFO']='����ϵͳ��Ϊ��"��Ч�û�"���뿪���� (����δ����������ע���δ��֤����)';
// SEARCH DIFF
$language['SEARCH_DIFF']='Search Diff.';//һֱû��������ô����
$language['SEARCH_DIFF_MESSAGE']='��Ϣ';//message
$language['DIFFERENCE']='����';//��Difference��
$language['SEARCH_DIFF_CHANGE_GROUP']='�����û���';
// PRUNE TORRENTS (��̭����)
$language['PRUNE_TORRENTS_PRUNED']='�ѱ���̭������';
$language['PRUNE_TORRENTS']='��̭��Ч����';
$language['PRUNE_TORRENTS_INFO']='����ϵͳ��Ϊ��"��Ч����"��ʧЧ����';
$language['LEECHERS']='������';
$language['SEEDS']='������';

// DBUTILS
$language['DBUTILS_TABLENAME']='����';
$language['DBUTILS_RECORDS']='��¼��';
$language['DBUTILS_DATALENGTH']='���ݴ�С';
$language['DBUTILS_OVERHEAD']='���';//��Overhead����ʱ��Ϊ�������
$language['DBUTILS_REPAIR']='�޸�';
$language['DBUTILS_OPTIMIZE']='�Ż�';
$language['DBUTILS_ANALYSE']='����';
$language['DBUTILS_CHECK']='���';
$language['DBUTILS_DELETE']='ɾ��';
$language['DBUTILS_OPERATION']='����';
$language['DBUTILS_INFO']='��Ϣ';
$language['DBUTILS_STATUS']='״̬';
$language['DBUTILS_TABLES']='���ݱ�';

// MYSQL STATUS 
$language['MYSQL_STATUS']='MySQL ״̬';

// SITE LOG
$language['SITE_LOG']='վ����־';

// FORUMS (��̳����)
$language['FORUM_MIN_CREATE']='�ɴ����������С�û���';
$language['FORUM_MIN_WRITE']='�ɻ�������С�û���';
$language['FORUM_MIN_READ']='�ɿ�������С�û���';
$language['FORUM_SETTINGS']='��̳����';
$language['FORUM_EDIT']='�༭����';
$language['FORUM_ADD_NEW']='��������';
$language['FORUM_PARENT']='�ϼ�����';
$language['FORUM_SORRY_PARENT']='(��Ǹ, �޷������ϼ�����, �˰����Ѿ����ϼ�����)';
$language['FORUM_PRUNE_1']='�����д�������/����!<br />�㽫��ʧȥ��������...<br />';
$language['FORUM_PRUNE_2']='�����ȷ��ɾ����Щ����';
$language['FORUM_PRUNE_3']='���򷵻�.';
$language['FORUM_ERR_CANNOT_DELETE_PARENT']='����ɾ�����Ӱ���İ���, ���ȷʵҪɾ��, �뽫�Ӱ����Ƶ�����λ��';

// MODULES (ģ������)
$language['ADD_NEW_MODULE']='�����ģ��';
$language['TYPE']='����';
$language['DATE_CHANGED']='�������޸�';//�����������������
$language['DATE_CREATED']='�����Ѵ���';
$language['ACTIVE_MODULES']='�ģ��: ';
$language['NOT_ACTIVE_MODULES']='���ģ��: ';
$language['TOTAL_MODULES']='ȫ��ģ��: ';
$language['DEACTIVATE']='������';//��Deactivate��
$language['ACTIVATE']='����';
$language['STAFF']='վ����';
$language['MISC']='����';//��Miscellaneous��
$language['TORRENT']='����';
$language['STYLE']='����';
$language['ID_MODULE']='ID';

// HACKS (���)
$language['HACK_TITLE']='���';//��Title��
$language['HACK_VERSION']='��ǰ�汾';
$language['HACK_AUTHOR']='����';
$language['HACK_ADDED']='�������';
$language['HACK_NONE']='��ʱû�а�װ���...';
$language['HACK_ADD_NEW']='����²��';
$language['HACK_SELECT']='ѡ��';
$language['HACK_STATUS']='״̬';
$language['HACK_INSTALL']='��װ';
$language['HACK_UNINSTALL']='ж��';
$language['HACK_INSTALLED_OK']='�ɹ���װ���!<br />'."\n".'�鿴��ǰ�����Ϣ�� <a href="index.php?page=admin&amp;user='.$CURUSER['uid'].'&amp;code='.$CURUSER['random'].'&amp;do=hacks&amp;action=read">���ز���������</a>';
$language['HACK_BAD_ID']='�ô�ID��ȡ�����Ϣ����.';
$language['HACK_UNINSTALLED_OK']='�ɹ�ж�ز��!<br />'."\n".'�鿴��ǰ�����Ϣ�� <a href="index.php?page=admin&amp;user='.$CURUSER['uid'].'&amp;code='.$CURUSER['random'].'&amp;do=hacks&amp;action=read">���ز���������</a>';
$language['HACK_OPERATION']='����';//��Operation��
$language['HACK_SOLUTION']='����';//��Solution"

// added rev 520
$language['HACK_WHY_FTP']='һЩ�����Ҫ�޸ĵ��ļ�����д��. <br />'."\n".'��Ҫ����FTPʹ��chmod�����ֱ�Ӵ����ļ����ļ���. <br />'."\n".'Ϊ�˰�װ�˲��, ���FTP��Ϣ���ᱻ��ʱ��������.';
$language['HACK_FTP_SERVER']='FTP ������';
$language['HACK_FTP_PORT']='FTP �˿�';
$language['HACK_FTP_USERNAME']='FTP �û���';
$language['HACK_FTP_PASSWORD']='FTP ����';
$language['HACK_FTP_BASEDIR']='xbtit�ı���·�� (FTP�������ĸ�Ŀ¼)';

// USERS TOOLS (�û�����)
$language['USER_NOT_DELETE']='�޷�ɾ���ÿͻ�ɾ�����Լ�!';
$language['USER_NOT_EDIT']='�޷��༭�ÿͻ�༭���Լ�!';
$language['USER_NOT_DELETE_HIGHER']='�޷�ɾ���ȼ�����ߵ��û�.';
$language['USER_NOT_EDIT_HIGHER']='�޷��༭�ȼ�����ߵ��û�.';
$language['USER_NO_CHANGE']='û����������.';

//Manual Hack Install (�ֶ������װ)
$language['MHI_VIEW_INSRUCT'] = '�鿴�ֶ������װָ��?';
$language['MHI_MAN_INSRUCT_FOR'] = '�ֶ������װָ�� - ';
$language['MHI_RUN_QUERY'] = '��phpMyAdminִ������SQL���';
$language['MHI_IN'] = '��';//"In"
$language['MHI_ALSO_IN'] = 'Ҳ��';
$language['MHI_FIND_THIS'] = '�ҳ�����';//"find this"
$language['MHI_ADD_THIS'] = '��Ӵ���';//"Add this"
$language['MHI_IT'] = '���';//"it"
$language['MHI_REPLACE'] = '�滻Ϊ';//"Replace with"
$language['MHI_COPY'] = '����';
$language['MHI_AS'] = 'Ϊ';//��as��
?>