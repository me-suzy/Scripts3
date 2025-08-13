<div class="mainheader">Change Password</div>

<div class="maintext">

<br>

  	<table align="center">

	<form action=member_chpass.php METHOD=POST>

	<tr>

	<td width="50%"><div class="maininputleft">New Password : </div></td>

        <td><input type=password name=password value="<? echo $password; ?>"></td>

    	</tr>



  	<tr>

    	<td width="50%"><div class="maininputleft">Repeat New Password : </div></td>

        <td><input type=password name=password2 value="<? echo $password2; ?>"></td>

    	</tr>



	<tr>

	<td></td>

	<td><br><input type=submit value=Update></td>

	</tr>

	</form>

  	</table>

</div>