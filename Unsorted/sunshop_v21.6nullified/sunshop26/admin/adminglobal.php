<?PHP
///////////////////////////////////////////////////////
// Program Name         : SunShop
// Program Author       : Turnkey Solutions 2001-2002.
// Program Version      : 2.6
// Supplied by          : Stive [WTN], CyKuH [WTN]                            
// Nullified by         : CyKuH [WTN]                                   
///////////////////////////////////////////////////////
require("config.php");
include("state-country.php");

// ###################### start database #######################
$dbclassname="db_mysql.php";
require($dbclassname);

$DB_site=new DB_Sql_vb;
$DB_site->appname="SunShop";
$DB_site->appshortname="SunShop";
$DB_site->database=$dbname;
$DB_site->server=$servername;
$DB_site->user=$dbusername;
$DB_site->password=$dbpassword;
$DB_site->connect();

if ($action != "updatesettings") {
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."options");
	while ($row=$DB_site->fetch_array($temps)) {
		$productpath = stripslashes($row[productpath]);
		$thumbwidth = stripslashes($row[thumbwidth]);
		$thumbheight = stripslashes($row[thumbheight]);
		$picwidth = stripslashes($row[picwidth]);
		$picheight = stripslashes($row[picheight]);
		if ($action == "package") { 
			$companyname = stripslashes($row[companyname]);
			$title = stripslashes($row[title]);
			$shopaddress = stripslashes($row[address]);
			$shopurl = stripslashes($row[shopurl]);
			$shopcity = stripslashes($row[city]);
			$shopstate = stripslashes($row[state]);
			$shopzip = stripslashes($row[zip]);
			$shopcountry = stripslashes($row[country]);
			$shopphone = stripslashes($row[phone]);
			$shopfax = stripslashes($row[faxnumber]);
			$contactemail = stripslashes($row[contactemail]);
		}
		$cs = stripslashes($row[cs]);
		$license = stripslashes($row[license]);
	}
}

// ##################### dovars ################################
function dovars($vartext) {
  $newtext=$vartext;
  return $newtext;
}

