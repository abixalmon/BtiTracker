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
////////////////////////////////////////////////////////////////////////////////////

if (!defined('IN_ACP'))
    die('non direct access!');

include(load_language('lang_usercp.php'));
# get uid
$uid=isset($_GET['uid'])?(int)$_GET['uid']:0;
# test uid
if ($uid==$CURUSER['uid'] || $uid==1) {
    if ($action=='delete') # cannot delete guest/myself
        stderr($language['ERROR'],$language['USER_NOT_DELETE']);
    # cannot edit guest/myself
    stderr($language['ERROR'],$language['USER_NOT_EDIT']);
}

# get uid info
if ($XBTT_USE)
    $curu=get_result('SELECT u.username, u.cip, ul.level, ul.id_level as base_level, u.email, u.avatar, u.joined, u.lastconnect, u.id_level, u.language, u.style, u.flag, u.time_offset, u.topicsperpage, u.postsperpage, u.torrentsperpage, (u.downloaded+x.downloaded) as downloaded, (u.uploaded+x.uploaded) as uploaded FROM '.$TABLE_PREFIX.'users u INNER JOIN '.$TABLE_PREFIX.'users_level ul ON ul.id=u.id_level LEFT JOIN xbt_users x ON x.uid=u.id WHERE u.id='.$uid.' LIMIT 1',true);
else
    $curu=get_result('SELECT u.username, u.cip, ul.level, ul.id_level as base_level, u.email, u.avatar, u.joined, u.lastconnect, u.id_level, u.language, u.style, u.flag, u.time_offset, u.topicsperpage, u.postsperpage, u.torrentsperpage, u.downloaded, u.uploaded FROM '.$TABLE_PREFIX.'users u INNER JOIN '.$TABLE_PREFIX.'users_level ul ON ul.id=u.id_level WHERE u.id='.$uid.' LIMIT 1',true);

# test for bad id
if (!isset($curu[0]))
    stderr($language['ERROR'],$language['BAD_ID']);
# save memory address sums
$curu=$curu[0];
# test levels
if ($CURUSER['id_level'] < $curu['base_level']){
    if ($action=='delete') # cannot delete guest/myself
        stderr($language['ERROR'],$language['USER_NOT_DELETE_HIGHER']);
    # cannot edit guest/myself
    stderr($language['ERROR'],$language['USER_NOT_EDIT_HIGHER']);
}
$note='';
# find smf_id
if ($FORUMLINK=='smf') {
    if (!isset($curu['smf_id']) || $curu['smf_id']==0) {
        # go full mysql search on it's ass
        $smf_user=get_result('SELECT `ID_MEMBER` FROM `'.$db_prefix.'members` WHERE `memberName`='.sqlesc($curu['username']).' LIMIT 1;');
        if (isset($smf_user[0])) {
            $smf_fid=$smf_user[0]['ID_MEMBER'];
            quickQuery('UPDATE `'.$TABLE_PREFIX.'users` SET `smf_fid`='.$smf_fid.' WHERE `id`='.$uid.' LIMIT 1;');
        } else {
            $smf_fid=false;
            $note=' User not found in SMF.';
        }
    } else $smf_fid=$curu['smf_fid'];
} else $smf_fid=false;

# init vars
if (isset($_GET['returnto'])) {
    $ret_decode=urldecode($_GET['returnto']);
    $ret_url=htmlspecialchars($_GET['returnto']);
} else {
    $ret_decode='index.php';
    $ret_url='index.php';
}
$edit=true;
$profile=array();
$newname='';

