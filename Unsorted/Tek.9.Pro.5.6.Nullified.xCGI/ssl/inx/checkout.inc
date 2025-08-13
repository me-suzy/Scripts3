<%
req = "<font color=""#990000"">*</font> "
InvalidPrefix = "<img src=""img/arrow_ani.gif"" width=5 height=5 align=middle> <font color=""#FF0000"">"

'' CC Variables
Dim RequireNumber, RequireExpireDate, RequireName
RequireNumber = True
RequireExpireDate = True
RequireName = True

shipping = Request.Form("shipping")

PAY_EXPIRE_M = Request.Form("PAY_EXPIRE_M") 'use this as the flag, seeing if the page was submitted once yet or not
PAY_EXPIRE_Y = Request.Form("PAY_EXPIRE_Y")
PAY_NAME = Request.Form("PAY_NAME")
cctype = Request.Form("PAY_TYPE")
number = Request.Form("PAY_CCNUM")

pay_name_txt = "Exact Name on Card:"
pay_ccnum_txt = "Credit Card Number:"
pay_expires_txt = "Expires:"

email_txt = "Email Address:"
password_txt = "Password:"

if EmailPwdReq = True then
	email_txt = req & email_txt
	password_txt = req & password_txt
end if

bill_fname_txt = req & "First Name:"
bill_lname_txt = req & "Last Name:"
bill_addr1_txt = req & "Address 1:"
bill_addr2_txt = "Address 2:"
bill_city_txt = req & "City or Province:"
bill_state_txt = state_type & ":"
bill_zip_txt = req & "Zip Code:"
bill_phone_txt = req & "Telephone:"

ship_fname_txt = "First Name:"
ship_lname_txt = "Last Name:"
ship_addr1_txt = "Address 1:"
ship_addr2_txt = "Address 2:"
ship_city_txt = "City or Province:"
ship_state_txt = state_type & ":"
ship_zip_txt = "Zip Code:"
ship_phone_txt = "Telephone:"

BILL_FNAME = Request.Form("BILL_FNAME")
BILL_LNAME = Request.Form("BILL_LNAME")
BILL_ADDR1 = Request.Form("BILL_ADDR1")
BILL_ADDR2 = Request.Form("BILL_ADDR2")
BILL_CITY = Request.Form("BILL_CITY")
BILL_STATE = Request.Form("BILL_STATE")
BILL_COUNTRY = Request.Form("BILL_COUNTRY")
BILL_ZIP = Request.Form("BILL_ZIP")
BILL_PHONE = Request.Form("BILL_PHONE")

EMAIL = Request.Form("EMAIL")
PASSWORD = Request.Form("PASSWORD")