// ###################### showpref #############################
function showpref () {
    global $DB_site, $dbprefix, $state_list, $countries_list;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."options");
	while ($row=$DB_site->fetch_array($temps)) {
	    $shoptitle = stripslashes($row[title]);
		$hometitle = stripslashes($row[hometitle]);
		$shopurl = stripslashes($row[shopurl]);
		$homeurl = stripslashes($row[homeurl]);
		$securepath = stripslashes($row[securepath]);
		$companyname = stripslashes($row[companyname]);
		$shopaddress = stripslashes($row[address]);
		$shopcity = stripslashes($row[city]);
		$shopstate = stripslashes($row[state]);
		$shopzip = stripslashes($row[zip]);
		$shopcountry = stripslashes($row[country]);
		$shopphone = stripslashes($row[phone]);
		$shopfax = stripslashes($row[faxnumber]);
		$contactemail = stripslashes($row[contactemail]);
		$taxrate = stripslashes($row[taxrate]);
		$shipups = stripslashes($row[shipups]);
		$grnd = stripslashes($row[grnd]);
		$nextdayair = stripslashes($row[nextdayair]);
		$seconddayair = stripslashes($row[seconddayair]);
		$threeday = stripslashes($row[threeday]);
		$canada = stripslashes($row[canada]);
		$worldwidex = stripslashes($row[worldwidex]);
		$worldwidexplus = stripslashes($row[worldwidexplus]);
		$fixedshipping = stripslashes($row[fixedshipping]);
		$method = stripslashes($row[method]);
		$shoprate = stripslashes($row[rate]);
		$productpath = stripslashes($row[productpath]);
		$catimage = stripslashes($row[catimage]);
		$catopen = stripslashes($row[catopen]);
		$viewcartimage = stripslashes($row[viewcartimage]);
		$viewaccountimage = stripslashes($row[viewaccountimage]);
		$checkoutimage = stripslashes($row[checkoutimage]);
		$helpimage = stripslashes($row[helpimage]);
		$cartimage = stripslashes($row[cartimage]);
		$tablehead = stripslashes($row[tablehead]);
		$tableheadtext = stripslashes($row[tableheadtext]);
		$tableborder = stripslashes($row[tableborder]);
		$tablebg = stripslashes($row[tablebg]);
		$header1 = $row[header1];
	    $footer = $row[footer];
		$shipchart = stripslashes($row[shipchart]);
		$ship1p1 = stripslashes($row[ship1p1]);
		$ship1us = stripslashes($row[ship1us]);
		$ship1ca = stripslashes($row[ship1ca]);
		$ship2 = stripslashes($row[ship2]);
		$ship2p1 = stripslashes($row[ship2p1]);
		$ship2p2 = stripslashes($row[ship2p2]);
		$ship2us = stripslashes($row[ship2us]);
		$ship2ca = stripslashes($row[ship2ca]);
		$ship3 = stripslashes($row[ship2]);
		$ship3p1 = stripslashes($row[ship3p1]);
		$ship3p2 = stripslashes($row[ship3p2]);
		$ship3us = stripslashes($row[ship3us]);
		$ship3ca = stripslashes($row[ship3ca]);
		$ship4p1 = stripslashes($row[ship4p1]);
		$ship4us = stripslashes($row[ship4us]);
		$ship4ca = stripslashes($row[ship4ca]);
		$visa = stripslashes($row[visa]);
		$mastercard = stripslashes($row[mastercard]);
		$discover = stripslashes($row[discover]);
		$amex = stripslashes($row[amex]);
		$check = stripslashes($row[check]);
		$fax = stripslashes($row[fax]);
		$moneyorder = stripslashes($row[moneyorder]);
		$cc = stripslashes($row[cc]);
		$payable = stripslashes($row[payable]);
		$paypal = stripslashes($row[paypal]);
    	$paypalemail = stripslashes($row[paypalemail]);
	    $shopimage = stripslashes($row[shopimage]);
		$centercolor = stripslashes($row[centercolor]);
		$centerborder = stripslashes($row[centerborder]);
		$centerheader = stripslashes($row[centerheader]);
		$centerfont = stripslashes($row[centerfont]);
		$centerbg = stripslashes($row[centerbg]);
		$myheader = stripslashes($row[myheader]);
		$myfooter = stripslashes($row[myfooter]);
		$useheader = stripslashes($row[useheader]);
		$usefooter = stripslashes($row[usefooter]);
		$css = stripslashes($row[css]);
		$thumbwidth = stripslashes($row[thumbwidth]);
		$thumbheight = stripslashes($row[thumbheight]);
		$picwidth = stripslashes($row[picwidth]);
		$picheight = stripslashes($row[picheight]);
		$showstock = stripslashes($row[showstock]);
		$showitem = stripslashes($row[showitem]);
		$showintro = stripslashes($row[showintro]);
		$shopintro = stripslashes($row[shopintro]);
		$orderby = stripslashes($row[orderby]);
		$outofstock = stripslashes($row[outofstock]);
		$cs = stripslashes($row[cs]);
	    $showprice = stripslashes($row[showprice]);
		$po = stripslashes($row[po]);
		$license = stripslashes($row[license]);
	    $handling = stripslashes($row[handling]);
		$imagel = stripslashes($row[imagel]);
	}
	?>
	    <form action="admin.php" method="post">
		<input type="hidden" name="action" value="updatesettings">
		<div align="center">
		<table border="0" cellspacing="2" cellpadding="2">
			<tr>
				<td><a href="#gs"><li>General Settings</li></a></td>
				<td width="40">&nbsp;</td>
				<td><a href="#ts"><li>Tax Settings</li></td>
			</tr>
			<tr>
				<td><a href="#ci"><li>Company Information</li></td>
				<td width="40">&nbsp;</td>
				<td><a href="#is"><li>Item Stock Settings</li></td>
			</tr>
			<tr>
				<td><a href="#ss"><li>Shipping Settings</li></td>
				<td width="40">&nbsp;</td>
				<td><a href="#sd"><li>Shop Design</li></td>
			</tr>
			<tr>
				<td><a href="#po"><li>Payment Options</li></td>
				<td width="40">&nbsp;</td>
				<td></td>
			</tr>
		</table></div><br>

		<div align="center">All information is needed for smooth usage.</div><br>
		
		<table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="Navy">
			<tr>
				<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="4">
				<tr><td colspan="2" bgcolor="Navy">
				<div align="center"><b><font color="White"><a name="gs"></a>General Settings</font></b></div></td></tr>
				<input type="hidden" size="35" name="license" value="1234567890">
				<tr>
				<td width="110" bgcolor="#E9E9E9"><b>Shop Title:</b></td>
				<td bgcolor="#E9E9E9"><input type="text" size="35" name="title" value="<?PHP echo $shoptitle ?>"></td>
				</tr>
				<tr><td bgcolor="#E9E9E9">&nbsp;</td>
				<td bgcolor="#E9E9E9">Title of your shopping cart.<br></td></tr>
				
				<tr>
				<td width="110" bgcolor="#C0C0C0"><b>Home Title:</b></td>
				<td bgcolor="#C0C0C0"><input type="text" size="35" name="hometitle" value="<?PHP echo $hometitle ?>"></td>
				</tr>
				<tr><td bgcolor="#C0C0C0">&nbsp;</td>
				<td bgcolor="#C0C0C0">Name of your main site.<br></td></tr>
				
				<tr>
				<td width="110" bgcolor="#E9E9E9"><b>Shop URL:</b></td>
				<td bgcolor="#E9E9E9"><input type="text" size="35" name="shopurl" value="<?PHP echo $shopurl ?>"></td>
				</tr>
				<tr><td bgcolor="#E9E9E9">&nbsp;</td>
				<td bgcolor="#E9E9E9">URL&nbsp;of the shopping cart. (Add final "/")<br></td></tr>
				
				<tr>
				<td width="110" bgcolor="#C0C0C0"><b>Home URL:</b></td>
				<td bgcolor="#C0C0C0"><input type="text" size="35" name="homeurl" value="<?PHP echo $homeurl ?>"></td>
				</tr>
				<tr><td bgcolor="#C0C0C0">&nbsp;</td>
				<td bgcolor="#C0C0C0">URL of your home main site. (Add final "/")<br></td></tr>
				
				<tr>
				<td width="110" bgcolor="#E9E9E9"><b>Secure URL:</b></td>
				<td bgcolor="#E9E9E9"><input type="text" size="35" name="securepath" value="<?PHP echo $securepath ?>"></td>
				</tr>
				<tr>
				<td width="110" bgcolor="#E9E9E9">&nbsp;</td>
				<td bgcolor="#E9E9E9">Secure connection URL. (Add
							final "/"). Read documentation for additional important information.<br></td></tr>
				</table>
				</td>
			</tr>
	    </table><br>
        
		
		<table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="Navy">
			<tr>
				<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="4">
				<tr><td colspan="2" bgcolor="Navy">
				<div align="center"><b><font color="White"><a name="ci"></a>Company Information</font></b></div></td></tr>
					<tr>
					<td width="110" bgcolor="#E9E9E9"><b>Company Name:</b></td>
					<td bgcolor="#E9E9E9"><input type="text" size="35" name="companyname" value="<?PHP echo $companyname ?>"></td>
					</tr>
					
					<tr>
					<td width="110" bgcolor="#C0C0C0"><b>Address:</b></td>
					<td bgcolor="#C0C0C0"><input type="text" size="35" name="address" value="<?PHP echo $shopaddress ?>"></td>
					</tr>
					
					<tr>
					<td width="110" bgcolor="#E9E9E9"><b>City:</b></td>
					<td bgcolor="#E9E9E9"><input type="text" size="35" name="city" value="<?PHP echo $shopcity ?>"></td>
					</tr>
					
					<tr>
					<td width="110" bgcolor="#C0C0C0"><b>State/Province:</b></td>
					<td bgcolor="#C0C0C0"><select NAME="state">
					 <option VALUE="<?PHP echo $shopstate ?>"><?PHP echo $shopstate ?>
					<?PHP echo $state_list;?>
					</select>
					</td>
					</tr>
					
					<tr>
					<td width="110" bgcolor="#E9E9E9"><b>Zip/Postal Code:</b></td>
					<td bgcolor="#E9E9E9"><input type="text" size="35" name="zip" value="<?PHP echo $shopzip ?>"></td>
					</tr>
					
					<tr>
					<td width="110" bgcolor="#C0C0C0"><b>Country:</b></td>
					<td bgcolor="#C0C0C0"><select NAME="country">
					 <option VALUE="<?PHP echo $shopcountry ?>"><?PHP echo $shopcountry ?>
					<?PHP echo $countries_list?>
					</td>
					</tr>
					
					<tr>
					<td width="110" bgcolor="#E9E9E9"><b>Phone Number:</b></td>
					<td bgcolor="#E9E9E9"><input type="text" size="35" name="phone" value="<?PHP echo $shopphone ?>"></td>
					</tr>
					
					<tr>
					<td width="110" bgcolor="#C0C0C0"><b>Fax Number:</b></td>
					<td bgcolor="#C0C0C0"><input type="text" size="35" name="faxnumber" value="<?PHP echo $shopfax ?>"></td>
					</tr>
					
					<tr>
					<td width="110" bgcolor="#E9E9E9"><b>Contact Email Address:</b></td>
					<td bgcolor="#E9E9E9"><input type="text" size="35" name="contactemail" value="<?PHP echo $contactemail ?>"></td>
					</tr>
		        </table>
				</td>
			</tr>
	    </table><br>
		
		
		<table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="Navy">
			<tr>
				<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="4">
				<tr><td colspan="2" bgcolor="Navy">
				<div align="center"><b><font color="White"><a name="ss"></a>Shipping Settings</font></b></div></td></tr>
				<tr>
				<td align="left" valign="top" bgcolor="Silver"><b>Handling Charge:</b></td>
				<td bgcolor="#C0C0C0"><input type="text" name="handling" value="<?PHP echo $handling; ?>"><br>Will be charged in addition to calculated shipping charge.</td>
				</tr>				
				<tr>
				<td width="250" bgcolor="#E9E9E9"><b>UPS Shipping Calculator: </b><br>(Best Method)</td>
				<td bgcolor="#E9E9E9"><input type="radio" name="shipups" value="Yes"
				<?PHP
				if ($shipups == "Yes") { print("checked"); }
				?>
				>Yes&nbsp;<input type="radio" name="shipups" value="No"
				<?PHP
				if ($shipups == "No") { print("checked"); }
				?>
				>No</td>
				</tr>
				<tr><td bgcolor="#E9E9E9">&nbsp;</td>
				<td align="left" bgcolor="#E9E9E9">
					<table border="1" cellspacing="0" cellpadding="0">
	                    <tr>
						<td align="center"><b>Offered Shipping Methods</b></td>
						</tr>
						<tr>
						<td>
						<table width="100%" border="0" cellspacing="0" cellpadding="2">
							<tr>
								<td>Ground:</td>
								<td><input type="radio" name="grnd" value="Yes" 
								<?PHP
								if ($grnd == "Yes") { print("checked"); }
								?>
								>Yes&nbsp;<input type="radio" name="grnd" value="No"
								<?PHP
								if ($grnd == "No") { print("checked"); }
								?>
								>No</td>
							</tr>
							<tr>
								<td>Next Day Air:</td>
								<td><input type="radio" name="nextdayair" value="Yes" 
								<?PHP
								if ($nextdayair == "Yes") { print("checked"); }
								?>
								>Yes&nbsp;<input type="radio" name="nextdayair" value="No"
								<?PHP
								if ($nextdayair == "No") { print("checked"); }
								?>>No</td>
							</tr>
							<tr>
								<td>Second Day Air:</td>
								<td><input type="radio" name="seconddayair" value="Yes" 
								<?PHP
								if ($seconddayair == "Yes") { print("checked"); }
								?>
								>Yes&nbsp;<input type="radio" name="seconddayair" value="No"
								<?PHP
								if ($seconddayair == "No") { print("checked"); }
								?>
								>No</td>
							</tr>
							<tr>
								<td>Third Day Select:</td>
								<td><input type="radio" name="threeday" value="Yes" 
								<?PHP
								if ($threeday == "Yes") { print("checked"); }
								?>
								>Yes&nbsp;<input type="radio" name="threeday" value="No"
								<?PHP
								if ($threeday == "No") { print("checked"); }
								?>
								>No</td>
							</tr>
							<tr>
								<td>Canada Standard:</td>
								<td><input type="radio" name="canada" value="Yes" 
								<?PHP
								if ($canada == "Yes") { print("checked"); }
								?>
								>Yes&nbsp;<input type="radio" name="canada" value="No"
								<?PHP
								if ($canada == "No") { print("checked"); }
								?>
								>No</td>
							</tr>
							<tr>
								<td>Worldwide Express:</td>
								<td><input type="radio" name="worldwidex" value="Yes" 
								<?PHP
								if ($worldwidex == "Yes") { print("checked"); }
								?>
								>Yes&nbsp;<input type="radio" name="worldwidex" value="No"
								<?PHP
								if ($worldwidex == "No") { print("checked"); }
								?>
								>No</td>
							</tr>
							<tr>
								<td>Worldwide Express Plus:</td>
								<td><input type="radio" name="worldwidexplus" value="Yes" 
								<?PHP
								if ($worldwidexplus == "Yes") { print("checked"); }
								?>
								>Yes&nbsp;<input type="radio" name="worldwidexplus" value="No"
								<?PHP
								if ($worldwidexplus == "No") { print("checked"); }
								?>
								>No</td>
							</tr>
						</table>
						</td>
						</tr>
					</table>

				
				</td>
				<tr><td bgcolor="#E9E9E9">&nbsp;</td>
				<td bgcolor="#E9E9E9">UPS realtime shipping calculator. If this is set to "Yes", all
							others will automatically be turned off.<br><br></td></tr>
				<tr>
			    
				<td width="250" bgcolor="#C0C0C0"><b>Shipping Table:</b></td>
				<td bgcolor="#C0C0C0"><input type="radio" name="shipchart" value="Yes"
				<?PHP
				if ($shipchart == "Yes") { print("checked"); }
				?>
				>Yes&nbsp;<input type="radio" name="shipchart" value="No"
				<?PHP
				if ($shipchart == "No") { print("checked"); }
				?>
				>No</td>
				</tr>
				<tr>
				<td align="right" bgcolor="#C0C0C0">&nbsp;</td><!--CyKuH-->
				<td bgcolor="#C0C0C0"><table border="1" cellspacing="0" cellpadding="4">
					<tr>
						<td colspan="4" align="center"><font size="3" color="Red"><b>Shipping Charges:</b></font></td>
					</tr>
					<tr>
						<td align="center"><b>Enable</b></td>
						<td align="center"><font size="3" color="Red">Orders Totaling</font></td>
						<td align="center"><b>Shipping<br>to US</b></td>
						<td align="center"><b>Shipping<br>to Canada</b></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>Between <?PHP echo $cs ?>0.00 and <?PHP echo $cs ?><input type="text" name="ship1p1" size="4" value="<?PHP echo $ship1p1 ?>"></td>
						<td><?PHP echo $cs ?><input type="text" name="ship1us" size="4" value="<?PHP echo $ship1us ?>"></td>
						<td><?PHP echo $cs ?><input type="text" name="ship1ca" size="4" value="<?PHP echo $ship1ca ?>"></td>
					</tr>
					<tr>
						<td><input type="checkbox" name="ship2" value="Yes"
						<?PHP
						if ($ship2 == "Yes") { print("checked"); }
						?>
						></td>
						<td>Between <?PHP echo $cs ?><input type="text" name="ship2p1" size="4" value="<?PHP echo $ship2p1 ?>"> and <?PHP echo $cs ?><input type="text" name="ship2p2" size="4" value="<?PHP echo $ship2p2 ?>"></td>
						<td><?PHP echo $cs ?><input type="text" name="ship2us" size="4" value="<?PHP echo $ship2us ?>"></td>
						<td><?PHP echo $cs ?><input type="text" name="ship2ca" size="4" value="<?PHP echo $ship2ca ?>"></td>
					</tr>
					<tr>
						<td><input type="checkbox" name="ship3" value="Yes"
						<?PHP
						if ($ship3 == "Yes") { print("checked"); }
						?>
						></td>
						<td>Between <?PHP echo $cs ?><input type="text" name="ship3p1" size="4" value="<?PHP echo $ship3p1 ?>"> and <?PHP echo $cs ?><input type="text" name="ship3p2" size="4" value="<?PHP echo $ship3p2 ?>"></td>
						<td><?PHP echo $cs ?><input type="text" name="ship3us" size="4" value="<?PHP echo $ship3us ?>"></td>
						<td><?PHP echo $cs ?><input type="text" name="ship3ca" size="4" value="<?PHP echo $ship3ca ?>"></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><?PHP echo $cs ?><input type="text" name="ship4p1" size="4" value="<?PHP echo $ship4p1 ?>"> and up</td>
						<td><?PHP echo $cs ?><input type="text" name="ship4us" size="4" value="<?PHP echo $ship4us ?>"></td>
						<td><?PHP echo $cs ?><input type="text" name="ship4ca" size="4" value="<?PHP echo $ship4ca ?>"></td>
					</tr>
				</table>
					</td>
				</tr>
				<tr><td bgcolor="#C0C0C0">&nbsp;</td>
				<td bgcolor="#C0C0C0">Shipping chart.<br><br></td></tr>
				
				<tr>
				<td bgcolor="#E9E9E9"><b>Fixed Shipping:</b></td>
				<td bgcolor="#E9E9E9"><input type="radio" name="fixedshipping" value="Yes"
				<?PHP
				if ($fixedshipping == "Yes") { print("checked"); }
				?>
				>Yes&nbsp;<input type="radio" name="fixedshipping" value="No" 
				<?PHP
				if ($fixedshipping == "No") { print("checked"); }
				?>
				>No</td>
				</tr>
				<tr>
				<td align="right" bgcolor="#E9E9E9"><b>Method:<br>Rate:</b></td>
				<td bgcolor="#E9E9E9">
				<input type="radio" name="method" value="perorder" 
				<?PHP
				if ($method == "perorder") { print("checked"); }
				?>
				>Per Order&nbsp;<input type="radio" name="method" value="perpound" 
				<?PHP
				if ($method == "perpound") { print("checked"); }
				?>
				>Per Pound&nbsp;<input type="radio" name="method" value="peritem"
				<?PHP
				if ($method == "peritem") { print("checked"); }
				?>
				>Per Item<br>
				<input type="text" name="rate" value="<?PHP echo $shoprate ?>" size="4">
				</td>
				</tr>
				<tr><td bgcolor="#E9E9E9">&nbsp;</td>
				<td bgcolor="#E9E9E9">Fixed shipping cost<br><br></td></tr>
		        </table>
				</td>
			</tr>
	    </table><br><!--CyKuH-->	
		<table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="Navy">
			<tr>
				<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="4">
				<tr><td colspan="2" bgcolor="Navy">
				<div align="center"><b><font color="White"><a name="po"></a>Payment Options</font></b></div></td></tr>
				    <tr>
					  <td bgcolor="#E9E9E9"><b>Currency Symbol:</b></td>
					  <td bgcolor="#E9E9E9"><input name="cs" size="3" value="<?PHP echo $cs ?>"></td>
					</tr>
					<tr>
					  <td bgcolor="#C0C0C0"><b>Credit Cards:</b></td>
					  <td bgcolor="#C0C0C0"><INPUT name="cc" type="radio" 
				      value="Yes"
					  <?PHP
					  if ($cc == "Yes") { print("checked"); }
					  ?>
					  >Yes&nbsp;<INPUT name="cc" type="radio" value="No"
					  <?PHP
					  if ($cc == "No") { print("checked"); }
					  ?>
					  >No</td>
					</tr>
					<tr>
					  <td bgcolor="#E9E9E9"><b>Visa:</b></td>
					  <td bgcolor="#E9E9E9"><INPUT name="visa" type="radio" 
				      value="Yes"
					  <?PHP
					  if ($visa == "Yes") { print("checked"); }
					  ?>
					  >Yes&nbsp;<INPUT name="visa" type="radio" value="No"
					  <?PHP
					  if ($visa == "No") { print("checked"); }
					  ?>
					  >No</td>
					</tr>
					<tr>
					  <td bgcolor="#C0C0C0"><b>Master Card:</b></td>
					  <td bgcolor="#C0C0C0"><INPUT name="mastercard" type="radio" 
				      value="Yes"
					  <?PHP
					  if ($mastercard == "Yes") { print("checked"); }
					  ?>
					  >Yes&nbsp;<INPUT name="mastercard" type="radio" value="No"
					  <?PHP
					  if ($mastercard == "No") { print("checked"); }
					  ?>
					  >No</td>
					<tr>
					  <td bgcolor="#E9E9E9"><b>Discover:</b></td>
					  <td bgcolor="#E9E9E9"><input type="radio" name="discover" value="Yes"
					  <?PHP
					  if ($discover == "Yes") { print("checked"); }
					  ?>
					  >Yes&nbsp;<input type="radio" name="discover" value="No"
					  <?PHP
					  if ($discover == "No") { print("checked"); }
					  ?>
					  >No</td>
					</tr>
					<tr>
					  <td bgcolor="#C0C0C0"><b>American Express:</b></td>
					  <td bgcolor="#C0C0C0"><input type="radio" name="amex" value="Yes"
					  <?PHP
					  if ($amex == "Yes") { print("checked"); }
					  ?>
					  >Yes&nbsp;<input type="radio" name="amex" value="No"
					  <?PHP
					  if ($amex == "No") { print("checked"); }
					  ?>
					  >No</td>
					</tr>
					<tr>
					  <td valign="top" bgcolor="#E9E9E9"><b>Personal Check:</b></td>
					  <td bgcolor="#E9E9E9"><input type="radio" name="check" value="Yes" 
					  <?PHP
					  if ($check == "Yes") { print("checked"); }
					  ?>
					  >Yes&nbsp;<input type="radio" name="check" value="No"
					  <?PHP
					  if ($check == "No") { print("checked"); }
					  ?>
					  >No
					      <br>*Payable To: <input name="payable" value="<?PHP echo $payable ?>"></td>
					</tr>
					<tr>
					  <td bgcolor="#C0C0C0"><b>Money Order:</b></td>
					  <td bgcolor="#C0C0C0"><input type="radio" name="moneyorder" value="Yes"
					  <?PHP
					  if ($moneyorder == "Yes") { print("checked"); }
					  ?>
					  >Yes&nbsp;<input type="radio" name="moneyorder" value="No"
					  <?PHP
					  if ($moneyorder == "No") { print("checked"); }
					  ?>
					  >No</td>
					</tr>
					<tr>
					  <td bgcolor="#E9E9E9"><b>Fax:</b></td>
					  <td bgcolor="#E9E9E9"><input type="radio" name="fax" value="Yes"
					  <?PHP
					  if ($fax == "Yes") { print("checked"); }
					  ?>
					  >Yes&nbsp;<input type="radio" name="fax" value="No"
					  <?PHP
					  if ($fax == "No") { print("checked"); }
					  ?>
					  >No</td>
					</tr>
					<tr>
					  <td valign="top" bgcolor="#C0C0C0"><b>Paypal:</b></td>
					  <td bgcolor="#C0C0C0"><input type="radio" name="paypal" value="Yes"
					  <?PHP
					  if ($paypal == "Yes") { print("checked"); }
					  ?>
					  >Yes&nbsp;<input type="radio" name="paypal" value="No"
					  <?PHP
					  if ($paypal == "No") { print("checked"); }
					  ?>
					  >No<br>*PayPal Email Address: <input name="paypalemail" value="<?PHP echo $paypalemail ?>"></td>
					</tr>
					<tr>
					  <td valign="top" bgcolor="#E9E9E9"><b>Purchase Order:</b></td>
					  <td bgcolor="#E9E9E9"><input type="radio" name="po" value="Yes"
					  <?PHP
					  if ($po == "Yes") { print("checked"); }
					  ?>
					  >Yes&nbsp;<input type="radio" name="po" value="No"
					  <?PHP
					  if ($po == "No") { print("checked"); }
					  ?>
					  >No</td>
					</tr>
					<tr><td bgcolor="#C0C0C0">&nbsp;</td>
				<td bgcolor="#C0C0C0">Acceptable payment methods.<br></td></tr>
		        </table>
				</td>
			</tr>
	    </table><br>
		
		<table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="Navy">
			<tr>
				<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="4">
				<tr><td colspan="2" bgcolor="Navy">
				<div align="center"><b><font color="White"><a name="ts"></a>Tax Settings:</font></b></div></td></tr>
				<tr>
				<td bgcolor="#E9E9E9"><b>Tax Rate</b></td>
				<td bgcolor="#E9E9E9"><input type="text" size="6" name="taxrate" value="<?PHP echo $taxrate ?>"></td>
				</tr>
				<tr><td bgcolor="#E9E9E9">&nbsp;</td>
				<td bgcolor="#E9E9E9">Tax rate for your state converted to decimal. (i.e. 7.5% to decimal is .0750)<br></td></tr>
				<tr>
		        </table>
				</td>
			</tr>
	    </table><br>
		
		<table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="Navy">
			<tr>
				<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="4">
				<tr><td colspan="2" bgcolor="Navy">
				<div align="center"><b><font color="White"><a name="is"></a>Item Stock Settings:</font></b></div></td></tr>
				<tr>
				<td bgcolor="#E9E9E9"><b>Show Stock:</b></td>
				<td bgcolor="#E9E9E9"><input type="radio" name="showstock" value="Yes" 
				<?PHP
				if ($showstock == "Yes") { print("checked"); }
				?>
				>Yes&nbsp;&nbsp;<input type="radio" name="showstock" value="No" 
				<?PHP
				if ($showstock == "No") { print("checked"); }
				?>
				>No</td>
				</tr>
				<tr><td bgcolor="#E9E9E9">&nbsp;</td>
				<td bgcolor="#E9E9E9">Select whether or not you would like the number of items in stock to be viewed by customers.<br></td></tr>
				<tr>
				<tr>
				<td bgcolor="#c0c0c0"><b>Allow Out Of Stock Items:</b></td>
				<td bgcolor="#c0c0c0"><input type="radio" name="outofstock" value="Yes"  
				<?PHP
				if ($outofstock == "Yes") { print("checked"); }
				?>
				>Yes&nbsp;<input type="radio" name="outofstock" value="No" 
				<?PHP
				if ($outofstock == "No") { print("checked"); }
				?>
				>No</td>
				</tr>
				<tr><td bgcolor="#c0c0c0">&nbsp;</td>
				<td bgcolor="#c0c0c0">When items are out of stock do you want people to be able to add them to their cart?<br></td></tr>
		        </table>
				</td>
			</tr>
	    </table><br>
		
		<table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="Navy">
			<tr>
				<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="4">
				<tr><td colspan="2" bgcolor="Navy">
				<div align="center"><b><font color="White"><a name="sd"></a>Shop Design</font></b></div></td></tr>

				<tr>
				<td bgcolor="#E9E9E9"><b>Item Order:</b></td>
				<td bgcolor="#E9E9E9"><input type="radio" name="orderby" value="title"  
				<?PHP
				if ($orderby == "title") { print("checked"); }
				?>
				>Alphabetically&nbsp;<input type="radio" name="orderby" value="itemid"  
				<?PHP
				if ($orderby == "itemid") { print("checked"); }
				?>
				>By Item ID&nbsp;<input type="radio" name="orderby" value="price"
				<?PHP
				if ($orderby == "price") { print("checked"); }
				?>
				>By Price</td>
				</tr>
				<tr><td bgcolor="#E9E9E9">&nbsp;</td>
				<td bgcolor="#E9E9E9">How would you like items to be displayed within the cart. (Setting to Item ID will display in the order they are entered)<br></td></tr>
				
				<tr>
				<td bgcolor="#C0C0C0"><b>Item Listing Image:</b></td>
				<td bgcolor="#C0C0C0"><input type="radio" name="imagel" value="1"  
				<?PHP
				if ($imagel == "1") { print("checked"); }
				?>
				>Right Of Price&nbsp;<input type="radio" name="imagel" value="2"  
				<?PHP
				if ($imagel == "2") { print("checked"); }
				?>
				>Centered Above Price</td>
				</tr>
				<tr><td bgcolor="#C0C0C0">&nbsp;</td>
				<td bgcolor="#C0C0C0">When a customer is viewing item details, where would you like the image to be placed?<br></td></tr>
				
				<tr>
				<td bgcolor="#E9E9E9" valign="top"><b>Show item pictures or list them?</b></td>
				<td bgcolor="#E9E9E9"><input type="radio" name="showitem" value="picture" 
				<?PHP
				if ($showitem == "picture") { print("checked"); }
				?>
				>Pictures&nbsp;<input type="radio" name="showitem" value="list"
				<?PHP
				if ($showitem == "list") { print("checked"); }
				?>
				>List Them</td>
				</tr>
				<tr><td bgcolor="#E9E9E9">&nbsp;</td>
				<td bgcolor="#E9E9E9">Would you like to show the items with their pictures or just list them with text only?<br></td></tr>
				
				<tr>
				<td bgcolor="#C0C0C0" valign="top"><b>Display a welcome message or show bestsellers?</b></td>
				<td bgcolor="#C0C0C0"><input type="radio" name="showintro" value="No" 
				<?PHP
				if ($showintro == "No") { print("checked"); }
				?>
				>Bestsellers&nbsp;<input type="radio" name="showintro" value="Yes" 
				<?PHP
				if ($showintro == "Yes") { print("checked"); }
				?>
				>Welcome Message<br><br>
				Message:<br>
				<textarea cols="50" rows="10" name="shopintro"><?PHP echo $shopintro ?></textarea><br>
				(Use HTML for best results)
				</td>
				</tr>
				<tr><td bgcolor="#C0C0C0">&nbsp;</td>
				<td bgcolor="#C0C0C0">Whould you like to display a welcome message or show bestsellers on the main page?<br></td></tr>
				
				
				<tr>
				<td valign="top" bgcolor="#E9E9E9"><b>Style Sheet:</b></td>
				<td bgcolor="#E9E9E9"><textarea cols="50" rows="10" name="css" wrap="off"><?PHP echo $css ?></textarea></td>
				</tr>
				<tr><td bgcolor="#E9E9E9">&nbsp;</td>
				<td bgcolor="#E9E9E9">Style sheet settings. (Optional)<br></td></tr>
				
				<tr>
				<td valign="top" bgcolor="#C0C0C0"><b>Header:</b></td>
				
				<td bgcolor="#C0C0C0"><input type="radio" name="useheader" value="Yes" <?PHP if ($useheader == "Yes") { print("checked"); } ?>><textarea cols="50" rows="10" name="header1" wrap="off"><?PHP echo $header1 ?></textarea><br>
								      <input type="radio" name="useheader" value="No" <?PHP if ($useheader == "No") { print("checked"); } ?>> Header File: <input type="text" name="myheader" size="20" value="<?PHP echo $myheader ?>">
				</td>
				</tr>
				<tr><td bgcolor="#C0C0C0">&nbsp;</td>
				<td bgcolor="#C0C0C0">Common header. Appears at the top of every page. You may use the built in or define your own file.<br></td></tr>
				
				<tr>
				<td valign="top" bgcolor="#E9E9E9"><b>Footer:</b></td>
				<td bgcolor="#E9E9E9"><input type="radio" name="usefooter" value="Yes" <?PHP if ($usefooter == "Yes") { print("checked"); } ?>><textarea cols="50" rows="10" name="footer" wrap="off"><?PHP echo $footer ?></textarea><br>
				                      <input type="radio" name="usefooter" value="No" <?PHP if ($usefooter == "No") { print("checked"); } ?>> Footer File: <input type="text" name="myfooter" size="20" value="<?PHP echo $myfooter ?>">
				</td>
				</tr>
				<tr><td bgcolor="#E9E9E9">&nbsp;</td>
				<td bgcolor="#E9E9E9">Common header. Appears at the bottom of every page. You may use the built in or define your own file.<br></td></tr>
				
				<tr>
				<td bgcolor="#C0C0C0"><b>Product Path:</b></td>
				<td bgcolor="#C0C0C0"><input type="text" size="35" name="productpath1" value="<?PHP echo $productpath ?>"></td>
				</tr>
				<tr><td bgcolor="#C0C0C0">&nbsp;</td>
				<td bgcolor="#C0C0C0">Path to product pictures. SunShop will look for product images in this directory.<br></td></tr>
				
				<tr>
				<td valign="top" bgcolor="#E9E9E9"><b>Image Settings:</b></td>
				<td bgcolor="#E9E9E9">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					    <tr>
						<td bgcolor="#E9E9E9" width="230"><b>Thumbnail Sizes:</b></td>
						<td bgcolor="#E9E9E9">Width: <input type="text" size="3" name="thumbwidth1" value="<?PHP echo $thumbwidth ?>">&nbsp;&nbsp;Height: <input type="text" size="3" name="thumbheight1" value="<?PHP echo $thumbheight ?>"><br>Leave both blank to use actual size</td>
						</tr>
						
						<tr>
						<td bgcolor="#C0C0C0" width="230"><b>Picture Sizes:</b></td>
						<td bgcolor="#C0C0C0">Width: <input type="text" size="3" name="picwidth1" value="<?PHP echo $picwidth ?>">&nbsp;&nbsp;Height: <input type="text" size="3" name="picheight1" value="<?PHP echo $picheight ?>"><br>Leave both blank to use actual size</td>
						</tr>
						
						<tr>
						<td bgcolor="#E9E9E9" width="230"><b>Logo Image:</b></td>
						<td bgcolor="#E9E9E9"><input type="text" size="35" name="shopimage" value="<?PHP echo $shopimage ?>"></td>
						</tr>
						
						<tr>
						<td bgcolor="#C0C0C0" width="230"><b>Category Image:</b></td>
						<td bgcolor="#C0C0C0"><input type="text" size="35" name="catimage" value="<?PHP echo $catimage ?>"></td>
						</tr>
						
						<tr>
						<td bgcolor="#E9E9E9" width="230"><b>Category Open Image:</b></td>
						<td bgcolor="#E9E9E9"><input type="text" size="35" name="catopen" value="<?PHP echo $catopen ?>"></td>
						</tr>
						
						<tr>
						<td bgcolor="#C0C0C0" width="230"><b>View Cart Image:</b></td>
						<td bgcolor="#C0C0C0"><input type="text" size="35" name="viewcartimage" value="<?PHP echo $viewcartimage ?>"></td>
						</tr>
						
						<tr>
						<td bgcolor="#E9E9E9" width="230"><b>View Account Image:</b></td>
						<td bgcolor="#E9E9E9"><input type="text" size="35" name="viewaccountimage" value="<?PHP echo $viewaccountimage ?>"></td>
						</tr>
						
						<tr>
						<td bgcolor="#C0C0C0" width="230"><b>Checkout Image:</b></td>
						<td bgcolor="#C0C0C0"><input type="text" size="35" name="checkoutimage" value="<?PHP echo $checkoutimage ?>"></td>
						</tr>
						
						<tr>
						<td bgcolor="#E9E9E9" width="230"><b>Add To Cart Image:</b></td>
						<td bgcolor="#E9E9E9"><input type="text" size="35" name="cartimage" value="<?PHP echo $cartimage ?>"></td>
						</tr>
						
						<tr>
						<td bgcolor="#C0C0C0" width="230"><b>Help Image</b></td>
						<td bgcolor="#C0C0C0"><input type="text" size="35" name="helpimage" value="<?PHP echo $helpimage ?>"></td>
						</tr>
					</table>
				</td>
				</tr>
				<tr>
				<td valign="top" bgcolor="#E9E9E9">&nbsp;</td>
				<td bgcolor="#E9E9E9">Specify the location of the images you wish to use. Defaults are included with SunShop and additional are available via the client login.</td>
				</tr>
				
				<tr>
				<td valign="top" bgcolor="#C0C0C0"><b>Side Table Settings:</b></td>
				<td bgcolor="#C0C0C0">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					    <tr>
						<td bgcolor="#C0C0C0" width="230"><b>Heading Background Color:</b></td>
						<td bgcolor="#C0C0C0"><input type="text" size="15" name="tablehead" value="<?PHP echo $tablehead ?>"></td>
						</tr>
						
						<tr>
						<td bgcolor="#E9E9E9" width="230"><b>Heading Text Color:</b></td>
						<td bgcolor="#E9E9E9"><input type="text" size="15" name="tableheadtext" value="<?PHP echo $tableheadtext ?>"></td>
						</tr>
						
						<tr>
						<td bgcolor="#C0C0C0" width="230"><b>Border Color:</b></td>
						<td bgcolor="#C0C0C0"><input type="text" size="15" name="tableborder" value="<?PHP echo $tableborder ?>"></td>
						</tr>
						
						<tr>
						<td bgcolor="#E9E9E9" width="230"><b>Table Background Color:</b></td>
						<td bgcolor="#E9E9E9"><input type="text" size="15" name="tablebg" value="<?PHP echo $tablebg ?>"></td>
						</tr>
					</table>
				</td>
				</tr>
				<tr>
				<td valign="top" bgcolor="#C0C0C0">&nbsp;</td>
				<td bgcolor="#C0C0C0">The side tables are the tables displayed on the left and right of the screen ("Category Index" and "Account Login"). Specify the properties for those tables.</td>
				</tr>
				
				<tr>
				<td valign="top" bgcolor="#E9E9E9"><b>Center Table Settings:</b></td>
				<td bgcolor="#E9E9E9">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					    <tr>
						<td bgcolor="#E9E9E9" width="230"><b>Heading Background Color:</b></td>
						<td bgcolor="#E9E9E9"><input type="text" size="15" name="centerheader" value="<?PHP echo $centerheader ?>"></td>
						</tr>

						<tr>
						<td bgcolor="#C0C0C0" width="230"><b>Heading Text Color:</b></td>
						<td bgcolor="#C0C0C0"><input type="text" size="15" name="centerfont" value="<?PHP echo $centerfont ?>"></td>
						</tr>
							
						<tr>
						<td bgcolor="#E9E9E9" width="230"><b>Border Color:</b></td>
						<td bgcolor="#E9E9E9"><input type="text" size="15" name="centercolor" value="<?PHP echo $centercolor ?>"></td>
						</tr>
						
						<tr>
						<td bgcolor="#C0C0C0" width="230"><b>Table Background Color:</b></td>
						<td bgcolor="#C0C0C0"><input type="text" size="15" name="centerbg" value="<?PHP echo $centerbg ?>"></td>
						</tr>
						
						<tr>
						<td bgcolor="#E9E9E9" width="230"><b>Border Size:</b></td>
						<td bgcolor="#E9E9E9"><input type="text" size="15" name="centerborder" value="<?PHP echo $centerborder ?>"></td>
						</tr>

					</table>
				</td>
				</tr>
				<tr>
				<td valign="top" bgcolor="#E9E9E9">&nbsp;</td>
				<td bgcolor="#E9E9E9">The center table is the main table displayed on the screen ("Products" display and others). Specify the properties for this table.</td>
				</tr>
				<tr>
					<td bgcolor="#C0C0C0"><b>Show Product Price:</b></td>
					<td bgcolor="#C0C0C0"><input type="radio" name="showprice" value="No" 
					<?PHP
					if ($showprice == "No") { print("checked"); }
					?>
					>No&nbsp;<input type="radio" name="showprice" value="Yes" 
					<?PHP
					if ($showprice == "Yes") { print("checked"); }
					?>
					>Yes</td>
				</tr>
				<tr><td bgcolor="#C0C0C0">&nbsp;</td>
					<td bgcolor="#C0C0C0">Would you like to show the item price next to the product name when displayed in category areas.<br>
					</td>
				</tr>
		        </table>
				</td>
			</tr>
	    </table><br>
		
		<input type=submit value="Store Changes">&nbsp;&nbsp;<input type="Reset"></form>
	<?PHP
}
// ###################### null #############################
function nullification () {
?>
<b><font color="Navy">Nullification info:</font></b><br><br>
<div align="left"><br>
	<table width="95%" border="0" cellspacing="2" cellpadding="3">
		<tr>
			<td width="100" valign="top" bgcolor="Silver">Programm Name</td>
			<td bgcolor="Silver">SunShop</td>
		</tr>
		<tr>
			<td width="100" valign="top">Programm Version</td>
			<td>2.6</td>
		</tr>
		<tr>
			<td width="100" valign="top" bgcolor="Silver">Program Author</td>
			<td bgcolor="Silver">Turnkey Solutions 2001-2002.</td>
		</tr>
		<tr>
			<td width="100" valign="top">Home Page</td>
			<td>http://www.turnkeywebtools.com</td>
		</tr>
		<tr>
			<td width="100" valign="top" bgcolor="Silver">Retail Price</td>
			<td bgcolor="Silver">$99.99 United States Dollars</td>
		</tr>
		<tr>
			<td width="100" valign="top">WebForum Price</td>
			<td>$00.00 Always 100% Free</td>
		</tr>
		<tr>
			<td width="100" valign="top" bgcolor="Silver">xCGI Price</td>
			<td bgcolor="Silver">$00.00 Always 100% Free</td>
		</tr>
		<tr>
			<td width="100" valign="top">Supplied by</td>
			<td>Stive [WTN] , CyKuH [WTN]</td>
		</tr>
		<tr>
			<td width="100" valign="top" bgcolor="Silver">Nullified by</td>
			<td bgcolor="Silver">CyKuH [WTN]</td>
		</tr>
		<tr>
			<td width="100" valign="top">Distribution</td>
			<td>via WebForum and Forums File Dumps</td>
		</tr>
		<tr>
			<td width="100" valign="top" bgcolor="Silver">Protection</td>
			<td bgcolor="Silver">Email Call Home, License Number, Refferer links and more...</td>
		</tr>
		<tr>
			<td width="100" valign="top">Extra Note</td>
			<td>WTN Team Rulez  :o)</td>
		</tr>
	</table>
	</div>
