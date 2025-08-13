<%
'Dimension these for global access:
Dim ua, BrowserType, BrowserVersion
Dim jsNumVal, popup, ask, showXML, dynrow, dyncell, dyngo, load, divhdr, divhdr2, showborder

'Here is where we load the javascripts and stylesheet stuff for the entire website:
Function LoadStyle
	'Begin with a little browser detection:
	ua = Request.ServerVariables("HTTP_USER_AGENT") & ";"
	BrowserType = IIF(InStr(1, ua, "MSIE") > 0, "IE", "NS")
	BrowserVersion = 0
	If BrowserType = "IE" Then
		a = InStr(1, ua, "MSIE") + 5
		If a > 5 Then
			b = InStr(a, ua, ";")
			If b > a Then BrowserVersion = Mid(ua, a, b - a) Else BrowserVersion = 0
		Else BrowserVersion = 0
		End If
	ElseIf Len(ua) > 12 Then BrowserVersion = Mid(ua, 9, 3)
	Else BrowserVersion = 0
	End If
	If IsNumeric(BrowserVersion) And Trim(BrowserVersion) <> "" Then
		BrowserVersion = CDbl(BrowserVersion)
	Else BrowserVersion = 0
	End If
	'End browser detection.

	If BrowserVersion >= 4 Then
		'Begin standard js, validate the requestform pgNum value, and load empty cart confirm functions:
		If pg <> "default" Then Response.Write"<script src=""inx/ie4_ns4.js"" language=JavaScript></script>"
		jsNumVal = " onSubmit='return dataValidate(this)'"
		popup = " onClick='popup()'"
		ask = " onClick='return ask()'"
		'End standard js.
		If BrowserType = "IE" Then
			If UseMightyZip Then showHelp1 = " onmouseover=""shoHelp(1);"" onmouseout=""hideHelp(1);""" Else showHelp1 = ""
			showHelp2 = " onmouseover=""shoHelp(2);"" onmouseout=""hideHelp(2);"""
			showHelp3 = " onmouseover=""shoHelp(3);"" onmouseout=""hideHelp(3);"""
			If pg = "cart" And mode = "step2" Then
				loadit = " onLoad=""hideShipping()"""
				hideit = " onclick='showhide(0); return true'" 
				style = "style=""display:none"" "
				If Request.Form("SAME") = "on" Then style = ""
				openingNestedTable = "<tr><td colspan=2><div " & style & " class=links><table width=""100%"" cellspacing=0 cellpadding=0 border=0>"
				closingNestedTable = "</table></div></td></tr>"
			End If
			If BrowserVersion >= 5 Then 'if IE5 or greater:
				Response.Write"<script src=""inx/xmljs.asp?pg="&pg&""" language=JavaScript></script>"
				showXML = "Yep"
			Else Response.Write"<script src=""inx/ie4.js"" language=JavaScript></script>" 'must be IE4.x
			End If
			'Begin dyncell.. if >= IE4 then lets show the dynamic mouseover effects:
			Response.Write"<script src=""inx/dynrows.js"" language=JavaScript></script>"
			dynrow = " onmouseover='ovrRow(this)' onmouseout='outRow(this)'"
			dyncell = " onmouseover='ovrCell(this)' onmouseout='outCell(this)' onclick='go(this)'"
			dyngo = " onclick='go(this)'"
			'End dyncell.
			'Begin transition and xml.. this loads the transition text effect, and if IE5+ this will also load the XML stuff (just UN-comment ' the following lines to enable them):
			' load = " onLoad='hdr()'"
			' divhdr = "<div style=""position:relative;height:0;filter:revealTrans(duration=3.0,transition=0);letter-spacing:1px"" id=d><font id=f>"
			' divhdr2 = "</font></div>"
			'End transition and xml.
			'this shows that dynamic bordering effect around all cart buttons (and some others):
			showborder = " onmouseover=""this.style.border='1px solid #"&clr1&"'"" onmouseout=""this.style.border='none'"""
			'proprietary text field sizes (since diff browsers display field size differently):
			srchfldsize = "23"
			bidfldsize = "36"
			bidtxtareasize = "80"
		Else
			'proprietary text field sizes (since diff browsers display field size differently):
			srchfldsize = "14"
			bidfldsize = "20"
			bidtxtareasize = "54"
		End If
		'Begin StyleSheet stuff here:
		Response.Write"<style>"&vbCrLf&_
		"A{ie4:bug}"&vbCrLf&_
		"A{text-decoration:none}"&vbCrLf&_
		"A:hover{color:#"&hoverlink_color&"}"&vbCrLf&_
		"A:hover.x{text-decoration:underline}"&vbCrLf&_
		"INPUT,SELECT,TEXTAREA{color:"&hyperlink_color&";font-size:10px;letter-spacing:1px;font-family:Verdana,Arial}"&vbCrLf&_
		".r{background-color:#"&clr2&"}"&vbCrLf&_
		"A.nl{color:#"&hyperlink_color&"}"&vbCrLf&_
		"A:hover.nl{text-decoration:underline}"&vbCrLf&_
		"TABLE.coupon{border:1px dashed #CCCC99}"&vbCrLf
		
		'Begin Intranet Styles
		Response.Write"BODY.intranet{font-family:Verdana;font-size:10pt}"&vbCrLf&_
		"TD{font-family:Verdana;font-size:10pt}"&vbCrLf&_
		"TD.f3{font-family:Verdana;font-size:10pt}"&vbCrLf&_
		"TD.req{color:#"&required_color&";font-family:Verdana;font-size:10pt}"&vbCrLf&_
		"TR.f3{background-color:#"&clr2&"}"&vbCrLf&_
		"TR.req{color:#"&required_color&";background-color:#"&clr2&"}"&vbCrLf&_
		"A:link{color:#"&hyperlink_color&";text-decoration:none}"&vbCrLf&_
		"A:active{color:red;text-decoration:underline}"&vbCrLf
		'special intranet settings for IE
		If BrowserType = "IE" Then
			Response.Write"INPUT.btn{border:1px outset white;color:#"&hyperlink_color&";font-weight:bold;background:#"&clr1&"}"&vbCrLf&_
			"INPUT.f3,SELECT.f3,TEXTAREA.f3{border:0.2mm groove;color:#"&text_color&"}"&vbCrLf&_
			"INPUT.req,SELECT.req,TEXTAREA.req{border:0.2mm groove;color:#"&required_color&"}"&vbCrLf
		End If
		'End Intranet Styles
		
		Response.Write"</style>"
		'End Style Sheet.
	Else
		'proprietary text field sizes (since diff browsers display field size differently):
		srchfldsize = "13"
		bidfldsize = "20"
		bidtxtareasize = "54"
	End If

End Function




%>
