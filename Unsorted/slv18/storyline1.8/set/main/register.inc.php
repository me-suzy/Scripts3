<table border='0' width='100%'>
	<tr>
		<td class='cleardis'>
<?

if ( !$_POST["regsubmit"] ) {

?>

	<form method='post' action='register.php'>
	<table border='0' width='100%' cellspacing='4'>
		<tr>
			<td width='20%'>User Name</td> <td> <input type='text' name='regusername' maxlength='10'></td>
		</tr><tr>
			<td width='20%'>Pen Name</td> <td> <input type='text' name='regpenname' maxlength='10'></td>	
		</tr><tr>		
			<td width='20%'>Password</td> <td> <input type='text' name='regpassword' maxlength='10'></td>
		</tr><tr>		
			<td width='20%'>Email</td> <td> <input type='text' name='regemail'></td>	
		</tr><tr>
			<td colspan='2'><input type='submit' name='regsubmit' value='register'></td>
		</tr>
	</table>	
	</form>

<?

} else {
	$_POST["regusername"] = trim($_POST["regusername"]);
	$_POST["regpenname"] = trim($_POST["regpenname"]);
	$_POST["regpassword"] = strip_tags(trim($_POST["regpassword"]));
	$_POST["regemail"] = trim($_POST["regemail"]);


	if (!$_POST["regpassword"] || !$_POST["regemail"]) 
		print "Error: Please go back and fill in every field";

	elseif (strlen($_POST["regusername"]) > 10 || strlen($_POST["regusername"]) < 3)
		print "Error: Please give a username greater than 3 characters, and less than 11.";

	elseif (strlen($_POST["regpenname"]) > 10 || strlen($_POST["regpenname"]) < 3)
		print "Error: Please give a penname greater than 3 characters, and less than 11.";

	elseif (!eregi('^[A-Z0-9]+@([A-Z0-9-]+.)+([A-Z0-9]){2,4}$',$_POST["regemail"]))
		print "Error: Please give a valid email address.";

	elseif (!eregi("^[A-Z]*$",$_POST["regusername"]) || !eregi("^[A-Z]*$",$_POST["regpenname"]))
		print "Error: User names and Pen names can contain alpha characters only.";

	else {
		$dl = new TheDB();
		$dl->connect() or die($dl->getError());
		$dl->debug=false;

		$table = $dl->select("*","sl18_users",array('urealname'=>$_POST["regusername"]));
		if ( !empty($table) )
			print "The user name is already taken, please choose another";
		else {
			$ins = array(
				array( 
				'urealname'=>$_POST["regusername"],
				'upenname'=>$_POST["regpenname"],
				'uemail'=>$_POST["regemail"],
				'ustart'=>date("Y-m-d"),
				'upass'=>$_POST["regpassword"])
				);

			foreach($ins as $val) {
				$dl->insert("sl18_users",$val) or die($dl->getError());
			}

?>
			<table border='0' width='100%' cellspacing='4'>
				<tr>
					<td width='20%'>User Name</td> <td> <?=$_POST["regusername"]?></td>
				</tr><tr>
					<td width='20%'>Pen Name</td> <td> <?=$_POST["regpenname"]?></td>	
				</tr><tr>		
					<td width='20%'>Password</td> <td> <?=$_POST["regpassword"]?></td>
				</tr><tr>		
					<td width='20%'>Email</td> <td> <?=$_POST["regemail"]?></td>	
				</tr><tr>
					<td colspan='2'>You have registered successfully! Go to the userpanel to log in and upload your work.</td>
				</tr>
			</table>	
<?
		}
	}
}

?>

		</td>
	</tr>
</table>