<?
}
// ######################## sqldumptable ####################
function sqldumptable($table) {
  global $DB_site;

  // ## $tabledump = "DROP TABLE IF EXISTS $table;\n";
  // ## $tabledump .= "CREATE TABLE $table (\n";
  // ## $firstfield=1;

  // ## $fields = $DB_site->query("SHOW FIELDS FROM $table");
  // ## while ($field = $DB_site->fetch_array($fields)) {
  // ##   if (!$firstfield) {
  // ##     $tabledump .= ",\n";
  // ##   } else {
  // ##     $firstfield=0;
  // ##   }
  // ##   $tabledump .= "   $field[Field] $field[Type]";
  // ##   if (!empty($field["Default"])) {
  // ##     $tabledump .= " DEFAULT '$field[Default]'";
  // ##   }
  // ##   if ($field[Null] != "YES") {
  // ##     $tabledump .= " NOT NULL";
  // ##   }
  // ##   if ($field[Extra] != "") {
  // ##     $tabledump .= " $field[Extra]";
  // ##   }
  // ## }
  // ## $DB_site->free_result($fields);

  // ## $keys = $DB_site->query("SHOW KEYS FROM $table");
  // ## while ($key = $DB_site->fetch_array($keys)) {
  // ##   $kname=$key['Key_name'];
  // ##   if ($kname != "PRIMARY" and $key['Non_unique'] == 0) {
  // ##     $kname="UNIQUE|$kname";
  // ##   }
  // ##   if(!is_array($index[$kname])) {
  // ##     $index[$kname] = array();
  // ##   }
  // ##   $index[$kname][] = $key['Column_name'];
  // ## }
  // ## $DB_site->free_result($keys);

  // ## while(list($kname, $columns) = @each($index)){
  // ##   $tabledump .= ",\n";
  // ##   $colnames=implode($columns,",");

  // ##   if($kname == "PRIMARY"){
  // ##     $tabledump .= "   PRIMARY KEY ($colnames)";
  // ##   } else {
  // ##     if (substr($kname,0,6) == "UNIQUE") {
  // ##       $kname=substr($kname,7);
  // ##     }

  // ##     $tabledump .= "   KEY $kname ($colnames)";

  // ##   }
  // ## }

  // ## $tabledump .= "\n);\n\n";

  $rows = $DB_site->query("SELECT * FROM $table");
  $numfields=$DB_site->num_fields($rows);
  while ($row = $DB_site->fetch_array($rows)) {
    $tabledump .= "INSERT INTO $table VALUES(";

    $fieldcounter=-1;
    $firstfield=1;
    while (++$fieldcounter<$numfields) {
      if (!$firstfield) {
        $tabledump.=",";
      } else {
        $firstfield=0;
      }

      if (!isset($row[$fieldcounter])) {
        $tabledump .= "NULL";
      } else {
        $tabledump .= "'".addslashes($row[$fieldcounter])."'";
      }
    }

    $tabledump .= ");\n";
  }
  $DB_site->free_result($rows);
  
  return $tabledump;
}

