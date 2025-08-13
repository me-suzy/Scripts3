<?
function register($username, $email, $password)
{
 
  global $set_evalidation;
  global $code;
  
  $code = rand(99, 5030200) . $username;
  
  $conn = db_connect();
  if (!$conn)
    return "Could not connect to database server - please try later.";

  // Username not taken ?
  $result = mysql_query("select * from user where username='$username'"); 

	if (!$result)
     return "Could not execute query";
  if (mysql_num_rows($result)>0) 
     return "That username is taken - go back and choose another one.";

  $string = "insert into user (username, passwd, email, verify,prefs)values ('$username', password('$password'), '$email','$code','$prefs')";
  $result = mysql_query($string);
  

	if (!$result)
    return "Could not register you  in database - please try again later.";

  return true;
}

function login($username, $password)
{
  $conn = db_connect();
  if (!$conn)
    return 0;


  $result = mysql_query("select * from user 
                         where username='$username'
                         and passwd = password('$password')");
  if (!$result)
     return 0;
  
  if (mysql_num_rows($result)>0)
     return 1;
  else 
     return 0;
}
?>