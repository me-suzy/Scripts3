<%
'this template.inc file just writes the "template", or framework for all your pages and sub-pages, etc.
'the Page Content (PC) for your pages comes directly from the database. this template houses your pages.
'if you copy the html source code from an outside editor such as Frontpage or Dreamweaver into this file,
'do NOT copy in the <html> or <body> tags.. JUST the tags within them only! see example and links below..
%>
<%
Sub TemplateBeforePC
'Begin all the html tags that go Before your Page Content here:
%>



	<a name=top></a>
	<h2>Put the opening html tags for your template here.</h2>
	Feel free to delete all of this text and HTML tags, and create your own. Or, just copy/paste 
	some generated HTML from your favorite wysiwyg editor such 
	as Frontpage, Dreamweaver, Hotdog, or others..<br>
	If you have questions on how to build your template further, feel free to search 
	our online Knowledge Base (http://LINE9.com/support/) for examples.<br>
	<table width="100%" border=2>
	<tr>
	<td width=150 valign=top bgcolor="#F6F6F6">As an example, your main menu or categories could go in this column..<br>
	<br>
	main menu:<br>
		<%
		'-Begin main menu code:
		Set rs = cnDZ.Execute("SELECT PG, MENU_TITLE, MAIN_MENU_ITEM FROM TBLPAGES WHERE MAIN_MENU_ITEM = -1 ORDER BY MENU_TITLE ASC;")
		If Not rs.EOF Then
			Do Until rs.EOF
			If pg = ""&rs("PG")&"" Then mnu = "<u>"&rs("MENU_TITLE")&"</u>" Else mnu = rs("MENU_TITLE")
			Response.Write"<a href=""http://"&homepage_url&"/tek9.asp?pg="&rs("PG")&""">"&mnu&"</a><br>"
			rs.MoveNext
			Loop
			rs.Close
		End If
		Set rs = Nothing
		'-End main menu.
		%>
	&nbsp;<br>
	categories/groups:<br>
		<% ShowGroups NULL, 0, False, False %>
	<br>&nbsp;<br>
	or, categories as a select box:<br>
		<% ShowGroups NULL, 0, False, True %>
	</td>
	<td valign=top width="100%">
	<h4><%=pg_header%></h4>
	<font size=3>
	Your main Page Content will begin here. Use as much HTML tags and/or ASP code as you like.. 
	Your main <b>Page Content</b> will begin here. Use as much HTML tags and/or ASP code as you like.. 
	Your main Page Content will begin here. Use as much HTML tags and/or ASP code as you like.. <p>
	Your main Page Content will begin here. Use as much HTML tags and/or ASP code as you like.. 
	Your main Page Content will begin here. Use as much HTML tags and/or ASP code as you like.. 
	Your main Page Content will begin here. Use as much HTML tags and/or ASP code as you like.. <p>
	Your main <u>Page Content</u> will begin here. Use as much HTML tags and/or ASP code as you like.. 
	Your main Page Content will begin here. Use as much HTML tags and/or ASP code as you like.. 
	Your main Page Content will begin here. Use as much HTML tags and/or ASP code as you like.. <p>




<%
End Sub
Sub TemplateAfterPC
'Begin all the closing html tags that go After your Page Content here:
%>




	</font></td>
	</tr>
	</table>
	<h2>Close your html tags within this Sub-routine.</h2>



<a name=bottom></a>
<img src="img/shopping_cart_software.gif" width=2 height=1 alt="shopping cart software" border=0>
<% End Sub %>




