// common js function
function test_smtp_password()
  {
  var empty;
  empty=document.getElementById('mail_type').value=='php';

  if (document.getElementById('smtp_server').value=="" && !empty)
    {
     alert("SMTP server empty!");
     return false;
    }

  if (document.getElementById('smtp_username').value=="" && !empty)
    {
     alert("SMTP username empty!");
     return false;
    }

  if (document.getElementById('smtp_password').value=="" && !empty)
    {
     alert("SMTP password empty!");
     return false;
    }

  if (document.getElementById('smtp_pwd_repeat').value=="" && !empty)
    {
     alert("SMTP controll password empty!");
     return false;
    }

  if (document.getElementById('smtp_password').value!=document.getElementById('smtp__pwd_repeat').value && !empty)
    {
     alert("SMTP password and controll password are different!");
     return false;
    }


  return true;

}