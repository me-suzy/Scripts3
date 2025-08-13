<!-- #include file="database_connect.inc" -->
<!-- #include file="style.inc" -->
<%
'' This line needed for older server versions like IIS 4.0 and PWS
Response.Buffer = True

Const adEditAdd = 2
Const adCmdText = 1
Const adCmdTable = 2
Const adOpenForwardOnly = 0
Const adOpenKeyset = 1
Const adOpenDynamic = 2
Const adOpenStatic = 3
Const adUseClient = 3
Const adLockPessimistic = 2
Const adLockOptimistic = 3
Const maxRecords = 200
'On Error Resume Next

'handlingCost must be initialized outside of displayCart function
handlingCost = 0

Dim ValidCoupon
Dim srchfldsize, bidfldsize, bidtxtareasize

Set cnDZ = Server.CreateObject("ADODB.Connection")
cnDZ.Open DSNString

If Request.Cookies("Username") <> "" Then
	If Session("Username") = "" Then
		Set rs = cnDZ.Execute("SELECT EMail, Password FROM tblContacts WHERE EMail = '"&Request.Cookies("Username")&"';")
		If Not rs.EOF Then
			Session("Username") = rs("EMail")
			Session("Password") = rs("PASSWORD")
		End If
		rs.Close
		Set rs = Nothing
	End If
End If

'--Begin master page settings here:
pg = Request.QueryString("pg")
If pg = "" Then pg = Request.Form("pg")
If pg = "" Then pg = "default"
Set rsPg = cnDZ.Execute("SELECT * FROM TBLPAGES WHERE PG = '"&pg&"';")

If rsPg.EOF Then
	'If Not(NoRedirection = True) then Response.Redirect"tek9.asp"
	Response.Redirect"tek9.asp"
Else
	meta_author = rsPg("META_AUTHOR")
	meta_descrip = rsPg("META_DESCRIPTION")
	kwds = rsPg("META_KEYWORDS")
	pg_title = rsPg("PG_TITLE")
	If Trim("X"&rsPg("PG_HEADER")) <> "X" Then pg_header = rsPg("PG_HEADER") Else pg_header = ""
	pc = rsPg("PG_CONTENT")
	al1 = rsPg("ALN1")
	If al1 = "center" Then
		c1 = "<center>"
		c11 = "</center>"
	End If
	If Trim("X"&rsPg("IMG1")) <> "X" Then img1 = ""&c1&"<img src=""img/"&rsPg("IMG1")&""" align="&al1&" border=0>"&c11&""
	txt2 = rsPg("TXT2")
	al2 = rsPg("ALN2")
	If al2 = "center" Then
		c2 = "<center>"
		c22 = "</center>"
	End If
	If Trim("X"&rsPg("IMG2")) <> "X" Then img2 = ""&c2&"<img src=""img/"&rsPg("IMG2")&""" align="&al2&" border=0>"&c22&""
	txt3 = rsPg("TXT3")
	al3 = rsPg("ALN3")
	If al3 = "center" Then
		c3 = "<center>"
		c33 = "</center>"
	End If
	If Trim("X"&rsPg("IMG3")) <> "X" Then img3 = ""&c3&"<img src=""img/"&rsPg("IMG3")&""" align="&al3&" border=0>"&c33&""
	txt4 = rsPg("TXT4")
	al4 = rsPg("ALN4")
	If al4 = "center" Then
		c4 = "<center>"
		c44 = "</center>"
	End If
	If Trim("X"&rsPg("IMG4")) <> "X" Then img4 = ""&c4&"<img src=""img/"&rsPg("IMG4")&""" align="&al4&" border=0>"&c44&""

	'--Begin Global settings and company info:
	Set rs = cnDZ.Execute("SELECT * FROM TBLGLOBAL;")
	If Not rs.EOF Then
		If Trim("X"&rs("COMPANY_NAME")) <> "X" Then company_name = rs("COMPANY_NAME")
		If Trim("X"&rs("ADDRESS1")) <> "X" Then address1 = rs("ADDRESS1")
		If Trim("X"&rs("ADDRESS2")) <> "X" Then address2 = rs("ADDRESS2")
		If Trim("X"&rs("CITY")) <> "X" Then city = rs("CITY")
		state_code = rs("STATE_CODE")
		If Trim("X"&rs("ZIP")) <> "X" Then zip = rs("ZIP")
		Set rs2 = cnDZ.Execute("SELECT CODE FROM TBLCOUNTRIES WHERE CODE = '"&rs("COUNTRY_CODE")&"';")
		If Not rs2.EOF Then
			country_code = rs2("CODE")
			rs2.Close
			Set rs2 = Nothing
		End If
		If Trim("X"&rs("PHONE1")) = "X" Then phone1 = "" Else phone1 = rs("PHONE1")
		If Trim("X"&rs("PHONE2")) = "X" Then phone2 = "" Else phone2 = rs("PHONE2")
		If Trim("X"&rs("BIZHOURS")) = "X" Then bizhours = "" Else bizhours = " <font size=1>("&rs("BIZHOURS")&")</font>"
		If Trim("X"&rs("FAX")) = "X" Then fax = "" Else fax = rs("FAX")
		If Trim("X"&rs("MOBILE")) = "X" Then mobile = "" Else mobile = rs("MOBILE")
		If Trim("X"&rs("EMAIL1")) = "X" Then email1 = "" Else email1 = Server.HTMLEncode(rs("EMAIL1"))
		state_type = rs("STATE_TYPE")
		If Trim("X"&rs("STATE_TYPE")) = "X" Then state_type = "" Else state_type = Server.HTMLEncode(rs("STATE_TYPE"))
		developer1_name = rs("DEVELOPER1_NAME")
		If Trim("X"&rs("DEVELOPER1_EMAIL")) = "X" Then developer1_email = "" Else developer1_email = Server.HTMLEncode(rs("DEVELOPER1_EMAIL"))
		developer2_name = rs("DEVELOPER2_NAME")
		If Trim("X"&rs("DEVELOPER2_EMAIL")) = "X" Then developer2_email = "" Else developer2_email = Server.HTMLEncode(rs("DEVELOPER2_EMAIL"))
		developer3_name = rs("DEVELOPER3_NAME")
		If Trim("X"&rs("DEVELOPER3_EMAIL")) = "X" Then developer3_email = "" Else developer3_email = Server.HTMLEncode(rs("DEVELOPER3_EMAIL"))
		mailhost = rs("MAILHOST")
		If rs("SSL_ENABLED") = -1 Then protocol = "https" Else protocol = "http"

		'If you have to use a separate, secure server url, enable ('un-comment) the 4 lines below:

		'If protocol = "https" Then
			'delete this comment line if you like, but the line below needs to be modified to YOUR secured domain. (NO https stuff!)
		'	If Trim("X"&rs("HOMEPAGE_URL")) = "X" Then homepage_url = "localhost" Else homepage_url = "secured.yourdomain.com"
		'Else
			If Trim("X"&rs("HOMEPAGE_URL")) = "X" Then homepage_url = "localhost" Else homepage_url = rs("HOMEPAGE_URL")
		'End If
		
		cart_step1_text = rs("CART_STEP1_TEXT")
		cart_step1_ssltext = rs("CART_STEP1_SSLTEXT")
		cart_checklogin_text = rs("CART_CHECKLOGIN_TEXT")
		cart_step23_text = rs("CART_STEP23_TEXT")
		EmailPwdReq = rs("EMAILPWD_REQ")
		PaypalAccount = rs("PAYPAL_ACCOUNT")
		CybercashMerchantConfFilepath = rs("CYBERCASH_MCF")
		PayflowPartner = rs("PAYFLOW_PARTNER")
		PayflowVendor = rs("PAYFLOW_VENDOR")
		PayflowUser = rs("PAYFLOW_USER")
		PayflowPassword = rs("PAYFLOW_PASSWORD")
		PayflowThreshold = rs("PAYFLOW_FRAUDTHRESHOLD")
		UseCybercash = rs("USE_CYBERCASH")
		UseFraudpatrol = rs("USE_FRAUDPATROL")
		UseAuthorizenet = rs("USE_AUTHORIZENET")
		UsePaypal = rs("USE_PAYPAL")
		UsePayflow = rs("USE_PAYFLOW")
		UseMightyZip = rs("USE_MIGHTYZIP")
		UseUPS = rs("USE_UPS")
		UseUSPS = rs("USE_USPS")
		uspsuserid = rs("USPS_USERID")
		uspspassword = rs("USPS_PASSWORD")
		UseFedex = rs("USE_FEDEX")
		FEDEX_ACCT = rs("FEDEX_ACCT")
		FEDEX_METER = rs("FEDEX_METER")
		UseWillCall = rs("USE_WILLCALL")
		UseASPMail = rs("USE_ASPMAIL")
		If UseMightyZip Then UseMightyZip = TestForObject("MightyZip.ZipLookup")
		If UseASPMail Then UseASPMail = TestForObject("SMTPsvg.Mailer")
		OrderAlert = rs("ORDER_ALERT") 'sends an email from placeorder page to site admin, notifying them an order has been placed
		FlatRateShipping = rs("FLAT_SHIPPING")
		FreeShipping = rs("FREE_SHIPPING") 'make ground basic freight free?
		If FreeShipping Then FlatRateShipping = 0
		ShowQIS = rs("SHOW_QIS") 'show the Quantity In Stock on product pages?
		AllowBOPO = rs("ALLOW_BO_PO") 'allow the ability to add an item to cart if QIS <= 0?
		If rsPg("BYPASS_DEFAULTS") = 0 Then
			pg_bgcolor = rs("PG_BACKGROUNDCOLOR")
			If Trim("X"&rs("PG_BACKGROUND")) <> "X" Then pg_background = " background=""img/"&rs("PG_BACKGROUND")&""""
			text_Color = rs("TEXT_COLOR")
			hyperlink_Color = rs("HYPERLINK_COLOR")
			hoverlink_Color = rs("HOVERLINK_COLOR")
			activelink_Color = rs("ACTIVELINK_COLOR")
			visitedlink_color = rs("VISITEDLINK_COLOR")
			clr1 = rs("ALT_COLOR1")
			clr2 = rs("ALT_COLOR2")
		Else
			pg_bgcolor = rsPg("PG_BACKGROUNDCOLOR")
			If Trim("X"&rsPg("PG_BACKGROUND")) <> "X" Then pg_background = " background=""img/"&rsPg("PG_BACKGROUND")&""""
			text_Color = rsPg("TEXT_COLOR")
			hyperlink_Color = rsPg("HYPERLINK_COLOR")
			hoverlink_Color = rsPg("HOVERLINK_COLOR")
			activelink_Color = rsPg("ACTIVELINK_COLOR")
			visitedlink_color = rsPg("VISITEDLINK_COLOR")
			clr1 = rsPg("ALT_COLOR1")
			clr2 = rsPg("ALT_COLOR2")
		End If
		rs.Close
		Set rs = Nothing
	End If
	'--End Global settings and company info.

