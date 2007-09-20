<!-- VIEWNEWS.PHP Template - Just plain HTML and CSS + Template TAGS-->

<table cellpadding="4" cellspacing="1" border="0" width="100%" style="font-family:Verdana;font-size:11px" class="lista">
  <if:news_exists>
  <loop:viewnews>
  <if:can_edit_news>
  <tr>
    <td class="header" align="center"><tag:viewnews[].add_edit_news />
      <if:can_delete_news>
        <tag:viewnews[].delete_news />
      </if:can_delete_news>
    </td>
  </tr>
  </if:can_edit_news>
  <tr>
     <td class="header" align="center">
       <tag:language.POSTED_BY />:&nbsp;<tag:viewnews[].user_posted /><br />
       <tag:language.POSTED_DATE />:&nbsp;<tag:viewnews[].posted_date />
     </td>
   </tr>
   <tr>
     <td class="lista" align="center">
       <b><tag:language.TITLE />:&nbsp;<tag:viewnews[].news_title /></b><br /><br />
         <table style="border-top:1px solid gray; width:100%; font-family: Verdana;font-size:10px">
           <tr><td><tag:viewnews[].news /></td></tr>
         </table>
     </td>
  </tr>
  </loop:viewnews>
  <else:news_exists>
    <tr>
      <td align="center"><tag:language.NO_NEWS /><br />
        <if:can_edit_news_1>
          <br /><tag:insert_news_link /><br />
        </if:can_edit_news_1>
      </td>
    </tr>
  </if:news_exists>
</table>