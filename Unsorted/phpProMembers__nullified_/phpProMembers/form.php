<?php
/*********************************************************************/
/* Program Name         : phpProMembers                              */
/* Home Page            : http://www.gacybertech.com                 */
/* Retail Price         : $149.99 United States Dollars              */
/* WebForum Price       : $000.00 Always 100% Free                   */
/* xCGI Price           : $000.00 Always 100% Free                   */
/* Supplied by          : South [WTN]                                */
/* Nullified by         : CyKuH [WTN]                                */
/* Distribution         : via WebForum and Forums File Dumps         */
/*********************************************************************/
$page_account = "form";
require "include.php";
if ($include_template == "1") {
	include "$template_directory/header.php";	
}
?>	
<br><br>
<center><a href="member_form.php"><FONT SIZE="2" FACE="verdana, arial, helvetica" COLOR="RED"><b>Click Here If You Are Already A Member</b></FONT></a></center>
<br><br>
<FORM ACTION="correct_form.php" METHOD="POST">
	<CENTER>
    	

<?php
// Do I need to print the agreement?
if ($include_agreement == "1") {
	echo "<TABLE BORDER=\"0\" CELLPADDING=\"5\">
			<tr>
			<td colspan=\"2\">";
			
			
	include "$agreement_page";			
	
	echo "	</td>
			</tr>
			<TR>		 
            <TD>
				<INPUT TYPE=\"CHECKBOX\" NAME=\"agreement\" VALUE=\"agree\">
			</TD>
            <TD>
				<FONT SIZE=\"-1\" FACE=\"verdana, arial, helvetica\">
					<B>I agree have read and agree to the Terms and Conditions above.</B>
				</FONT>
			</TD>
          </TR>
		   </TABLE>";
}
// End agreement.
?>
         
      
<BR>

      <TABLE>
      <TR>
        <TD>
		<FONT SIZE="-1" FACE="verdana, arial, helvetica">
			<b>Choose an account:</b><br>
		</FONT>
		<br>
<?php
if ($use_radio == "1") {
$get_items = "SELECT * FROM account_types WHERE active = \"1\"";
$result = mysql_query($get_items);
while($row = mysql_fetch_object($result)) {
	echo "<input type=\"radio\" name=\"accounts\" value=\"$row->id\">$row->account_name";
	echo "<BR><br>";
}
}else{
?>
		
		<SELECT NAME="accounts">

<?php
// Entering the account_types dynamically.
$account_type_sql = "SELECT * from account_types WHERE active = \"1\"";
$result = mysql_query($account_type_sql);
while($row = mysql_fetch_object($result)) {
	echo "<OPTION VALUE=\"$row->id\">$row->account_name</OPTION>";
}
// End entering account_type dynamically.
?>

        </SELECT> 
<?php
}		
?>		
		 </TD>
      </TR>
      <TR>
        <TD><HR SIZE="2" NOSHADE="NOSHADE"></TD>
      </TR>
      <TR>
        <TD ALIGN="CENTER">
        <TABLE BORDER="0" WIDTH="350" NOWRAP="NOWRAP">
          <TR>
            <TD><FONT SIZE="-1" FACE="verdana, arial, helvetica"><B>Customer
            Information</B></FONT><BR>
            <FONT SIZE="-2" FACE="verdana, arial, helvetica" COLOR="#FF0000"><B>Fields
            marked with an astriks<BR>
             are required input fields.</B></FONT><BR>
             <BR>
            </TD>
          </TR>
          <TR>
            <TD><INPUT TYPE="TEXT" NAME="fname"> <FONT SIZE="-1" FACE="verdana, arial, helvetica">First
            Name *</FONT></TD>
          </TR>
          <TR>
            <TD><INPUT TYPE="TEXT" NAME="lname"> <FONT SIZE="-1" FACE="verdana, arial, helvetica">Last
            Name *</FONT></TD>
          </TR>
          <TR>
            <TD><INPUT TYPE="TEXT" NAME="address"> <FONT SIZE="-1" FACE="verdana, arial, helvetica">Address
            *</FONT></TD>
          </TR>
          <TR>
            <TD><INPUT TYPE="TEXT" NAME="address2"> <FONT SIZE="-1" FACE="verdana, arial, helvetica">Address
            2</FONT></TD>
          </TR>
          <TR>
            <TD><INPUT TYPE="TEXT" NAME="city"> <FONT SIZE="-1" FACE="verdana, arial, helvetica">City
            *</FONT></TD>
          </TR>
          <TR>
            <TD><INPUT TYPE="TEXT" NAME="state"> <FONT SIZE="-1" FACE="verdana, arial, helvetica">State
            or Province *</FONT></TD>
          </TR>
          <TR>
            <TD><INPUT TYPE="TEXT" NAME="zip"> <FONT SIZE="-1" FACE="verdana, arial, helvetica">Postal
            Code (ZIP) *</FONT></TD>
          </TR>
          <TR>
            <TD><INPUT TYPE="TEXT" NAME="country"> <FONT SIZE="-1" FACE="verdana, arial, helvetica">Country
            *</FONT></TD>
          </TR>
          <TR>
            <TD><INPUT TYPE="TEXT" NAME="phone"> <FONT SIZE="-1" FACE="verdana, arial, helvetica">Telephone
            Number *</FONT></TD>
          </TR>
          <TR>
            <TD><INPUT TYPE="TEXT" NAME="email"> <FONT SIZE="-1" FACE="verdana, arial, helvetica">E-Mail
            Address *</FONT></TD>
          </TR>
        </TABLE>
        
		<TABLE BORDER="0" WIDTH="350" NOWRAP="NOWRAP">
          <TR>
            <TD><BR>
            <FONT SIZE="-1" FACE="verdana, arial, helvetica"><B>Choose a User
            Name</B></FONT><BR>
            <INPUT TYPE="TEXT" NAME="username"> <FONT SIZE="-1" FACE="verdana, arial, helvetica">User
            Name</FONT> <FONT SIZE="-1" FACE="arial, helvetica"><BR>
             <BR>
            <B>Choose a Password</B></FONT><BR>
            <INPUT TYPE="password" NAME="pwd"> <FONT SIZE="-1" FACE="verdana, arial, helvetica">Password</FONT><BR>
            <INPUT TYPE="password" NAME="pwd2"> <FONT SIZE="-1" FACE="verdana, arial, helvetica">Verify
            Password</FONT></TD>
          </TR>
        </TABLE>
		
		</TD>
		</TR>
		<TR>
		<TD>
		<br><br>
