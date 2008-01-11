<script type="text/javascript">
function ShowHide(id,id1) {
    obj = document.getElementsByTagName("div");
    if (obj[id].style.display == 'block'){
     obj[id].style.display = 'none';
     obj[id1].style.display = 'block';
    }
    else {
     obj[id].style.display = 'block';
     obj[id1].style.display = 'none';
    }
}

function windowunder(link)
{
  window.opener.document.location=link;
  window.close();
}
</script>
    <div align="center">
      <table width="100%" class="lista" border="0" cellspacing="5" cellpadding="5">
        <tr>
          <td class="block" colspan="2" align="center"><tag:torrent.filename /></td>
        </tr>
        <tr>
          <td class="blocklist" border="1" align="left" valign="top" width="21%">  
            <table align="center" cellpadding="0" cellspacing="2">
              <!--<tr>
                <td align="center"><a href="link" rel="lightbox" target="_blank" title="Kliknij aby powiêkszyæ"><img src="link" border="0" width="128"></a></td>
                <td align="center"><img src="images/no_image.gif" border="0"></td>
              </tr>-->
            </table>
            <tag:language.ADDED />: <tag:torrent.date /><br />
            <tag:language.SIZE />: <tag:torrent.size /><br />
            <if:SHOW_UPLOADER>
            <tag:language.UPLOADER />: <tag:torrent.uploader /><br />
            </if:SHOW_UPLOADER>
            <tag:torrent.seeds /><br />
            <tag:torrent.leechers /><br /><br />
            <a href="download.php?id=<tag:torrent.info_hash />&amp;f=<tag:torrent.filename />.torrent"><tag:language.TORRENT /></a><br />
            <if:EXTERNAL>
                <tag:torrent.update_url />
            </if:EXTERNAL><br />
            <if:MOD>
                <tag:mod_task />
            </if:MOD>
          </td>
          <td class="lista" valign="top"><tag:torrent.description /></td>
        </tr>
        <tr>
          <td class="block" colspan="2" align="center"><!--<tag:language.TORRENT_DETAIL />--></td>
        </tr>
            <!--<table border="0" cellpadding="3" cellspacing="0" width="100%">-->
              <tr>
                <td align="right" class="header"><tag:language.INFO_HASH /></td>
                <td class="lista" align="center"><tag:torrent.info_hash /></td>
              </tr>
              <if:EXTERNAL>
              <tr>
                <td valign="middle" align="right" class="header"><tag:torrent.update_url /></td>
                <td class="lista" align="center"><tag:torrent.announce_url /></td>
              </tr>
              <tr>
                <td valign="middle" align="right" class="header"><tag:language.LAST_UPDATE /></td>
                <td class="lista" align="center"><tag:torrent.lastupdate /> (<tag:torrent.lastsuccess />)</td>
              </tr>
              </if:EXTERNAL>
              <tr>
                <td align="right" class="header"><tag:language.CATEGORY_FULL /></td>
                <td class="lista" align="center"><tag:torrent.cat_name /></td>
              </tr>
              <tr>
                <td align="right" class="header"><tag:language.RATING /></td>
                <td class="lista" align="center"><tag:torrent.rating /></td>
              </tr>
        <if:DISPLAY_FILES>
        <tr>
        <td align="right" class="header" valign="top"><a name="expand" href="#expand" onclick="javascript:ShowHide('files','msgfile');"><tag:language.SHOW_HIDE /></a></td>
        <td align="left" class="lista">
        <div style="display:none" id="files">
          <table class="lista">
            <tr>
              <td align="center" class="header"><tag:language.FILE /></td>
              <td align="center" class="header"><tag:language.SIZE /></td>
            </tr>
            <loop:files>
            <tr>
              <td align="center" class="lista"><tag:files[].filename /></td>
              <td align="center" class="lista"><tag:files[].size /></td>
            </tr>
            </loop:files>
          </table>
        </div>
        <div style="display:block" id="msgfile" align="left"><tag:torrent.numfiles /></div>
        </td>
        </tr>
        </if:DISPLAY_FILES>
   <!--   </table>-->
      <a name="comments" />
      <br />
      <br />
      <table width="100%" class="lista">
        <if:INSERT_COMMENT>
        <tr>
          <td align="center" colspan="3">
             <a href="index.php?page=comment&amp;id=<tag:torrent.info_hash />&amp;usern=<tag:current_username />"><tag:language.NEW_COMMENT /></a>
          </td>
        </tr>
        </if:INSERT_COMMENT>
        <if:NO_COMMENTS>
        <tr>
          <td colspan="3" class="lista" align="center"><tag:language.NO_COMMENTS /></td>
        </tr>
        <else:NO_COMMENTS>
        <loop:comments>
        <tr>
          <td class="header"><tag:comments[].user /></td>
          <td class="header"><tag:comments[].date /></td>
          <td class="header" align="right"><tag:comments[].delete /></td>
        </tr>
        <tr>
          <td colspan="3" class="lista" align="center"><tag:comments[].comment /></td>
        </tr>
        </loop:comments>
        </if:NO_COMMENTS>
      </table>
    </div>
    <br />
    <br />
    <div align="center">
      <tag:torrent_footer />
    </div>