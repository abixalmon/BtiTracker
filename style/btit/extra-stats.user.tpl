
  <table width="100%" class="lista">
    <tr>
      <td class="header" align="center"><tag:language.RANK /></td>
      <td class="header" align="center"><tag:language.USER_NAME /></td>
      <td class="header" align="center"><tag:language.UPLOADED /></td>
      <td class="header" align="center"><tag:language.DOWNLOADED /></td>
      <td class="header" align="center"><tag:language.RATIO /></td>
    </tr>
    <loop:user>
    <tr>
      <td class="lista" align="center" width="15%"><tag:user[].rank /></td>
      <td class="lista" align="center"><tag:user[].username /></td>
      <td class="lista" align="center" width="15%"><tag:user[].uploaded /></td>
      <td class="lista" align="center" width="15%"><tag:user[].downloaded /></td>
      <td class="lista" align="center" width="15%"><tag:user[].ratio /></td>
    </tr>
    </loop:user>
  </table>


