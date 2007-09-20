<div align="center">
<form action="<tag:torrent_script />" method="get" name="torrent_search">
  <input type="hidden" name="page" value="torrents" />
  <table border="0" class="lista" align="center">
    <tr>
      <td class="block"><tag:language.TORRENT_SEARCH /></td>
      <td class="block"><tag:language.CATEGORY_FULL /></td>
      <td class="block"><tag:language.TORRENT_STATUS /></td>
      <td class="block">&nbsp;</td>
    </tr>
    <tr>
      <td><input type="text" name="search" size="30" maxlength="50" value="<tag:torrent_search />" /></td>
      <td>
        <tag:torrent_categories_combo />
      </td>
      <td>
        <select name="active" size="1">
        <option value="0" <tag:torrent_selected_all />><tag:language.ALL /></option>
        <option value="1" <tag:torrent_selected_active />><tag:language.ACTIVE_ONLY /></option>
        <option value="2" <tag:torrent_selected_dead />><tag:language.DEAD_ONLY /></option>
        </select>
      </td>
      <td><input type="submit" value="<tag:language.SEARCH />" /></td>
     </tr>
  </table>
</form>
</div>

<table width="100%">
  <tr>
    <td colspan="2" align="center"> <tag:torrent_pagertop /></td>
  </tr>
  <tr>
  <td>
    <table width="100%" class="lista">
      <!-- Column Headers  -->
      <tr>
        <td align="center" class="header"><tag:torrent_header_category /></td>
        <td align="center" class="header"><tag:torrent_header_filename /></td>
        <td align="center" class="header"><tag:torrent_header_comments /></td>
        <td align="center" class="header"><tag:torrent_header_rating /></td>
        <if:WT>
        <td align="center" class="header"><tag:torrent_header_waiting /></td>
        <else:WT>
        </if:WT>
        <td align="center" class="header"><tag:torrent_header_download /></td>
        <td align="center" class="header"><tag:torrent_header_added /></td>
        <td align="center" class="header"><tag:torrent_header_size /></td>
        <if:uploader>
        <td align="center" class="header"><tag:torrent_header_uploader /></td>
        <else:uploader>
        </if:uploader>
        <td align="center" class="header"><tag:torrent_header_seeds /></td>
        <td align="center" class="header"><tag:torrent_header_leechers /></td>
        <td align="center" class="header"><tag:torrent_header_complete /></td>
        <td align="center" class="header"><tag:torrent_header_downloaded /></td>
        <if:XBTT>
        <else:XBTT>
        <td align="center" class="header"><tag:torrent_header_speed /></td>
        </if:XBTT>
        <td align="center" class="header"><tag:torrent_header_average /></td>
      </tr>
      <!-- torrents' listing -->
      <loop:torrents>
      <tr>
        <td align="center" class="lista"><tag:torrents[].category /></td>
        <td align="left" class="lista"><tag:torrents[].filename /></td>
        <td align="center" class="lista"><tag:torrents[].comments /></td>
        <td align="center" class="lista"><tag:torrents[].rating /></td>
        <if:WT1>
        <td align="center" class="lista"><tag:torrents[].waiting /></td>
        <else:WT1>
        </if:WT1>
        <td align="center" class="lista"><tag:torrents[].download /></td>
        <td align="center" class="lista"><tag:torrents[].added /></td>
        <td align="center" class="lista"><tag:torrents[].size /></td>
        <if:uploader1>
        <td align="center" class="lista"><tag:torrents[].uploader /></td>
        <else:uploader1>
        </if:uploader1>
        <td align="center" class="<tag:torrents[].classe_seeds />"><tag:torrents[].seeds /></td>
        <td align="center" class="<tag:torrents[].classe_leechers />"><tag:torrents[].leechers /></td>
        <td align="center" class="lista"><tag:torrents[].complete /></td>
        <td align="center" class="lista"><tag:torrents[].downloaded /></td>
        <if:XBTT1>
        <else:XBTT1>
        <td align="center" class="lista"><tag:torrents[].speed /></td>
        </if:XBTT1>
        <td align="center" class="lista"><tag:torrents[].average /></td>
      </tr>
      </loop:torrents>
    </table>
  </td>
  </tr>
  <tr>
    <td colspan="2" align="center"> <tag:torrent_pagerbottom /></td>
  </tr>
</table>