<?php
// Check and see if we need to get billing information.		
if ($include_billing == "1") {
?>
		<TABLE>
			<TR>
				<TD>
					<FONT SIZE="-1" FACE="verdana, arial, helvetica">
						<b>Billing Information</b>
					</FONT>
				</TD>
			<TR>
			<TR>
            	<TD>
					<INPUT TYPE="TEXT" NAME="billingaddress"> 
					<FONT SIZE="-1" FACE="arial, helvetica">
						Billing Address *
					</FONT>
				</TD>
          </TR>
          <TR>
            <TD>
				<INPUT TYPE="TEXT" NAME="billingaddress2"> 
				<FONT SIZE="-1" FACE="arial, helvetica">
					Billing Address 2
				</FONT>
			</TD>
          </TR>
          <TR>
            <TD>
				<INPUT TYPE="TEXT" NAME="ccity" SIZE="10">
				<FONT SIZE="-1" FACE="arial, helvetica">
					City *
				</FONT> 
				<INPUT TYPE="TEXT" NAME="cstate" SIZE="2">
            	<FONT SIZE="-1" FACE="arial, helvetica">
					State *
				</FONT>
            	<INPUT TYPE="TEXT" NAME="czip" SIZE="7"> 
				<FONT SIZE="-1" FACE="arial, helvetica">
					Zip *
				</FONT> 
				<br>
				<INPUT TYPE="TEXT" NAME="ccountry" SIZE="15">
            	<FONT SIZE="-1" FACE="arial, helvetica">
					Country *
				</FONT> 
			</TD>
          </TR>
		</TABLE>
<?php
// Ends billing agreement.
}
?>

        <TABLE BORDER="0" WIDTH="350" NOWRAP="NOWRAP">
          <TR>
            <TD COLSPAN="2"><BR>
             <BR>
            <FONT SIZE="-1" FACE="verdana, arial, helvetica"><B>Method of
            Payment</B><BR>
            </FONT><FONT SIZE="-2" FACE="verdana, arial, helvetica"><B><FONT COLOR="#FF0000">Please
            be sure to select your payment method</FONT></B></FONT><BR>
             <BR>
            </TD>
          </TR>
		  