End If
rsPg.Close
Set rsPg = Nothing
'--End master page settings.

Function Excerpt(inputString, minLength, maxLength)
	inputString = ""&inputString
	If Trim(inputString) <> "" Then
		'i just added this next line after i included this filefor xml.asp
		On Error Resume Next
		Dim theString, a, b, i, outputLimit, PunctuationString
		theString = Trim(inputString)
		Do
			a = InStr(a + 1, theString, "<")
			If a > 0 Then
				b = InStr(a, theString, ">")
				If b > 0 Then
					theString = Trim(Left(theString, a - 1)) & " " & Trim(Mid(theString, b + 1))
				Else exitLoop = True
				End If
				a = 0
			Else exitLoop = True
			End If
			CheckConnection
		Loop Until exitLoop = True
		PunctuationString = ".!?:;,"
		outputLimit = maxLength
		For i = 1 To Len(PunctuationString)
		a = InStr(minLength, theString, Mid(PunctuationString, i, 1) & " ")
		If a > 0 And a < outputLimit Then outputLimit = a
		Next
		outputString = Left(theString, outputLimit)
		If Len(RTrim(theString)) > Len(RTrim(outputString)) Then outputString = RTrim(outputString) & ".."
		Excerpt = outputString
	End If
End Function

Function Y2KDate(ByVal theDate)
	If IsDate(theDate) Then
		Dim retVal
		theDate = CDate(theDate)
		retVal = Right("00" & Month(theDate), 2) & "."
		retVal = retVal & Right ("00" & Day(theDate), 2) & "."
		retVal = retVal & Year(theDate)
		Y2KDate = retVal
	Else Y2KDate = theDate
	End If
End Function

Function ValidEmailAddress(checkEmail)
	email = Trim(checkEmail)
	at = InStr(2, email, "@")
	ValidEmailAddress = False
	If at = 0 Then Exit Function
	lft = Left(email, at - 1)
	If Left(lft, 1) <> """" Or Right(lft, 1) <> """" Then
		For i = 1 To Len(lft)
			a = UCase(Mid(lft, i, 1))
			If (Asc(a) < Asc("A") Or Asc(a) > Asc("Z")) And a <> "_" And a <> "-" And a <> "&" And a <> "." And (Asc(a) < Asc("0") Or Asc(a) > Asc("9")) Then Exit Function
		Next
	End If
	rght = Mid(email, at + 1)
	For i = 1 To Len(rght)
		a = UCase(Mid(rght, i, 1))
		If (Asc(a) < Asc("A") Or Asc(a) > Asc("Z")) And a <> "." And a <> "-" And (Asc(a) < Asc("0") Or Asc(a) > Asc("9")) Then Exit Function
	Next
	If InStr(1, email, "..") Or InStr(1, email, "__") Or InStr(1, email, "--") Then Exit Function
	If Mid(email, at + 1, 1) = "." Then Exit Function
	domain = Split(Mid(email, at + 1), ".")
	If Ubound(domain) < 1 Then Exit Function
	If Len(domain(UBound(domain))) < 2 Or Len(domain(UBound(domain))) > 3 Then Exit Function
	If Len(domain(UBound(domain) - 1)) < 2 And UBound(domain) - 1 > 0 Then Exit Function
	ValidEmailAddress = True
End Function
  
Function FormatPhoneNumber(strNumber)
	strInput = UCase(strNumber)
	For I = 1 To Len(strInput)
	strCurrentChar = Mid(strInput, I, 1)
	If Asc("0") <= Asc(strCurrentChar) And Asc(strCurrentChar) <= Asc("9") Then strTemp = strTemp & strCurrentChar
	If Asc("A") <= Asc(strCurrentChar) And Asc(strCurrentChar) <= Asc("Z") Then strTemp = strTemp & strCurrentChar
	Next
	strInput = strTemp
	strTemp = ""
	If Len(strInput) = 11 And Left(strInput, 1) = "1" Then strInput = Right(strInput, 10)
	strTemp = "("
	strTemp = strTemp & Left(strInput, 3)
	strTemp = strTemp & ") "
	strTemp = strTemp & Mid(strInput, 4, 3)
	strTemp = strTemp & "-"
	strTemp = strTemp & Right(strInput, 4)
	FormatPhoneNumber = strTemp
End Function

Function NumbersOnly(strNumber)
	strInput = UCase(strNumber)
	For I = 1 To Len(strInput)
	strCurrentChar = Mid(strInput, I, 1)
	If Asc("0") <= Asc(strCurrentChar) And Asc(strCurrentChar) <= Asc("9") Then strTemp = strTemp & strCurrentChar
	Next
	NumbersOnly = strTemp
End Function

Function PCase(strInput)
	iPosition = 1
	Do While InStr(iPosition, strInput, " ", 1) <> 0
		iSpace = InStr(iPosition, strInput, " ", 1)
		strOutput = strOutput & UCase(Mid(strInput, iPosition, 1))
		strOutput = strOutput & LCase(Mid(strInput, iPosition + 1, iSpace - iPosition))
		iPosition = iSpace + 1
		CheckConnection
	Loop
	strOutput = strOutput & UCase(Mid(strInput, iPosition, 1))
	strOutput = strOutput & LCase(Mid(strInput, iPosition + 1))
	PCase = strOutput
End Function

Function AddSuffix(num)
	dz = Right(num,1)
	If dz = 1 Then
	If num = 11 Then AddSuffix = AddSuffix & "th" Else AddSuffix = AddSuffix & "st"
	ElseIf dz = 2 Then
	If num = 12 Then AddSuffix = AddSuffix & "th" Else AddSuffix = AddSuffix & "nd"
	ElseIf dz = 3 Then
	If num = 13 Then AddSuffix = AddSuffix & "th" Else AddSuffix = AddSuffix & "rd"
	ElseIf dz < 21 Then
	AddSuffix = AddSuffix & "th"
	End If
	AddSuffix = num & AddSuffix
End Function

Function ShowSeason(inputDate)
	'spring = march 20 - june 20
	'summer = june 21 - sept 21
	'autumn = sept 22 - dec 20
	'winter = dec 21 - march 19
	currDate = Month(inputDate)
	If currDate = "12" Or currDate = "1" Or currDate = "2" Then
		ShowSeason = "Winter"
	ElseIf currDate = "3" Or currDate = "4" Or currDate = "5" Then ShowSeason = "Spring"
	ElseIf currDate = "6" Or currDate = "7" Or currDate = "8" Then ShowSeason = "Summer"
	Else ShowSeason = "Autumn"
	End If
End Function

Function Encrypt(theNumber)
	On Error Resume Next
	If Trim(theNumber) = "" Then
		Encrypt = ""
		Exit Function
	End If
	Dim n, szEnc, t, HiN, LoN, i
	n = CDbl((theNumber + 1570) ^ 2 - 7 * (theNumber + 1570) - 450)
	If n < 0 Then szEnc = "r" Else szEnc = "j"
	n = CStr(abs(n))
	For i = 1 To Len(n) step 2
		t = Mid(n, i, 2)
		If Len(t) = 1 Then
			szEnc = szEnc & t
			Exit For
		End If
		HiN = (CInt(t) And 240) / 16
		LoN = CInt(t) And 15
		szEnc = szEnc & Chr(Asc("m") + HiN) & Chr(Asc("c") + LoN)
	Next
	Encrypt = LCase(szEnc)
End Function

Function Decrypt(theNumber)
	On Error Resume Next
	Dim e, n, sign, t, HiN, LoN, NewN, i
	e = theNumber
	If Left(e, 1) = "r" Then sign = -1 Else sign = 1
	e = Mid(e, 2)
	NewN = ""
	For i = 1 To Len(e) step 2
		t = Mid(e, i, 2)
		If Asc(t) >= Asc("0") And Asc(t) <= Asc("9") Then
			NewN = NewN & t
			Exit For
		End If
		HiN = Mid(t, 1, 1)
		LoN = Mid(t, 2, 1)
		HiN = (Asc(HiN) - Asc("m")) * 16
		LoN = Asc(LoN) - Asc("c")
		t = CStr(HiN Or LoN)
		If Len(t) = 1 Then t = "0" & t
		NewN = NewN & t
	Next
	e = CDbl(NewN) * sign
	Decrypt = CLng((7 + sqr(49 - 4 * (-450 - e))) / 2 - 1570)
