<script type="text/javascript">
<!--
function valid_folder(value) {
  if (value=='')
    document.add_hack.confirm.disabled=true;
  else
    document.add_hack.confirm.disabled=false;
}
-->
</script>
<if:test>
<br />
<form name="add_hack" action="<tag:form_action />" method="post">
<input type="hidden" name="add_hack_folder" value="<tag:hack_folder />" />
<table class="lista" cellpadding="4" cellspacing="0">
  <tr>
    <td class="block" colspan="3" align="center"><tag:hack_title_action /></td>
  </tr>
<if:test_ok>
  <tr>
    <td class="header" align="center"><tag:language.HACK_OPERATION /></td>
    <td class="header" align="center"><tag:language.FILE_NAME /></td>
    <td class="header" align="center"><tag:language.HACK_STATUS /></td>
  </tr>
  <loop:test_result>
  <tr>
    <td class="lista"><tag:test_result[].operation /></td>
    <td class="lista"><tag:test_result[].name /></td>
    <td class="lista" style="text-align:center;"><tag:test_result[].status /></td>
  </tr>
  </loop:test_result>
  <tr>
    <td class="header" colspan="3" style="text-align:center;">
      <input type="submit" name="confirm" class="btn" value="<tag:hack_install />" />
      <input type="submit" name="confirm" class="btn" value="<tag:language.FRM_CANCEL />" />
    </td>
  </tr>
<else:test_ok>
  <tr>
    <td class="header" align="center"><tag:language.FILE_NAME /></td>
    <td class="header" align="center"><tag:language.HACK_STATUS /></td>
    <td class="header" align="center"><tag:language.HACK_SOLUTION /></td>
  </tr>
  <loop:test_result>
  <tr>
    <td class="lista"><tag:test_result[].file /></td>
    <td class="lista"><tag:test_result[].message /></td>
    <td class="red" style="font-weight:bold;"><tag:test_result[].solution /></td>
  </tr>
  </loop:test_result>
</if:test_ok>
</table>
</form>
<br />
<a href="<tag:hack_main_link />"><tag:language.ACP_HACKS_CONFIG /></a>
<br />
<else:test>
<table class="lista" width="100%" cellspacing="1" cellpadding="6">
  <tr>
    <td class="header" align="center"><tag:language.HACK_TITLE /></td>
    <td class="header" align="center"><tag:language.HACK_VERSION /></td>
    <td class="header" align="center"><tag:language.HACK_AUTHOR /></td>
    <td class="header" align="center"><tag:language.HACK_ADDED /></td>
    <td class="header" align="center">&nbsp;</td>
  </tr>
  <if:no_hacks>
    <tr>
      <td class="lista" colspan="5" style="text-align:center;"><tag:language.HACK_NONE /></td>
    </tr>

  <else:no_hacks>
  <loop:hacks>
    <tr>
      <td class="lista"><tag:hacks[].title /></td>
      <td class="lista" style="text-align:center;"><tag:hacks[].version /></td>
      <td class="lista"><tag:hacks[].author /></td>
      <td class="lista" style="text-align:center;"><tag:hacks[].added /></td>
      <td class="lista" style="text-align:center;"><a href="<tag:hacks[].uninstall />"><tag:language.HACK_UNINSTALL /></a></td>
    </tr>
  </loop:hacks>
  </if:no_hacks>
</table>
<br />
<form name="add_hack" action="<tag:form_action />" method="post">
<table class="lista" cellpadding="4" cellspacing="0">
  <tr>
    <td class="header" colspan="3"><tag:language.HACK_ADD_NEW /></td>
  </tr>
  <tr>
    <td class="lista"><tag:language.HACK_SELECT /></td>
    <td class="lista" align="left"><tag:hack_combo /></td>
    <td class="lista"><input type="submit" class="btn" name="confirm" disabled="disabled" value="<tag:language.FRM_CONFIRM />" /></td>
  </tr>
</table>
</form>
</if:test>