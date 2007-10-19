
<!-- ### Password strength javascript ### -->

<script type="text/javascript" src="jscript/passwdcheck.js"></script>

<!-- ### End ### -->

<script type="text/javascript">
function form_control()
  {
    if (document.getElementById('user').value.length==0)
      {
        alert('<tag:language.INSERT_USERNAME />');
        document.getElementById('user').focus();
        return false;
      }

    if (document.getElementById('want_password').value == "")
      {
      alert('<tag:language.INSERT_PASSWORD />');
      document.getElementById('want_password').focus();
      return false;

      }

    if (document.getElementById('check_password').value == "")
      {
      alert('<tag:language.USER_PWD_AGAIN />');
      document.getElementById('check_password').focus();
      return false;

      }

    if (document.getElementById('want_password').value !=  document.getElementById('check_password').value)
      {
      alert('<tag:language.DIF_PASSWORDS />');
      return false;
      }

    var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

    if (document.getElementById('email').value == "")
      {
      alert('<tag:language.ERR_NO_EMAIL />');
      document.getElementById('email').focus();
      return false;

      }
    else
      {
        if (!filter.test(document.getElementById('email').value))
         {
          alert('<tag:language.ERR_NO_EMAIL />');
          document.getElementById('email').focus();
          return false;
         }
      }


    if (document.getElementById('email_again').value == "")
      {
      alert('<tag:language.ERR_NO_EMAIL_AGAIN />');
      document.getElementById('email_again').focus();
      return false;

      }
    else
      {
        if (!filter.test(document.getElementById('email_again').value))
         {
          alert('<tag:language.ERR_NO_EMAIL />');
          document.getElementById('email_again').focus();
          return false;
         }
      }

    if (document.getElementById('email').value !=  document.getElementById('email_again').value)
      {
      alert('<tag:language.DIF_EMAIL />');
      return false;
      }

   return true;
  }
</script>

<form name="utente" method="post" onsubmit="return form_control()" action="<tag:account_form_actionlink />" >
  <input type="hidden" name="act" value="<tag:account_action />" />
  <input type="hidden" name="uid" value="<tag:account_uid />" />
  <input type="hidden" name="returnto" value="<tag:account_returnto />" />
  <input type="hidden" name="language" value="<tag:account_IDlanguage />" />
  <input type="hidden" name="style" value="<tag:account_IDstyle />" />
  <input type="hidden" name="flag" value="<tag:account_IDcountry />" />
  <input type="hidden" name="username" value="<tag:account_username />"/>
  <table width="60%" border="0" class="lista">
    <tr>
       <td align="left" class="header"><tag:language.USER_NAME />:</td>
       <td align="left" class="lista">
       <if:DEL>
         <input type="text" size="40" name="user" id="user" value="<tag:dati.username />" "readonly" />
       <else:DEL>
         <input type="text" size="40" name="user" id="user" value="<tag:dati.username />" />
       </if:DEL>
       </td>
    </tr>
    <if:DISPLAY_FULL>

    <tr>
       <td align="left" class="header"><tag:language.USER_PWD />:</td>
       
       <td align="left" class="lista"><input type="password" size="40" id="want_password" name="pwd" 
           onkeyup="EvalPwdStrength(document.forms[0],this.value);"/> <!-- The textbox itself onkeyup-->

       
       
           <!-- ### Password strength tables and columns ### -->
           <div id="pwdstrenght" style="display:none;">
           <table border="0" width="268px" align="left">
             <tr>
                <td id="idSM1" class="pwdChkCon0" align="center" width="25%">
                <span id="idSMT1" style="display: none;"><tag:language.WEEK /></span>
                </td>

                <td id="idSM2" class="pwdChkCon0" align="center" width="25%">
                <span id="idSMT0" style="display: none;"><!-- NOT RATED --></span>
                <span id="idSMT2" style="display: none;"><tag:language.MEDIUM /></span>
                </td>

                <td id="idSM3" class="pwdChkCon0" align="center" width="25%">
                <span id="idSMT3" style="display: none;"><tag:language.SAFE /></span>
                </td>

                <td id="idSM4" class="pwdChkCon0" align="center" width="25%">
                <span id="idSMT4" style="display: none;"><tag:language.STRONG /></span>
                </td>
             </tr>
           </table>
           </div>

           <!-- ### End ### -->

    </td>
  </tr>

    <tr>
       <td align="left" class="header"><tag:language.USER_PWD_AGAIN />:</td>
       <td align="left" class="lista"><input type="password" size="40" id="check_password" name="pwd1" /></td>
    </tr>
    <tr>
       <td align="left" class="header"><tag:language.USER_EMAIL />:</td>
       <td align="left" class="lista"><input type="text" size="30" name="email" id="email" value="<tag:dati.email />"/></td>
    </tr>
    <tr>
       <td align="left" class="header"><tag:language.USER_EMAIL_AGAIN />:</td>
       <td align="left" class="lista"><input type="text" size="30" name="email_again" id="email_again" value="<tag:dati.email />"/></td>
    </tr>
    <tr>
       <td align="left" class="header"><tag:language.USER_LANGUE />:</td>
       <td align="left" class="lista"><tag:account_combo_language /></td>
    </tr>
    <tr>
       <td align="left" class="header"><tag:language.USER_STYLE />:</td>
       <td align="left" class="lista"><tag:account_combo_style /></td>
    </tr>
    <tr>
       <td align="left" class="header"><tag:language.COUNTRY />:</td>
       <td align="left" class="lista"><tag:account_combo_country /></td>
    </tr>
    <tr>
       <td align="left" class="header"><tag:language.TIMEZONE />:</td>
       <td align="left" class="lista"><tag:account_combo_timezone /></td>
    </tr>
    <if:CAPTCHA>
    <tr>
       <td align="left" class="header"><tag:language.IMAGE_CODE />:</td>
       <td align="left" class="lista"><input type="text" name="private_key" maxlength="6" size="6" value="" />&nbsp;&nbsp;<tag:account_captcha /></td>
    </tr>
    <else:CAPTCHA>
    <tr>
       <td align="left" class="header"><tag:language.SECURITY_CODE />:</td>
       <td align="left" class="lista"><tag:scode_question />&nbsp;&nbsp;<input type="text" name="scode_answer" maxlength="6" size="6" value="" /></td>
    </tr>
    </if:CAPTCHA>
    <tr>
       <td align="center" class="header" colspan="2">
              <!-- input/button for confirm or delete -->
              <tag:account_from_delete_confirm />
       </td>
    </tr>
  </if:DISPLAY_FULL>
  </table>
</form>