End Function

'-- This function will show the navigational bar at the bottom of all rs results pages. Go to page 2, 3, etc.
Function ShowNavBar(page, style)
	If mypage + 1 > maxcount Then nextPg = 1 Else nextPg = mypage + 1
	If style = "norm" Then
		Response.Write"<form action=""http://"&homepage_url&"/tek9.asp?pg="&page&""""&jsNumVal&" method=post><nobr><img src=""img/arrow_ani.gif"" width=5 height=5 align=middle> <b>Page "&mypage&" of "&maxcount&" &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Navigate to Page:</b> <input type=text name=pgNum size=3 maxlength=3 value="""&nextPg&"""> <input type=image src=""img/submit.gif"" value=Go width=16 height=16 hspace=2 align=absmiddle alt=Go border=0></nobr></form>"
	ElseIf style = "products_group" Then Response.Write"<form action=""http://"&homepage_url&"/tek9.asp?pg="&page&"&grp="&grp&""""&jsNumVal&" method=post><nobr><img src=""img/arrow_ani.gif"" width=5 height=5 align=middle> <b>Page "&mypage&" of "&maxcount&" &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Navigate to Page:</b> <input type=text name=pgNum size=3 maxlength=3 value="""&nextPg&"""> <input type=image src=""img/submit.gif"" value=Go width=16 height=16 hspace=2 align=absmiddle alt=Go border=0><input type=hidden name=sortby value="""&sortby&"""><input type=hidden name=how value="""&how&"""></nobr></form>"
	ElseIf style = "products_manuf" Then Response.Write"<form action=""http://"&homepage_url&"/tek9.asp?pg="&page&"&manuf="&manuf&""""&jsNumVal&" method=post><nobr><img src=""img/arrow_ani.gif"" width=5 height=5 align=middle> <b>Page "&mypage&" of "&maxcount&" &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Navigate to Page:</b> <input type=text name=pgNum size=3 maxlength=3 value="""&nextPg&"""> <input type=image src=""img/submit.gif"" value=Go width=16 height=16 hspace=2 align=absmiddle alt=Go border=0><input type=hidden name=sortby value="""&sortby&"""><input type=hidden name=how value="""&how&"""></nobr></form>"
	ElseIf style = "search" Then Response.Write"<form action=""http://"&homepage_url&"/tek9.asp?pg="&page&"&mode=results"""&jsNumVal&" method=post><nobr><img src=""img/arrow_ani.gif"" width=5 height=5 align=middle> <b>Page "&mypage&" of "&maxcount&" &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Navigate to Page:</b> <input type=text name=pgNum size=3 maxlength=3 value="""&nextPg&"""> <input type=image src=""img/submit.gif"" value=Go width=16 height=16 hspace=2 align=absmiddle alt=Go border=0><input type=hidden name=srch value="""&srch&"""><input type=hidden name=searchBy value="""&searchBy&"""></nobr></form>"
	End If
End Function

Function ShowProductDescrip(descrip, demopage, downloadableSoftware)
	Response.Write descrip
End Function

Function BeginRow()
	Response.Write"<tr bgcolor=""#"&clr2&""">"
End Function

Function EndRow()
	Response.Write"</tr>"
End Function

Function WriteStateSelectBox (stateName, currentState, countryName, currentCountry)
	Response.Write CreateStateSelectBox (stateName, currentState, countryName, currentCountry)
End Function

Function CreateStateSelectBox(stateName, currentState, countryName, currentCountry)
	Dim rs, text, baseValue
	If mode = "step2" Then
		text = "<td><font"&fc&" size=1>"&state_type&" / Country:</font><br><select name="""&stateName&"""><option value=""""></option>"
		Set rs = cnDZ.Execute("SELECT CODE, STATE FROM TBLSTATES ORDER BY STATE ASC;")
		If Not rs.EOF Then
			Do Until rs.EOF
				If currentState = ""&rs("CODE") Then Sel = " SELECTED" Else Sel = ""
				text = text & "<option value="&rs("CODE")&Sel&">"&rs("CODE")&"</option>"
				rs.MoveNext
			Loop
			rs.Close
		End If
		text = text & "</select> <font size=2>/</font> "&CreateCountrySelectBox(countryName, currentCountry)&"</td>"
		Set rs = Nothing
	ElseIf mode = "step3" Then
		If Trim("X"&currentState) = "X" Then
			text = CreateOrderTextField("Country:", name, currentCountry, False, maxlength, "", False)
		Else
			text = CreateOrderTextField(state_type & " / Country:", name, currentState&", "&currentCountry, False, maxlength, "", False)
		End If
	Else 'write a basic State select box:
		text = "<select name="""&stateName&"""><option value=""""></option>"
		Set rs = cnDZ.Execute("SELECT CODE FROM TBLSTATES ORDER BY CODE ASC;")
		If Not rs.EOF Then
			Do Until rs.EOF
				If currentVal = CStr(rs("CODE")) Then Sel = " SELECTED" Else Sel = ""
				text = text & "<option value="&rs("CODE")&Sel&">"&rs("CODE")&"</option>"
				rs.MoveNext
			Loop
			rs.Close
		End If
		text = text & "</select>"
		Set rs = Nothing
	End If
	CreateStateSelectBox = text
End Function

Function WriteCountrySelectBox (name, currentVal)
	Response.Write CreateCountrySelectBox (name, currentVal)
End Function

Function CreateCountrySelectBox(name, currentVal)
	Dim rs, text, CountryName
	text = "<select name="""&name&""">"
	If currentVal = "" Then currentVal = "US"
	Set rs = cnDZ.Execute("SELECT CODE2, CODE, COUNTRY FROM TBLCOUNTRIES WHERE NOT(CODE2 IS NULL) ORDER BY COUNTRY ASC;")
	If Not rs.EOF Then
		Do Until rs.EOF
			If currentVal = ""&rs("CODE2") or currentVal = ""&rs("CODE") Then Sel = " SELECTED" Else Sel = ""
			CountryName = rs("COUNTRY")
			If Len(CountryName) > 15 Then CountryName = Excerpt(CountryName,17,19)
			text = text & "<option value="&rs("CODE2")&Sel&">"&CountryName&"</option>"
			rs.MoveNext
		Loop
		rs.Close
	End If
	text = text & "</select>"
	Set rs = Nothing
	CreateCountrySelectBox = text
End Function

Dim a_recordsViewed(), a_datesViewed, remote_addr, remote_host

Function InitSurvey()
	Dim rs
	'-- Survey Module header code:
	remote_addr = Request.ServerVariables("REMOTE_ADDR")
	remote_host = Request.ServerVariables("REMOTE_HOST")
	If remote_addr = "" Then userIP = ""&remote_host&"" Else userIP = ""&remote_addr&""
	Set rs = Server.CreateObject("ADODB.Recordset")
	rs.Open "SELECT * FROM tblSurveyViewed WHERE USERIP = '"&userIP&"';", cnDZ, adOpenKeyset, adLockOptimistic
	If Not rs.EOF Then
		Redim a_recordsViewed(rs.Recordcount)
		Redim a_datesViewed(rs.Recordcount)
		i = 0
		Do until rs.EOF
			a_recordsViewed(i) = rs("QuestionID")
			a_datesViewed(i) = rs("QuestionID") & ":" & rs("THEDATE")
			i = i + 1
			rs.MoveNext
		Loop
		recordsViewed = Join(a_recordsViewed, ",")
		datesViewed = Join(a_datesViewed, ",")
	End If
	rs.Close
	Set rs = Nothing
End Function

'this subroutine used for displaying all children of a parent group (on default products page):
Sub ShowGroups(ParentGroup, parentCount, commaDelimit, style)
	Dim rsG, theDescrip, Indent, ttl2, rsCount, count, showIMG
	Set rsG = cnDZ.Execute("SELECT * FROM qGroupsParents WHERE ParentID "&_
	IIF(IsNull(ParentGroup), "IS NULL", "= "&ParentGroup)&" ORDER BY Name ASC;")
	If Not rsG.EOF Then
		count = 0
		If parentCount = 0 And style = True Then
			Response.Write"<form action=""http://"&homepage_url&"/tek9.asp"" method=get> <input type=hidden name=pg value=products>"&_
			"<select name=grp>"
		End If
		Do Until rsG.EOF
			If IsNull(rsG("IMG")) Or Trim("X"&rsG("IMG")) = "X" Then showIMG = "" Else showIMG = "<img src=""img/"&rsG("IMG")&""" border=0>"
			thedescrip = rsG("DESCRIP")
			Indent = Replace(Space(parentCount), " ", "&nbsp;")
			If Trim("X"&thedescrip) <> "X" Then ttl2 = " title="""&thedescrip&""""
			If commaDelimit = True Then
				If count > 0 Then Response.Write ", "
			Else
				If parentCount <> 0 Or count > 0 Then Response.Write"<br>"
				Response.Write Indent
			End If
			If parentCount = 0 Then
				If style = False Then
					Response.Write"<a href=""http://"&homepage_url&"/tek9.asp?pg=products&grp="&rsG("GROUPID")&""""&ttl2&">"&showIMG&"<b style=""letter-spacing:1px"">"&rsG("NAME")&"</b></a>"
				Else Response.Write"<option value=" & rsG("GroupID") & ">" & rsG("Name") & "</option>"
				End If
			Else
				If style = True Then Response.Write"<option value=" & rsG("GroupID") & ">"
				If parentCount > 1 Then Response.Write"&nbsp;&nbsp;"
				If commaDelimit = False And style = False Then
					Response.Write"<a href=""http://"&homepage_url&"/tek9.asp?pg=products&grp="&rsG("GROUPID")&""""&ttl2&"><img src=""img/indent-dots.gif"" width=10 height=1 align=absmiddle border=0>&nbsp;"&showIMG&""&rsG("NAME")&"</a>"
				ElseIf style = False Then Response.Write" <a href=""http://"&homepage_url&"/tek9.asp?pg=products&grp="&rsG("GROUPID")&""""&ttl2&">"&showIMG&""&rsG("NAME")&"</a>"
				Else Response.Write"&nbsp;&nbsp;" & rsG("NAME")
				End If
				If style = True Then Response.Write "</option>"
			End If
			If commaDelimit = False Then
				Set rsCount = cnDZ.Execute("SELECT COUNT(*) AS Total FROM qInventoryByGroup WHERE GroupID = "&rsG("GroupID")&";")
				If Not rsCount.EOF Then Response.Write"&nbsp;&nbsp;<font size=1>("&rsCount("Total")&" product"&CheckShowS(rsCount("Total"))&")</font>"
				rsCount.Close
				Set rsCount = Nothing
				ShowGroups rsG("GROUPID"), parentCount + 1, commaDelimit, style
			End If
			count = count + 1
			rsG.MoveNext
			CheckConnection
		Loop
		If parentCount = 0 And style = True Then Response.Write"</select> <input type=submit value=""Click to view"" class=btn></form>"
	End If
	rsG.Close
	Set rsG = Nothing