If UseMightyZip <> False And BILL_CITY = "" Then
	Set zipObj = Server.CreateObject("MightyZip.ZipLookup")
	If zipObj.DoZipLookup(BILL_ZIP) = True Then
		BILL_CITY = PCase(zipObj.City(1))
		BILL_STATE = zipObj.State(1)
		BILL_COUNTRY = "US"
		billField = "<input type=hidden name=""BILL_CITY"" value="""&BILL_CITY&""">"&_
		"<input type=hidden name=""BILL_STATE"" value="""&BILL_STATE&""">"
	End If
	If BILL_ZIP <> "" Then OverRideBillZip = True
	Set zipObj = Nothing
Else OverRideBillZip = True
End If

SAME = Request.Form("SAME")

If Request.QueryString("just") = "true" Then
	Set rs = cnDZ.Execute("SELECT * FROM tblContacts WHERE EMail = '"&Session("USERNAME")&"' AND Password = '"&Session("PASSWORD")&"';")
	BILL_FNAME = rs("FNAME")
	BILL_LNAME = rs("LNAME")
	BILL_ADDR1 = rs("ADDRESS1")
	BILL_ADDR2 = rs("ADDRESS2")
	BILL_CITY = rs("CITY")
	BILL_STATE = rs("STATE")
	BILL_COUNTRY = rs("COUNTRY")
	BILL_PHONE = rs("PHONE")
	BILL_ZIP = rs("ZIP")
	EMAIL = rs("EMAIL")
	PASSWORD = rs("PASSWORD") 'added to prevent step2 problem
	SAME = "on"
	OverRideBillZip = True	
	rs.Close
	Set rs = Nothing
End If

If IsLoggedIn = True Then
	EMAIL = Session("Username")
	PASSWORD = Session("Password")
End If

If SAME = "on" Then 'use the billing info instead:
	SHIP_FNAME = Request.Form("BILL_FNAME")
	SHIP_LNAME = Request.Form("BILL_LNAME")
	SHIP_ADDR1 = Request.Form("BILL_ADDR1")
	SHIP_ADDR2 = Request.Form("BILL_ADDR2")
	SHIP_CITY = Request.Form("BILL_CITY")
	SHIP_STATE = Request.Form("BILL_STATE")
	SHIP_COUNTRY = Request.Form("BILL_COUNTRY")
	SHIP_ZIP = Request.Form("BILL_ZIP")
	SHIP_PHONE = Request.Form("BILL_PHONE")
Else
	SHIP_FNAME = Request.Form("SHIP_FNAME")
	SHIP_LNAME = Request.Form("SHIP_LNAME")
	SHIP_ADDR2 = Request.Form("SHIP_ADDR2")
	SHIP_CITY = Request.Form("SHIP_CITY")
	SHIP_ADDR1 = Request.Form("SHIP_ADDR1")
	SHIP_STATE = Request.Form("SHIP_STATE")
	SHIP_COUNTRY = Request.Form("SHIP_COUNTRY")
	SHIP_ZIP = Request.Form("SHIP_ZIP")
	SHIP_PHONE = Request.Form("SHIP_PHONE")
	If UseMightyZip And Ship_CITY = "" Then
		Set zipObj = Server.CreateObject("MightyZip.ZipLookup")
		If zipObj.DoZipLookup(SHIP_ZIP) = True Then
			SHIP_CITY = PCase(zipObj.City(1))
			SHIP_STATE = zipObj.State(1)
			SHIP_COUNTRY = "US"
			shipField = "<input type=hidden name=""SHIP_CITY"" value="""&SHIP_CITY&""">"&_
			"<input type=hidden name=""SHIP_STATE"" value="""&SHIP_STATE&""">"
		End If
		If SHIP_ZIP <> "" Then OverRideShipZip = True
		Set zipObj = Nothing
	Else OverRideShipZip = True
	End If
End If

If PAY_EXPIRE_M <> "" Then 'PAY_EXPIRE_M is just used as the flag(whether the page has been submitted once yet)
	'Validate Payment info:
	Set rs = Server.CreateObject("ADODB.Recordset")
	rs.Open "Select * from tblCardTypes Where ABBREV = '" & cctype & "'", cnDZ, adOpenKeyset, adLockOptimistic
	RequireNumber = IIF(rs("REQUIRE_NUMBER") = -1, True, False)
	RequireExpireDate = IIF(rs("REQUIRE_EXPIREDATE") = -1, True, False)
	RequireName = IIF(rs("REQUIRE_NAME") = -1, True, False)
	flags = False
	If RequireNumber Then
		chk = checkcc(number,cctype)
		Select Case chk
			Case 0, 4
				pay_ccnum_txt = "Card number looks ok!"
				flags = True
			Case 1:	 pay_ccnum_txt = InvalidPrefix & "Wrong card type</font>"
			Case 2:	 pay_ccnum_txt = InvalidPrefix & "Wrong number length</font>"
			Case 3:	 pay_ccnum_txt = InvalidPrefix & "Wrong length &amp; card type</font>"
			Case 5:	 pay_ccnum_txt = InvalidPrefix & "Wrong value for card type</font>"
			Case 6:	 pay_ccnum_txt = InvalidPrefix & "Wrong number length</font>"
			Case 7:	 pay_ccnum_txt = InvalidPrefix & "Wrong val &amp; length for card type</font>"
			Case 8:	 pay_ccnum_txt = InvalidPrefix & "Unknown cardtype</font>"
			Case 9
				pay_ccnum_txt = "Number looks ok"
				flags = True
			Case Else: pay_ccnum_txt = InvalidPrefix & "Bad Card Number</font>"
		End Select
	Else flags = True
	End If
	If RequireExpireDate Then
		If CInt(PAY_EXPIRE_M) < Month(Now) And CInt(PAY_EXPIRE_Y) <= Year(Now) Then
			pay_expires_txt = InvalidPrefix & "Expire Date is in past</font>"
			flags = False
		End If
	End If
	If RequireName Then
		flags = flags And ValidateField (PAY_NAME, 1, "EXACT Name on Card:", pay_name_txt)
	End If
	flags_P = flags	
	'Validate Billing info:
	flags = ValidateField (BILL_FNAME, 1, "Enter a VALID First Name:", bill_fname_txt)
	flags = flags And ValidateField (BILL_LNAME, 1, "Enter a VALID Last Name:", bill_lname_txt)
	flags = flags And ValidateField (BILL_ADDR1, 2, "Enter a VALID Address:", bill_addr1_txt)
	flags = flags And ValidateField (BILL_CITY, 2, "VALID City or Province:", bill_city_txt)
	'this next line WAS commented out:
	flags = flags And ValidateField (BILL_ZIP, 1, "Enter a VALID Zip Code:", bill_zip_txt)
	flags = flags And ValidateField (BILL_PHONE, 1, "Enter a VALID Telephone:", bill_phone_txt)
	flags = flags And ValidatePhone (BILL_PHONE, "Enter a VALID Telephone:", bill_phone_txt)
	flags_B = flags
	If SAME = "on" Then
		flags_S = True
	Else 'Validate Shipping info:
		flags = ValidateField (SHIP_FNAME, 1, "Enter a VALID First Name:", ship_fname_txt)
		flags = flags And ValidateField (SHIP_LNAME, 1, "Enter a VALID Last Name:", ship_lname_txt)
		flags = flags And ValidateField (SHIP_ADDR1, 2, "Enter a VALID Address:", ship_addr1_txt)
		flags = flags And ValidateField (SHIP_CITY, 2, "VALID City or Province:", ship_city_txt)
		'this next line WAS commented out:
		flags = flags And ValidateField (SHIP_ZIP, 1, "Enter a VALID Zip Code:", ship_zip_txt)
		flags = flags And ValidateField (SHIP_PHONE, 1, "Enter a VALID Telephone:", ship_phone_txt)
		flags = flags And ValidatePhone (SHIP_PHONE, "Enter a VALID Telephone:", ship_phone_txt)
		flags_S = flags
	End If
	totalFlags = flags_B And flags_S And flags_P
	If (EMAIL <> "" Or EmailPwdReq <> False) And Not IsLoggedIn Then
		If ValidEmailAddress(EMAIL) = False Then
			email_txt = InvalidPrefix & "Enter a VALID email address</font>"
			totalFlags = False
		End If
		If Len(PASSWORD) < 3 Then
			password_txt = InvalidPrefix & "Enter a longer password</font>"
			totalFlags = False
		End If
	End If
Else
	If BILL_COUNTRY = "" Then BILL_COUNTRY = "US"
	If SHIP_COUNTRY = "" Then SHIP_COUNTRY = "US"
End If
'If all the info is good, then let's jump to step3:
If totalFlags = True And lcase(pg) = "cart" And lcase(mode) = "step2" And lcase(Request.QueryString("revalidate")) <> "true" Then
	mode = "step3"
End If
If totalFlags = False And lcase(mode) = "step3" Then
	mode = "step2"
End If
Function trimtodigits(tstring)
	s = ""
	ts = tstring
	For x = 1 To Len(ts)
		ch = Mid(ts,x,1)
		If Asc(ch) >= 48 And Asc(ch) <= 57 Then s = s & ch
	Next
	trimtodigits = s
End Function
Function checkcc(ccnumber,cctype)
	ctype = UCase(cctype)
	Select Case ctype
	Case "VS"							'Visa
		cclength = "13;16"
		ccprefix = "4"
	Case "MC"							'Mastercard / Eurocard
		cclength = "16"
		ccprefix = "51;52;53;54;55"
	Case "AX"							'American Express
		cclength = "15"
		ccprefix = "34;37"
	Case "DS"							'Discover
		cclength = "16"
		ccprefix = "6011"
	Case "DN"							'Diners Club
		cclength = "14"
		ccprefix = "300;301;302;303;304;305;36;38"
	Case "CB"							'Carte Blanche
		cclength = "14"
		ccprefix = "300;301;302;303;304;305;36;38"
	Case "JC"							'JCB
		cclength = "15;16"
		ccprefix = "3;2131;1800"
	Case Else							'Delta(DT), Switch(SW), Solo(SO), ATM/DirectDebit(DD), enRoute(EN)
		'' for anything else, just check to see if the length is greater than 0
		If Len(ccnumber) > 0 Then
			checkcc = 9
			Exit Function
		Else
			checkcc = 2
			Exit Function
		End If
	End Select
	prefixes = Split(ccprefix,";",-1)
	lengths = Split(cclength,";",-1)
	number = trimtodigits(ccnumber)
	prefixvalid = False
	lengthvalid = False
	For Each prefix In prefixes
		If InStr(number,prefix) = 1 Then prefixvalid = True
	Next
	For Each length In lengths
		If Cstr(Len(number)) = length Then lengthvalid = True
	Next
	If cType = "PP" Then prefixvalid = True: lengthvalid = true
	result = 0
	If Not prefixvalid Then result = result Or 1
	If Not lengthvalid Then result = result Or 2
	qsum = 0
	For x = 1 To Len(number)
		ch = Mid(number,Len(number)-x+1,1)
		If x Mod 2 = 0 Then
			sum = 2 * CInt(ch)
			qsum = qsum + (sum Mod 10)
			If sum > 9 Then qsum = qsum + 1
		Else qsum = qsum + CInt(ch)
		End If
	Next
	If qsum Mod 10 <> 0 Then result = result Or 4
	If cclength = "" Then result = result Or 8
	checkcc = result
End Function
Function ValidateField(FieldContents, MinLength, ErrorString, txt)	
	If FieldContents <> "" And Len(FieldContents) > MinLength Then
		ValidateField = True
	Else
		ValidateField = False
		txt = InvalidPrefix & ""&ErrorString&"</font>"
	End If
End Function
Function ValidatePhone(FieldContents, ErrorString, txt)
	valid = True
	For i = 1 To Len(FieldContents)
		ch = Mid(FieldContents, i, 1)
		valid = valid And ((ch >= "0" And ch <= "9") Or ch = "(" Or ch = ")" Or ch = "-" Or ch = "+" Or ch = " " Or ch = ".")
	Next
	If valid = False Then txt = InvalidPrefix & ""&ErrorString&"</font>"
	ValidatePhone = valid
End Function
Function CreateOrderTextField (txt, name, value, required, maxlength, vcardval, pwd)
	Dim br
	If Trim(txt) = "" Then br = "" Else br = "<br>"
	If pwd = True Then fieldtype = "password" Else fieldtype = "text"
	If vcardval <> "" Then shovcardval = " VCARD_NAME="""&vcardval&"""" Else shovcardval = ""
	If mode = "step2" Then
		CreateOrderTextField = "<td><font"&fc&" size=1>"&txt&"</font>"&br&_
		"<input type="&fieldtype&" name="""&name&""" value="""&value&""""&shovcardval&" size=26 maxlength="&maxlength&"></td>"
	ElseIf mode = "step3" Then
		If pwd = True Then
			For i = 1 To Len(PASSWORD)
				writeStar = writeStar & "*"
			Next
			value = writeStar
		Else value = value
		End If
		CreateOrderTextField = "<td valign=top><font"&fc&" size=1>"&Replace(txt, req, "")&_
		"</font>"&br&"<font"&fc&" size=2><b>"&value&"</td>"
	End If
End Function
Function WriteOrderTextField (txt, name, value, required, maxlength, vcardval, pwd)
	Response.Write CreateOrderTextField(txt, name, value, required, maxlength, vcardval, pwd)
End Function
Function DisplayFreeShipping
	Dim Sel
	If shipping = "free" Or shipping = "" Then
		Sel	= " SELECTED"
		shipping = "free"
		selShipCost = 0
	End If
	Response.Write"<option"&Sel&" value=free>Free Ground Shipping!</option>"
End Function
Function DisplayUPSOptions
	On Error Resume Next
	Set UPSCalc = Server.CreateObject("UPSCalcPro.Calc")
	'UPSCalc.UPSServer = "153.2.228.50"	'this line was added temporarily when their ups server (upscalcpro) crashed..!
	UPSCalc.Weight = totalWeight
	UPSCalc.FromPostal = Left(zip,5) 'we use Left() here cuz UPS won't recognize the -nnnn part of a zipcode.
	If SAME = "on" Then UPSCalc.ToPostal = Left(BILL_ZIP,5) Else UPSCalc.ToPostal = Left(SHIP_ZIP,5)
	UPSCalc.ToCountry = "US" 'if other than US, (such as CA(Canada)), we don't need the ToPostal?
	If Err.Number <> 0 Then
		Response.Write"<option value=FLAT>UPSCalc Not Installed</option><option value=FLAT>Defaulting to $"&FlatRateShipping&" Basic Freight</option>"
		selShipCost = FlatRateShipping 'if the upscalcpro component dies for some reason, lets ship gndres and charge them flat rate
	ElseIf UPSCalc.PriceMultipleServices Then
		For Each Service In UPSCalc.UPSProducts
			If shipping = Service.Product or shipping = "" Then 
				Sel = " SELECTED" 
				shipping = Service.Product
			Else Sel = ""
			End If
			If Sel <> "" Then
				shipping = Service.Product
				selShipCost = Service.TotalCharge
			End If
			showShipping = True
			If Service.Product = "GNDRES" and FreeShipping = False Then
				serviceName = "UPS Ground Residential"
			ElseIf Service.Product = "GNDCOM" and FreeShipping = False Then serviceName = "UPS Ground Commercial"
			ElseIf Service.Product = "3DS" Then	serviceName = "UPS 3 Day"
			ElseIf Service.Product = "2DA" Then serviceName = "UPS 2 Day"
			ElseIf Service.Product = "1DA" Then serviceName = "UPS Next Day"
			Else
				serviceName = Service.Product
				showShipping = False
			End If
			If showShipping = True Then
				Response.Write"<option value="&Service.Product&""&Sel&">"&serviceName&" = "&FormatCurrency(Service.TotalCharge)&"</option>"
				'' If shipping hasn't been selected yet, pick the first one:
				If shipping = "" Then shipping = Service.Product
				If selShipCost = "" Then selShipCost = Service.TotalCharge
			End If
		Next
	Else
		Response.Write"<option value=FLAT>Shipping zipcode is invalid or,</option><option value=FLAT>UPS server is offline.</option><option value=FLAT>Defaulting to $" & FlatRateShipping & " Basic Freight..</option>"
		selShipCost = FlatRateShipping 'if upscalcpro dies for some reason, lets charge them flat rate
	End If
End Function
Function DisplayPostalOptions 'this function gets called on the tek9.asp checkout page. it displays the avail shipping <option>'s
	GetPostalOption "USPS_Priority","USPS Priority","Priority"
	GetPostalOption "USPS_Express","USPS Express Shipping","Express"
	'GetPostalOption "USPS_Media","USPS Media Mail","Media"
	'GetPostalOption "USPS_Parcel","USPS Parcel Post","Parcel"
	'GetPostalOption "USPS_BPM","USPS Bound Printed Matter","BPM"
	'GetPostalOption "USPS_Library","USPS Library Mail","Library"
End Function
Function GetPostalOption(Value, Name, Service)
	'Value is the form <option value=????...
	'Name is what actually displays in the checkout screen dropdown box
	'Service is what is actually sent to usps in the request
	Dim rs, Postage
	Postage = 1
	Set xmlDoc = Server.CreateObject("MSXML.DOMDocument")
	Set RequestLevel = xmlDoc.createElement("RateRequest")
	RequestLevel.setAttribute "USERID", uspsuserid 'see global.inc for these 2 vars
	RequestLevel.setAttribute "PASSWORD", uspspassword
	Set rs = cnDZ.Execute ("SELECT DistinctRow ItemID, Quantity, ItemName, Weight FROM qCart WHERE CustID = " & cid)
	i = 0
	Do Until rs.EOF
		If rs("Weight") > 0 Then
			For j = 1 To rs("Quantity")
				Set PackageLevel = xmlDoc.createElement("Package")
				PackageLevel.setAttribute "ID", i
				i = i + 1
				Set PackageElementLevel = xmlDoc.createElement("Service")
				Set t = xmlDoc.createTextNode(Service)
				PackageElementLevel.appendChild (t)
				Call PackageLevel.appendChild(PackageElementLevel)
				Set PackageElementLevel = xmlDoc.createElement("ZipOrigination")
				Set t = xmlDoc.createTextNode(Left(ZIP, 5))
				PackageElementLevel.appendChild (t)
				Call PackageLevel.appendChild(PackageElementLevel)
				Set PackageElementLevel = xmlDoc.createElement("ZipDestination")
				Set t = xmlDoc.createTextNode(SHIP_ZIP)
				PackageElementLevel.appendChild (t)
				Call PackageLevel.appendChild(PackageElementLevel)
				Set PackageElementLevel = xmlDoc.createElement("Pounds")
				Set t = xmlDoc.createTextNode(CInt(rs("WEIGHT")))
				PackageElementLevel.appendChild (t)
				Call PackageLevel.appendChild(PackageElementLevel)
				Set PackageElementLevel = xmlDoc.createElement("Ounces")
				Set t = xmlDoc.createTextNode(CInt((rs("WEIGHT") - CInt(rs("WEIGHT"))) * 16))
				PackageElementLevel.appendChild (t)
				Call PackageLevel.appendChild(PackageElementLevel)
				Set PackageElementLevel = xmlDoc.createElement("Container")
				Set t = xmlDoc.createTextNode("None") 'see Domestic-Rates.pdf at uspswebtools.com, page 8-9
				PackageElementLevel.appendChild (t)
				Call PackageLevel.appendChild(PackageElementLevel)
				Set PackageElementLevel = xmlDoc.createElement("Size")
				Set t = xmlDoc.createTextNode("Regular") 'see Domestic-Rates.pdf at uspswebtools.com, page 8-9
				PackageElementLevel.appendChild (t)
				Call PackageLevel.appendChild(PackageElementLevel)
				Set PackageElementLevel = xmlDoc.createElement("Machinable")
				Set t = xmlDoc.createTextNode("True") 'make False if the package Size is Oversized only
				PackageElementLevel.appendChild (t)
				Call PackageLevel.appendChild(PackageElementLevel)
				Call RequestLevel.appendChild(PackageLevel)
			Next
		End If
		rs.MoveNext
	Loop
	Call xmlDoc.appendChild(RequestLevel)
	Set httpconn = server.createobject("httpcom.chttpcom")
	srvr = "production.shippingapis.com" 'You must modify the Server name
	port = 80
	path = "/ShippingAPI.dll" 'You must modify the Path name
	query = ""
	msg = "API=Rate&XML=" & Server.URLEncode(xmldoc.xml)
	'You must modify the API name to use other APIs
	contentType = "application/x-www-form-urlencoded"
	proxyServer = "" 'You must modify proxy server or leave blank if no proxy
	'proxyPort = 8080 'Modify the proxy port, if necessary. Leave blank if no proxy.
	Dim resp
	resp = ""
	On Error Resume Next
	respcode = httpconn.GetResponse(srvr, port, path, query, msg, contentType, proxyServer, proxyPort, resp)
	If err.number <> 0 Then
		Response.Write("Error:<br><table cols=2 border=1><tr><td>Err.Number</td><td>" & _
			err.number & "</td></tr><td>Err.Source</td><td>" & _
			err.source & "</td></tr><td>Err.Description</td><td>" & _
			err.description & "</td></tr></table>")
		Response.Write("</body></html>")
		Response.Write("HTML Error:<br>" & resp)
	End If
	Set xmlDoc = Nothing
	Set httpconn = Nothing
	Set xmlDoc = Server.CreateObject("MSXML.DOMDocument")
	xmlDoc.validateOnParse = False
	xmlDoc.loadXML (resp) 'Response
	If xmlDoc.documentElement.nodeName = "Error" Then 'Top-level Error
		Set nodeList = xmlDoc.getElementsByTagName("Error")
		Set n = nodeList.Item(0)
		For i = 0 To n.childNodes.length - 1
			Set e = n.childNodes.Item(i)
			Select Case e.nodeName
			Case "Source"
			Case "Number"
			Case "Description"
				'Response.Write("Top Node Error: " & e.firstChild.nodeValue)
			Case "HelpFile"
			Case "HelpContext"
			End Select
		Next
	Else 'no Top-level Error
		Set nodeList = xmlDoc.getElementsByTagName("Package")
		For i = 0 To nodeList.length - 1 'this line shows the service price
			Set n = nodeList.Item(i)
			For j = 0 To n.childNodes.length - 1
				Set e = n.childNodes.Item(j)
				If e.nodeName = "Error" Then 'Lower-level error
					For k = 0 To e.childNodes.length - 1
						Set t = e.childNodes.Item(k)
						Select Case t.nodeName
						Case "Source"
						Case "Number"
						Case "Description"
							'Response.Write("Lower Node Error: " & t.firstChild.nodeValue)
						Case "HelpFile"
						Case "HelpContext"
						End Select
					Next
				ElseIf e.nodeName = "Postage" Then
					Postage = Postage + CDbl(e.firstChild.nodeValue)
					Postage = Postage - 1
				End If
			Next
		Next
	End If
	If Postage > 0 Then
		If shipping = value Or shipping = "" Then
			Sel = " Selected"
			shipping = value
			selShipCost = Postage
		End If
		Response.Write"<option"&Sel&" value="""&value&""">"&Name&" = "&FormatCurrency(Postage)&"</option>"
	End If
End Function
Function DisplayFedexOptions
	Dim fedex
	Set fedex = Server.CreateObject("MNCustom.FedEx")
    fedex.CompanyCountry = country_code
    fedex.CompanyState = state_code
    fedex.CompanyZip = zip
    fedex.DestCountry = SHIP_COUNTRY
    fedex.DestState = SHIP_STATE
    fedex.DestZip = SHIP_ZIP
    fedex.FedexAcctNumber = FEDEX_ACCT
    fedex.MeterNumber = FEDEX_METER
    fedex.PackageCount = 1
    fedex.ShippingWeight = totalWeight
    fedex.Units = "LBS"
    fedex.Subtotal = subtotal
    fedex.RequestRate
	If shipping = "Fedex" Or shipping = "" Then
		Sel = " Selected"
		shipping = "Fedex"
		selShipCost = fedex.NetCharge
	End If
	Response.Write "<option value=""Fedex""" & Sel & ">Fedex = " & FormatCurrency(fedex.NetCharge) & "</option>"
End Function
Function DisplayWillCallOptions
	If shipping = "WillCall" Or shipping = "" Then
		Sel = " Selected"
		shipping = "WillCall"
		selShipCost = 0
	End If
	Response.Write"<option value=WillCall "&Sel&">Will Call (FREE)</option>"
End Function
%>

