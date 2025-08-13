
 Top Downloads 2.0
 Copyright (c)2000 Chi Kien Uong
 URL: http://www.proxy2.de

 This script counts how many times a file has been downloaded.
 It keeps statistics of your downloads and can generate
 a TOP10 of your downloaded files.
 The administration function of this script allows you to
 delete, rename or change count numbers for your downloads.
 It can check a directory that you specify for new files and
 add it to a database. Count numbers and TOP10 can be included
 into a HTML document.

 1.Open download.pl with a text editor.
   Change the url in line one, to the Perl program at your server.
   Usually it is: - /usr/bin/perl or /usr/local/bin/perl for Unix.
                  - C:/Perl/Perl.exe for Windows (use slash "/")
   Set the correct paths and required urls.

 2.Upload all files in ASCII-mode to your cgi-bin directory
   and change mode these files:
    - download.pl         755 (-rwxr-xr-x)
    - status.pl           755 (-rwxr-xr-x)
    - log.txt             666 (-rw-rw-rw-)
    - daylog.txt          666 (-rw-rw-rw-)
    - download_stats.txt  666 (-rw-rw-rw-)

 3.Before you can track a download you have to add it
   to your database.
   Enter http://www.host.com/cgi-bin/download.pl?admin=enter
   and use the scan function to include the files to your database.
   If your file is located outside your base directory each entry
   must be included manually using the submit form.

 4.To count a download use:
   <a href="download.pl?file=filename_id">yourfile</a>
   "filename_id" can be a number or a string. 

 5.To show your top xx downloads enter:
   http://www.host.com/cgi-bin/download.pl?job=show&top=xx

 -- Javascript --
 
 To include the table into a HTML document use:

 <script language="JavaScript" src="http://www.domain.com/cgi-bin/stat_js.pl?top=xx"></script>

 To include a count number into a HTML document use:

 <script language="JavaScript" src="http://www.domain.com/cgi-bin/stat_js.pl?stat=file_id"></script>

 -- SSI Addon --
 
 Most web servers require SSI documents to have the extension .shtml or .shtm
 For Apache web servers:
 - AddType text/html .shtml
 - AddHandler server-parsed .shtml
 
 To include the table into a HTML document use:

 <!--#include virtual="/~you/cgi-bin/status.pl?top=xx" --> or
 <!--#exec cgi="/~you/cgi-bin/status.pl?top?=xx" -->

 To include a count number into a HTML document use:

 <!--#include virtual="/~you/cgi-bin/status.pl?stat=filename_id" --> or
 <!--#exec cgi="/~you/cgi-bin/status.pl?stat=filename_id" -->

