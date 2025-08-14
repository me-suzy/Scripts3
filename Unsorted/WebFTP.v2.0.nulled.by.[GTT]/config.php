<?
class Config
{
     # Default server to connect, used for auto login
 var $d_serv           = 'ftp.null.ru',

     # Default FTP port, should be 21
     $d_port           = 21,

     # Default transfert mode - should be FTP_BINARY
     $d_trans_mode     = FTP_BINARY,

     # Default language
     $d_lang           = 'en',

     # Folder to use once logged
     $d_origdir        = '/',

     # Username by default or used for auto login
     $d_username       = 'anonymous',

     # Default sort order - SORT_NAME, SORT_SIZE, SORT_DATE, SORT_OWNER, SORT_GROUP
     $d_tripar         = SORT_NAME,

     # Default timeout
     $d_timeout        = 2,

     # Use SSL connction by default
     $d_seccon         = NO,

     # Use PASSIVE FTP mode by default
     $d_ispassive      = NO,

     # If a password is set, there won't be a login box and all default
     # parameters are used to connect. BEWARE ! Empty password IS a password.
     # Set to NULL to disable.
     $d_password       = NULL,

     # Valide languages. Must exist in lang/ directory
     $valide_lang      = Array('fr','en'),

     # Allow to change server, or use default ?
     $change_serv      = YES,

     # Allow to set passive, or use default ?
     $change_ispassive = YES,

     # Allow to enable SSL connections, or use default ?
     $change_seccon    = YES,

     # Allow to change timeout, or use default ?
     $change_timeout   = YES,

     # Display the transfert mode checkbox on the login page
     $change_encod     = YES,

     # Allow or not users to change the language
     $change_lang      = YES,

     # This is not easy. This is in fact an ereg() patern to
     # check whenever a file is a banner file.
     # An empty string would disable the banners
     $banner_type      = '(motd\\.txt|^banner|index$|banner$)',

     # Allow or not to directly edit files
     # This creates temporary files with tmpfile() but
     # they should be automaticaly deleted
     $allow_edit       = YES,

     # Allow or not to create dynamic TAR archives
     # This makes use of the 'temp' folder, so temporary
     # files may not be deleted, and you have to do it
     # yourself, even if this shouldn't happen
     $allow_tar        = YES,

     # Allow uploads ?
     $allow_upload     = YES,

     # Enable the action pannel ?
     # If disabled, users can only browse the FTP and download files
     $action_pannel    = YES;
}

?>