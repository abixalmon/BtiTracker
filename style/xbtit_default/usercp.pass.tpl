<form method="post" name="password" action="<tag:pwd.frm_action />">
  <table class="lista" width="100%" align="center">
    <tr>
      <td class="header" align="left"><tag:language.OLD_PWD />:</td>
      <td class="lista"><input type="password" name="old_pwd" size="40" maxlength="40" /></td>
    </tr>
    <tr>
      <td class="header" align="left"><tag:language.USER_PWD />:</td>
      <td class="lista"><input type="password" name="new_pwd" size="40" maxlength="40" /></td>
    </tr>
    <tr>
      <td class="header" align="left"><tag:language.USER_PWD_AGAIN />:</td>
      <td class="lista"><input type="password" name="new_pwd1" size="40" maxlength="40" /></td>
    </tr>

    <tr>
      <td align="center" class="header" colspan="2">
    <table align="center" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td align="center"><input type="submit" class="btn" name="confirm" value="<tag:language.FRM_CONFIRM />"/></td>
        <td align="center"><input type="button" class="btn" name="confirm" onclick="javascript:window.open('<tag:pwd.frm_cancel />','_self');" value="<tag:language.FRM_CANCEL />"/></td>
      </tr>
    </table>
      </td>
    </tr>
  </table>
</form>