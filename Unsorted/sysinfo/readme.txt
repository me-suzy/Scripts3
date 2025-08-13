
  Sysinfo v1.39
 ---------------
Die Lizenz finden sie unter lizenz.html.

Sie suchen eine Dokumentation zu einem Perl Modul das auf dem Server installiert ist? 
Kein Problem hiermit geht es klicken sie einfach auf den Modul Namen dann erscheint die 
Dokumentation zu dem jeweiligne Perl Modul!

--README (Sysinfo.cgi)

 Sysinfo.cgi öffnen mit Text-Editor

 Kopieren Sie dieses Skript im ASCII-Modus auf Ihren Server und chmoden Sie es mit 755.
 Aufruf: http://www.IhreDomain.de/cgi-bin/sysinfo.cgi (CHMOD 755)

 $free = "50"; # Anzahl an Verfügbaren MB's auf dem Webspace
 Die Zahl "50" durch die jeweilige Anzahl an MB die sie auf ihrem Webspace zur verfügung haben ersetzen.

 $passwort = "test"; # Passwort
 Das Wort "test" durch ihr Passwort ersetzen.

 $gzip = 0;
 Die GZIP Komprimierung. Es muss GZIP vorhanden sein um dies zunutzen. Hiermit wird Ladezeit und Traffic gespart.

-- HTACCESS (txt.htaccess)
 Sofern sie es wünschen Fehlerseiten auf ihrem Webspace zu nutzen dann sollten sie die 
 datei "txt.htaccess" in ".htaccess" umbenennen!
 Bitte öffnen sie die Datei ".htaccess" und ändern sie den pfad zu Sysinfo (/cgi-bin/sysinfo.cgi) viermal ab!
 Die Datei ".htaccess" dann ins root Verzeichnis eures FTP Acccounts ablegen.
 Root Verzeichnis nennt man immer das erste Verzeichnis "/" und nicht ein Unterverzeichnis z.b. "/cgi-bin" !

 Unter /templates können sie die Fehlerseiten an ihre Bedürfnisse anpassen.

 Um neue Templates hinzuzufügen einfach in /templates erstellen und in der .htaccess den Eintrag hinzufügen 
 und die Fehlernummer entsprechend abändern z.b. 404 in 501 umändern.

---------------------------------------------------------------------------
Euer Stefanos - support@coder-world.de - http://www.coder-world.de