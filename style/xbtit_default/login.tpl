<script type="text/javascript">
function form_control()
  {
    if (document.getElementById('want_username').value.length==0)
      {
        alert('<tag:language.INSERT_USERNAME />');
        document.getElementById('want_username').focus();
        return false;
      }

    if (document.getElementById('want_password').value == "")
      {
      alert('<tag:language.INSERT_PASSWORD />');
      document.getElementById('want_password').focus();
      return false;

      }

   return true;
  }
</script>
<form method="post" onsubmit="return form_control()" action="<tag:login.action />">
  <table align="center" class="lista" border="0" cellpadding="10">
    <if:FALSE_USER>
    <tr>
      <td align="center" class="lista" colspan="2"><span style="color:#FF0000;"><tag:login_username_incorrent /></span></td>
    </tr>
    </if:FALSE_USER>
    <if:FALSE_PASSWORD>
    <tr>
      <td align="center" class="lista" colspan="2"><span style="color:#FF0000;"><tag:login_password_incorrent /></span></td>
    </tr>
    </if:FALSE_PASSWORD>
    <tr>
      <td align="right" class="header"><tag:language.USER_NAME />:</td>
      <td class="lista"><input type="text" size="40" name="uid" id="want_username" value="<tag:login.username />" maxlength="40" /></td>
    </tr>
    <tr>
      <td align="right" class="header"><tag:language.USER_PWD />:</td>
      <td class="lista"><input type="password" size="40" name="pwd" id="want_password" maxlength="40" /></td>
    </tr>
    <tr>
      <td colspan="2" class="header" align="center"><input type="submit" class="btn" value="<tag:language.FRM_CONFIRM />" /></td>
    </tr>
    <tr>
      <td colspan="2" class="header" align="center"><tag:language.NEED_COOKIES /></td>
    </tr>
  </table>
</form>
<p align="center">
  <a href="<tag:login.create />"><tag:language.ACCOUNT_CREATE /></a>&nbsp;&nbsp;&nbsp;<a href="<tag:login.recover />"><tag:language.RECOVER_PWD /></a>
</p>