<html>

<head>

<title>MatchMaker</title>
<link rel="stylesheet" href="style.css" type="text/css" />
</head>

<body>

<!- Main table -->
<table border="0" cellpadding="0" cellspacing="0" width="614">
<tr>
	<td width="612"><img border="0" src="matchm1.gif" width="285" height="95">
        <p>&nbsp;</p>
    </td>
</tr>

<tr>
	<td width="100%" valign="top">
	
		<table border="0" cellspacing="1" width="100%">
        <tr>
			<td></td>
            <td bgcolor="#800000" colspan="2">&nbsp;</td>
		</tr>
		
		<tr>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF" colspan="2">&nbsp;</td>
		</tr>
          
		<tr>
            <td></td>
            <td valign="top"><b>Benefits (for members)</b>:<br>
              <br>
              - Your own mailbox.<br>
              - Matchlists.<br>
              - Statistics.<br>
              - Favorites.
              <p><a href="register.php"><img border="0" src="matchm2.gif" width="188" height="62"></a></p>
            </td>
            <td valign="top">
            
            <b>Guest Search<br>
            <br>
            </b>
            <? $inc = 1;
            include_once "search.php"; 
            ?>
              <p>&nbsp;
            </td>
		</tr>
		<tr>
            <td></td>
            <td valign="top" bgcolor="#800000" colspan="2">&nbsp;</td>
		</tr>
	
		<tr>
            <td></td>
          
            <td valign="top"><b>Existing members<br>
            <br>
            </b>
            <form method=post action=member.php>
            
            
            <!-- Open table 2  -->
            <table>
            <tr>
                  <td>Username:</td>
                  <td><input name="username" size="20"></td>
			</tr>
			
			<tr>
                  <td>Password:</td>
                  <td><input type="passwd" value name="passwd" size="20"></td>
			</tr>
			
			
			<tr>
                  <td align="middle" colSpan="2">
                  
                  <input type="image" border="0" src="matchm3.gif" width="137" height="32" name=submit><br>
                  <a href="lostpassword.php">Lost password ?</a>
                  </td>
			</tr>
			</table>
			<!-- Close table 2 -->
            </form>
            </td>
            
            
            <td valign="top"></td>
		</tr>
          
          
		<tr>
            <td></td>
            <td valign="top" bgcolor="#800000" colspan="2">&nbsp;</td>
		</tr>
		
		<tr>
            <td></td>
            
            
            <td valign="top" colspan="2"><b>Random Specials</b><br>
            <? include "special.php";  ?>
			</td>
            </tr>
			</table>

            </td></tr></table>
            
</body>

</html>
