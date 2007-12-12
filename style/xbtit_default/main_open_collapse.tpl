<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html<tag:main_rtl />>
  <head>
  <title><tag:main_title /></title>
  <meta http-equiv="content-type" content="text/html; charset=<tag:main_charset />" />
  <link rel="stylesheet" href="<tag:main_css />" type="text/css" />
  <tag:more_css />
  <tag:main_jscript />
  </head>
  <body>
    <div id="main">
        <div id="logo">
            <tag:main_logo />
      </div>
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
<div align="right" style="padding-right:10px;background:#89A7C1;">
<span><a name="collapse" href="#collapse" onclick="javascript:ShowHide('header','header1');"><img src="images/arrow.gif" border="0" alt="" /></a></span>
</div>
<div id="header" style="display:none">
            <tag:main_header />
      </div><div id="header1" style="display:none"><br />
</div>
      <div id="left">
            <tag:main_left />
      </div>
      <div id="right">
            <tag:main_right />
      </div>
      <div id="central">
            <tag:main_content />
      </div>
      <div id="footer">
          <tag:main_footer />
      </div>
    </div>
  </body>
</html>