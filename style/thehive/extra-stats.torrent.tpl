
  <table width="100%" class="lista">
    <tr>
      <td class="header" align="center"><tag:language.RANK /></td>
      <td class="header"><tag:language.FILE_NAME /></td>
      <td class="header" align="center"><tag:language.FINISHED /></td>
      <td class="header" align="center"><tag:language.SEEDERS /></td>
      <td class="header" align="center"><tag:language.LEECHERS /></td>
      <td class="header" align="center"><tag:language.PEERS /></td>
      <td class="header" align="center"><tag:language.RATIO /></td>
      <if:DISPLAY_SPEED>
      <td class="header" align="center"><tag:language.SPEED /></td>
      </if:DISPLAY_SPEED>
    </tr>
    <loop:torrent>
    <tr>
      <td class="lista" align="center"><tag:torrent[].rank /></td>
      <td class="lista" align="left"><tag:torrent[].filename /></td>
      <td class="lista" align="center" width="10%"><tag:torrent[].complete /></td>
      <td class="lista" align="center" width="10%"><tag:torrent[].seeds /></td>
      <td class="lista" align="center" width="10%"><tag:torrent[].leechers /></td>
      <td class="lista" align="center" width="10%"><tag:torrent[].peers /></td>
      <td class="lista" align="center" width="10%"><tag:torrent[].ratio /></td>
      <if:DISPLAY_SPEED1>
      <td class="lista" align="center"><tag:torrent[].speed /></td>
      </if:DISPLAY_SPEED1>
    </tr>
    </loop:torrent>
  </table>

