Q:  How do I import the two .sql files??

A:
Please note, depending on your access rights to the SQL Server you may or
may not be allowed to create a new database.  Contact your DBA/SysAdmin if
you are having trouble logging on or you receive a security message of some
kind.  If you are running on your own SQL Server, consult SQL Server Books On-Line 
for information on creating a new database and administering it in the future.


SQL 6.5

1) Run ISQL_w program.
2) Logon to SQL Server you wish to use.
3) Click Open button on Query Window, or File->Open.
4) Select SQL_Script.SQL
5) Click Play button on Query Window, or Query->Execute, or Ctrl-E.  (This
creates the database.)
6) Click New button on Query Window, or File->New, or Ctrl-N.
7) Change DB dropdown to 'CartPro', if doesn't show up, select '<Refresh>',
then select 'CartPro'.
8) Click Open button on Query Window, or File->Open.
9) Select SQL_Views.SQL
10) Click Play button on Query Window, or Query->Execute, or Ctrl-E.  (This
creates the views on the database.)
11) Done.


SQL 7.0

1) Run Query Analyzer program.
2) Logon to SQL Server you wish to use.
3) Click Open button on Query Window, or File->Open.
4) Select SQL_Script.SQL
5) Click Play button on Query Window, or Query->Execute, or Ctrl-E, or F5.
(This creates the database.)
6) Click New button on Query Window, or Ctrl-N.
7) Change DB dropdown to 'CartPro', if doesn't show up, select '<Refresh>',
then select 'CartPro'.
8) Click Open button on Query Window, or File-Open.
9) Select SQL_Views.SQL
10) Click Play button on Query Window, or Query-Execute, or Ctrl-E, or F5.
(This creates the views on the database.)
11) Done.


SQL 2000 (almost identical to SQL 7.0)


Perform this same procedure for your SQL_Tables.SQL, and you're totally done!
LINE9.com Staff