End Sub

Function StripSymbols(sString) 'strips out all non-alphanumeric characters
	Dim nCharPos, sOut, nChar
	nCharPos = 1
	sOut = ""
	For nCharPos = 1 To Len(sString)
		nChar = Asc(Lcase(Mid(sString, nCharPos, 1)))
		If ((nChar > 47 And nChar < 58) Or (nChar > 96 And nChar < 123) Or nChar = 32) Then
			sOut = sOut & Mid(sString, nCharPos, 1)
		End If
	Next
	StripSymbols = sOut
End Function

sorry = "<br><br><br><br><center><b>Sorry, nothing was found.</b><br><br>Care to <a href=""http://"&homepage_url&"/tek9.asp?pg=products"">browse</a>..?</center><br><br><br><br><br>"
strsrch = Request.Form("srch")
If strsrch = "" Then strsrch = Request.QueryString("srch")
srch = Replace(StripSymbols(strsrch), " ", "%")
	If srch = "" Then srch = "XX"
searchBy = Request.Form("searchBy")
If searchBy = "" Then searchBy = Request.QueryString("searchBy")
mypage = Request.Form("pgNum")
	If Not IsNumeric(mypage) Then
		mypage = 1
	ElseIf mypage = "" Then mypage = 1
	Else mypage = Int(Request.Form("pgNum"))
	End If
grp = Request.QueryString("grp")
	If Not IsNumeric(grp) Then grp = 1
mode = Request.QueryString("mode")
If pg = "products" Or pg = "cart" Then
	specific = Decrypt(Request.QueryString("specific"))
Else specific = Request.QueryString("specific")
End If
sortby = Request.QueryString("sortby")
	If sortby = "" Then sortby = Request.Form("sortby")
how = Request.QueryString("how")
	If how = "" Then how = Request.Form("how")
	If sortby <> "" And how = "" Then Response.Redirect"tek9.asp"
yr = Year(Now)
mnth = MonthName(Month(Now))
fc = " face=Verdana"
cid = Decrypt(Request.Cookies("cid"))
'If pg = "cart" Then Response.Expires = 0

Sub AddItemToCart ()
	Dim expireTime, itemInCart, rs, rsU, rs2, rsMod, SelectedModifiers
	expireTime = FormatNumber(Date,0,-1,-1,0)
	If Trim(cid) = "" Or cid = 0 Then
		cid = GenerateCID
		Response.Cookies("cid") = Encrypt(cid)
		Response.Cookies("cid").Expires = Now() + 14
	End If
	itemID = Request.Form("itemID")
	itemQty = 1
	Set SelectedModifiers = ParseSelectedModifiers
	Set rs = Server.CreateObject("ADODB.Recordset")
	rs.Open "SELECT * FROM qCart WHERE CUSTID = "&cid&" AND ITEMID = "&itemID&";", cnDZ, adOpenStatic, adLockOptimistic
	If Not rs.EOF Then
		itemInCart = True
		For	Each item In SelectedModifiers
			rs.MoveFirst
			rs.Find "ModifierGroupID = "&item
			If ""&rs("ModifierID") <> SelectedModifiers(item) Then
				itemInCart = False
				Exit For
			End If
		Next
	Else itemInCart = False
	End If
	If itemInCart = True Then 'We found the item in their cart, so we only need to modify the quantity:
		rs.MoveFirst
		qis = 100000
		rs("Quantity") = rs("Quantity") + 1
		rs.Update
	Else 'Didn't find it, so we have to add a new record:
		Set rs2 = cnDZ.Execute("SELECT ITEMID, SKU, AltSku, ITEMNAME, COST, QIS, ComplexQIS, ONSALE, WEIGHT, SALEDISCOUNT FROM TBLINVENTORY WHERE ITEMID = "&itemID&";")
		If Not rs2.EOF Then
			sku = rs2("SKU")
			itemName = rs2("ITEMNAME")
			SQL = "SELECT * FROM tblInvModifiers WHERE ItemID = "&itemID&" AND ModifierID In (0"
			For Each item In SelectedModifiers
				SQL = SQL & "," & SelectedModifiers(item)
				ModifierString = ModifierString & SelectedModifiers(item) & "&"
			Next
			SQL = SQL & ");"
			If Right(ModifierString, 1) = "&" Then ModifierString = Left(ModifierString, Len(ModifierString) - 1)
			If rs2("WEIGHT") > 0 Then
				qis = rs2("QIS")
				ComplexQis = rs2("ComplexQIS")
				a = InStr(1, ComplexQis, ModifierString)
				If a > 0 Then
					b = InStr(a, ComplexQis, ":")
					If b > 0 Then
						c = InStr(b, ComplexQis, ",")
						If c > 0 Then qis = CLng(Mid(ComplexQis, b + 1, c - b - 1))
					End If
				End If
			Else qis = 10000
			End If
			sku = rs2("SKU")
			ComplexSKU = rs2("AltSKU") & ","
			a = InStr(1, ComplexSKU, ModifierString)
			If a > 0 Then
				b = InStr(a, ComplexSKU, ":")
				If b > 0 Then
					c = InStr(b, ComplexSKU, ",")
					If c > 0 Then sku = Mid(ComplexSKU, b + 1, c - b - 1)
				End If
			End If
			If qis > 0 Or ALLOWBOPO Then
				cost = rs2("COST")
				Set rsMod = cnDZ.Execute(SQL)
				Do Until rsMod.EOF
					cost = cost + rsMod("AdditionalCost")
					rsMod.MoveNext
					CheckConnection
				Loop
				rsMod.Close
				Set rsMod = Nothing
				If rs2("ONSALE") = -1 Then cost = FormatCurrency(cost - (cost * rs2("SALEDISCOUNT") / 100))
				Set rsCart = Server.CreateObject("ADODB.Recordset")
				rsCart.Open "TBLCART", cnDZ, adOpenKeyset, adLockOptimistic
				rsCart.AddNew
				rsCart("CUSTID") = cid
				rsCart("ITEMID") = itemID
				rsCart("SKU") = sku
				rsCart("ITEMNAME") = itemname
				rsCart("COST") = cost
				rsCart("EXPIRE") = expiretime
				rsCart("QUANTITY") = itemQty
				rsCart.Update
				Set rsCartModifiers = Server.CreateObject("ADODB.Recordset")
				rsCartModifiers.Open "tblCartModifiers", cnDZ, adOpenStatic, adLockOptimistic
				For Each item In SelectedModifiers
					rsCartModifiers.AddNew
					rsCartModifiers("CartItemID") = rsCart("CartItemID")
					rsCartModifiers("ModifierID") = SelectedModifiers(item)
					rsCartModifiers.Update
				Next
				rsCartModifiers.Close
				Set rsCartModifiers = Nothing
				rsCart.Close 'dz added these 2 lines:
				Set rsCart = Nothing
			End If
		End If
		rs2.Close
		Set rs2 = Nothing
	End If
	rs.Close
	Set rs = Nothing
	Response.Redirect"tek9.asp?pg=cart"
End Sub

