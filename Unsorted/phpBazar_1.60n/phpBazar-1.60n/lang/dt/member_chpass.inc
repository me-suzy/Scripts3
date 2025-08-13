<div class="mainheader">&Auml;ndere Passwort</div>

<div class="maintext">

<br>

  	<table align="center">

	<form action=member_chpass.php METHOD=POST>

	<tr>

	<td width="50%"><div class="maininputleft">Passwort : </div></td>

        <td><input type=password name=password value="<? echo $password; ?>"></td>

    	</tr>



  	<tr>

    	<td width="50%"><div class="maininputleft">nochmal Passwort : </div></td>

        <td><input type=password name=password2 value="<? echo $password2; ?>"></td>

    	</tr>



	<tr>

	<td></td>

	<td><br><input type=submit value=Weiter></td>

	</tr>

	</form>

  	</table>

</div>