// ##################### adminauthenticate ##################
function adminauthenticate ($login, $password) {
    global $DB_site, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."user where username='$login'");
    while ($row=$DB_site->fetch_array($temps)) {
		if ($password == $row[password]) {
		   if ($row[name] == "Admin_Account") {
			   $result = "valid";
		   } else {
		       $result = "invalid";
		   }
		} else {
		   $result = "invalid";
		}
		return $result;
	}
	$result = "invalid";
	return $result;
}

// ##################### getuser ##################
function getuser ($ID) {
    global $DB_site, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."user where userid='$ID'");
    while ($row=$DB_site->fetch_array($temps)) {
		$userinfo[userid] = $row[userid];
		$userinfo[username] = $row[username];
		$userinfo[password] = $row[password];
		$userinfo[name] = $row[name];
		$userinfo[address_line1] = $row[address_line1];
		$userinfo[address_line2] = $row[address_line2];
		$userinfo[city] = $row[city];
		$userinfo[state] = $row[state];
		$userinfo[zip] = $row[zip];
		$userinfo[country] = $row[country];
		$userinfo[baddress_line1] = $row[baddress_line1];
		$userinfo[baddress_line2] = $row[baddress_line2];
		$userinfo[bcity] = $row[bcity];
		$userinfo[bstate] = $row[bstate];
		$userinfo[bzip] = $row[bzip];
		$userinfo[bcountry] = $row[bcountry];
		$userinfo[phone] = $row[phone];
		$userinfo[email] = $row[email];
		$userinfo[lastvisit] = $row[lastvisit];
		return $userinfo;
	}
}

// ##################### iteminfo ##################
function iteminfo ($id) {
    global $DB_site, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where itemid='$id'");
    while ($row=$DB_site->fetch_array($temps)) {
		$iteminfo[categoryid] = $row[categoryid]; $iteminfo[title] = stripslashes($row[title]);
		$iteminfo[stitle] = stripslashes($row[stitle]); $iteminfo[imagename] = $row[imagename];
		$iteminfo[thumb] = $row[thumb]; $iteminfo[poverview] = stripslashes($row[poverview]);
		$iteminfo[pdetails] = stripslashes($row[pdetails]); $iteminfo[quantity] = $row[quantity];
		$iteminfo[sold] = $row[sold]; $iteminfo[subcategoryid] = $row[subcategoryid];
		$iteminfo[price] = $row[price];	$iteminfo[weight] = $row[weight];
		$iteminfo[viewable] = $row[viewable]; $iteminfo[option1] = $row[option1];
		$iteminfo[option12] = $row[option12]; $iteminfo[option2] = $row[option2];
		$iteminfo[option22] = $row[option22]; $iteminfo[option3] = $row[option3];
		$iteminfo[option32] = $row[option32]; $iteminfo[payop] = $row[payop];
		$iteminfo[itemid] = $row[itemid]; $iteminfo[sku] = $row[sku];
		if ($iteminfo[sku] != "") { $iteminfo[num] = $iteminfo[sku]; } else { $iteminfo[num] = $iteminfo[itemid]; } 
	}
	$temp=$DB_site->query("SELECT * FROM ".$dbprefix."specials where itemid='$id'");
    while ($row=$DB_site->fetch_array($temp)) {
		$iteminfo[specialid] = $row[specialid]; $iteminfo[sdescription] = stripslashes($row[sdescription]); 
		$iteminfo[sprice] = $row[sprice];
		return $iteminfo;
	}
	return $iteminfo;
}

// ##################### getcategory ##################
function getcategory ($id) {
    global $DB_site, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where itemid='$id'");
	while ($row=$DB_site->fetch_array($temps)) {
	    $cat = $row[categoryid];
	}
	return $cat;
}

// ##################### getsubcategory ##################
function getsubcategory ($id) {
    global $DB_site, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where itemid='$id'");
	while ($row=$DB_site->fetch_array($temps)) {
	    $cat = $row[subcategoryid];
	}
	return $cat;
}

