<?
	/////////////////////////////////////
	// PPC Search Engine.              //
	// (c) Ettica.com, 2002            //
	// Developed by Ettica             //
	/////////////////////////////////////
	//                                 //
	// Members registration processing //
	//                                 //
	/////////////////////////////////////
	require("path.php");
	require($INC."config/config_main.php");
	require($INC."config/config_member.php");
	require("mma.php");
	mysql_connect ($DBHost, $DBLogin, $DBPassword);
	mysql_selectdb ($DBName);
	if(!isset($cmd)||$cmd==""||$cmd==0){
	//main registration form: step 1
		$location = "";
		$tmp = ReadTemplate($MEMBER_REGISTRATION_1_TMP);
		$vars = array("action"=>$MEMBER_REGISTRATION_URL, "login"=>"", "pw"=>"", "rpw"=>"", "error"=>"", "name"=>"", "company"=>"", "email"=>"", "street"=>"", "city"=>"", "state"=>"", "zip"=>"", "country"=>"", "phone"=>"");if(dbSelectCount($TMembersAccounts)>=500){header("Location: ".$SITE_URL."other/sorry.html");exit;}
		print ParseTemplate($tmp, $vars);
	//end of main registration form: step 1
	}elseif($cmd==1){
	//process form
		$location = "";
		$error = "";
		if(!isset($login)||$login=="") $error.="Missing login!<br>";
		else{
			if(preg_match("/[<`;>]/",$login)) $error.="login is incorrect!<br>";
		}
		if(!isset($pw)||$pw=="") $error.="Missing password!<br>";
		if($pw!=$rpw) $error.="You have error in Your password<br>";
		if(!isset($name)||$name=="") $error.="Missing name!<br>";
		else{
			if(preg_match("/[<`;>]/",$name)) $error.="Name is incorrect!<br>";
		}
		if(!isset($email)||$email=="") $error.="Missing email!<br>";
		else{
			if(!preg_match("/^\w+\@\w+(?:\.\w+)+$/",$email)) $error.="Email is incorrect!<br>";
			if(preg_match("/[<`;>]/",$email)) $error.="Illegal character in email!<br>";
		}
		if(!isset($street)||$street=="") $error.="Missing street!<br>";
		else{
			if(preg_match("/[<`;>]/",$street)) $error.="Street is incorrect!<br>";
		}
		if(!isset($city)||$city=="") $error.="Missing city!<br>";
		else{
			if(preg_match("/[<`;>]/",$city)) $error.="City is incorrect!<br>";
		}
		if(!isset($country)||$country=="") $error.="Missing country!<br>";
		else{
			if(preg_match("/[<`;>]/",$country)) $error.="Country is incorrect!<br>";
		}
		if(!isset($zip)||$zip=="") $error.="Missing zip!<br>";
//		else{
//			if(preg_match("/[^0-9]/",$zip)) $error.="Zip is incorrect!<br>";
//		}
		if($error!=""){
			$tmp = ReadTemplate($MEMBER_REGISTRATION_1_TMP);
			$vars = array("action"=>$MEMBER_REGISTRATION_URL, "login"=>$login, "pw"=>"", "rpw"=>"", "error"=>$error, "name"=>$name, "company"=>$company, "email"=>$email, "street"=>$street, "city"=>$city, "state"=>$state, "zip"=>$zip, "country"=>$country, "phone"=>$phone);
			print ParseTemplate($tmp, $vars);
		}else{
			$cpw = md5($pw);
			$F = "MemberID, MemberLogin, MemberPassword, STATUS, CreateDate, BUPW";
			$Q = "null, '".addslashes($login)."', '".$cpw."', 0, CURDATE(), '".addslashes(base64_encode($pw))."'";
			if(dbSelectCount($TMembersAccounts, "MemberLogin='".addslashes($login)."'")==0){
				$id = dbInsert($TMembersAccounts, $F, $Q);
				if($id>0){
					$F = "MemberID, Name, Company, EMail, Street, City, State, Zip, Country, Phone";
					$Q = "$id, '".addslashes($name)."', '".addslashes($company)."', '".addslashes($email)."', '".addslashes($street)."', '".addslashes($city)."', '".addslashes($state)."', '$zip', '".addslashes($country)."', '".addslashes($phone)."'";
					dbInsert($TMembersInfo, $F, $Q);
					session_unregister("member_id");
					session_unregister("member_login");
					session_unregister("member_pw");
					session_register("member_id");
					session_register("member_login");
					session_register("member_pw");
					$member_id = $id;
					$member_login = $login;
					$member_pw = $cpw;
					$location = $MEMBER_REGISTRATION_URL."?cmd=2";
					SendMessage($email, 1, $id, $login, $pw);
				}else ShowError("System error", "can't insert recort to database", $MEMBER_REGISTRATION_URL);
			}else{
				ShowError("Registration error", "such user login is already exists", $MEMBER_REGISTRATION_URL);
			}
		}
	//end of process form
	}elseif($cmd==2){
	//main registration form: step 2
		if(session_is_registered("member_id")&&session_is_registered("member_login")&&session_is_registered("member_pw")){
			if(dbSelectCount($TMembersAccounts, "MemberID=$member_id and MemberLogin='".addslashes($member_login)."' and MemberPassword='".$member_pw."'")>0){
				$location = "";
				$tmp = ReadTemplate($MEMBER_REGISTRATION_2_TMP);
				$vars = array(
					"action"=>$MEMBER_REGISTRATION_URL,
					"error"=>"",
					"name"=>"",
					"ccn"=>"",
					"expires"=>"",
					"ppa"=>""
				);
				print ParseTemplate($tmp, $vars);
			}else{
				ShowError("Login error", "You must register first", $MEMBER_REGISTRATION_URL);
			}
		}else{
			ShowError("Login error", "You must register first", $MEMBER_REGISTRATION_URL);
		}
	//end of main registration form: step 2
	}elseif($cmd==3){
	//process form
		$location = "";
		if(session_is_registered("member_id")&&session_is_registered("member_login")&&session_is_registered("member_pw")){
			if(dbSelectCount($TMembersAccounts, "MemberID=$member_id and MemberLogin='".addslashes($member_login)."' and MemberPassword='".$member_pw."'")>0){
				$error = "";
				if(isset($ccn)&&$ccn!=""){
					if(!isset($name)||$name=="") $error.="Missing name!<br>";
					if(!isset($expires)||$expires=="") $error.="Missing Expiration date!<br>";
					else{
						if(!preg_match("/^\d\d-\d\d$/",$expires)){
							$error.="Expiration date is incorrect!<br>";
						}else{
							$ex2 = explode("-", $expires);
							$expires = "20".$ex2[1]."-".$ex2[0]."-01";
						}
					}
				}else{
					$expires = "0000-00-00";
					if(!isset($ppa)||$ppa=="") $error.= "Missing Pay Pal Account<br>";
				}
				if($error!=""){
					$tmp = ReadTemplate($MEMBER_REGISTRATION_2_TMP);
					$vars = array("action"=>$MEMBER_REGISTRATION_URL, "error"=>$error, "name"=>$name, "ccn"=>$ccn, "expires"=>$expires, "ppa"=>$ppa);
					print ParseTemplate($tmp, $vars);
				}else{
					$F = "MemberID, CCNumber, CCName, CCExpires, PayPalAccount";
					$Q = "$member_id, '".addslashes($ccn)."', '".addslashes($name)."', '".$expires."', '".addslashes($ppa)."'";
					if(dbSelectCount($TMembersCC, "MemberID=$member_id")==0){
						dbInsert($TMembersCC, $F, $Q);
						if($tt = dbSelect($TAdminMembersBonus, "Bonus")){
							dbInsert($TMembersBalance, "MemberID, Balance", "$member_id, ".$tt["Bonus"]);
						}
					}
					$location = $MEMBER_REGISTRATION_URL."?cmd=4";
				}
			}else{
				ShowError("Login error", "You must register first", $MEMBER_REGISTRATION_URL);
			}
		}else{
			ShowError("Login error", "You must register first", $MEMBER_REGISTRATION_URL);
		}
	//end of process form
	}elseif($cmd==4){
	//main registration form: step 3
		if(session_is_registered("member_id")&&session_is_registered("member_login")&&session_is_registered("member_pw")){
			if(dbSelectCount($TMembersAccounts, "MemberID=$member_id and MemberLogin='".addslashes($member_login)."' and MemberPassword='".$member_pw."'")>0){
				$location = "";
				$tmp = ReadTemplate($MEMBER_REGISTRATION_3_TMP);
				$vars = array("action"=>$MEMBER_REGISTRATION_URL, "error"=>"", "title"=>"", "descr"=>"", "url"=>"", "categories"=>GetCategoriesSelectList());
				print ParseTemplate($tmp, $vars);
			}else{
				ShowError("Login error", "You must register first", $MEMBER_REGISTRATION_URL);
			}
		}else{
			ShowError("Login error", "You must register first", $MEMBER_REGISTRATION_URL);
		}
	//end of main registration form: step 3
	}elseif($cmd==5){
	//process form
		$location = "";
		if(session_is_registered("member_id")&&session_is_registered("member_login")&&session_is_registered("member_pw")){
			if(dbSelectCount($TMembersAccounts, "MemberID=$member_id and MemberLogin='".addslashes($member_login)."' and MemberPassword='".$member_pw."'")>0){
				$error = "";
				if(!isset($title)||$title=="") $error.="Missing Site Title!<br>";
				if(!isset($url)||$url=="") $error.="Missing Site URL!<br>";
				else{
					$url = preg_replace("/^http:\/\//i", "", $url);
				}
				if(!isset($category)||$category==""||$category==0){
					$error.="Missing Category!<br>";
					$category = 0;
				}
				if($error!=""){
					$tmp = ReadTemplate($MEMBER_REGISTRATION_3_TMP);
					$vars = array("action"=>$MEMBER_REGISTRATION_URL, "error"=>$error, "title"=>$title, "descr"=>$descr, "url"=>$url, "categories"=>GetCategoriesSelectList($category));
					print ParseTemplate($tmp, $vars);
				}else{
					$logofile = $HTTP_POST_FILES['logo']['tmp_name'];
					$logofilename = basename($HTTP_POST_FILES['logo']['name']);
					if(file_exists($logofile)){
						$logourl = 	md5(microtime()).$logofilename;
						move_uploaded_file($logofile, $LOGOS_DIR.$logourl);
					}
					$F = "MemberID, CategoryID, Title, Descr, Link";
					$Q = "$member_id, $category, '".addslashes($title)."', '".addslashes($descr)."', '".addslashes($url)."'";
					if(dbSelectCount($TMembersSites, "MemberID=$member_id")==0){
						dbInsert($TMembersSites, $F, $Q);
						if($logourl!="") dbInsert($TMembersLogos, "MemberID, LogoURL", "$member_id, '".addslashes($logourl)."'");
					}
					$location = $MEMBER_CP_URL;
				}
			}else{
				ShowError("Login error", "You must register first", $MEMBER_REGISTRATION_URL);
			}
		}else{
			ShowError("Login error", "You must register first", $MEMBER_REGISTRATION_URL);
		}
	}else{
		$location = $MEMBER_REGISTRATION_URL;
	}
	mysql_close();
	if($location!=""){
		header("Location: $location");
		exit;
	}
?>