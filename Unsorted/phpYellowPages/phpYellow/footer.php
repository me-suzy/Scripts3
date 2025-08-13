<!-- START OF footer.php -->
<br>
<table width="100%">
   <tr>
      <td align="left">
         <a href="http://www.dreamriver.com">
				<img src="appimage/pypbook.gif" width="150" height="22" border=0 alt="Easily create your own Yellow Pages">
			</a><br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo INSTALLPATH;?>docs/DOCSindex.html">
	      <b style="font-size:xx-small; color:silver;">Version <?php echo INSTALLVERSION;?></b></a>
      </td>

      <td>
         <span style="font-size:small;">
				<a href="new.php">New!</a> | 
				<a href="index.php">Home</a> | 
				<a href="login.php">Add-Edit-Delete</a> |  
				<a href="password.php">Password</a> | 
				<a href="goPremium.php"> Go Premium</a> 
				<?php if(defined("SETRANK")){if(file_exists("premiumTellAFriend.php")){print" | <a href=\"premiumTellAFriend.php\">Tell A Friend!</a>";}}?>
			</span>
      </td>
   </tr>
</table>

    
</BODY>
</HTML>