// ##################### gcategorytot ##################
function categorytot ($id) {
    global $DB_site, $dbprefix;
	$temp = 0;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where categoryid='$id'");
	while ($row=$DB_site->fetch_array($temps)) {
	    $temp++;
	}
	return $temp;
}

// ##################### gsubcategorytot ##################
function subcategorytot ($id) {
    global $DB_site, $dbprefix;
	$temp = 0;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where subcategoryid='$id'");
	while ($row=$DB_site->fetch_array($temps)) {
	    $temp++;
	}
	return $temp;
}

// ##################### getcategoryname ##################
function getcategoryname ($id) {
    global $DB_site, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."category where categoryid='$id'");
	while ($row=$DB_site->fetch_array($temps)) {
	    $cat = $row[title];
	}
	return $cat;
}

// ##################### getsubcategoryname ##################
function getsubcategoryname ($id) {
    global $DB_site, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."subcategory where subcategoryid='$id'");
	while ($row=$DB_site->fetch_array($temps)) {
	    $cat = $row[title];
	}
	return $cat;
}

// ##################### getcategorynum ##################
function getcategorynum ($id) {
    global $DB_site, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."category where title='$id'");
	while ($row=$DB_site->fetch_array($temps)) {
	    $cat = $row[categoryid];
	}
	return $cat;
}

// ##################### getsubcategorynum ##################
function getsubcategorynum ($id) {
    global $DB_site, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."subcategory where title='$id'");
	while ($row=$DB_site->fetch_array($temps)) {
	    $cat = $row[subcategoryid];
	}
	return $cat;
}

// ##################### viewuser #######################
function viewuser ($ID) {
	   $userinfo = getuser ($ID);
	   $username = $userinfo[username];
	   $password = $userinfo[password];
	   $name = $userinfo[name];
	   $address_line1 = $userinfo[address_line1];
	   $address_line2 = $userinfo[address_line2];
	   $city = $userinfo[city];
	   $state = $userinfo[state];
	   $zip = $userinfo[zip];
	   $country = $userinfo[country];
	   $baddress_line1 = $userinfo[baddress_line1];
	   $baddress_line2 = $userinfo[baddress_line2];
	   $bcity = $userinfo[bcity];
	   $bstate = $userinfo[bstate];
	   $bzip = $userinfo[bzip];
	   $bcountry = $userinfo[bcountry];
	   $phone = $userinfo[phone];
	   $email = $userinfo[email];
	?>
	<table>
		<tr>
			<td><b>Login:</b></td>
			<td><?PHP echo $username ?></td>
		</tr>
		<tr>
			<td><b>Full Name:</b></td>
			<td><?PHP echo $name ?></td>
		</tr>
		<tr>
			<td><b>Phone:</b></td>
			<td><?PHP echo $phone ?></td>
		</tr>
		<tr>
			<td><b>Email:</b></td>
			<td><a href="mailto:<?PHP echo $email ?>"><?PHP echo $email ?></a></td>
		</tr>
		<tr>
			<td valign="top"><b>Shipping<br>Address:</b></td>
			<td><?PHP echo $address_line1 ?><br>
			<?PHP if ($address_line2 != "") { echo "$address_line2 <br>"; } ?>
			<?PHP echo $city ?>, <?PHP echo $state ?> <?PHP echo $zip ?><br>
			<?PHP echo $country ?><br>
			</td>
		</tr>
		<tr>
			<td valign="top"><b>Billing<br>Address:</b></td>
			<?PHP if($baddress_line1 != "") { ?>
			<td><?PHP echo $baddress_line1 ?><br>
			<?PHP if ($baddress_line2 != "") { echo "$baddress_line2 <br>"; } ?>
			<?PHP echo $bcity ?>, <?PHP echo $bstate ?> <?PHP echo $bzip ?><br>
			<?PHP echo $bcountry ?><br>
			<?PHP } else { ?>
			<td><?PHP echo $address_line1 ?><br>
			<?PHP if ($address_line2 != "") { echo "$address_line2 <br>"; } ?>
			<?PHP echo $city ?>, <?PHP echo $state ?> <?PHP echo $zip ?><br>
			<?PHP echo $country ?><br>
			<?PHP } ?>
			</td>
			
		</tr>
	</table></div><br>	
	<?PHP
}

// ##################### viewaccount #######################
function viewaccount ($ID) {
	   $userinfo = getuser ($ID);
	   $username = $userinfo[username];
	   $password = $userinfo[password];
	   $name = $userinfo[name];
	   $address_line1 = $userinfo[address_line1];
	   $address_line2 = $userinfo[address_line2];
	   $city = $userinfo[city];
	   $state = $userinfo[state];
	   $zip = $userinfo[zip];
	   $country = $userinfo[country];
	   $baddress_line1 = $userinfo[baddress_line1];
	   $baddress_line2 = $userinfo[baddress_line2];
	   $bcity = $userinfo[bcity];
	   $bstate = $userinfo[bstate];
	   $bzip = $userinfo[bzip];
	   $bcountry = $userinfo[bcountry];
	   $phone = $userinfo[phone];
	   $email = $userinfo[email];
	?>
	<div align="left"><b><font color="Navy">View User ID:</font> <?PHP echo $ID ?></b><br><br>
	<table width="95%" border="0" cellspacing="2" cellpadding="3">
		<tr>
			<td width="100" bgcolor="Silver"><b>Login:</b></td>
			<td wisth="*" bgcolor="Silver"><?PHP echo $username ?></td>
		</tr>
				<tr>
			<td width="100"><b>Password:</b></td>
			<td><?PHP echo $password ?></td>
		</tr>
		<tr>
			<td width="100" bgcolor="Silver"><b>Full Name:</b></td>
			<td bgcolor="Silver"><?PHP echo $name ?></td>
		</tr>
		<tr>
			<td width="100"><b>Phone:</b></td>
			<td><?PHP echo $phone ?></td>
		</tr>
		<tr>
			<td width="100" bgcolor="Silver"><b>Email:</b></td>
			<td bgcolor="Silver"><a href="mailto:<?PHP echo $email ?>"><?PHP echo $email ?></a></td>
		</tr>
		<tr>
			<td width="100">&nbsp;</td>
			<td><b>Shipping Address</b></td>
		</tr>
		<tr>
			<td width="100"><b>Address 1:</b></td>
			<td><?PHP echo $address_line1 ?></td>
		</tr>
		<tr>
			<td width="100" bgcolor="Silver"><b>Address 2:</b></td>
			<td bgcolor="Silver"><?PHP echo $address_line2 ?>&nbsp;</td>
		</tr>
		<tr>
			<td width="100"><b>City:</b></td>
			<td><?PHP echo $city ?></td>
		</tr>
		<tr>
			<td width="100" bgcolor="Silver"><b>State/Province:</b></td>
			<td bgcolor="Silver"><?PHP echo $state ?></td>
		</tr>
		<tr>
			<td width="100"><b>Zip/Postal Code:</b></td>
			<td><?PHP echo $zip ?></td>
		</tr>
		<tr>
			<td width="100" bgcolor="Silver"><b>Country:</b></td>
			<td bgcolor="Silver"><?PHP echo $country ?></td>
		</tr>
		<tr>
			<td width="100">&nbsp;</td>
			<td><b>Billing Address</b></td>
		</tr>
		<tr>
			<td width="100"><b>Address 1:</b></td>
			<td><?PHP echo $baddress_line1 ?></td>
		</tr>
		<tr>
			<td width="100" bgcolor="Silver"><b>Address 2:</b></td>
			<td bgcolor="Silver"><?PHP echo $baddress_line2 ?>&nbsp;</td>
		</tr>
		<tr>
			<td width="100"><b>City:</b></td>
			<td><?PHP echo $bcity ?></td>
		</tr>
		<tr>
			<td width="100" bgcolor="Silver"><b>State/Province:</b></td>
			<td bgcolor="Silver"><?PHP echo $bstate ?></td>
		</tr>
		<tr>
			<td width="100"><b>Zip/Postal Code:</b></td>
			<td><?PHP echo $bzip ?></td>
		</tr>
		<tr>
			<td width="100" bgcolor="Silver"><b>Country:</b></td>
			<td bgcolor="Silver"><?PHP echo $bcountry ?></td>
		</tr>
	</table></div><br>	
	<?PHP
}

// ##################### editaccount #######################
function editaccount ($ID) {
    global $state_list, $countries_list;
	   $userinfo = getuser ($ID);
	   $username = $userinfo[username];
	   $password = $userinfo[password];
	   $name = $userinfo[name];
	   $address_line1 = $userinfo[address_line1];
	   $address_line2 = $userinfo[address_line2];
	   $city = $userinfo[city];
	   $state = $userinfo[state];
	   $zip = $userinfo[zip];
	   $country = $userinfo[country];
	   $baddress_line1 = $userinfo[baddress_line1];
	   $baddress_line2 = $userinfo[baddress_line2];
	   $bcity = $userinfo[bcity];
	   $bstate = $userinfo[bstate];
	   $bzip = $userinfo[bzip];
	   $bcountry = $userinfo[bcountry];
	   $phone = $userinfo[phone];
	   $email = $userinfo[email];
	?>
	<div align="left"><b><font color="Navy">Edit User ID:</font> <?PHP echo $ID ?></b><br><br>
	<form action="admin.php?action=updateaccount" method="post">
	<table width="95%" border="0" cellspacing="2" cellpadding="3">
		<tr>
			<td width="100" bgcolor="Silver"><b>Login:</b></td>
			<td bgcolor="Silver"><?PHP echo $username ?><input type="hidden" name="username" value="<?PHP echo $username ?>"></td>
		</tr>
				<tr>
			<td width="100"><b>Password:</b></td>
			<td><input type="text" name="password" size="10" value="<?PHP echo $password ?>">
			</td>
		</tr>
		<tr>
			<td width="100" bgcolor="Silver"><b>Full Name:</b></td>
			<td bgcolor="Silver"><input type="text" name="name" size="20" value="<?PHP echo $name ?>">
			</td>
		</tr>
		<tr>
			<td width="100"><b>Phone:</b></td>
			<td><input type="text" name="phone" size="15" value="<?PHP echo $phone ?>">
			</td>
		</tr>
		<tr>
			<td width="100" bgcolor="Silver"><b>Email:</b></td>
			<td bgcolor="Silver"><input type="text" name="email" size="25" value="<?PHP echo $email ?>">
			</td>
		</tr>
		<tr>
			<td width="100">&nbsp;</td>
			<td><b>Shipping Address</b></td>
		</tr>
		<tr>
			<td width="100"><b>Address 1:</b></td>
			<td><input type="text" name="address_line1" size="20" value="<?PHP echo $address_line1 ?>">
			</td>
		</tr>
		<tr>
			<td width="100" bgcolor="Silver"><b>Address 2:</b></td>
			<td bgcolor="Silver"><input type="text" name="address_line2" size="20" value="<?PHP echo $address_line2 ?>">
			</td>
		</tr>
		<tr>
			<td width="100"><b>City:</b></td>
			<td><input type="text" name="city" size="10" value="<?PHP echo $city ?>">
			</td>
		</tr>
		<tr>
			<td width="100" bgcolor="Silver"><b>State/Province:</b></td>
			<td bgcolor="Silver"><select NAME="state">
				<option VALUE="<?PHP echo $state ?>"><?PHP echo $state ?>
				<?PHP echo $state_list;?>
				</select>
	         </td>
		</tr>
		<tr>
			<td width="100"><b>Zip/Postal Code:</b></td>
			<td><input type="text" name="zip" size="10" value="<?PHP echo $zip ?>">
			</td>
		</tr>
		<tr>
			<td width="100" bgcolor="Silver"><b>Country:</b></td>
			<td bgcolor="Silver"><select NAME="country">
				 <option VALUE="<?PHP echo $country ?>"><?PHP echo $country ?>
				 <?PHP echo $countries_list; ?>
				</SELECT>
			</td>
		</tr>
		<tr>
			<td width="100">&nbsp;</td>
			<td><b>Billing Address</b></td>
		</tr>
		<tr>
			<td width="100"><b>Address 1:</b></td>
			<td><input type="text" name="baddress_line1" size="20" value="<?PHP echo $baddress_line1 ?>">
			</td>
		</tr>
		<tr>
			<td width="100" bgcolor="Silver"><b>Address 2:</b></td>
			<td bgcolor="Silver"><input type="text" name="baddress_line2" size="20" value="<?PHP echo $baddress_line2 ?>">
			</td>
		</tr>
		<tr>
			<td width="100"><b>City:</b></td>
			<td><input type="text" name="bcity" size="10" value="<?PHP echo $bcity ?>">
			</td>
		</tr>
		<tr>
			<td width="100" bgcolor="Silver"><b>State/Province:</b></td>
			<td bgcolor="Silver"><select NAME="bstate">
				<option VALUE="<?PHP echo $bstate ?>"><?PHP echo $bstate ?>
				<?PHP echo $state_list;?>
				</select>
	         </td>
		</tr>
		<tr>
			<td width="100"><b>Zip/Postal Code:</b></td>
			<td><input type="text" name="bzip" size="10" value="<?PHP echo $bzip ?>">
			</td>
		</tr>
		<tr>
			<td width="100" bgcolor="Silver"><b>Country:</b></td>
			<td bgcolor="Silver"><select NAME="bcountry">
				 <option VALUE="<?PHP echo $bcountry ?>"><?PHP echo $bcountry ?>
				 <?PHP echo $countries_list; ?>
				</SELECT>
			</td>
		</tr>
		<tr>
			<td width="100">&nbsp;</td>
			<td><input type="submit" name="Submit" value="Continue">&nbsp;&nbsp;<input type="Reset"></td>
		</tr>
	</table></div><br>	
	<?PHP
}

