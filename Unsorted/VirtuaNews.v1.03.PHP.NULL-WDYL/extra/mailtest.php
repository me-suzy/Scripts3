<?php
echo "<"."?xml version=\"1.0\" encoding=\"utf-8\""."?".">\n";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-scrict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
  <title>PHP Mail Tester</title>
  <meta http-equiv="content-type" content="text/html; charset=windows-urf-8" />
  <style type="text/css">
    div {
      font-family : Verdana,Arial,sans-serif;
      font-size : 10px;
      color : #000000;
    }

    input {
      background-color: #eeeeee;
      font-family: Verdana;
      font-size: 10px;
      color: #000000;
      border: 1px solid #000000;
    }

    a {
      text-decoration: underline;
      color : #015885;
    }
  </style>
</head>

<body>
<?php

if (empty($address)) {

  echo "  <form action=\"mailtest.php\" method=\"get\">
    <div>
      Address to email: <input type=\"text\" name=\"address\" size=\"40\" tabindex=\"1\" />
    </div>
    <div>
      <br />
      <br />
      <input type=\"submit\" value=\"Send email\" tabindex=\"2\" />
    </div>
  </form>\n";

} else {

  $subject = "Testing mail() function";
  $message = "This is a test email.\n\nIf this is received then email works correctly.";

  mail($address,$subject,$message,"From: \"Mail Test\" <$address>");

  echo "  <div>
    The email has been sent.  If no erros were shown then email has been set up properly, otherwise there is most likly a problem with it, please contact your host for help.
  </div>\n";

}

?>
  <div>
    <br />
    Email test script, &#169; Tingle 2002, created for VirtuaNews
  </div>
</body>
</html>