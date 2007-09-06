var smooth_timer;
function go2(selID, link) {
  var selOBJ = (document.getElementById) ? document.getElementById(selID) : eval("document.all['" + selID + "']");
  window.location=link + selOBJ.options[selOBJ.selectedIndex].value;
}
function toclip(string) { window.clipboardData.setData('Text', string); }
function ShowTip(fArg) {
  var tooltipOBJ = (document.getElementById) ? document.getElementById('ih' + fArg) : eval("document.all['ih" + fArg + "']");
  if (tooltipOBJ != null) {
    var tooltipLft = (document.body.offsetWidth?document.body.offsetWidth:document.body.style.pixelWidth) - (tooltipOBJ.offsetWidth?tooltipOBJ.offsetWidth:(tooltipOBJ.style.pixelWidth?tooltipOBJ.style.pixelWidth:$TOOLTIPWIDTH)) - 5;
    var tooltipTop = 10;
    if (navigator.appName == 'Netscape') {
      if (parseFloat(navigator.appVersion) >= 5) tooltipTop = (document.body.scrollTop>=0?document.body.scrollTop+10:event.clientY+10);
      tooltipOBJ.style.left = tooltipLft; tooltipOBJ.style.top = tooltipTop;
    }
    else {
      tooltipLft -= 30;
      tooltipTop = (document.body.scrollTop?document.body.scrollTop:document.body.offsetTop) + event.clientY - (tooltipOBJ.scrollHeight?tooltipOBJ.scrollHeight:tooltipOBJ.style.pixelHeight) - 30;
      if (tooltipTop < (document.body.scrollTop?document.body.scrollTop:document.body.offsetTop) + 10) {
        if (event.clientX > tooltipLft) tooltipTop = (document.body.scrollTop?document.body.scrollTop:document.body.offsetTop) + event.clientY + 30;
        else tooltipTop = (document.body.scrollTop?document.body.scrollTop:document.body.offsetTop) + 10;
      } 
      tooltipOBJ.style.pixelLeft = tooltipLft; tooltipOBJ.style.pixelTop = tooltipTop;
    }
    tooltipOBJ.style.visibility = "visible";
  }
}
function HideTip(fArg) {
  var tooltipOBJ = (document.getElementById) ? document.getElementById('ih' + fArg) : eval("document.all['ih" + fArg + "']");
  if (tooltipOBJ != null) tooltipOBJ.style.visibility = "hidden";
}
function smoothHeight(id, curH, targetH, stepH, mode) {
  diff = targetH - curH;
  if (diff != 0) {
    newH = (diff > 0) ? curH + stepH : curH - stepH;
    ((document.getElementById) ? document.getElementById(id) : eval("document.all['" + id + "']")).style.height = newH + "px";
    if (smooth_timer) window.clearTimeout(smooth_timer);
    smooth_timer = window.setTimeout( "smoothHeight('" + id + "'," + newH + "," + targetH + "," + stepH + ",'" + mode + "')", 16 );
  }
  else if (mode != "o") ((document.getElementById) ? document.getElementById(mode) : eval("document.all['" + mode + "']")).style.display="none";
}
function servOC(i, href, nColor) {
  var trObj = (document.getElementById) ? document.getElementById('ihtr' + i) : eval("document.all['ihtr" + i + "']");
  var nameObj = (document.getElementById) ? document.getElementById('name' + i) : eval("document.all['name" + i + "']");
  var ifObj = (document.getElementById) ? document.getElementById('ihif' + i) : eval("document.all['ihif" + i + "']");
  if (trObj != null) {
    if (trObj.style.display=="none") {
      ifObj.style.height = "0px";
      trObj.style.display="";
      nameObj.style.background="none";
      if (!ifObj.src) ifObj.src = href;
      smoothHeight('ihif' + i, 0, 210, 42, 'o');
    }
    else {
      nameObj.style.background=nColor;
      smoothHeight('ihif' + i, 210, 0, 42, 'ihtr' + i);
    }
  }
}