// ##################### additem #######################
function additems () {
    global $DB_site, $productpath, $thumbheight, $thumbwidth, $picheight, $picwidth, $cs, $dbprefix;
    ?>
	<b><font color="Navy">Add New Item:</font></b><br><br>
	<form action="admin.php?action=storeitem" method="post">
	<table width="95%" border="0" cellspacing="2" cellpadding="3">
	    <tr>
			<td><b>SKU#:</b> (Optional)</td>
			<td><input type="text" name="sku" size="25"></td>
		</tr>
		<tr>
			<td bgcolor="Silver"><b>Category:</b></td>
			<td bgcolor="Silver">
			<select name="categoryid">
			<?PHP
			$temp=$DB_site->query("SELECT * FROM ".$dbprefix."category ORDER by title");
			while ($row=$DB_site->fetch_array($temp)) {
			     print("<option value=\"$row[categoryid]\">$row[title]</option>"); 
			}
			?>
			</select>
			</td>
		</tr>
		<tr>
			<td><b>Subcategory:</b></td>
			<td>
			<select name="subcategoryid">
			<?PHP
			print("<option value=\"None\">None</option>");
			$temp=$DB_site->query("SELECT * FROM ".$dbprefix."subcategory ORDER by title");
			while ($row=$DB_site->fetch_array($temp)) {
			     $temp2=$DB_site->query("SELECT * FROM ".$dbprefix."category where categoryid='$row[categoryid]'");
				 $row2=$DB_site->fetch_array($temp2);
			     print("<option value=\"$row[subcategoryid]\">$row2[title] -> $row[title]</option>"); 
			}
			?>
			</select>
			</td>
		</tr>
		<tr>
			<td bgcolor="Silver"><b>Title:</b></td>
			<td bgcolor="Silver"><input type="text" name="title" size="30"></td>
		</tr>
		<tr>
			<td valign="top"><b>Option1:</b> (Optional)</td>
			<td><b>Name:</b> <input type="text" name="option1" size="20"><br>
			    <b>Drop Down Items:</b> <input type="text" name="option12" size="20"><br>
				(Seperate each drop down item with a "-")<br>
				<b>Price Increase:</b> <input type="text" name="payop" size="20"><br>
				(Seperate each price increase with a "-". Leave blank if no increase.)
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="Silver"><b>Option2:</b> (Optional)</td>
			<td bgcolor="Silver"><b>Name:</b> <input type="text" name="option2" size="20"><br>
			    <b>Drop Down Items:</b> <input type="text" name="option22" size="20"><br>
				(Seperate each drop down item with a "-")
			</td>
		</tr>
		<tr>
			<td valign="top"><b>Option3:</b> (Optional)</td>
			<td><b>Name:</b> <input type="text" name="option3" size="20"><br>
			    <b>Drop Down Items:</b> <input type="text" name="option32" size="20"><br>
				(Seperate each drop down item with a "-")
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="Silver"><b>Image:</b></td>
			<td bgcolor="Silver"><?PHP echo $productpath ?><input type="text" name="imagename" size="20"><br>(Displays at <?PHP echo $picwidth ?> x <?PHP echo $picheight ?>)</td>
		</tr>
		<tr>
			<td valign="top"><b>Thumbnail:</b></td>
			<td><?PHP echo $productpath ?><input type="text" name="thumb" size="20"><br>(Displays at <?PHP echo $thumbwidth ?> x <?PHP echo $thumbheight ?>) You may use the same image for both.</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="Silver"><b>Product Overview:</b></td>
			<td bgcolor="Silver">
				<textarea cols="40" rows="8" name="poverview"></textarea><br>
				(You may use HTML. Instead of the ENTER key use &lt;br&gt; to skip lines.)
			</td>
		</tr>
		<tr>
			<td valign="top"><b>Product Details:</b></td>
			<td>
				<textarea cols="40" rows="8" name="pdetails"></textarea><br>
				(You may use HTML. Instead of the ENTER key use &lt;br&gt; to skip lines.)
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="Silver"><b># In Stock:</b></td>
			<td bgcolor="Silver">
				<input type="text" name="quantity" size="4">
			</td>
		</tr>
		<tr>
			<td valign="top"><b>Number Sold:</b></td>
			<td>0<input type="hidden" name="sold" value="0">
			</td>
		</tr>
		<tr>
			<td bgcolor="Silver"><b>Price:</b></td>
			<td bgcolor="Silver"><?PHP echo $cs; ?><input type="text" name="price" value="0.00" size="8"></td>
		</tr>
		<tr>
			<td valign="top"><b>Weight:</b></td>
			<td valign="top"><input type="text" name="weight" size="8"><br>Important for calculating shipping costs.</td>
		</tr>
		<tr>
			<td bgcolor="Silver"><b>Viewable:</b></td>
			<td bgcolor="Silver"><input type="checkbox" name="viewable" value="Yes" checked>Uncheck this to hide product.</td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" name="Submit" value="Add Item">&nbsp;&nbsp;<input type="Reset"></td>
		</tr>
	</table></form>
	<?PHP
}

// ##################### edititem #######################
function edititem ($id) {
    global $DB_site, $productpath, $picheight, $picwidth, $thumbheight, $thumbwidth, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where itemid='$id'");
	while ($row=$DB_site->fetch_array($temps)) {
    ?>
	<b><font color="Navy">Edit Item:</font> <?PHP echo $id ?></b><br><br>
	<form action="admin.php?action=updateitem" method="post">
	<table width="95%" border="0" cellspacing="2" cellpadding="3">
	    <tr>
			<td><b>SKU#:</b> (Optional)</td>
			<td><input type="text" name="sku" size="25" value="<?PHP echo $row[sku]; ?>"></td>
		</tr>
		<tr>
			<td bgcolor="Silver"><b>Product ID:</b></td>
			<td bgcolor="Silver"><?PHP echo $id ?><input type="hidden" name="id" value="<?PHP echo $id ?>"></td>
		</tr>
		<tr>
			<td><b>Category:</b></td>
			<td>
			<select name="categoryid">
			<option value="<?PHP echo $row[categoryid] ?>" selected><?PHP echo getcategoryname($row[categoryid]); ?></option>
			<?PHP
			$temp1=$DB_site->query("SELECT * FROM ".$dbprefix."category ORDER by title");
			while ($row1=$DB_site->fetch_array($temp1)) {
			     print("<option value=\"$row1[categoryid]\">$row1[title]</option>"); 
			}
			?>
			</select>
			</td>
		</tr>
		<tr>
			<td bgcolor="Silver"><b>Subcategory:</b></td>
			<td bgcolor="Silver">
			<select name="subcategoryid">
			<option value="<?PHP echo $row[subcategoryid] ?>" selected><?PHP echo getsubcategoryname($row[subcategoryid]); ?></option>
			<?PHP
			print("<option value=\"None\">None</option>");
			$temp1=$DB_site->query("SELECT * FROM ".$dbprefix."subcategory ORDER by title");
			while ($row1=$DB_site->fetch_array($temp1)) {
			     $temp2=$DB_site->query("SELECT * FROM ".$dbprefix."category where categoryid='$row1[categoryid]'");
				 $row2=$DB_site->fetch_array($temp2);
			     print("<option value=\"$row1[subcategoryid]\">$row2[title] -> $row1[title]</option>"); 
			}
			?>
			</select>
			</td>
		</tr>
		<tr>
			<td><b>Title:</b></td>
			<td><input type='text' name='title' size='30' value='<?PHP echo stripslashes($row[title]); ?>'></td>
		</tr>
		<tr>
			<td valign="top" bgcolor="Silver"><b>Option1:</b> (Optional)</td>
			<td bgcolor="Silver"><b>Name:</b> <input type='text' name='option1' size='20' value='<?PHP echo $row[option1] ?>'><br>
			    <b>Drop Down Items:</b> <input type='text' name='option12' size='20' value='<?PHP echo $row[option12] ?>'><br>
				(Seperate each drop down item with a "-")<br>
				<b>Price Increase:</b> <input type="text" name="payop" size="20" value="<?PHP echo $row[payop] ?>"><br>
				(Seperate each price increase with a "-". Leave blank if no increase.)
			</td>
		</tr>
		<tr>
			<td valign="top"><b>Option2:</b> (Optional)</td>
			<td><b>Name:</b> <input type='text' name='option2' size='20' value='<?PHP echo $row[option2] ?>'><br>
			    <b>Drop Down Items:</b> <input type='text' name='option22' size='20' value='<?PHP echo $row[option22] ?>'><br>
				(Seperate each drop down item with a "-")
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="Silver"><b>Option3:</b> (Optional)</td>
			<td bgcolor="Silver"><b>Name:</b> <input type='text' name='option3' size='20' value='<?PHP echo $row[option3] ?>'><br>
			    <b>Drop Down Items:</b> <input type='text' name='option32' size='20' value='<?PHP echo $row[option32] ?>'><br>
				(Seperate each drop down item with a "-")
			</td>
		</tr>
		<tr>
			<td valign="top"><b>Image:</b></td>
			<td><?PHP echo $productpath ?><input type="text" name="imagename" size="20" value="<?PHP echo $row[imagename] ?>"><br>
				<img src="../<?PHP echo $productpath ?>/<?PHP echo $row[imagename] ?>" border="0" alt="" width="<?PHP echo $picwidth ?>" height="<?PHP echo $picheight ?>">
				<br>(Displays at <?PHP echo $picwidth ?> x <?PHP echo $picheight ?>)
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="Silver"><b>Thumbnail:</b></td>
			<td bgcolor="Silver"><?PHP echo $productpath ?><input type="text" name="thumb" size="20" value="<?PHP echo $row[thumb] ?>"><br>
			    <img src="../<?PHP echo $productpath ?>/<?PHP echo $row[thumb] ?>" border="0" alt="" width="<?PHP echo $thumbwidth ?>" height="<?PHP echo $thumbheight ?>">
			    <br>(Displays at <?PHP echo $thumbwidth ?> x <?PHP echo $thumbheight ?>) You may use the same image for both.
			</td>
		</tr>
		<tr>
			<td valign="top"><b>Product Overview:</b></td>
			<td>
				<textarea cols="40" rows="8" name="poverview"><?PHP echo stripslashes($row[poverview]); ?></textarea><br>
				(You may use HTML. Instead of the ENTER key use &lt;br&gt; to skip lines.)
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="Silver"><b>Product Details:</b></td>
			<td bgcolor="Silver">
				<textarea cols="40" rows="8" name="pdetails"><?PHP echo stripslashes($row[pdetails]); ?></textarea><br>
				(You may use HTML. Instead of the ENTER key use &lt;br&gt; to skip lines.)
			</td>
		</tr>
		<tr>
			<td valign="top"><b># In Stock:</b></td>
			<td>
				<input type="text" name="quantity" size="4" value="<?PHP echo $row[quantity] ?>">
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="Silver"><b>Number Sold:</b></td>
			<td bgcolor="Silver">
				<?PHP echo $row[sold] ?>
			</td>
		</tr>
		<tr>
			<td><b>Price:</b></td>
			<td><?PHP echo $cs; ?><input type="text" name="price" value="<?PHP echo $row[price] ?>" size="8"></td>
		</tr>
		<tr>
			<td valign="top" bgcolor="Silver"><b>Weight:</b></td>
			<td valign="top" bgcolor="Silver"><input type="text" name="weight" size="8" value="<?PHP echo $row[weight] ?>"><br>Important for calculating shipping costs.</td>
		</tr>
		<tr>
			<td><b>Viewable:</b></td>
			<td><input type="checkbox" name="viewable" value="Yes"
			<?PHP
			if ($row[viewable] == "Yes") { print("checked"); }
			?>
			>Uncheck this to hide product.</td>
		</tr>
		<tr>
			<td bgcolor="Silver">&nbsp;</td>
			<td bgcolor="Silver"><input type="submit" name="Submit" value="Update Item">&nbsp;&nbsp;<input type="Reset"></td>
		</tr>
	</table></form>
	<?PHP
	}
}

// ##################### addcoupon #######################
function addcoupon () {
    global $cs;
	?>
	<div align="left"><b><font color="Navy">Add New Coupon</font></b><br><br>
	<form action="admin.php?action=storecoupon" method="post">
	<table width="95%" border="0" cellspacing="2" cellpadding="3">
		<tr>
			<td width="100" valign="top" bgcolor="Silver"><b>Coupon Number:</b></td>
			<td bgcolor="Silver"><input type="text" name="couponid" size="20" value="">
			</td>
		</tr>
		<tr>
			<td width="100" valign="top"><b>Discount:</b></td>
			<td><input type="text" name="discount" value="" size="5"><br>
			For percentage use decimal. (i.e. 50% = .50)
			</td>
		</tr>
		<tr>
			<td width="100" valign="top" bgcolor="Silver"><b>Type:</b></td>
			<td bgcolor="Silver"><input type="radio" name="type" value="D" checked>Dollar Ammount<input type="radio" name="type" value="P">Percentage<br>
			</td>
		</tr>
		<tr>
			<td width="100">&nbsp;</td>
			<td><input type="submit" name="Submit" value="Add New">&nbsp;&nbsp;<input type="Reset"></td>
		</tr>
	</table>
	</div><br>	
	<?PHP
}

