<%
'By default, we use a Microsoft Access System DSN.
'If you do not want to use a System DSN, then just comment out that
' line with an apostrophe (') as you see below. The server will
' then ignore that line.
'To enable one of the lines below, just UN-comment that line by
' removing the apostrophe (') and the server will run it...

'-----------------------------------------------------------------
'MICROSOFT ACCESS USERS:
'-----------------------------------------------------------------

'Here is a Microsoft Access System DSN if you need it:
DSNString = "DSN=mydsn"

'Here is a DSN-less connection if you need it:
'DSNString = "Driver={Microsoft Access Driver (*.mdb)}; DBQ=db\database.mdb"

'Here is an OLEDB connection if you need it:
'dbPath = "C:\mydatabase\someFolder\database.mdb"
'DSNString = "Provider=Microsoft.Jet.OLEDB.3.51;Data Source="&dbPath

'...or this one:
'DSNString = "Provider=Microsoft.Jet.OLEDB.4.0;Data Source="&dbPath


'------------------------------------------------------------------
'SQL SERVER USERS:
' (see the two .SQL files in your \db\ directory for script import)
'------------------------------------------------------------------

'If you are using a SQL Server ODBC Connection, replace ¿..? w/Proper Nouns
'DSNString = "DSN=¿SQLServerDSN?;"

'...or this one: (this is the OLE DB method (FAST!!!)):
'DSNString = "Provider=SQLOLEDB;Data Source=¿SQLServerName?;Initial Catalog=¿DBName?;User ID=sa;Password=;"


%>
