<div class="mainheader">ERROR</div>

<div class="maintext">

<br>

<em id="red">Error : <? echo $confirm; ?></em>

<br>

     <table align="center">

     <form action=confirm.php METHOD=POST>

     <tr>

     <td width="50%"><div class="maininputleft">Hash : </div></td>

     <td><input type=text name=hash value="<? echo $hash; ?>"></td>

     </tr>



     <tr>

     <td width="50%"><div class="maininputleft">Username : </div></td>

     <td><input type=test name=nick value="<? echo $nick; ?>"></td>

     <td><input type=submit value=Confirm></td>

     </tr>

     </form>

     </table>



<center><form action=javascript:window.close() METHOD=POST>

<input type=submit value=Done></form></center>

</div>