// ##################### editcoupon #######################
function editcoupon ($ID) {
	global $DB_site, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."coupons where id='$ID'");
	while ($row=$DB_site->fetch_array($temps)) {
	?>
	<div align="left"><b><font color="Navy">Edit Coupon ID:</font> <?PHP echo $ID ?></b><br><br>
	<form action="admin.php?action=updatecoupon" method="post">
	<input type="hidden" name="id" value="<?PHP echo $ID ?>">
   	<table width="95%" border="0" cellspacing="2" cellpadding="3">
		<tr>
			<td width="100" valign="top" bgcolor="Silver"><b>Coupon Number:</b></td>
			<td bgcolor="Silver"><input type="text" name="couponid" size="20" value="<?PHP echo $row[couponid] ?>"></td>
		</tr>
				<tr>
			<td width="100" valign="top"><b>Discount:</b></td>
			<td><input type="text" name="discount" size="5" value="<?PHP echo $row[discount] ?>">
			</td>
		</tr>
		<tr>
			<td width="100" valign="top" bgcolor="Silver"><b>Type:</b></td>
			<td bgcolor="Silver"><input type="radio" name="type" value="D" <?PHP if ($row[type] == "D") { print("checked"); } ?>>Dollar Ammount<input type="radio" name="type" value="P" <?PHP if ($row[type] == "P") { print("checked"); } ?>>Percentage<br>
			</td>
		</tr>
		<tr>
			<td width="100" valign="top"><b>Sold:</b></td>
			<td><?PHP echo $row[sold] ?></td>
		</tr>
		<tr>
			<td width="100" bgcolor="Silver">&nbsp;</td>
			<td bgcolor="Silver"><input type="submit" name="Submit" value="Continue">&nbsp;&nbsp;<input type="Reset"></td>
		</tr>
	</table>
	</div><br>	
	<?PHP
	}
}

// ##################### addcat #######################
function addcat () {
	?>
	<div align="left"><b><font color="Navy">Add New Category</font></b><br><br>
	<form action="admin.php?action=storecat" method="post">
	<table width="95%" border="0" cellspacing="2" cellpadding="3">
		<tr>
			<td width="100" valign="top" bgcolor="Silver"><b>Title:</b></td>
			<td bgcolor="Silver"><input type="text" name="title" size="30" value="">
			</td>
		</tr>
		<tr>
			<td width="100" valign="top"><b>Short Title:</b></td>
			<td><input type="text" name="stitle" value="" size="20" maxlength="20"><br>(20 Characters or less)
			</td>
		</tr>
		<tr>
			<td width="100" valign="top" bgcolor="Silver"><b>Display Order:</b></td>
			<td bgcolor="Silver"><input type="text" name="displayorder" size="2" value="">
			</td>
		</tr>
		<tr>
			<td width="100">&nbsp;</td>
			<td><input type="submit" name="Submit" value="Add New">&nbsp;&nbsp;<input type="Reset"></td>
		</tr>
	</table>
	</div><br>	
	<?PHP
}

// ##################### editcat #######################
function editcat ($ID) {
	global $DB_site, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."category where categoryid='$ID'");
	while ($row=$DB_site->fetch_array($temps)) {
	?>
	<div align="left"><b><font color="Navy">Edit Category ID:</font> <?PHP echo $ID ?></b><br><br>
	<form action="admin.php?action=updatecat" method="post">
   	<table width="95%" border="0" cellspacing="2" cellpadding="3">
		<tr>
			<td width="100" valign="top" bgcolor="Silver"><b>Category ID:</b></td>
			<td bgcolor="Silver"><?PHP echo $row[categoryid] ?><input type="hidden" name="categoryid" value="<?PHP echo $row[categoryid] ?>"></td>
		</tr>
				<tr>
			<td width="100" valign="top"><b>Title:</b></td>
			<td><input type="text" name="title" size="30" value="<?PHP echo $row[title] ?>">
			</td>
		</tr>
		<tr>
			<td width="100" valign="top" bgcolor="Silver"><b>Short Title:</b></td>
			<td bgcolor="Silver"><input type="text" name="stitle" value="<?PHP echo $row[stitle] ?>" size="20" maxlength="20"><br>(20 Characters or less)
			</td>
		</tr>
		<tr>
			<td width="100" valign="top"><b>Display Order:</b></td>
			<td><input type="text" name="displayorder" size="2" value="<?PHP echo $row[displayorder] ?>">
			</td>
		</tr>
		<tr>
			<td width="100" bgcolor="Silver">&nbsp;</td>
			<td bgcolor="Silver"><input type="submit" name="Submit" value="Continue">&nbsp;&nbsp;<input type="Reset"></td>
		</tr>
	</table>
	</div><br>	
	<?PHP
	}
}

// ##################### addsubcat #######################
function addsubcat () {
    global $DB_site, $dbprefix;
	?>
	<div align="left"><b><font color="Navy">Add New Subcategory</font></b><br><br>
	<form action="admin.php?action=storesubcat" method="post">
	<table width="95%" border="0" cellspacing="2" cellpadding="3">
		<tr>
			<td width="100" valign="top" bgcolor="Silver"><b>Category:</b></td>
			<td bgcolor="Silver">
			<select name="categoryid">
			<?PHP
			$temp1=$DB_site->query("SELECT * FROM ".$dbprefix."category ORDER by title");
			while ($row1=$DB_site->fetch_array($temp1)) {
			     print("<option value=\"$row1[categoryid]\">$row1[title]</option>"); 
			}
			?>
		    </select>
		    </td>
		</tr>
		<tr>
			<td width="100" valign="top"><b>Title:</b></td>
			<td><input type="text" name="title" size="30" value="">
			</td>
		</tr>
		<tr>
			<td width="100" valign="top" bgcolor="Silver"><b>Short Title:</b></td>
			<td bgcolor="Silver"><input type="text" name="stitle" value="" size="20" maxlength="20"><br>(20 Characters or less)
			</td>
		</tr>
		<tr>
			<td width="100" valign="top"><b>Display Order:</b></td>
			<td><input type="text" name="displayorder" size="2" value="">
			</td>
		</tr>
		<tr>
			<td width="100" bgcolor="Silver">&nbsp;</td>
			<td bgcolor="Silver"><input type="submit" name="Submit" value="Add New">&nbsp;&nbsp;<input type="Reset"></td>
		</tr>
	</table>
	</div><br>	
	<?PHP
}

// ##################### editsubcat #######################
function editsubcat ($ID) {
	global $DB_site, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."subcategory where subcategoryid='$ID'");
	while ($row=$DB_site->fetch_array($temps)) {
	?>
	<div align="left"><b><font color="Navy">Edit Subcategory ID:</font> <?PHP echo $ID ?></b><br><br>
	<form action="admin.php?action=updatesubcat" method="post">
   	<table width="95%" border="0" cellspacing="2" cellpadding="3">
	    <tr>
			<td width="100" valign="top" bgcolor="Silver"><b>Category:</b></td>
			<td bgcolor="Silver">
			<select name="categoryid">
			<option value="<?PHP echo $row[categoryid] ?>" selected><?PHP echo getcategoryname($row[categoryid]); ?></option>
			<?PHP
			$temp1=$DB_site->query("SELECT * FROM ".$dbprefix."category ORDER by title");
			while ($row1=$DB_site->fetch_array($temp1)) {
			     print("<option value=\"$row1[categoryid]\">$row1[title]</option>"); 
			}
			?>
		    </select>
		    </td>
		</tr>
		<tr>
			<td width="100" valign="top"><b>Subcategory ID:</b></td>
			<td><?PHP echo $row[subcategoryid] ?><input type="hidden" name="subcategoryid" value="<?PHP echo $row[subcategoryid] ?>"></td>
		</tr>
	    <tr>
			<td width="100" valign="top" bgcolor="Silver"><b>Title:</b></td>
			<td bgcolor="Silver"><input type="text" name="title" size="30" value="<?PHP echo $row[title] ?>">
			</td>
		</tr>
		<tr>
			<td width="100" valign="top"><b>Short Title:</b></td>
			<td><input type="text" name="stitle" value="<?PHP echo $row[stitle] ?>" size="20" maxlength="20"><br>(20 Characters or less)
			</td>
		</tr>
		<tr>
			<td width="100" valign="top" bgcolor="Silver"><b>Display Order:</b></td>
			<td bgcolor="Silver"><input type="text" name="displayorder" size="2" value="<?PHP echo $row[displayorder] ?>">
			</td>
		</tr>
		<tr>
			<td width="100">&nbsp;</td>
			<td><input type="submit" name="Submit" value="Continue">&nbsp;&nbsp;<input type="Reset"></td>
		</tr>
	</table>
	</div><br>	
	<?PHP
	}
}

// ##################### parseitems #######################
function parseitems ($itemstring) {
	$iarray = explode("-", $itemstring);
	return $iarray;
}

// ##################### parsequantity #######################
function parsequantity ($quantitystring) {
	$qarray = explode("-", $quantitystring);
	return $qarray;
}

// ##################### parseoptions #######################
function parseoptions ($quantitystring) {
	$oarray = explode("-", $quantitystring);
	return $oarray;
}

// ##################### viewtransaction #######################
function viewtransaction ($ID) {
	global $DB_site, $cs, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."transaction where orderid='$ID'");
	while ($row=$DB_site->fetch_array($temps)) {
		$iarray = parseitems ($row[items]);
		$qarray = parsequantity ($row[itemquantity]);
		$oarray = parseoptions ($row[options]);
		$ship = number_format($row[shipprice],2,".","");
	?>
	<div align="left"><b><font color="Navy">View Transaction #:</font> <?PHP echo $ID ?></b><br><br>
	<form action="admin.php?action=updatetransaction" method="post">
	<input type="hidden" name="id" value="<?PHP echo $ID ?>">
	<table>
		<tr>
			<td><b>Transaction Status:</b></td>
			<td><select name="status">
			    <option value="<?PHP echo $row[status] ?>"><?PHP echo $row[status] ?>
				<option value="Pending">Pending
				<option value="Denied">Denied
				<option value="Error">Error
				<option value="Awaiting Shipment">Awaiting Shipment
				<option value="Shipped">Shipped
			    </select>
			</td>
		</tr>
		<tr>
			<td valign="top"><b>User:</b></td>
			<td><?PHP viewuser ($row[userid]); ?>
			</td>
		</tr>
		<tr>
			<td valign="top"><b>Items:</b></td>
			<td>
				<table width="100%" border="1" cellspacing="0" cellpadding="5">
					<tr>
						<td><div align="center"><b>Item #:</b></div></td>
						<td><div align="center"><b>Item Name:</b></div></td>
						<td><div align="center"><b>Quantity:</b></div></td>
						<td><div align="center"><b>Options:</b></div></td>
					</tr>
					<?PHP
					$num = count($iarray);
					for ($i = 0 ; $i < $num ; $i++) { 
						$iteminfo = iteminfo ($iarray[$i]);
						print ("
						<tr>
							<td>$iteminfo[num]</td>
							<td>$iteminfo[title]</td>
							<td>$qarray[$i]</td>
							<td>$oarray[$i]</td>
						</tr>
						");
					}
					?>
				</table><br>
				<a href="admin.php?action=package&id=<?PHP echo $ID; ?>" target="_PKGLIST">Print Packing List</a>
			</td>
		</tr>
		<tr>
			<td><b>Transaction Date:</b></td>
			<td><?PHP echo $row[tdate] ?>
			</td>
		</tr>
		<tr>
			<td><b>Ship Method/Price:</b></td>
			<td><?PHP echo $row[shipmethod] ?> - <?PHP echo $cs ?><?PHP echo $ship ?>
			</td>
		</tr>
		<tr>
			<td><b>Coupon Used:</b></td>
			<td><?PHP echo $row[coupon] ?>
			</td>
		</tr>
		<tr>
			<td><b>Total Price:</b></td>
			<td><?PHP echo $cs.$row[total] ?>
			</td>
		</tr>
		<tr>
			<td><b>Payment Method:</b></td>
			<td><?PHP echo $row[method] ?>
			</td>
		</tr>
	</table></div><br>
	<div align="left"><b><font color="Navy">Credit Card/Payment Info:</font></b><br><br>
	<table>
		<tr>
			<td><b>Name On Card:</b></td>
			<td><?PHP echo $row[name_on_card] ?>
			</td>
		</tr>
		<tr>
			<td><b>Card Number:</b></td>
			<td><?PHP echo $row[card_no] ?>
			</td>
		</tr>
		<tr>
			<td><b>Expiration Date:</b></td>
			<td><?PHP echo $row[expir_date] ?>
			</td>
		</tr>
		<tr>
			<td><b>Card Type:</b></td>
			<td><?PHP echo $row[card_type] ?>
			</td>
		</tr>
		<tr>
			<td><b>CVV2:</b></td>
			<td><?PHP echo $row[cvv2] ?>
			</td>
		</tr>
		<tr>
			<td><b>Payment Status:</b></td>
			<td><select name="ccstatus">
			    <option value="<?PHP echo $row[ccstatus] ?>"><?PHP echo $row[ccstatus] ?>
				<option value="Awaiting Payment">Awaiting Payment
				<option value="Payment Received">Payment Received
				<option value="Pending Approval">Pending Approval
				<option value="Declined">Declined
				<option value="Charged">Charged
			    </select>
			</td>
		</tr>
		<tr>
			<td><b>Comments:</b></td>
			<td><?PHP echo $row[comments] ?></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><br><input type="submit" name="Submit" value="Store Changes" class="submit_button">&nbsp;&nbsp;
			<SCRIPT Language="Javascript">  
			var NS = (navigator.appName == "Netscape");
			var VERSION = parseInt(navigator.appVersion);
			if (VERSION > 3) {
			    document.write('<form><input type=button value="Print Transaction" name="Print" onClick="printit()" class="submit_button"></form>');        
			}
			</script>
			</td>
		</tr>
	</table></div><br>	
	<?PHP
	}
}

