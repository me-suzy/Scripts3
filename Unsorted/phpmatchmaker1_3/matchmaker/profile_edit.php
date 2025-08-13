<?
// include function files for this application
require_once("php_inc.php"); 
session_start();
db_connect();

  if (session_is_registered("valid_user"))
  {
			print "<form method=post action=profile_edit.php>";
								
			do_html_heading("Your Profile");
			member_menu();
			
			if ($submit)
			{
			 	 	$sql_string = "update users set "  .
												"option1 = '$submit1', " .
												"option2 = '$submit2', " .
												"option3 = '$submit3' " .
												" where username = $valid_user";
					$result = mysql_query($sql_string);
					print "$sql_string";
			}
			
			?><p>
			<table border="0" cellpadding="2" cellspacing="2">
			<?
			$result = mysql_query("select * from user where username = '$valid_user'");
			$row = mysql_fetch_array($result);
 			$option1 = $row[option1];
			$option2 = $row[option2];
			$option3 = $row[option3];
			$option4 = $row[option4];
			$option5 = $row[option5];
			$option6 = $row[option6];
			$option7 = $row[option7];
			
			$result = mysql_query("select * from properties where controlorder <> 0 order by controlorder");

			while ($row = mysql_fetch_array($result))
			{
 			$fieldid = $row[fieldid];
			$control = $row[control];
			$optionfile = $row[optionfile];
			$caption = $row[caption];
			$controlorder = $row[controlorder];
			
			
			print "<tr><td><b>$caption</b><br></td>";
			
			if ($control == 'radio')
			{
			 	 $options = file("optionfiles/$optionfile");
				 $num_options =  count($options);
				 
				 print "<td align=right>";
				 for ($i=0; $i<$num_options; $i++)
				 {			 	 
				 					 $fi = option . $fieldid;
									 print "-$fi-";
									 
									 if ($fi == $fi)
									 {
									 	print $options[$i];
										print "<input type='radio' value='$options[$i]' name='submit$fieldid'";
									 	
										print "$option1 - $options[$i]";
										
									  if ($option1 == $options[$i])
									  {
									 		print " checked ";
									  }
									 
									 print ">&nbsp;&nbsp;&nbsp;";		
									 }
									 
									 

				 }
				 print "</td>";

			
			}
			
			if ($control == 'dropdown')
			{
		 				
				 $options = file("optionfiles/$optionfile");
				 $num_options =  count($options);
				 
				 print "<td align=right>";
				 print "<select size='1' name='submit$fieldid'>";
				 print "<option value='$option$fieldid' selected>";				 
				 for ($i=0; $i<$num_options; $i++)
				 {			 	 

									 print "<option value='$options[$i]'>$options[$i]</option>";
									 
									 
				 }
				 print "</select>&nbsp;&nbsp;&nbsp;</td>";
				 
			}
			
			if ($control == 'text')
			{
						 
				 print "<td align=right>";
				 print "<input type='text' name='submit$fieldid' value='$option$fieldid'>&nbsp;&nbsp;&nbsp;";
				 print "</td>";

			
			}
			
			if ($control == 'textarea')
			{
						 
				 print "<td align=right>";
				 print "<textarea rows='4' name='submit$fieldid' cols='30'>$option$fieldid</textarea>&nbsp;&nbsp;&nbsp;";
				 print "</td>";

			
			}
			
			
			
			print "</tr>";
			
}

?>
</table>
<?

			print "<input type='submit' value='Save' name='submit'></form>";
	
	}
	else
	{
	 		print "Not registered";
			exit;
	}

?>