<?php
// Is there a credit card option for payment active?
$get_payment_info_sql = "SELECT * FROM method_of_payment WHERE payment_type = \"credit card\" and active = \"1\"";		
$result = mysql_query($get_payment_info_sql);
if ($row = mysql_fetch_object($result)) {  
?>
		  <TR>
            <TD><INPUT TYPE="RADIO" NAME="payment" VALUE="creditcard"></TD>
            <TD><SELECT NAME="creditcards">
			<OPTION VALUE="- Choose One - " SELECTED="SELECTED">- Choose One -</OPTION>
			
<?php		
// Build credit cards selection dynamically.
$account_type_sql = "SELECT * from method_of_payment WHERE active = \"1\" and payment_type = \"credit card\"";
$result = mysql_query($account_type_sql);
while($row = mysql_fetch_object($result)) {
	echo "<OPTION VALUE=\"$row->sub_type\">$row->sub_type</OPTION>";
}
?>			
			
			</SELECT>
            <FONT SIZE="-1" FACE="arial, helvetica">Pay by credit card *</FONT></TD>
          </TR>
          <TR>
            <TD></TD>
            <TD><INPUT TYPE="TEXT" NAME="nameoncard"> <FONT SIZE="-1" FACE="arial, helvetica">Name
            on card *</FONT></TD>
          </TR>
          <TR>
            <TD></TD>
            <TD><INPUT TYPE="TEXT" NAME="cardnumber"><FONT SIZE="-1" FACE="arial, helvetica">
            Card Number *</FONT></TD>
          </TR>
          <TR>
            <TD></TD>
            <TD><INPUT TYPE="TEXT" NAME="exp" SIZE="4"> <FONT SIZE="-1" FACE="arial, helvetica">Expiration
            Date *</FONT></TD>
          </TR>
<?php
// Ending credit card info section.
}
?>
		  
		  
<?php
// Do we except checks? If so, print the address.
$get_payment_info_sql = "SELECT * FROM method_of_payment WHERE payment_type = \"check\" and active = \"1\"";		
$result = mysql_query($get_payment_info_sql);
if ($row = mysql_fetch_object($result)) {  
?>		  
          <TR>
            <TD><INPUT TYPE="RADIO" NAME="payment" VALUE="check"> </TD>
            <TD><FONT SIZE="-1" FACE="arial, helvetica">Pay by check *</FONT></TD>
          </TR>
          <TR>
            <TD></TD>
            <TD><FONT SIZE="-1"><B><FONT COLOR="#FF0000" FACE="arial, helvetica"><FONT COLOR="#000000">
			Send Payments To:
			<br>
			<br>
			
<?php			
	echo $row->payment_address;		
?>			
			</FONT></B></FONT></TD>
          </TR>

<?php
}
?>

	        <TR>
            <TD ALIGN="CENTER" COLSPAN="2"><BR>


<?php
// Is there a paypal option for payment active?
$get_payment_info_sql = "SELECT * FROM method_of_payment WHERE payment_type = \"paypal\" and active = \"1\"";		
$result = mysql_query($get_payment_info_sql);
if ($row = mysql_fetch_object($result)) {  
?>				
            <TABLE BORDER="0" WIDTH="350">
              <TR>
                <TD><INPUT TYPE="RADIO" NAME="payment" VALUE="paypal">
                <FONT SIZE="-1" FACE="arial, helvetica">PayPal *</FONT></TD>
                <TD></TD>
				
              </TR>
            </TABLE>
<?php
// End paypal section.
}
?>	

<?php
$get_payment_info_sql = "SELECT * FROM method_of_payment WHERE payment_type = \"clickbank\" and active = \"1\"";		
$result = mysql_query($get_payment_info_sql);
if ($row = mysql_fetch_object($result)) {  
?>				
            <TABLE BORDER="0" WIDTH="350">
              <TR>
                <TD><INPUT TYPE="RADIO" NAME="payment" VALUE="clickbank">
                <FONT SIZE="-1" FACE="arial, helvetica">ClickBank *</FONT></TD>
                <TD></TD>
				
              </TR>
            </TABLE>
<?php
}
?>	

<?php
$get_payment_info_sql = "SELECT * FROM method_of_payment WHERE payment_type = \"regnow\" and active = \"1\"";		
$result = mysql_query($get_payment_info_sql);
if ($row = mysql_fetch_object($result)) {  
?>				
            <TABLE BORDER="0" WIDTH="350">
              <TR>
                <TD><INPUT TYPE="RADIO" NAME="payment" VALUE="regnow">
                <FONT SIZE="-1" FACE="arial, helvetica">RegNow *</FONT></TD>
                <TD></TD>
				
              </TR>
            </TABLE>
<?php
}
?>	
			<BR>
            <INPUT TYPE="SUBMIT" NAME="process" VALUE="Submit My Order"><INPUT TYPE="RESET" NAME=""></TD>
          </TR>
	    </TABLE></TD>
      </TR>
      <TR>
        <TD><HR SIZE="2" NOSHADE="NOSHADE"></TD>
      </TR>
    </TABLE></CENTER></FORM>

<?php
// Adding footer if we got it.
if ($include_template == "1") {
	include "$template_directory/footer.php";	
}
?>	