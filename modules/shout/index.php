<?php
ob_start();

  #################################################################
  #
  #         Ajax MySQL shoutbox for btit
  #         Version 1.0
  #         Author: miskotes
  #         Created: 11/07/2007
  #         Contact: miskotes [at] yahoo.co.uk
  #         Website: http://www.yu-corner.com
  #         Credits: linuxuser.at, plasticshore.com
  #
  #################################################################



  if ($CURUSER["uid"] > 1)
    {
    require_once("$THIS_BASEPATH/include/smilies.php");
  if (!isset($CURUSER)) global $CURUSER;

 global $tpl;

  print "<script src='ajaxchat/scripts.js' language='JavaScript' type='text/javascript'></script>";

function smile() {

  print "<div align='center'><table cellpadding='1' cellspacing='1'><tr>";

  global $smilies, $count;
  reset($smilies);

  while ((list($code, $url) = each($smilies)) && $count<16) {
        print("\n<td><a href=\"javascript: SmileIT('".str_replace("'","\'",$code)."')\">
               <img border=\"0\" src=\"images/smilies/$url\" alt=\"$code\" /></a></td>");
               
        $count++;
  }
  
  print "</tr></table></div>";

}

?>

<center>

 <div id="chat" style="height:400px">
 
  <div id="chatoutput">

      <ul id="outputList">

        <li>
          <span class="name">BTIT SHOUT:</span><h2 style='padding-left:20px;'><?php echo $language["WELCOME"] ?></h2>
          
            <center><div class="loader"></div></center>

          </li>

      </ul>

  </div>
    
</div>


 <div id="shoutheader">
     
    <form id="chatForm" name="chatForm" onsubmit="return false;" action="">
    
      <input type="hidden" name="name" id="name" value="<?php echo $CURUSER["username"] ?>" />
      <input type="hidden" name="uid" id="uid" value="<?php echo $CURUSER["uid"] ?>" />
      <input type="text" size="45" maxlength="500" name="chatbarText" id="chatbarText" onblur="checkStatus('');" onfocus="checkStatus('active');" /> 
      <input onclick="sendComment();" type="submit" id="submit" name="submit" value="<?php echo $language["FRM_CONFIRM"]; ?>" />
      &nbsp;
      <a href="javascript: PopMoreSmiles('chatForm','chatbarText');">
      <img src="images/smile.gif" border="0" class="form" title="<?php echo $language['MORE_SMILES']; ?>" align="top" alt="" /></a>
  
      <a href="javascript: Pophistory()">
      <img src="images/quote.gif" border="0" class="form" title="<?php echo $language['HISTORY']; ?>/Moderate" align="top" alt="" /></a>

<!--      
       &nbsp;&nbsp;
      <a href="javascript: PopMoreSmiles('chatForm','chatbarText')">Admin!</a>
-->
      <br />
      
      <?php smile(); ?>
      
    </form>

 </div>

</center>

<?php
  }
  
else
    print("<div align=\"center\">\n
           <br />".$language["ERR_MUST_BE_LOGGED_SHOUT"]."</div>");

    block_end();

$module_out=ob_get_contents();
ob_end_clean();
?>