Function DisplayCart (CurrentStep, FormTags)
	Dim rs, itemCount, lastItem, showCost, st, aln
	Dim bgColor, sale, createdRow, modifiers, modifierString, i
	Dim shortModString, altWeight
	Dim StepName(4)
	StepName(1) = "ViewCart"
	StepName(2) = "Billing"
	StepName(3) = "Shipping"
	Stepname(4) = "Receipt"
	Set rs = Server.CreateObject("ADODB.Recordset")
	rs.Open "SELECT * FROM qCart WHERE CustID = "&cid&";", cnDZ, adOpenStatic, adLockOptimistic
	If Not rs.EOF Then  'the users cart is NOT empty, so show it here:
		Response.Write"<table cellspacing=0 cellpadding=0 width=440 align=center border=0><tr>"
		If FormTags <> False Then Response.Write"<form id=cartform action=""http://"&homepage_url&"/tek9.asp?pg=cart&mode=update"" method=post>"
		For i = 1 To 4
			Response.Write"<td width=110 nowrap><font"&fc&" size=1>"
			If CurrentStep = i Then Response.Write"<b title=""You are here"" style=""cursor:help"">"
			Response.Write"Step "&i&":"
			If CurrentStep = i Then Response.Write"</b>"
			Response.Write" "&StepName(i)&"</td>"
		Next
		'calculating handling charges for cart items and storing them in global variable:
		Dim rsHandling
		Set rsHandling = Server.CreateObject("ADODB.Recordset")
		rsHandling.Open "SELECT TBLINVENTORY.SH FROM TBLINVENTORY RIGHT JOIN qCart ON TBLINVENTORY.ITEMID = qCart.ITEMID WHERE CUSTID = "&cid&";", cnDZ, adOpenStatic, adLockOptimistic
		Do Until rsHandling.EOF
		handlingCost = handlingCost + rsHandling("SH")
		rsHandling.MoveNext
		Loop
		rsHandling.close
		Set rsHandling = Nothing
		Response.Write"</tr><tr><td colspan=4><img src=""img/ec-progress"&CurrentStep&".gif"" width=440 height=5></td></tr>"&_
		"</table>"&_
		"<table cellspacing=1 width=440 align=center border=0>"&_
		"<tr bgcolor=""#"&clr1&"""><td><font"&fc&" size=2><b>&nbsp;Shopping cart contents:</td><td align=center><font"&fc&" size=2><b>Price</td><td align=center><font"&fc&" size=2>"
		If FormTags <> False Then Response.Write"*"
		Response.Write"<b>Qty</td><td align=center><font"&fc&" size=2><b>Total</td></tr>"
		itemCount = 0
		totalWeight = 0
		lastItem = 0
		Set rsCoupons = Server.CreateObject("ADODB.Recordset")
		rsCoupons.Open "Select * FROM qCustomerCartModifiers Where CustID = "&cid&";", cnDZ, adOpenStatic, adLockOptimistic
		Set rsUCoupons = Server.CreateObject("ADODB.Recordset")
		rsUCoupons.Open "tblCoupons", cnDZ, adOpenStatic, adLockOptimistic
		Do Until rsUCoupons.EOF
			rsUCoupons("Used") = 0
			rsUCoupons.Update
			rsUCoupons.MoveNext
		Loop
		Dim rsShipTime
		Set rsShipTime = Server.CreateObject("ADODB.Recordset")
		Do Until rs.EOF
			st = CDbl(rs("COST"))
			runningQty = runningQty + rs("QUANTITY")
			If itemCount Mod 2 = 0 Then bgColor = " bgcolor=""#"&clr2&"""" Else bgColor = ""
			If rs("ONSALE") = -1 Then sale = " <img src=""img/sale.gif"" width=14 height=14 alt=""This Item On Sale Today!"" align=absmiddle border=0>" Else sale = ""
			lastItem = rs("CartItemID")
			createdRow = False
			modifiers = ""
			modifierString = ""
			shortModString = ""
			AllCouponModifiers = True
			rsCoupons.Filter = "ItemID = " & rs("ITEMID")
			Do Until lastItem <> rs("CartItemID")
				If Trim(rs("GroupName")) <> "" Then
					If createdRow = False Then
						modifiers = "<br><img src=""img/no.gif"" width=10 height=10 hspace=2 border=0><font size=1>"
						createdRow = True
					End If
					modifiers = modifiers & "<b>"&rs("GroupName")&":</b> "&rs("ModifierName")&" "
					modifierString = modifierString & "&group"&rs("ModifierGroupID")&"="&rs("ModifierID")
					shortModString = shortModString & "&" & rs("ModifierID")
				End If
				If Not rsCoupons.EOF Then
					If Trim(rs("ModifierID")) <> "" Then
						rsCoupons.MoveFirst
						rsCoupons.MovePrevious
						rsCoupons.Find "ModifierID = "&rs("ModifierID")
						If rsCoupons.EOF Then
							rsCoupons.MoveFirst
							rsCoupons.MovePrevious
							rsCoupons.Find "ModifierGroup = "&rs("ModifierGroupID")
							If Not rsCoupons.EOF Then AllCouponModifiers = False
						End If
					End If
				Else AllCouponModifiers = False
				End If
				rs.MoveNext
				If rs.EOF Then Exit Do
			Loop
			rs.MovePrevious
			shortModString = mid(shortModString, 2)
			showcost = ""
			itemTotal = st * rs("QUANTITY")
			If AllCouponModifiers = True Then
				rsCoupons.MoveFirst
				discountQuantity = rs("QUANTITY")
				If Not IsNull(rsCoupons("MaxQty")) Then
					If CDbl(rsCoupons("MaxQty")) < CDbl(rs("QUANTITY")) Then discountQuantity = rsCoupons("MaxQty")
				End If
				rsUCoupons.MoveFirst
				rsUCoupons.MovePrevious
				rsUCoupons.Find "CouponItemID = "&rsCoupons("CouponItemID")
				rsUCoupons("Used") = discountQuantity
				rsUCoupons.Update
				itemTotal = itemTotal - discountQuantity * st * rsCoupons("PD") / 100
				showCost = "<font size=1>Originally<br>"
			End If
			If st <= 0 Then
				showcost = showCost & "FREE"
				aln = "center"
			Else
				showcost = showCost & FormatCurrency(rs("COST"))
				aln = "right"
			End If
			altWeight = FindComplexValue(rs("AltWeight"), shortModString, ":", ",", "No Weight")
			If altWeight = "No Weight" Then	altWeight = rs("WEIGHT")
			totalWeight = totalWeight + altWeight
			rsShiptime.Open "SELECT SHIPTIME FROM TBLSHIPTIME WHERE ID = "&rs("SHIPTIME")&";", cnDZ, adOpenStatic, adLockOptimistic
			Response.Write"<tr "&bgColor&" "&dynrow&"><td "&dyngo&" nowrap><font"&fc&" size=2><img src=""img/"&rs("SHIPTIMEIMG")&""" width=10 height=10 hspace=2 alt="""&rsShipTime("SHIPTIME")&""" border=0>"
			If FormTags <> False Then Response.Write"<a href=""http://"&homepage_url&"/tek9.asp?pg=products&specific="&Encrypt(rs("ITEMID"))&modifierString&""">" Else Response.Write"<a href=""javascript://"">"
			Response.Write rs("Name") & " " & Left(rs("ITEMNAME"),40) & "</a>" & sale &_
			modifiers & "</td><td align="&aln&"><font"&fc&" size=2>"&showcost&"</td><td align=center nowrap>"
			If FormTags <> False Then
				Response.Write"<input size=2 maxlength=3 name="&rs("CartItemID")&" value="&rs("QUANTITY")&">"
			Else Response.Write"<font"&fc&" size=2>"&rs("Quantity")
			End If
			If itemTotal <= 0 Then
				showCost = "FREE"
				itemTotal = 0
			Else showCost = FormatCurrency(itemTotal)
			End If
			Response.Write"</td><td align=right><font"&fc&" size=2>"&showCost&"</td></tr>"
			itemCount = itemCount + 1
			CheckConnection
			runningTotal = runningTotal + itemTotal
			rs.MoveNext
			rsShipTime.Close
		Loop
		Set rsShipTime = Nothing
		Response.Write"</table>"&_
		"<table cellspacing=0 cellpadding=0 width=440 align=center border=0>"&_
		"<tr><td bgcolor=""#990000"" nowrap><img src=""img/no.gif"" width=1 height=1></td></tr>"&_
		"</table><table cellspacing=0 cellpadding=0 width=440 align=center border=0>"
		totalString = "Sub-Total"
		If totalWeight = 0 Then totalString = "Total"
		Response.Write"<tr bgcolor=""#"&clr2&"""><td align=right nowrap><font"&fc&" size=2><b>"&totalString&": &nbsp;"&FormatCurrency(runningtotal)&"</td></tr>"
		If mode = "step3" Then
			If Trim("X"&BILL_STATE) <> "X" Then
				taxState = BILL_STATE
				Set rsState = cnDZ.Execute("SELECT * FROM tblStates WHERE CODE = '" & taxState & "'")
				If rsState("NEXUS") = -1 Then
					tax = CLng(NullSafeNumber(rsState("Tax")) * runningTotal) / 100
				End If
			End If
		End If
		If tax > 0 Then Response.Write"<tr bgcolor=""#"&clr2&"""><td align=right nowrap><font"&fc&" size=2><b>Sales Tax: "&FormatCurrency(tax)&"</td></tr>"
		If FormTags <> False Then
			Response.Write"<tr><td bgcolor=""#990000""><img src=""img/no.gif"" width=1 height=1></td></tr>"&_
			"<tr height=10><td><img src=""img/no.gif"" width=1 height=10></td></tr>"
			Set rsCouponRecordCount = cnDZ.Execute("SELECT Count(*) AS Total FROM tblCoupons;")
			If rsCouponRecordCount("Total") > 0 Then
				Response.Write"<tr><td bgcolor=""#"&clr2&""" align=right nowrap><font"&fc&" size=2>"
				If ValidCoupon = 1 Then
					Response.Write"Could not find that Coupon. Try again?"
				ElseIf ValidCoupon = 2 Then Response.Write"Coupon already entered! Add another?"
				ElseIf ValidCoupon = 3 Then Response.Write"Coupon has expired. Try another?"
				ElseIf ValidCoupon = -1 Then Response.Write"Successfully added Coupon. Add another?"
				Else Response.Write"<img src=""img/arrow_ani.gif"" width=5 height=5 align=absmiddle> <b>Have a Coupon?</b> Please enter it here:"
				End If
				Response.Write" <input type=text name=CouponID size=9 maxlength=6></td></tr>"&_
				"<tr height=10><td><img src=""img/no.gif"" width=1 height=10></td></tr>"
			End If
			Response.Write "<tr><td align=center bgcolor=""#"&clr2&""" nowrap>"&_
			"<input type=image"&showborder&" src=""img/ec-updatetotals.gif"" name=""Update Cart Totals"" width=120 height=16 alt=""Update Cart Totals"" align=absmiddle border=0> &nbsp;&nbsp;&nbsp;"&_
			"<a"&ask&" href=""http://"&homepage_url&"/tek9.asp?pg=cart&mode=empty""><img"&showborder&" src=""img/ec-empty.gif"" width=67 height=16 alt=""Remove all items from your cart?"" align=absmiddle border=0></a> &nbsp;&nbsp;&nbsp;"&_
			"<a href="""&protocol&"://"&homepage_url&"/ssl/tek9.asp?pg=cart&mode=checklogin&cid="&Encrypt(cid)&"""><img"&showborder&" src=""img/ec-gotocheckout.gif"" width=130 height=16 alt=""Go to Checkout (Step 2 of 4)"" align=absmiddle border=0></a>"&_
			"</td></tr>"&_
			"<tr height=10><td><img src=""img/no.gif"" width=1 height=16></td></tr>"
			If rsCouponRecordCount("Total") > 0 Then
				rsCoupons.Filter = ""
				rsCoupons.Requery
				If rsCoupons.RecordCount > 0 Then
					Set rsCouponCount = cnDZ.Execute ("Select Count(*) as total from (Select Distinct CouponID FROM qCustomerCartModifiers WHERE CustID = "&cid&")")
					Response.Write"<tr><td nowrap><font"&fc&" size=1>"&_
					rsCouponCount("total")&" Coupon"&CheckShowS(rsCouponCount("total")) &_
					" entered:<table width=428 align=center border=0>"
					rsCouponCount.Close
					Set rsCouponCount = Nothing
					lastCouponID = -1
					lastItemID = -1
					totalCoupons = 0
					Do Until rsCoupons.EOF
						If CLng(rsCoupons("CouponID")) <> lastCouponID Then
							Response.Write limitString
							limitString = ""
							If totalCoupons > 0 Then Response.Write"</td></tr></table></td>"
							If totalCoupons Mod 2 = 0 Then
								If totalCoupons > 0 Then Response.Write"</tr>"
								Response.Write"<tr>"
							End If
							lastCouponID = rsCoupons("CouponID") 'used to have width=""50%"" on this next td:
							Response.Write"<td valign=top>"&_
							"<table width=214 class=coupon cellpadding=3 cellspacing=0 align=center border=0>"&_
							"<tr bgcolor=""#"&clr2&"""><td bgcolor=""#"&clr1&""">"&_
							"<font"&fc&" size=1><b>Coupon#</b> "&lastCouponID&"</td><td><font"&fc&" size=1>Used"
							lastItemID = -1
							wroteModifiers = False
							totalCoupons = totalCoupons + 1
						End If
						If CLng(rsCoupons("ItemID")) <> lastItemID Then
							If rsCoupons("Used") = rsCoupons("MaxQty") Then showused = "All" Else showused = rsCoupons("Used")
							If Not IsNull(rsCoupons("MaxQty")) Then title = rsCoupons("MaxQty") Else title = "&#8734;"
							Response.Write limitString
							limitString = "</td><td align=center valign=top><font size=1><a title=""of "&title&""" style=""cursor:help"">&nbsp;"&showused&"&nbsp;</a>"
							lastItemID = rsCoupons("ItemID")
							If IsNull(rsCoupons("ExpireDate")) Then
								ExpireString = "Never"
							Else ExpireString = MonthName(Month(rsCoupons("EXPIREDATE")))
							End If
							Response.Write"</td></tr>"&_
							"<tr bgcolor=""#"&clr2&""""&dynrow&" title="""&rsCoupons("PD")&"% off each! Expires: "&ExpireString&" "&AddSuffix(Day(rsCoupons("EXPIREDATE")))&", Max: "&title&""">"&_
							"<td width=""100%"""&dyngo&"><font"&fc&" size=1><A HREF=""tek9.asp?pg=products&specific="&Encrypt(rsCoupons("ItemID"))&""" title=""Expires: "&Replace(ExpireString,"/",".")&", Max: "&title&""">"&rsCoupons("MANUFNAME")&" "&Left(rsCoupons("ItemName"),40)&"</A><br>"
							wroteModifiers = True
						End If
						If Trim(rsCoupons("ModifierID")) <> "" Then
							Response.Write" &nbsp;<b>"&rsCoupons("GroupName")&":</b> "&rsCoupons("ModifierName")&"<br>"
							wroteModifiers = True
						End If
						rsCoupons.MoveNext
					Loop
					Response.Write limitString & "</td></tr></table></td></tr></table>"&_
					"</td></tr><tr height=10><td><img src=""img/no.gif"" width=1 height=10></td></tr>"
				End If
			End If
			rsCouponRecordCount.Close
			Set rsCouponRecordCount = Nothing
			Response.Write"</form></table>"
			Set rsRelatedItems = cnDZ.Execute("SELECT * FROM qCartRelatedItems WHERE CustID = "&cid&" AND ItemID NOT IN (SELECT ItemID FROM qCart WHERE CustID = "&cid&");")
			If Not rsRelatedItems.EOF Then
				Response.Write"</p><form action=""tek9.asp?pg=products"" method=post><b>Related Items:</b> <select name=LinkTo>"
				Do Until rsRelatedItems.EOF
					Response.Write"<option value="&Encrypt(rsRelatedItems("ITEMID"))&">"&rsRelatedItems("ITEMNAME")&"</option>"
					rsRelatedItems.MoveNext
				Loop
				Response.Write"</select> <input"&showborder&" type=image name=preview src=""img/ec-preview.gif"" width=78 height=16 alt=""Preview the selected product"" align=absmiddle border=0></form>"
			End If
			rsRelatedItems.Close
			Set rsRelatedItems = Nothing
			Response.Write"</p><font"&fc&" size=1>*Set the Quantity field to zero (0) to remove an item from your cart.<br>Remember to click the ""Update Totals"" button if you modify quantities.<br>When you are ready for Step 2, click the ""Go to Checkout"" button.<br><br><br>"
		Else Response.Write"</table>"
		End If
		rsCoupons.Close
		Set rsCoupons = Nothing
		rsUCoupons.Close
		Set rsUCoupons = Nothing
	End If
	rs.Close
	Set rs = Nothing
	DisplayCart = itemCount
	subTotal = runningTotal
