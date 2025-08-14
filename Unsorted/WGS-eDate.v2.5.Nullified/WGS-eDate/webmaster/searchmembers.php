<?
  require("admin.php");
  require("_header.php");
?>
<b>SEARCH MEMBERS</b><br><br>
            <table  border="0" cellspacing="1" cellpadding="1">
              <form name=form1 action=sqledit.php?table=members method=post>
                <tr>
                  <td> LOGIN </td>
                  <td>
                    <input class=small type="text" name="accountkey" size=40 value=""> (ie. : ettica)
                  </td>
                </tr>
                <tr>
                  <td> EMAIL </td>
                  <td>
                    <input class=small type="text" name="emailkey" size=40 value=""> (ie. : yourdomain.com, contact@yourdomain.com)
                  </td>
                </tr>
                <tr>
                  <td> LAST NAME </td>
                  <td>
                    <input class=small type="text" name="lnamekey" size=40 value="">
                  </td>
                </tr>
	<tr>
                  <td> COUNTRY </td>
                  <td>
                    <input class=small type="text" name="countrykey" size=40 value="">
                  </td>
                </tr>
	<tr>
                  <td colspan=2>
	<input type=hidden name=conditions><input class=small type="button" name="Submit"  onclick="conditions.value='where country like \'%'+countrykey.value+'%\' and lname like \'%'+lnamekey.value+'%\' and login like \'%'+accountkey.value+'%\' and email like \'%'+emailkey.value+'%\'';form1.submit();"  value="Display results">
                  </td>
	</tr>
              </form>
            </table>
<br>Leave all fields blank to display all members in the database.<br>
<br>
<?php   require("_footer.php"); ?>