// ##################### showpl #######################
function showpl ($ID) {
	global $DB_site, $cs, $dbprefix, $companyname, $shopaddress, $shopcity, $shopurl, $shopstate, $shopzip, $shopphone, $shopcountry, $shopfax, $shopemail, $title;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."transaction where orderid='$ID'");
	while ($row=$DB_site->fetch_array($temps)) {
		$iarray = parseitems ($row[items]);
		$qarray = parsequantity ($row[itemquantity]);
		$oarray = parseoptions ($row[options]);
		$ship = number_format($row[shipprice],2,".","");
	?>
	<table width="100%" border="0" cellspacing="0" cellpadding="3">
		<tr>
			<td width="50%" valign="top">				
			    <table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td bgcolor="Black"><font face="Arial" size="4" color="White"><div align="center">PACKING LIST</div></font></td>
					</tr>
					<tr>
						<td valign="top">						
						<b><?PHP echo $companyname; ?></b><br>
						<?PHP echo $shopaddress; ?><br>
						<?PHP echo $shopcity; ?>, <?PHP echo $shopstate; ?> <?PHP echo $shopzip; ?><br>
						<?PHP echo $shopcountry; ?><br>
						</td>
					</tr>
				</table>
			</td>
			<td width="50%" valign="top">
			ORDER NUMBER: <?PHP echo $ID; ?><br> 
			ORDER DATE: <?PHP echo $row[tdate]; ?> 
			</td>
		</tr>
	</table><br>
	<?PHP $userinfo = getuser($row[userid]); ?>
	<table width="100%" border="0" cellspacing="0" cellpadding="3">
		<tr>
			<td width="50%" valign="top">				
			<b>Ship To:</b><br>
			<?PHP echo $userinfo[address_line1]; ?><br>
			<?PHP if ($userinfo[address_line2] != "") { echo $userinfo[address_line2]."<br>"; } ?>
			<?PHP echo $userinfo[city]; ?>, <?PHP echo $userinfo[state]; ?> <?PHP echo $userinfo[zip]; ?><br>
			<?PHP echo $userinfo[country]; ?>   
			</td>
			<td width="50%" valign="top">
			<b>Bill To:</b><br>
			<?PHP if ($userinfo[baddress_line1] != "") { ?>
			<?PHP echo $userinfo[baddress_line1]; ?><br>
			<?PHP if ($userinfo[baddress_line2] != "") { echo $userinfo[baddress_line2]."<br>"; } ?>
			<?PHP echo $userinfo[bcity]; ?>, <?PHP echo $userinfo[bstate]; ?> <?PHP echo $userinfo[bzip]; ?><br>
			<?PHP echo $userinfo[bcountry]; ?> 
			<?PHP } else { ?>
			<?PHP echo $userinfo[address_line1]; ?><br>
			<?PHP if ($userinfo[address_line2] != "") { echo $userinfo[address_line2]."<br>"; } ?>
			<?PHP echo $userinfo[city]; ?>, <?PHP echo $userinfo[state]; ?> <?PHP echo $userinfo[zip]; ?><br>
			<?PHP echo $userinfo[country]; ?>
			<?PHP } ?>
			</td>
		</tr>
	</table><br>
	<b>Your Order:</b><br>
	<table width="100%" border="0" cellspacing="0" cellpadding="2">
		<tr>
			<td><b>Item #:</b></div></td>
			<td><b>Item Name:</b></div></td>
			<td><b>Quantity:</b></div></td>
			<td><b>Options:</b></div></td>
		</tr>
		<?PHP
		$num = count($iarray);
		for ($i = 0 ; $i < $num ; $i++) { 
			$iteminfo = iteminfo ($iarray[$i]);
			print ("
			<tr>
				<td colspan=\"4\"><hr width=\"100%\" size=\"1\" noshade></td>
			</tr>
			<tr>
				<td>$iteminfo[num]</td>
				<td>$iteminfo[title]</td>
				<td>$qarray[$i]</td>
				<td>$oarray[$i]</td>
			</tr>
			");
		}
		?>
		<tr>
			<td colspan="4"><hr width="100%" size="1" noshade></td>
		</tr>
	</table><br>
	Thank you for shopping at <?PHP echo $title; ?><br>
	<?PHP echo $shopurl; ?>
	<?PHP
	}
	exit;
}

// ##################### editspecial #######################
function editspecial ($ID) {
	global $DB_site, $cs, $dbprefix;
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."specials where specialid='$ID'");
	while ($row=$DB_site->fetch_array($temps)) {
	?>
	<div align="left"><b><font color="Navy">Edit Special ID:</font> <?PHP echo $ID; ?></b><br><br>
	<form action="admin.php?action=updatespecial" method="post">
	<table width="95%" border="0" cellspacing="2" cellpadding="3">
		<tr>
			<td bgcolor="Silver" width="100"><b>Special ID:</b></td>
			<td bgcolor="Silver"><?PHP echo $row[specialid]; ?><input type="hidden" name="id" value="<?PHP echo $row[specialid] ?>"></td>
		</tr>
				<tr>
			<td width="100"><b>Product ID:</b></td>
			<td><?PHP echo $row[itemid]; ?><input type="hidden" name="itemid" value="<?PHP echo $row[itemid] ?>">
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="Silver" width="100"><b>Special Description:</b></td>
			<td bgcolor="Silver"><textarea cols="40" rows="8" name="sdescription"><?PHP echo $row[sdescription] ?></textarea>
			</td>
		</tr>
		<tr>
			<td width="100"><b>Special Price:</b></td>
			<td><?PHP echo $cs ?><input type="text" name="sprice" size="5" value="<?PHP echo $row[sprice] ?>">
			</td>
		</tr>
		<tr>
			<td bgcolor="Silver" width="100">&nbsp;</td>
			<td bgcolor="Silver"><input type="submit" name="Submit" value="Continue">&nbsp;&nbsp;<input type="Reset"></td>
		</tr>
	</table>
	</div><br>	
	<?PHP
	}
}

// ##################### addspecial #######################
function addspecial () {
    global $DB_site, $cs, $dbprefix;
	?>
	<div align="left"><b><font color="Navy">Add Special:</font></b><br><br>
	<form action="admin.php?action=storespecial" method="post">
	<table width="95%" border="0" cellspacing="2" cellpadding="3">
	    <tr>
			<td valign="top" bgcolor="Silver" width="110"><b>Choose a product:</b></td>
			<td bgcolor="Silver">
			<?PHP
				print ("<form action=\"admin.php?action=viewitems\" method=\"post\"><select name=\"itemid\">");
				$temp=$DB_site->query("SELECT * FROM ".$dbprefix."items");
				while ($row=$DB_site->fetch_array($temp)) {
					$temp1=$DB_site->query("SELECT * FROM ".$dbprefix."specials where itemid='$row[itemid]'");
				    while ($row1=$DB_site->fetch_array($temp1)) {
	                    $already = 1;
					}
					if ($already == 1) {
					}
					else {
						print("<option value=\"$row[itemid]\">$row[title] - Product #$row[itemid]</option>");
					}
					$already = 0;
				}
				print ("</select><br>Products not listed already have a special associated with them.");
			?>
			</td>
		</tr>
		<tr>
			<td valign="top" width="110"><b>Special Description:</b></td>
			<td><textarea cols="40" rows="8" name="sdescription"></textarea>
			</td>
		</tr>
		<tr>
			<td width="110" bgcolor="Silver"><b>Special Price:</b></td>
			<td bgcolor="Silver"><?PHP echo $cs ?><input type="text" name="sprice" size="5" value="0.00">
			</td>
		</tr>
		<tr>
			<td width="110">&nbsp;</td>
			<td><input type="submit" name="Submit" value="Continue">&nbsp;&nbsp;<input type="Reset"></td>
		</tr>
       </table>
	</div><br>
<?PHP
}

// ##################### deleteeaccount#######################
function deleteaccount($id) {
    global $DB_site, $dbprefix;
	$DB_site->query("DELETE from ".$dbprefix."user where userid='$id'");
	header("Location:admin.php?action=viewusers");
}

// ##################### updateaccount#######################
function updateaccount($username, $password, $password2, $name, $address_line1, $address_line2, $city, $state, $zip, $country, $phone, $email) {
    global $DB_site, $dbprefix, $baddress_line1, $baddress_line2, $bcity, $bstate, $bzip, $bcountry;
	$DB_site->query("UPDATE ".$dbprefix."user set password='".addslashes($password)."', name='".addslashes($name)."', address_line1='".addslashes($address_line1)."', address_line2='".addslashes($address_line2)."', city='".addslashes($city)."', state='".addslashes($state)."', zip='".addslashes($zip)."', country='".addslashes($country)."', baddress_line1='".addslashes($baddress_line1)."', baddress_line2='".addslashes($baddress_line2)."', bcity='".addslashes($bcity)."', bstate='".addslashes($bstate)."', bzip='".addslashes($bzip)."', bcountry='".addslashes($bcountry)."', phone='".addslashes($phone)."', email='".addslashes($email)."' where username='$username'");
	header("Location:admin.php?action=viewusers");
}

// ##################### deletecat#######################
function deletecat($id) {
    global $DB_site, $dbprefix;
	$on = 0;
	$DB_site->query("DELETE from ".$dbprefix."category where categoryid='$id'");
	$DB_site->query("DELETE from ".$dbprefix."subcategory where categoryid='$id'");
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where categoryid='$id'");
	while ($row=$DB_site->fetch_array($temps)) {
		$itemque[$on] = $row[itemid];
		$on++;
	}
	for ($i = 0; $i < $on; $i++) {
	    $item = $itemque[$i];
		$DB_site->query("DELETE from ".$dbprefix."specials where itemid='$item'");
	}
	$DB_site->query("DELETE from ".$dbprefix."items where categoryid='$id'");
	header("Location:admin.php?action=viewcat");
}

// ##################### deletesubcat#######################
function deletesubcat($id) {
    global $DB_site, $dbprefix;
	$on = 0;
	$DB_site->query("DELETE from ".$dbprefix."subcategory where subcategoryid='$id'");
	$temps=$DB_site->query("SELECT * FROM ".$dbprefix."items where subcategoryid='$id'");
	while ($row=$DB_site->fetch_array($temps)) {
		$itemque[$on] = $row[itemid];
		$on++;
	}
	for ($i = 0; $i < $on; $i++) {
	    $item = $itemque[$i];
		$DB_site->query("DELETE from ".$dbprefix."specials where itemid='$item'");
	}
	$DB_site->query("DELETE from ".$dbprefix."items where subcategoryid='$id'");
	header("Location:admin.php?action=viewsubcat");
}

// ##################### deleteitem#######################
function deleteitem($id) {
    global $DB_site, $dbprefix;
	$DB_site->query("DELETE from ".$dbprefix."items where itemid='$id'");
	$DB_site->query("DELETE from ".$dbprefix."specials where itemid='$id'");
	header("Location:admin.php?action=viewitems");
}

// ##################### deletespecial#######################
function deletespecial($id) {
    global $DB_site, $dbprefix;
	$DB_site->query("DELETE from ".$dbprefix."specials where specialid='$id'");
	header("Location:admin.php?action=viewspecials");
}

// ##################### deletetransaction#######################
function deletetransaction($id) {
    global $DB_site, $dbprefix;
	$DB_site->query("DELETE from ".$dbprefix."transaction where orderid='$id'");
	header("Location:admin.php?action=viewtransactions");
}

// ##################### updatecat#######################
function updatecat($categoryid, $title, $stitle, $displayorder) {
    global $DB_site, $dbprefix;
	$DB_site->query("UPDATE ".$dbprefix."category set title='".addslashes($title)."', stitle='".addslashes($stitle)."', displayorder='".addslashes($displayorder)."' where categoryid='$categoryid'");
	header("Location:admin.php?action=viewcat");
}

// ##################### updatesubcat#######################
function updatesubcat($categoryid, $subcategoryid, $title, $stitle, $displayorder) {
    global $DB_site, $dbprefix;
	$DB_site->query("UPDATE ".$dbprefix."subcategory set categoryid='".addslashes($categoryid)."', title='".addslashes($title)."', stitle='".addslashes($stitle)."', displayorder='".addslashes($displayorder)."' where subcategoryid='$subcategoryid'");
	header("Location:admin.php?action=viewsubcat");
}

?>