<?

   if($mid == "") header("Location: index.php");
   else{
	include "_header.php";

	$db = c();


	$r = q("select * from members where login='$mid' and status=0");

        if(e($r)){
	  echo "<b><font size=4>Confirmation error!</font></b><br>";
          echo "We are sorry .. but your login name does not exists or you have already confirmed! ";
        } else {
	  $member = f($r);

          if ($requireapproval) q("update members set status=2 where login='$mid' and status=0");
	else q("update members set status=1 where login='$mid' and status=0");

	  echo "<b><font size=4>Confirmation success!</font></b><br>";
	  echo "User login: $member[login]<br>Dear $member[fname] $member[lname], now you can access your account!<br><a href=".$ROOT_HOST."login.php>Click here to sign in</a>";

        }
        d($db);

	include "_footer.php";
   }
?>