; <?php exit("<code>Permission denied</code>"); ?>
; This is main configuration file
; Comments start with ';'
; DON'T DELETE THE FIRST LINE FOR SECURITY REASONS
;
[DATABASE]
dbtype=mysql
dbhost=localhost
dbname=phpfaber_tinylink
dbuser=root
dbpass=
[ADMIN]
alogin=admin
apwd=admin
[WEBSITE]
url_to_index=http://www.example.com/index.php
upp=10
limitrec=1000
refreshrate=3
link_lenght=10
limit=1000000
