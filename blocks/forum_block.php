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

global $CURUSER, $FORUMLINK, $THIS_BASEPATH, $db_prefix, $block_forumlimit, $btit_settings, $TABLE_PREFIX;

# return empty block if can't view
if (!$CURUSER || $CURUSER['view_forum']=='no')
    return;

# init based on forum type
if ($FORUMLINK=='smf') {
    $topicsTable=$db_prefix.'topics';
    $postsTable=$db_prefix.'messages';
} else {
    $topicsTable=$TABLE_PREFIX.'topics';
    $postsTable=$TABLE_PREFIX.'posts';
}

# init topics, posts, and average
$row=get_result("SELECT (SELECT COUNT(*) FROM `".$topicsTable."`) AS `tc`, (SELECT COUNT(*) FROM `".$postsTable."`) AS `pc`",true,$btit_settings['cache_duration']);
$topics=$row[0]['tc'];
$posts=$row[0]['pc'];
$postsAvg=($posts==0)?0:number_format(($topics/$posts)*100,0);
$realLastPosts=$btit_settings['forumblocktype']; # 0=topics, 1=posts

# check number of topics
if ($topics!=0) {
    # inits
    $limit='LIMIT '.((isset($block_forumlimit))?$block_forumlimit:5).';';
    $postsList='';
    # test forum type
    if ($FORUMLINK=='smf') {
        $boards=get_result('SELECT ID_BOARD, memberGroups FROM `'.$db_prefix.'boards`;',true,$btit_settings['cache_duration']);
        $exclude=($realLastPosts)?'':'WHERE t.ID_LAST_MSG=m.ID_MSG';
        foreach ($boards as $check) {
            $forumid=$check['ID_BOARD'];
            $read=explode(',',$check['memberGroups']);
            if (!in_array($CURUSER['id_level']+10, $read))
                $exclude.=(($exclude=='')?'WHERE ':' AND ').'m.ID_BOARD!='.$forumid;
        }
        # get posts [ shoult also test for permissions ]
        $lastPosts=get_result('SELECT m.ID_TOPIC AS tid, m.ID_MSG as pid, t.ID_FIRST_MSG as spid, m.posterTime AS added, m.posterName AS username, m.body as body, m.ID_MEMBER as userid FROM '.$db_prefix.'messages as m LEFT JOIN '.$db_prefix.'topics as t ON m.ID_TOPIC=t.ID_TOPIC '.$exclude.' ORDER BY m.posterTime DESC '.$limit,true,$btit_settings['cache_duration']);
        # format posts
        foreach ($lastPosts as $post) {
            # get topic subject
            $title=get_result('SELECT subject FROM '.$db_prefix.'messages WHERE ID_MSG='.$post['spid'].' LIMIT 1;',true,$btit_settings['cache_duration']);
            $title=$title[0]['subject'];
            # cut it if necessary
            $post['title']=(strlen($title>33))?substr($title,0,30).'...':$title;
            $postsList.='<tr><td class="lista"><b><a title="'.$language['FIRST_UNREAD'].': '.$post['title'].'" href="'.$btit_settings['url'].'/index.php?page=forum&amp;action=viewtopic&amp;topicid='.$post['tid'].'.msg'.$post['pid'].'#msg'.$post['pid'].'">'.$post['title'].'</a></b><br />'.$language['LAST_POST_BY'].' <a href="'.$btit_settings['url'].'/index.php?page=forum&amp;action=profile;u='.$post['userid'].'">'.$post['username'].'</a><br />On '.date('d/m/Y H:i:s',$post['added']).'</td></tr>';
        }
    } else {
        # get posts based if can read
        $lastPosts=get_result('SELECT p.topicid as tid, p.id as pid, t.subject, p.added, p.body, p.userid FROM '.$topicsTable.' as t LEFT JOIN '.$postsTable.' as p ON p.topicid=t.id LEFT JOIN '.$TABLE_PREFIX.'forums as f ON f.id=t.forumid WHERE f.minclassread<='.$CURUSER['id_level'].(($realLastPosts)?'':' AND p.id=t.lastpost').' ORDER BY p.added DESC '.$limit,true,$btit_settings['cache_duration']);
        # format posts
        foreach($lastPosts as $post) {
            # get username
            $user=get_result('SELECT ul.prefixcolor, u.username, ul.suffixcolor FROM '.$TABLE_PREFIX.'users_level as ul LEFT JOIN '.$TABLE_PREFIX.'users as u ON u.id_level=ul.id WHERE u.id='.$post['userid'].' LIMIT 1;',true,$btit_settings['cache_duration']);
            if (isset($user[0])) {
                $user=$user[0];
                $post['username']=unesc($user['prefixcolor'].$user['username'].$user['suffixcolor']);
            } else $post['username']='[DELETED USER]';
            $postsList.='<tr><td class="lista"><b><a href="'.$btit_settings['url'].'/index.php?page=forum&amp;action=viewtopic&amp;topicid='.$post['tid'].'&amp;msg='.$post['pid'].'#'.$post['pid'].'">'.htmlspecialchars(unesc($post['subject'])).'</a></b><br />'.$language['LAST_POST_BY'].' <a href="'.$btit_settings['url'].'/index.php?page=userdetails&amp;id='.$post['userid'].'">'.$post['username'].'</a><br />On '.get_date_time($post['added']).'</td></tr>';
        }
    }
} else $postsList='<tr><td class="lista">'.$language['NO_TOPIC'].'</td></tr>';
?>
<table cellpadding="4" cellspacing="1" width="100%">
    <tr>
        <td class="lista">
            <table width="100%" cellspacing="2" cellpadding="2">
                <tr>
                    <td><?php echo $language['TOPICS'];?>:</td>
                    <td align="right"><?php echo number_format($topics);?></td>
                </tr>
                <tr>
                    <td><?php echo $language['POSTS'];?>:</td>
                    <td align="right"><?php echo number_format($posts);?></td>
                </tr>
                <tr>
                    <td><?php echo $language['TOPICS'].'/'.$language['POSTS'];?>:</td>
                    <td align="right"><?php echo $postsAvg;?>%</td>
                </tr>
            </table>
        </td>
    </tr>
    <?php echo $postsList;?>
</table>