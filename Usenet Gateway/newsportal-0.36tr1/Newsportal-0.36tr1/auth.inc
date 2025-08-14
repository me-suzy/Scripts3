<?
  if   ( $server_auth_http )
  {
   if   ( ! isset($PHP_AUTH_USER))
   {
     header("WWW-Authenticate: Basic realm=\"NewsPortal\"");
     header("HTTP/1.0 401 Unauthorized");
     echo "Authentifizierung erforderlich<br>\n";
     echo "Your username and/or password are invalid.<br>\n";
     echo "Z³y login i/lub has³o\n";
     exit;
   }
   else
   {
       $server_auth_user = $PHP_AUTH_USER;
       $server_auth_pass = $PHP_AUTH_PW;
   }
  }
?>
