<if:NOPEERS>
<table width=100% class="lista" border="0"><tr><td align=\"center\" colspan=\"9\" class=\"lista\"><tag:language.NO_PEERS /></td></tr></table>
<else:NOPEERS>
<script language=javascript>
function windowunder(link)
{
  window.opener.document.location=link;
  window.close();
}
</script>
<table width=100% class="lista" border="0">
<tr><td align=center class="header" colspan=2><tag:language.USER_NAME /></td>
<td align=center class="header"><tag:language.PEER_COUNTRY /></td>
        <if:XBTT>
        <else:XBTT>
<td align=center class="header"><tag:language.PEER_PORT /></td>
        </if:XBTT>
<td align=center class="header"><tag:language.PEER_PROGRESS /></td>
<td align=center class="header"><tag:language.PEER_STATUS /></td>
<td align=center class="header"><tag:language.PEER_CLIENT /></td>
<td align=center class="header"><tag:language.DOWNLOADED /></td>
<td align=center class="header"><tag:language.UPLOADED /></td>
<td align=center class="header"><tag:language.RATIO /></td>
<td align=center class="header"><tag:language.SEEN /></td></tr>
      <!-- peers' listing -->
      <loop:peers>
<tr>
<td align=center class="lista"><tag:peers[].USERNAME /></td>
<td align=center class="lista"><tag:peers[].PM /></td>
<td align=center class="lista"><tag:peers[].FLAG /></td>
        <if:XBTT2>
        <else:XBTT2>
<td align=center class="lista"><tag:peers[].PORT /></td>
		</if:XBTT2>
<td valign=top align=center class="lista"><tag:peers[].PROGRESS /></td>
<td align=center class="lista"><tag:peers[].STATUS /></td>
<td align=center class="lista"><tag:peers[].CLIENT /></td>
<td align=center class="lista"><tag:peers[].DOWNLOADED /></td>
<td align=center class="lista"><tag:peers[].UPLOADED /></td>
<td align=center class="lista"><tag:peers[].RATIO /></td>
<td align=center class="lista"><tag:peers[].SEEN /></td></tr>
</loop:peers>

</table>
<tag:BACK2 /></a> </td>

  </tr>
</table>
</if:NOPEERS>