End Function

If pg = "cart" And mode = "update" Then UpdateCart
If pg = "cart" And mode = "empty" Then EmptyCart

Sub UpdateCart
	Dim rs, nuQty, expired
	Set rs = Server.CreateObject("ADODB.Recordset")
	rs.Open "SELECT * FROM TBLCART WHERE CUSTID = "&cid&";", cnDZ, adOpenStatic, adLockOptimistic
	If Not rs.EOF Then
		Do Until rs.EOF
			'Have to use the odd syntax on the "" & rs("CartItemID") so that asp will look at
			'it as a string, not an index number in the array Request.Form
			If Request.Form(""&rs("CartItemID")) <> rs("QUANTITY") Then
				nuQty = Request.Form(""&rs("CartItemID"))
				If nuQty = "" Then nuQty = 1
				If IsNumeric(nuQty) = True Then
					If nuQty = 0 Then
						sql = "DELETE FROM tblCartModifiers WHERE CartItemID = "&rs("CartItemID")&";"
						cnDZ.Execute(sql)
						rs.Delete
					Else
						rs("QUANTITY") = nuQty
						rs.Update
					End If
				End If
			End If
			rs.MoveNext
			CheckConnection
		Loop
	End If
	rs.Close
	CouponID = Request.Form("CouponID")
	If IsNumeric(CouponID) = True Then
		rs.Open "SELECT * FROM tblCoupons WHERE CouponID = "&CLng(CouponID)&";"
		If Not rs.EOF Then
			rs.MoveFirst
			expired = True
			Do Until rs.EOF
				If rs("ExpireDate") > Now Or IsNull(rs("ExpireDate")) Then expired = False: Exit Do
				rs.MoveNext
			Loop
			If expired = False Then
				rs.Close
				rs.Open "tblCartCoupons", cnDZ, adOpenStatic, adLockOptimistic
				On Error Resume Next
				rs.AddNew
				rs("CUSTID") = cid
				rs("CouponID") = CouponID
				rs.Update
				If err.Number = 0 Then ValidCoupon = -1 Else ValidCoupon = 2
			Else ValidCoupon = 3
			End If
		Else ValidCoupon = 1
		End If
		rs.Close
	ElseIf CouponID <> "" Then ValidCoupon = 1
	End If
	Set rs = Nothing
End Sub

Sub EmptyCart
	Dim expireTime, yest, sql
	expireTime = FormatNumber(Date,0,-1,-1,0)
	yest = expireTime - 5
	sql = "DELETE FROM TBLCART WHERE CUSTID = "&cid&" OR EXPIRE < '"&yest&"';"
	cnDZ.Execute(sql)
End Sub

Function ParseSelectedModifiers()
	Dim SelectedModifiers, item, ModifierGroup
	Set SelectedModifiers = CreateObject("Scripting.Dictionary")
	For Each item In Request.QueryString
		If Left(item, 5) = "group" Then
			ModifierGroup = Mid(item, 6)
			SelectedModifiers(ModifierGroup) = Request.QueryString(item)
		End If
	Next
	For Each item In Request.Form
		If Left(item, 5) = "group" Then
			ModifierGroup = Mid(item, 6)
			SelectedModifiers(ModifierGroup) = Request.Form(item)
		End If
	Next
	Set ParseSelectedModifiers = SelectedModifiers
