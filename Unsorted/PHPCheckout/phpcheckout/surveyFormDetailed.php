<?php echo"\n\n\n";?>
<!-- START OF surveyFormDetailed.php-->
<FORM NAME="surveyFormDetailed" ACTION="surveyResults.php" METHOD="post">
<input type="hidden" name="update_survey_results" value="true">
<input type="hidden" name="shortname" value="<?php echo $shortname;?>">	
<input type="hidden" name="productname" value="<?php echo $productname;?>">
<input type="hidden" name="availability" value="<?php echo $availability;?>">
<input type="hidden" name="productnumber" value="<?php echo $productnumber;?>">
<input type="hidden" name="via" value="<?php echo $via;?>">

<TABLE class="form">
<tr><th colspan=4>Optional Survey</th></tr>
	<TR>
		<td>Your role is?</td>
		<td>  
			<?php include("role.php");?>
		</td>
		<td>
			Developers on team?
		</td>
		<td>
			<SELECT NAME="numdevelopers" SIZE="1">
			<OPTION VALUE="<?php echo $NumDevelopers;?>" SELECTED>
			<OPTION VALUE="3 or less">3 or less</OPTION>
			<OPTION VALUE="4 to 6">4 to 6</OPTION>
			<OPTION VALUE="7 to 10">7 to 10</OPTION>			
			<OPTION VALUE="more than 10">more than 10</OPTION>
			<OPTION VALUE="No match">No match</OPTION>			
			</SELECT>
		</td>
	</TR>
	
	<TR>
		<td>Primary database?</td> 
		<td><SELECT NAME="dbtype" SIZE=1>
			
			<OPTION VALUE="<?php echo $dbtype;?>" SELECTED>
				<OPTION VALUE="None">None</OPTION>
				<OPTION VALUE="Access">Access</OPTION>
				<OPTION VALUE="DBase">DBase</OPTION>
				<OPTION VALUE="Informix">Informix</OPTION>
				<OPTION VALUE="Oracle">Oracle</OPTION>
				<OPTION VALUE="mySQL">mySQL</OPTION>
				<OPTION VALUE="mSQL">mSQL</OPTION>
				<OPTION VALUE="Paradox">Paradox</OPTION>
				<OPTION VALUE="Postgre">Postgre</OPTION>
				<OPTION VALUE="SQL Server">SQL Server</OPTION>
				<OPTION VALUE="Sybase">Sybase</OPTION>
				<OPTION VALUE="Filemaker">Filemaker</OPTION>
				<OPTION VALUE="Text File">Text File</OPTION>
				<OPTION VALUE="No match">No match</OPTION>
						</SELECT></td>

<td>Primary web server?</td> 
		<td><SELECT NAME="webserver" SIZE=1>
			<OPTION VALUE="<?php echo $webserver;?>" SELECTED>
				<OPTION VALUE="Apache">Apache</OPTION>
				<OPTION VALUE="Microsoft IIS">Microsoft IIS</OPTION>
				<OPTION VALUE="Netscape X">Netscape X</OPTION>
				<OPTION VALUE="Other">Other</OPTION>
				<OPTION VALUE="PWS">Personal Web Server</OPTION>
				<OPTION VALUE="Website">Website</OPTION>
				<OPTION VALUE="No match">No match</OPTION>
			</SELECT></td>
	</TR>

	
	<TR>
<td>Primary operating system?</td> 
		<td><?php include("os.php");?></td>

	
<td>Primary programming language? </td>
			<td><?php include("language.php");?></td>
	</TR>
	

	<TR>
<td>You found us by?</td>
		<td><SELECT NAME="hearabout" SIZE=1>
			<OPTION VALUE="<?php echo $hearabout;?>" SELECTED>
<?php /* Customize this field by deleting or adding a line, or by editing an existing line.
Note that you are changing the values that get added to the database - you are not changing the database field, 
which is the container for the information.  */ ?>			
				<OPTION VALUE="eSignature">email signature</OPTION>				
				<OPTION VALUE="website">website</OPTION>
				<OPTION VALUE="Search Engine">Search Engine</OPTION>		
				<OPTION VALUE="News Group">News Group posting</OPTION>
				<OPTION VALUE="Word of mouth">Word of mouth</OPTION>			
				<OPTION VALUE="Seminar">Seminar</OPTION>
				<OPTION VALUE="Article">Article</OPTION>				
				<OPTION VALUE="Trade Show">Trade Show</OPTION>
				<OPTION VALUE="Web Ad">Web Ad</OPTION>
				<OPTION VALUE="Newspaper Ad">Newspaper Ad</OPTION>				
				<OPTION VALUE="Magazine Ad">Magazine Ad</OPTION>
				<OPTION VALUE="Special Promotion">Special Promotion</OPTION>
				<OPTION VALUE="No Match">No Match</OPTION>				
		</SELECT></td>

		<TD colspan=2 ALIGN="left"><INPUT type="submit" value=" Next Step " class="submit">

		</TD>
	</TR>
</TABLE>

</form>
<!-- END of customer survey -->