switch ($action) {
    case 'delete':
        if (isset($_GET['sure']) && $_GET['sure']==1) {
            quickQuery('DELETE FROM '.$TABLE_PREFIX.'users WHERE id='.$uid.' LIMIT 1;',true);
            if ($FORUMLINK=='smf')
                quickQuery('DELETE FROM '.$db_prefix.'members WHERE ID_MEMBER='.$smf_fid.' LIMIT 1;');
            if ($XBTT_USE)
                quickQuery('DELETE FROM xbt_users WHERE uid='.$uid.' LIMIT 1;');

            write_log('Deleted '.unesc($curu['level']).' '.$profile['username'],'modified');
            redirect($ret_decode);
        } else {
            $edit=false;
            $block_title=$language['ACCOUNT_EDIT'];
            $profile['username']=unesc($curu['username']);
            $profile['last_ip']=unesc($curu['cip']);
            $profile['level']=unesc($curu['level']);
            $profile['joined']=unesc($curu['joined']);
            $profile['lastaccess']=unesc($curu['lastconnect']);
            $profile['downloaded']=makesize($curu['downloaded']);
            $profile['uploaded']=makesize($curu['uploaded']);
            $profile['return']='document.location.href=\''.$ret_decode.'\'';
            $profile['confirm_delete']='document.location.href=\'index.php?page=admin&amp;user='.$CURUSER['uid'].'&amp;code='.$CURUSER['random'].'&amp;do=users&amp;action=delete&amp;uid='.$uid.'&amp;sure=1&amp;returnto='.$ret_url.'\'';
        }
        break;

    case 'edit':
        # init vars
        $profile['username']=unesc($curu['username']);
        $profile['email']=unesc($curu['email']);
        $profile['uploaded']=$curu['uploaded'];
        $profile['downloaded']=$curu['downloaded'];
        $profile['down']=makesize($curu['downloaded']);
        $profile['up']=makesize($curu['uploaded']);
        $profile['ratio']=($curu['downloaded']>0?$curu['uploaded']/$curu['downloaded']:'');
        # init options
        $opts['name']='level';
        $opts['complete']=true;
        $opts['id']='id';
        $opts['value']='level';
        $opts['default']=$curu['id_level'];
        # rank list
        $ranks=rank_list();
        $admintpl->set('rank_combo',get_combo($ranks, $opts));
        # lang list
        $opts['name']='language';
        $opts['value']='language';
        $opts['default']=$curu['language'];
        $langs=language_list();
        $admintpl->set('language_combo',get_combo($langs, $opts));
        # style list
        $opts['name']='style';
        $opts['value']='style';
        $opts['default']=$curu['style'];
        $styles=style_list();
        $admintpl->set('style_combo',get_combo($styles, $opts));
        # timezone list
        $opts['name']='timezone';
        $opts['id']='difference';
        $opts['value']='timezone';
        $opts['default']=$curu['time_offset'];
        $tzones=timezone_list();
        $admintpl->set('tz_combo',get_combo($tzones, $opts));
        # flag list
        $opts['complete']=false;
        $opts['value']='name';
        $opts['id']='id';
        $opts['default']=$curu['flag'];
        $flags=flag_list();
        $admintpl->set('flag_combo',get_combo($flags, $opts));
        # posts/topics per page
        if ($FORUMLINK=='' || $FORUMLINK=='internal') {
            $admintpl->set('INTERNAL_FORUM',true,true);
            $profile['topicsperpage']=$curu['topicsperpage'];
            $profile['postsperpage']=$curu['postsperpage'];
        } else {
            $admintpl->set('INTERNAL_FORUM',false,true);
            $profile['topicsperpage']='';
            $profile['postsperpage']='';
        }
        # torrents per page
        $profile['torrentsperpage']=$curu['torrentsperpage'];
        # avatar
        $profile['avatar']=($curu['avatar']!='')?$curu['avatar']:$STYLEURL.'/images/default_avatar.gif';
        $profile['avatar_field']=unesc($curu['avatar']);
        $profile['avatar']='<img onload="resize_avatar(this);" src="'.htmlspecialchars($profile['avatar']).'" alt="" />';
        # form stuff
        $profile['frm_action']='index.php?page=admin&amp;user='.$CURUSER['uid'].'&amp;code='.$CURUSER['random'].'&amp;do=users&amp;action=save&amp;uid='.$uid;
        $profile['frm_cancel']='index.php?page=usercp&amp;uid='.$uid;
        # title
        $block_title=$language['ACCOUNT_EDIT'];
        break;
    
    case 'save':
        if ($_POST['confirm']==$language['FRM_CONFIRM']) {


            $idlangue=(int)$_POST['language'];
            $idstyle=(int)$_POST['style'];
            $idflag=(int)$_POST['flag'];
            $level=(int)$_POST['level'];
            $time=(int)$_POST['timezone']; # this is wrong, half hour based time zones won't work
            $topicsperpage=(isset($_POST['topicsperpage']))?(int)$_POST['topicsperpage']:$curu['topicsperpage'];
            $postsperpage=(isset($_POST['postsperpage']))?(int)$_POST['postsperpage']:$curu['postsperpage'];
            $torrentsperpage=(int)$_POST['torrentsperpage'];
            $uploaded=(float)$_POST['uploaded'];
            $downloaded=(float)$_POST['downloaded'];
            $email=AddSlashes($_POST['email']);
            $avatar=unesc($_POST['avatar']);
            $username=unesc($_POST['username']);
            $pass=$_POST['pass'];
            $chpass=(isset($_POST['chpass']) && $pass!='');
            # new level of the user
            $rlev=do_sqlquery('SELECT id_level as base_level, level as name FROM '.$TABLE_PREFIX.'users_level WHERE id='.$level.' LIMIT 1;');
            $reslev=mysql_fetch_assoc($rlev);
            if ( ($CURUSER['id_level'] < $reslev['base_level']))
                $level=0;
            # check avatar image extension if someone have better idea ;)
            if ($avatar && $avatar!='' && !in_array(substr($avatar,strlen($avatar)-4),array('.gif','.jpg','.bmp','.png')))
                stderr($language['ERROR'], $language['ERR_AVATAR_EXT']);
            if ($idlangue>0 && $idlangue != $curu['language'])
                $set[]='language='.$idlangue;
            if ($idstyle>0 && $idstyle != $curu['style'])
                $set[]='style='.$idstyle;
            if ($idflag>0 && $idflag != $curu['flag'])
                $set[]='flag='.$idflag;
            if ($level>0 && $level != $curu['id_level']) {
                if ($FORUMLINK=='smf') {
                    # find the coresponding level in smf
                    $smf_group=get_result('SELECT ID_GROUP FROM '.$db_prefix.'membergroups WHERE groupName="'.$reslev['name'].'" LIMIT 1;', true, $CACHE_DURATION);
                    # if there is one update it
                    if (isset($smf_group[0]))
                        $smfset[]='ID_GROUP='.$smf_group[0]['ID_GROUP'];
                    else $note.=' Group not found in SMF.';
                }
                $set[]='id_level='.$level;
            }
            if ($time != $curu['time_offset'])
                $set[]='time_offset='.$time;
            if ($email != $curu['email'])
                $set[]='email='.sqlesc($email);
            if ($avatar != $curu['avatar'])
                $set[]='avatar='.sqlesc(htmlspecialchars($avatar));
            if ($username != $curu['username']) {
                $sql_name=sqlesc($curu['username']);
                $username=sqlesc($username);
                $dupe=get_result('SELECT id FROM '.$TABLE_PREFIX.'users WHERE username='.$username.' LIMIT 1;');
                if (!isset($dupe[0])) {
                    $set[]='username='.$username;
                    $newname=' ( now: '.$username;
                    if ($FORUMLINK=='smf')
                    {
                        $dupe=get_result('SELECT ID_MEMBER FROM '.$db_prefix.'members WHERE memberName='.$username.' LIMIT 1;');
                        if (!isset($dupe[0])) {
                            $smfset[]='memberName='.$username;
                        } else
                            $newname.=', dupe name in smf memberName';
                        $dupe=get_result('SELECT ID_MEMBER FROM '.$db_prefix.'members WHERE realName='.$username.' LIMIT 1;');
                        if (!isset($dupe[0])) {
                            $smfset[]='realName='.$username;
                        } else
                            $newname.=', dupe name in smf realName';
                    }
                    $newname.=' )';
                } else $note.=' Dupe name in XBTIT.';
            }
            if ($topicsperpage != $curu['topicsperpage']) 
                $set[]='topicsperpage='.$topicsperpage;
            if ($postsperpage != $curu['postsperpage'])
                $set[]='postsperpage='.$postsperpage;
            if ($torrentsperpage != $curu['torrentsperpage'])
                $set[]='torrentsperpage='.$torrentsperpage;
            if ($XBTT_USE){
                if ($downloaded != $curu['downloaded']) {
                    $xbtset[]='downloaded='.$downloaded;
                    $set[]='downloaded=0';
                }
                if ($uploaded != $curu['uploaded']) {
                    $xbtset[]='uploaded='.$uploaded;
                    $set[]='uploaded=0';
                }
            } else {
                if ($uploaded != $curu['uploaded'])
                    $set[]='uploaded='.$uploaded;
                if ($downloaded != $curu['downloaded'])
                    $set[]='downloaded='.$downloaded;
            }
            if ($chpass) {
                $set[]='password='.sqlesc(md5($pass));
                $passhash=smf_passgen($username, $pass);
                $smfset[]='passwd='.sqlesc($passhash[0]);
                $smfset[]='passwordSalt='.sqlesc($passhash[1]);
            }

            $updateset=(isset($set))?implode(',',$set):'';
            $updatesetxbt=(isset($xbtset))?implode(',',$xbtset):'';
            $updatesetsmf=(isset($smfset))?implode(',',$smfset):'';
            if ($updateset!='') {
                if ($XBTT_USE && $updatesetxbt!='')
                    quickQuery('UPDATE xbt_users SET '.$updatesetxbt.' WHERE uid='.$uid.' LIMIT 1;');
                if (($FORUMLINK=='smf') && ($updatesetsmf!='') && (!is_bool($smf_fid)))
                    quickQuery('UPDATE '.$db_prefix.'members SET '.$updatesetsmf.' WHERE ID_MEMBER='.$smf_fid.' LIMIT 1;');
                quickQuery('UPDATE '.$TABLE_PREFIX.'users SET '.$updateset.' WHERE id='.$uid.' LIMIT 1;');

                success_msg($language['SUCCESS'], $language['INF_CHANGED'].$note.'<br /><a href="index.php?page=admin&amp;user='.$CURUSER['uid'].'&amp;code='.$CURUSER['random'].'">'.$language['MNU_ADMINCP'].'</a>');
                write_log('Modified user <a href="'.$btit_settings['url'].'/index.php?page=torrent-userdetails&amp;id='.$uid.'">'.$curu['username'].'</a> '.$newname.' ( '.count($set).' changes on uid '.$uid.' )','modified');
                stdfoot(true,false);
                die();
            } else stderr($language['ERROR'],$language['USER_NO_CHANGE']);
        }
        redirect('index.php?page=admin&user='.$CURUSER['uid'].'&code='.$CURUSER['random']);
        break;
}

# set template info
$admintpl->set('profile',$profile);
$admintpl->set('language',$language);
$admintpl->set('edit_user',$edit,true);
?>