End Function

'-- Generate a Customer ID number:
Function GenerateCID ()
	Dim cid, rsU
	Set rsU = Server.CreateObject("ADODB.RecordSet")
	rsU.Open "TBLCARTID", cnDZ, adOpenStatic, adLockPessimistic
	If Not rsU.EOF Then 'If a record exists, take it and add one to it:
		rsU.MoveFirst
		cid = Abs(rsU("CUSTID") + 1)
	Else 'Start at one if there is no CID record
		rsU.AddNew
		cid = 1001
	End If
	'' Update TBLCARTID with the current ID and sent the id to the customer
	rsU.Fields("CUSTID") = cid
	rsU.Update
	rsU.Close
	Set rsU = Nothing
	GenerateCID = cid
End Function

'-- Error recording function:
Sub ErrorRecord (ErrNumber, ErrFile, ErrLineNumber, ErrDescription)
	Dim rsError, rs, descrip, i, ErrID, MailBody
	ErrTime = Now()
	Set rsError = Server.CreateObject ("ADODB.Recordset")
	rsError.Open "TBLERRORS", cnDZ, adOpenKeyset, adLockOptimistic
	rsError.AddNew
	If IsNumeric(ErrNumber) = False Then ErrNumber = -1
	If IsNumeric(ErrLineNumber) = False Then ErrLineNumber = 0
	If IsNumeric(cid) = False Then theCustID = 0 Else theCustID = cid
	If ErrNumber <> "" Then rsError("ERROR_NUMBER") = ErrNumber
	If ErrTime <> "" Then rsError("ERROR_DATE") = ErrTime
	If homepage_url <> "" Then rsError("ERROR_DOMAIN") = homepage_url
	If Request.ServerVariables("REMOTE_HOST") <> "" Then rsError("ERROR_REMOTEHOST") = Request.ServerVariables("REMOTE_HOST")
	If ErrFile <> "" Then rsError("ERROR_FILE") = ErrFile
	If Request.QueryString <> "" Then rsError("ERROR_QUERYSTRING") = Request.QueryString
	If Request.Cookies <> "" Then rsError("ERROR_COOKIE") = Request.Cookies
	If Request.Form <> "" Then rsError("ERROR_FORM") = Request.Form
	If theCustID <> "" Then rsError("ERROR_CID") = theCustID
	If ErrLineNumber <> "" Then rsError("ERROR_LINENUMBER") = ErrLineNumber
	If ErrDescription <> "" Then descrip = ErrDescription & "<P>"
	descrip = descrip & "<U><B>Server Variables:</B></U><BR>"
	For Each i In Request.ServerVariables
		descrip = descrip & "<B>" & i & ":</B> " & Request.ServerVariables(i) & "<BR>"
	Next
	rsError("ERROR_DESCRIPTION") = descrip
	rsError.Update
	ErrID = rsError("ERROR_ID")
	MailBody = "An error has occured at <B>"&homepage_url & "</B><BR>" &_
		"<a href=""" & protocol & "://" & homepage_url & "/ssl/intranet/tek9.asp?pg=errors&err=" & ErrID & """>" & _
		"Error ID: " & ErrID & "</a><BR>" & _
		"Error Number: "&ErrNumber & "<BR>" &_
		"Error Time: "&ErrTime & "<BR>" &_
		"Remote Host: "&Request.ServerVariables("REMOTE_HOST") & "<BR>" &_
		"File: "&ErrFile & "<BR>" &_
		"Line: "&ErrLineNumber & "<BR>" &_
		"Description: "& descrip & "<BR>"
	rsError.Close
	Set rsError = Nothing
	Dim FromArray(1), ToArray(1), CCArray(3)
	FromArray(0) = "Error Checking Function"
	FromArray(1) = "error@" & homepage_url
	ToArray(0) = developer1_name
	ToArray(1) = developer1_email
	CCArray(0) = developer2_name
	CCArray(1) = developer2_email
	CCArray(2) = developer3_name
	CCArray(3) = developer3_email
	SendEMail FromArray, ToArray, CCArray, 0, "text/html", "Error on " & homepage_url, MailBody, 3
End Sub

'-- Error checking function:
' This records any errors that occur, and returns and non-zero value (the error number) if there was an error.
' If clear is non-zero, then the error will be cleared.
Function ErrorCheck(ErrFile, ErrLineNumber, ErrClear, ErrRecord)
	ErrNum = Err.number
	If ErrNum <> 0 And ErrRecord <> False Then ErrorRecord ErrNum, ErrFile, ErrLineNumber, Err.description
	If ErrClear <> 0 Then Err.Clear ()
	ErrorCheck = ErrNum
End Function

Function SendEMail(FromArray, ToArray, CCArray, BCCArray, Format, Subject, BodyText, Priority)
	Dim Mailer, cMailer, i, prior
	'' check to see if arrays are right size
	'' e_notimpl
	If UseASPMail Then
		Set Mailer = Server.CreateObject("SMTPsvg.Mailer")
		Mailer.RemoteHost = mailhost
		Mailer.FromName = FromArray(0)
		Mailer.FromAddress = FromArray(1)
		If IsArray(ToArray) Then
			For i = 0 To UBound(ToArray) Step 2
				Mailer.AddRecipient ToArray(i), ToArray(i + 1)
			Next
		End If
		If IsArray(CCArray) Then
			For i = 0 To UBound(CCArray) Step 2
				Mailer.AddCC CCArray(i), CCArray(i + 1)
			Next
		End If
		If IsArray(BCCArray) Then
			For i = 0 To UBound(BCCArray) Step 2
				Mailer.AddBCC BCCArray(i), BCCArray(i + 1)
			Next
		End If
		Mailer.Subject = Subject
		Mailer.ContentType = Format
		Mailer.BodyText = BodyText
		Mailer.Priority = Priority
		Mailer.SMTPLog = "C:\ASPMail.log"
		SendEMail = Mailer.SendMail
	ElseIf TestForObject("CDONTS.Newmail") = True Then
		Set cMailer = Server.CreateObject("CDONTS.Newmail")
		cMailer.From = CreateCDONTSAddress(FromArray)
		cMailer.To = CreateCDONTSAddress(ToArray)
		cMailer.Cc = CreateCDONTSAddress(CCArray)
		cMailer.Bcc = CreateCDONTSAddress(BCCArray)
		If Format = "text/html" Then
			cMailer.BodyFormat = 0
			cMailer.MailFormat = 0
		Else
			cMailer.BodyFormat = 1
			cMailer.MailFormat = 1
		End If
		If Priority = 3 Then
			prior = 1
		ElseIf Priority > 3 Then prior = 0
		ElseIf Priority < 3 Then prior = 2
		End If
		cMailer.Subject = Subject
		cMailer.Body = BodyText
		cMailer.Importance = Prior
		SendEMail = True
		cMailer.Send
	End If
End Function

Function CreateCDONTSAddress(TheArray)
	Dim i, cs
	If IsArray(TheArray) Then
		For i = 0 To UBound(TheArray) Step 2
			If TheArray(i + 1) <> "" Then
				cs = cs & TheArray(i) & " <" & TheArray(i + 1) & ">"
			End If
		Next
	Else cs = ""
	End If
	CreateCDONTSAddress = cs
End Function

Function CheckShowS(Value)
	CheckShowS = ""
	If IsNumeric(Value) = True Then
		If CDbl(Value) <> 1 Then CheckShowS = "s"
	End If
End Function

Function TestForObject(theObjectIdentifier)
	On Error Resume Next
	Dim objTest
	Set	objTest = Server.CreateObject (theObjectIdentifier)
	If err.Number <> 0 Then TestForObject = False Else TestForObject = True
	err.Clear
	Set objTest = Nothing
End Function

Sub ShowChart(q1, explanation, ByRef aValues, ByRef aLabels)
	Const gr_WIDTH  = 180
	iMaxValue = 0
	For I = 0 To UBound(aValues)
		If iMaxValue < aValues(I) Then iMaxValue = aValues(I)
	Next
	Response.Write"<table border=0><tr><td colspan=2><font"&fc&" size=2><b>"&q1&"</td></tr>"
	If IsArray(aLabels) Then
		For I = 1 To UBound(aValues)
		Response.Write"<tr><td nowrap><img src=""img/checkmark.gif"" hspace=4 width=8 height=8><font"&fc&" size=2>"&aLabels(I)&"</td>"
		iBarWidth =  CLng((aValues(I) / iMaxValue) * gr_WIDTH)
		If aValues(I) = 1 Then shocnt = "y" Else shocnt = "ies"
		Response.Write"<td><img src=""img/bar_red.gif"" width="&iBarWidth&" height=8 alt="""&aValues(I)&" repl"&shocnt&""" border=0></a></td></tr>"
		Next
	End If
	Response.Write"</table>"
	If Trim("X"&explanation) <> "X" Then Response.Write"<br><br><b>Explanation:</b> "&explanation
End Sub

Function IsLoggedIn()
	Dim rs
	Set rs = cnDZ.Execute("SELECT EMail, Password FROM tblContacts WHERE EMail = '"&Session("USERNAME")&"' AND Password = '"&Session("PASSWORD")&"';")
	If rs.EOF Then
		IsLoggedIn = False
	Else IsLoggedIn = True
	End If
	rs.Close
	Set rs = Nothing
End Function

Sub DisplayLogin (page, finalpage)
	Dim rs, strttl
	Set rs = cnDZ.Execute("SELECT * FROM tblContacts WHERE EMail = '"&Request.Form("USERNAME")&"' AND Password = '"&Request.Form("PASSWORD")&"';")
	If rs.EOF Then strttl = "Login" Else strttl = "Welcome"
	If IsLoggedIn() Then Response.Redirect finalpage
	Response.Write"<br><br><center><table cellspacing=0 cellpadding=0 width=""80%"" border=0>"&_
	"<tr height=4><td colspan=3><img src=""img/no.gif"" width=4 height=4></td></tr>"&_
	"<tr height=8><td><img src=""img/no.gif"" width=8 height=8></td><td bgcolor=""#"&clr1&""" rowspan=2><b><font size=4 style=""letter-spacing:2px"">&nbsp;"&strttl&"&nbsp;</td><td width=""100%""><img src=""img/no.gif"" width=8 height=8></td></tr>"&_
	"<tr height=8><td bgcolor=""#"&clr1&"""><img src=""img/no.gif"" width=8 height=8></td><td width=""100%"" bgcolor=""#"&clr1&"""><img src=""img/no.gif"" width=8 height=8></td></tr>"&_
	"<tr height=3><td bgcolor=""#"&clr1&""" colspan=3><img src=""img/no.gif"" width=3 height=3></td></tr>"&_
	"</table><table cellspacing=0 cellpadding=1 width=""80%"" border=0>"&_
	"<tr><td bgcolor=""#"&clr1&""">"&_
	"<table cellpadding=4 cellspacing=0 width=""100%"" border=0>"&_
	"<tr><td bgcolor=""#FFFFFF"" align=right><font size=4><b><i>ExtraNet</td></tr>"
	If rs.EOF Then
		Response.Write"<tr><td bgcolor=""#FFFFFF""><font"&fc&" size=1><font size=4><b>Please Login:</b></font>"
		If Request.Form("USERNAME") <> "" Or Request.Form("PASSWORD") <> "" Then Response.Write"<br>Forgot your password? <a href=""http://"&homepage_url&"/tek9.asp?pg=pwd_reminder"">Email it to me</a>!"
		Response.Write"</td></tr>"&_
		"<tr><td bgcolor=""#"&clr2&"""><font"&fc&" size=2><b>"&_
		"<form action=""" & page & """ method=post>"&_
		"Enter your Email: <input type=text size=16 maxlength=50 name=USERNAME><br>"&_
		"Enter your Password: <input type=password size=16 maxlength=50 name=PASSWORD>"&_
		"<input type=image src=""img/submit.gif"" value=Submit width=16 height=16 hspace=2 align=absmiddle alt=Submit border=0>"&_
		"</td></tr>"&_
		"<tr><td bgcolor=""#FFFFFF""><font"&fc&" size=1><label for=savecookie><input type=checkbox id=savecookie name=savecookie checked>Save my Login, so I won't have to do this again.</label></td></tr></form></table></td></tr></table></center><br><br>"
	Else
		save = Request.Form("savecookie")
		Session("USERNAME") = Request.Form("USERNAME")
		Session("PASSWORD") = Request.Form("PASSWORD")
		Session("OrderIDIncrement") = rs("OrderIDIncrement")
		If cid <> "" Then
			cnDZ.Execute("Update tblCart Set CUSTID = " & rs("CUSTID") & " Where CustID = " & cid)
		End If
		Response.Cookies("Username") = Session("USERNAME")
		Response.Cookies("cid") = Encrypt(rs("CUSTID"))
		If save <> "" Then
			Response.Cookies("Username").Expires = Date + 14
			Response.Cookies("cid").Expires = Date + 14
		End If
		Response.Redirect finalPage
	End If
	rs.Close
	Set rs = Nothing
End Sub

Function Min(val1, val2)
	If val1 < val2 Then Min = val1 Else Min = val2
End Function

Function Max(val1, val2)
	If val1 > val2 Then Max = val1 Else Max = val2
End Function

Sub CheckConnection
	If Response.IsClientConnected = False Then Response.End
End Sub

Function IIF(condition, truevalue, falsevalue)
	If condition <> False Then IIF = truevalue Else IIF = falsevalue
End Function

Function HiLight(expression, ByVal search, bgColor)
	Dim a, b, c, output, itemLength
	a = 1
	b = 1
	If InStr(1, search, "%") Then search = Split(search, "%")
	Do Until b = 0 Or b = Len(expression) + 1
		If IsArray(search) Then
			b = Len(expression) + 1
			For i = 0 To UBound(search)
				c = InStr(a, expression, search(i), 1)
				If c < b And c <> 0 Then
					b = c
					itemLength = Len(search(i))
				End If
			Next
		Else
			b = InStr(a, expression, search, 1)
			itemLength = Len(search)
		End If
		If b > 0 And b <= Len(expression) Then
			output = output & mid(expression, a, b - a)
			output = output & "<b style=""background:"&bgColor&""">"&Mid(expression, b, itemLength)&"</b>"
			a = b + itemLength
		End If
		CheckConnection
	Loop
	output = output & mid(expression, a)
	HiLight = output
