<?
include("login.class.php");
$l = new login('POST'/*method of posting*/,'index.php'/*post to*/,'100%'/*width*/, ''/*height*/, 'crisp'/*mode*/);
$l->setUsernameLabel("username");
$l->setPasswordLabel("password");
$l->setSubmitLabel("go");
$l->show();
echo $l->usernameLabel.": ".$l->getUsernameVar()."<br>"; //the username value submitted
echo $l->passwordLabel.": ".$l->getPasswordVar(); //the password value submitted
?>