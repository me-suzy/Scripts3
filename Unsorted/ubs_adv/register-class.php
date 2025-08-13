<? 
require("functions.php");
require("header.php");

$userclass_stat = $db_connect->query_first("select userclass from db_users where username='$user_name'");
$userclass = $userclass_stat[userclass];

if($userclass != ""){
      $ifgotaclass="You already have a class. Changing classes will result in the loss of stats.";
}

if($userclass == ""){
      $ifgotaclass="You do not have a class! Please choose one now.";
}

      if($send == send){

if($userclass2==""){ 
$userdeffense="0";
$userattack="0";
$usermagic="0";
$userspeed="0";
}

if($userclass2==fighter){ 
$userdeffense="25"; 
$userattack="30"; 
$usermagic="5"; 
$userspeed="5";
}

if($userclass2==paladin){ 
$userdeffense="35"; 
$userattack="20"; 
$usermagic="10"; 
$userspeed="10";
}

if($userclass2==mage){
$userdeffense="15";
$userattack="10";
$usermagic="50";
$userspeed="15";
}

if($userclass2==archer){ 
$userdeffense="40"; 
$userattack="15"; 
$usermagic="10"; 
$userspeed="30";
}

if($userclass2==amazon){ 
$userdeffense="35"; 
$userattack="15"; 
$usermagic="15"; 
$userspeed="35";
}

if($userclass2==necromancer){ 
$userdeffense="25"; 
$userattack="20"; 
$usermagic="30"; 
$userspeed="25";
}

if($userclass2==druid){ 
$userdeffense="20"; 
$userattack="25"; 
$usermagic="25";
$userspeed="35";
}
                                $db_connect->query("UPDATE db_users SET userclass='$userclass2', userdefense='$userdeffense', userattack='$userattack', usermagic='$usermagic', userspeed='$userspeed' WHERE username = '$user_name'");
      }

if($userclass2==""){ 
$userclass2[1] = "selected";
}

if($userclass2==fighter){ 
$userclass2[2] = "selected";
}

if($userclass2==paladin){ 
$userclass2[3] = "selected"; 
}

if($userclass2==mage){
$userclass2[4] = "selected";
}

if($userclass2==archer){ 
$userclass2[5] = "selected"; 
}

if($userclass2==amazon){ 
$userclass2[6] = "selected"; 
}

if($userclass2==necromancer){ 
$userclass2[7] = "selected"; 
}

if($userclass2==druid){ 
$userclass2[8] = "selected"; 
}

eval("dooutput(\"".gettemplate("register-class")."\");");
?>
			
					