End Function

Function GetQuantityInStock(itemId, SelectedModifiers)
	Dim rs, qtyinstock, validComplex, complexQis
	Set rs = cnDZ.Execute("SELECT * FROM TBLINVENTORY WHERE ITEMID = "&itemID&";")
	validComplex = True
	complexQis = rs("ComplexQIS")
	If Trim(complexQis) = "" Then validComplex = False
	If IsNull(complexQis) = True Then validComplex = False
	If Trim(rs("QIS")) <> "" Then qtyinstock = rs("QIS")
	If validComplex = True Then
		qis = Split(complexQis, ",")
		selectString = ""
		For Each item In SelectedModifiers
			If Not (InStr(1, item, "Cost") > 0) Then selectString = selectString & SelectedModifiers(item) & "&"
		Next
		s = Split(selectString, "&")
		'Order the list so it's always in ascending order, the way the DB should be
		i = 0
		Do Until i > UBound(s) - 1
			If Trim(s(i)) = "" Or Trim(s(i + 1)) = "" Then Exit Do
			If CLng(s(i)) > CLng(s(i + 1)) Then
				t = s(i)
				s(i) = s(i + 1)
				s(i + 1) = t
				If i > 0 Then i = i - 1
			Else i = i + 1
			End If
			CheckConnection
		Loop
		selectString = Join(s, "&")
		For i = 0 To UBound(qis)
			a = InStr(1, qis(i), ":")
			If a > 0 Then
				If Left(qis(i), a - 1) & "&" = selectString Then
					qtyinstock = Split(qis(i), ":")(1)
					Exit For
				End If
			End If
		Next
	End If
	rs.Close
	Set rs = Nothing
	GetQuantityInStock = qtyInStock
End Function

Function FindComplexValue(ComplexString, searchString, separator1, separator2, defaultValue)
	Dim CurrentValue, index, index2
	Dim search, cplx
	cplx = ComplexString & separator2
	search = searchString & separator1
	index = InStr(1, cplx, search)
	If index > 0 Then
		index = index + Len(search)
		index2 = InStr(index, cplx, separator2)
		If index2 > index + 1 Then
			FindComplexValue = mid(cplx, index, index2 - index)
		Else FindComplexValue = defaultValue
		End If
	Else FindComplexValue = defaultValue
	End If
	'OutputDebugString "Index: " & index & "<BR>" & "Index2: " & index2 & "<BR>"
End Function

Function NullSafeNumber(val)
	NullSafeNumber = IIF(IsNull(val), 0, val)
End Function

Sub DisplayRandomProducts (ProductCount)
	Dim rs, LinkCount, i, j
	Set rs = Server.CreateObject("ADODB.Recordset")
	rs.Open "SELECT ITEMID, ITEMNAME, DESCRIP, IMG_SMALL, SHOWIT FROM tblInventory WHERE SHOWIT = -1;", cnDZ, adOpenStatic, adLockOptimistic
	If rs.EOF Then Exit Sub
	ItemCount = ProductCount
	If ItemCount > rs.RecordCount Then ItemCount = rs.RecordCount 
	ReDim Items(ItemCount - 1)
	Randomize Timer
	For i = 0 To UBound(Items)
		Do
			Items(i) = CLng(Rnd * rs.RecordCount - 1)
			For j = 0 To i - 1
				If Items(i) = Items(j) Then items(i) = -1
			Next
			If Response.IsClientConnected = False Then Response.End 
		Loop While Items(i) = -1
	Next
	Response.Write"<p><table border=0 cellspacing=6 width=""100%"">"
	LinkCount = 0
	For i = 0 To UBound(Items)
		rs.MoveFirst
		rs.Move Items(i)
		If Trim(rs("IMG_SMALL")&"X") = "X" Then thmnl = "" Else thmnl = "<img src=""img/products/"&rs("IMG_SMALL")&""" align=left border=0>"
		If LinkCount Mod 2 = 0 Then
			If LinkCount <> 0 Then Response.Write"</tr><tr><td colspan=2><hr size=1 noshade></td></tr>"
			Response.Write"<tr><td><a href=""tek9.asp?pg=products&specific="&Encrypt(rs("ITEMID"))&""">"&thmnl&"<b>"&rs("ITEMNAME")&"</b></a><br>"&Excerpt(rs("DESCRIP"),40,110)&"</td>"
		Else Response.Write"<td><a href=""tek9.asp?pg=products&specific="&Encrypt(rs("ITEMID"))&""">"&thmnl&"<b>"&rs("ITEMNAME")&"</b></a><br>"&Excerpt(rs("DESCRIP"),40,110)&"</td>"
		End If
		LinkCount = LinkCount + 1
	Next
	If LinkCount Mod 2 = 1 Then Response.Write"<td>&nbsp;</td>"
	Response.Write"</tr></table>"
	rs.Close
	Set rs = Nothing
End Sub

%>
