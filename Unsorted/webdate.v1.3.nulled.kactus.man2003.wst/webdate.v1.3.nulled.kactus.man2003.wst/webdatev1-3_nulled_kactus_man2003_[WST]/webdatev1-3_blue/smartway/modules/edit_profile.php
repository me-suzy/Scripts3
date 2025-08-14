<?
	//include "temp_include.inc.php";
	
	$show_login = 0;
	
	if($profile_id)
	{
		settype($member_id, "integer");
		$login = $member_info["login"];
		$query = "select id from dt_members where login = '$login' and id != '$member_id'";
		$res = q($query);
		$i = 1;
		while(nr($res) > 0)
		{
			$login = $member_info["login"].$i;
			$i++;
			$msg = "User with login '".$member_info["login"]."' exists. Login was changed to '$login'. You may change it to yours.";
			$query = "select id from dt_members where login = '$login' and id != '$member_id'";
			$res = q($query);
		}
		
		$reg_date_arr = preg_split("/\//",$member_info[reg_date], 3, PREG_SPLIT_NO_EMPTY);
		$member_info[reg_date] = mktime(0, 0, 0, $reg_date_arr[0], $reg_date_arr[1], $reg_date_arr[2]);
		
		$reg_date_arr = preg_split("/\//",$member_info[system_status_end], 3, PREG_SPLIT_NO_EMPTY);
		$member_info[system_status_end] = mktime(0, 0, 0, $reg_date_arr[0], $reg_date_arr[1], $reg_date_arr[2]);

        $reg_date_arr = preg_split("/\//",$member_info[unlimited_end], 3, PREG_SPLIT_NO_EMPTY);
        $member_info[unlimited_end] = mktime(0, 0, 0, $reg_date_arr[0], $reg_date_arr[1], $reg_date_arr[2]);

        if($member_info[reg_date] == -1) $member_info[reg_date] = 0;
        if($member_info[unlimited_end] == -1) $member_info[unlimited_end] = $member_info[reg_date];
        
		if($member_info[reg_date] == -1) $member_info[reg_date] = 0;
		if($member_info[system_status_end] == -1) $member_info[system_status_end] = $member_info[reg_date];
		
		if($profile_id == "new")
		{
			$query = "insert into dt_members (name) values ('$member_info[name]')";
			q($query);
			$member_id = mysql_insert_id();
			
			$query = "insert into dt_profile (member_id) values ('$member_id')";
			q($query);
			$profile_id = mysql_insert_id();
		}
		settype($profile_id, "integer");
		$query = "update dt_members set
					login = '$login',
					pswd = '$member_info[pswd]',
					email = '$member_info[email]',
					name = '$member_info[name]',
					gender = '$member_info[gender]',
					age = '$member_info[age]',
					country = '$member_info[country]',
					looking_for = '$member_info[looking_for]',
					reg_date = '$member_info[reg_date]',
					system_status = '$member_info[system_status]',
					system_status_end = '$member_info[system_status_end]',
					unlimited = '$member_info[unlimited]',
					unlimited_end = '$member_info[unlimited_end]'
					where id = '$member_id'";
					
		q($query);
        $lzip = f(q("select longw, latn from dt_zips where zipcode = '$profile_info[zipcode]'"));
        if(!$lzip[longw])
            $lzip[longw] = 0;
        if(!$lzip[latn])
            $lzip[latn] = 0;

		$query = "update dt_profile set
					name = '$profile_info[name]',
					gender = '$profile_info[gender]',
					state = '$profile_info[state]',
					city = '$profile_info[city]',
					country = '$profile_info[country]',
					email = '$profile_info[email]',
					birth_month = '$profile_info[birth_month]',
					birth_day = '$profile_info[birth_day]',
					birth_year = '$profile_info[birth_year]',
					marital_status = '$profile_info[marital_status]',
					children = '$profile_info[children]',
					drinking = '$profile_info[drinking]',
					smoking = '$profile_info[smoking]',
					food = '$profile_info[food]',
					eye_color = '$profile_info[eye_color]',
					hair_color = '$profile_info[hair_color]',
					height = '$profile_info[height]',
					body_type = '$profile_info[body_type]',
					race = '$profile_info[race]',
					religion = '$profile_info[religion]',
					occupation = '$profile_info[occupation]',
					education = '$profile_info[education]',
					lang_1 = '$profile_info[lang_1]',
					lang_1_rate = '$profile_info[lang_1_rate]',
					lang_2 = '$profile_info[lang_2]',
					lang_2_rate = '$profile_info[lang_2_rate]',
					lang_3 = '$profile_info[lang_3]',
					lang_3_rate = '$profile_info[lang_3_rate]',
					lang_4 = '$profile_info[lang_4]',
					lang_4_rate = '$profile_info[lang_4_rate]',
					looking_for = '$profile_info[looking_for]',
					age_from = '$profile_info[age_from]',
					age_to = '$profile_info[age_to]',
					general_info = '$profile_info[general_info]',
					appearance_info = '$profile_info[appearance_info]',
					looking_for_info = '$profile_info[looking_for_info]',
                    zipcode = '$profile_info[zipcode]',
                    status = 1,
					longitude = ".$lzip["longw"].",
					latitude = ".$lzip["latn"]." where id = '$profile_id'";
//					zipcode = '$profile_info[zipcode]',

		q($query);

		echo mysql_error();

		$id = $profile_id;

		$query = "delete from dt_relationship_x where profile_id = '$profile_id'";
		q($query);
		settype($profile_info[relationship], "array");
		while(list($k, $v) = each($profile_info[relationship]))
		{
			if($v == "")
				continue;
			$query = "insert into dt_relationship_x (profile_id, relationship_id) values
						('$profile_id', '$v')";
			q($query);
		}
		$query = "delete from dt_interests_x where profile_id = '$profile_id'";
		q($query);
		settype($profile_info[interests], "array");
		while(list($k, $v) = each($profile_info[interests]))
		{
			if($v == "")
				continue;
			$query = "insert into dt_interests_x (profile_id, interest_id) values
						('$profile_id', '$v')";
			q($query);
		}

        $fstamp_res = f(q("select * from dt_stamps_balance where member_id='$member_id'"));
		if($fstamp_res["id"] != "")
		{
			$query = "update dt_stamps_balance set balance = '$profile_info[balance]' where member_id='$member_id'";
		}
		else
		{
			$query = "insert into dt_stamps_balance (member_id, balance) values ('$member_id', '$profile_info[balance]')";
		}
        q($query);
	}
	
	settype($id, "integer");
	if($id)
	{
		$fProfile = f(q("select * from dt_profile where id='$id'"));
		$fMember = f(q("select * from dt_members where id='$fProfile[member_id]'"));
		$fStamp_balance = f(q("select balance from dt_stamps_balance where member_id='$fProfile[member_id]'"));
	
		$fInterests_res = q("select * from dt_interests_x where profile_id='$fProfile[id]'");
		while($row = f($fInterests_res))
		{
			$interests[] = $row["interest_id"];
		}
	
		$fRelationship_res = q("select * from dt_relationship_x where profile_id='$fProfile[id]'");
		while($row = f($fRelationship_res))
		{
			$relationship[] = $row["relationship_id"];
		}
	}
	else
	{
		$id = "new";
		$fMember["login"] = "New member";
		$show_login = 1;
	}
	if($msg)
	{
		echo "<strong>$msg</strong>";
	}
?>


      <table width="100%" border="0" cellspacing="4" cellpadding="4" bgcolor="#FFFFFF">
        <tr>
          <td>
            <table width="95%" border="0" cellspacing="1" cellpadding="0" align="center" bgcolor="#ffffff">
              <form name="form1" action="main.php?service=edit_profile.php" method=post>
			  <input type="hidden" name="profile_id" value="<?php echo $id?>">
			  <input type="hidden" name="member_id" value="<?php echo $fProfile[member_id]?>">
                <tr>
                  <td valign="top" colspan="2" height="20" bgcolor="#D4E6C3"><b>&nbsp;&nbsp;<?php echo $fMember["login"] ?> - User Info</b></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Login</td>
                  <td width="50%" bgcolor="#F0F4EC"><input type="text" name="member_info[login]" value="<?php echo $fMember["login"] ?>"></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Password</td>
                  <td width="50%" bgcolor="#F0F4EC"><input type="text" name="member_info[pswd]" value="<?php echo $fMember["pswd"] ?>"></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;E-mail</td>
                  <td width="50%" bgcolor="#F0F4EC"><input type="text" name="member_info[email]" value="<?php echo $fMember["email"] ?>"></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Name</td>
                  <td width="50%" bgcolor="#F0F4EC"><input type="text" name="member_info[name]" value="<?php echo $fMember["name"] ?>"></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Gender</td>
                  <td width="50%" bgcolor="#F0F4EC">
                    <select name="member_info[gender]">
                      <option value="male"<?php echo (strtolower($fMember["gender"]) == "male") ? " selected" : ""?>>Male</option>
                      <option value="female"<?php echo (strtolower($fMember["gender"]) == "female") ? " selected" : ""?>>Female</option>
                    </select>
				  </td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Age</td>
                  <td width="50%" bgcolor="#F0F4EC"><input type="text" name="member_info[age]" value="<?php echo $fMember["age"] ?>"></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Country</td>
                  <td width="50%" bgcolor="#F0F4EC"><input type="text" name="member_info[country]" value="<?php echo $fMember["country"] ?>"></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Looking For</td>
                  <td width="50%" bgcolor="#F0F4EC">
                    <select name="member_info[looking_for]">
                      <option value="Male"<?php echo (strtolower($fMember["looking_for"]) == "male") ? " selected" : ""?>>Male</option>
                      <option value="Female"<?php echo (strtolower($fMember["looking_for"]) == "female") ? " selected" : ""?>>Female</option>
                    </select>
				  </td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Registration Date (mm/dd/yyyy)</td>
                  <td width="50%" bgcolor="#F0F4EC"><input type="text" name="member_info[reg_date]" value="<?php echo date("m/d/Y", $fMember["reg_date"]) ?>"></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Contact for free</td>
                  <td width="50%" bgcolor="#F0F4EC">
                    <select name="member_info[system_status]">
                      <option value="1"<?php echo ($fMember["system_status"] == "1") ? " selected" : ""?>>Active</option>
                      <option value="0"<?php echo ($fMember["system_status"] == "0") ? " selected" : ""?>>Not Active</option>
                    </select>
				  </td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Subscription End (mm/dd/yyyy)</td>
                  <td width="50%" bgcolor="#F0F4EC"><input type="text" name="member_info[system_status_end]" value="<?php echo @date("m/d/Y", $fMember["system_status_end"]) ?>"></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Unlimited Contacts</td>
                  <td width="50%" bgcolor="#F0F4EC">
                    <select name="member_info[unlimited]">
                      <option value="1"<?php echo ($fMember["unlimited"] == "1") ? " selected" : ""?>>Active</option>
                      <option value="0"<?php echo ($fMember["unlimited"] == "0") ? " selected" : ""?>>Not Active</option>
                    </select>
				  </td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Subscription End (mm/dd/yyyy)</td>
                  <td width="50%" bgcolor="#F0F4EC"><input type="text" name="member_info[unlimited_end]" value="<?php echo @date("m/d/Y", $fMember["unlimited_end"]) ?>"></td>
                </tr>
                <tr>
                  <td valign="top" colspan="2" height="20" bgcolor="#D4E6C3"><b>&nbsp;&nbsp;<?php echo $fMember["login"] ?> - Profile Info</b></td>
                </tr>
				
				
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Profile Name</td>
                  <td width="50%" bgcolor="#F0F4EC"><input type="text" name="profile_info[name]" value="<?php echo $fProfile["name"] ?>"></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Gender</td>
                  <td width="50%" bgcolor="#F0F4EC">
                    <select name="profile_info[gender]">
      <?php
    $gender_list = array(
    	"Male" => "Male",
    	"Female" => "Female",
    	"Mixed Couple" => "Mixed Couple",
    	"Female Couple" => "Female Couple",
    	"Male Couple" => "Male Couple"
    );

	$gender_list1 = $gender_list;
    // $gender_list is set in the index.php

//    $gender_list[""] = "Any";
    
	reset($gender_list);
    while(list($k, $v) = each($gender_list))
    {
    	$sel = "";
    	if($fProfile["gender"] == $k)
    	{
    		$sel = "selected";
    	}
?>
        <option value='<? echo $k; ?>' <? echo $sel; ?>><? echo $v; ?></option>
<?php    	
    }
      ?>
                    </select>
				  </td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Resident
                    country</td>
                  <td width="50%" bgcolor="#F0F4EC"><? sysMGetSelect("profile_info[country]", "dt_countries", $fProfile["country"], 0) ?></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;State</td>
                  <td width="50%" bgcolor="#F0F4EC"><input type="text" name="profile_info[state]" value="<?php echo $fProfile["state"] ?>"></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;City</td>
                  <td width="50%" bgcolor="#F0F4EC"><input type="text" name="profile_info[city]" value="<?php echo $fProfile["city"] ?>"></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Zipcode</td>
                  <td width="50%" bgcolor="#F0F4EC"><input type="text" name="profile_info[zipcode]" value="<?php echo $fProfile["zipcode"] ?>"></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;E-mail</td>
                  <td width="50%" bgcolor="#F0F4EC"><input type="text" name="profile_info[email]" value="<?php echo $fProfile["email"] ?>"></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Birthdate (mm/dd/yyyy)</td>
                  <td width="50%" bgcolor="#F0F4EC">
				  	<input type="text" name="profile_info[birth_month]" size="2" maxlength="2" value="<?php echo  $fProfile["birth_month"] ?>">
				  	<input type="text" name="profile_info[birth_day]" size="2" maxlength="2" value="<?php echo  $fProfile["birth_day"] ?>">
				  	<input type="text" name="profile_info[birth_year]" size="4" maxlength="4" value="<?php echo  $fProfile["birth_year"] ?>">
				  </td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Marital Status</td>
                  <td width="50%" bgcolor="#F0F4EC"><? sysMGetSelect("profile_info[marital_status]", "dt_marital_status", $fProfile["marital_status"], 0) ?></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Children</td>
                  <td width="50%" bgcolor="#F0F4EC"><input type="text" name="profile_info[children]" value="<?php echo $fProfile["children"] ?>"></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Drinking</td>
                  <td width="50%" bgcolor="#F0F4EC"><? sysMGetSelect("profile_info[drinking]", "dt_drinking", $fProfile["drinking"], 0) ?></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Smoking</td>
                  <td width="50%" bgcolor="#F0F4EC"><? sysMGetSelect("profile_info[smoking]", "dt_smoking", $fProfile["smoking"], 0) ?></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Food</td>
                  <td width="50%" bgcolor="#F0F4EC"><? sysMGetSelect("profile_info[food]", "dt_food", $fProfile["food"], 0) ?></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Eye Color</td>
                  <td width="50%" bgcolor="#F0F4EC"><? sysMGetSelect("profile_info[eye_color]", "dt_eye_colors", $fProfile["eye_color"], 0) ?></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Hair Color</td>
                  <td width="50%" bgcolor="#F0F4EC"><? sysMGetSelect("profile_info[hair_color]", "dt_hair_colors", $fProfile["hair_color"], 0) ?></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Height</td>
                  <td width="50%" bgcolor="#F0F4EC"><? sysMGetSelect("profile_info[height]", "dt_heights", $fProfile["height"], 0) ?></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Body Type</td>
                  <td width="50%" bgcolor="#F0F4EC"><? sysMGetSelect("profile_info[body_type]", "dt_body_types", $fProfile["body_type"], 0) ?></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Race</td>
                  <td width="50%" bgcolor="#F0F4EC"><? sysMGetSelect("profile_info[race]", "dt_races", $fProfile["race"], 0) ?></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Religion</td>
                  <td width="50%" bgcolor="#F0F4EC"><? sysMGetSelect("profile_info[religion]", "dt_religions", $fProfile["religion"], 0) ?></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Occupation</td>
                  <td width="50%" bgcolor="#F0F4EC"><? sysMGetSelect("profile_info[occupation]", "dt_occupations", $fProfile["occupation"], 0) ?></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Education</td>
                  <td width="50%" bgcolor="#F0F4EC"><? sysMGetSelect("profile_info[education]", "dt_educations", $fProfile["education"], 0) ?></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Languages</td>
                  <td width="50%" bgcolor="#F0F4EC">
					<? sysMGetSelect("profile_info[lang_1]", "dt_languages", $fProfile["lang_1"], 2)?><? sysMGetSelect("profile_info[lang_1_rate]", "dt_lang_rates", $fProfile["lang_1_rate"], 0)?><br>
					<? sysMGetSelect("profile_info[lang_2]", "dt_languages", $fProfile["lang_2"], 2)?><? sysMGetSelect("profile_info[lang_2_rate]", "dt_lang_rates", $fProfile["lang_2_rate"], 0)?><br>
					<? sysMGetSelect("profile_info[lang_3]", "dt_languages", $fProfile["lang_3"], 2)?><? sysMGetSelect("profile_info[lang_3_rate]", "dt_lang_rates", $fProfile["lang_3_rate"], 0)?><br>
					<? sysMGetSelect("profile_info[lang_4]", "dt_languages", $fProfile["lang_4"], 2)?><? sysMGetSelect("profile_info[lang_4_rate]", "dt_lang_rates", $fProfile["lang_4_rate"], 0)?>
				  </td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Looking For</td>
                  <td width="50%" bgcolor="#F0F4EC">
                    <select name="profile_info[looking_for]">
                      <option value="male"<?php echo (strtolower($fProfile["looking_for"]) == "male") ? " selected" : ""?>>Male</option>
                      <option value="female"<?php echo (strtolower($fProfile["looking_for"]) == "female") ? " selected" : ""?>>Female</option>
                    </select>
				  </td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Age From</td>
                  <td width="50%" bgcolor="#F0F4EC"><input type="text" name="profile_info[age_from]" value="<?php echo $fProfile["age_from"] ?>"></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Age To</td>
                  <td width="50%" bgcolor="#F0F4EC"><input type="text" name="profile_info[age_to]" value="<?php echo $fProfile["age_to"] ?>"></td>
                </tr>
                <!--tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Zip Code</td>
                  <td width="50%" bgcolor="#F0F4EC"><input type="text" name="profile_info[zipcode]" value="<?php echo $fProfile["zipcode"] ?>"></td>
                </tr-->
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Stamp Balance</td>
                  <td width="50%" bgcolor="#F0F4EC"><input type="text" name="profile_info[balance]" value="<?php echo $fStamp_balance["balance"] ?>"></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;General Info</td>
                  <td width="50%" bgcolor="#F0F4EC"><textarea name="profile_info[general_info]" cols="50" rows="5"><?php echo $fProfile["general_info"] ?></textarea></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Appearance Info</td>
                  <td width="50%" bgcolor="#F0F4EC"><textarea name="profile_info[appearance_info]" cols="50" rows="5"><?php echo $fProfile["appearance_info"] ?></textarea></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Looking For Info</td>
                  <td width="50%" bgcolor="#F0F4EC"><textarea name="profile_info[looking_for_info]" cols="50" rows="5"><?php echo $fProfile["looking_for_info"] ?></textarea></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Interests</td>
                  <td width="50%" bgcolor="#F0F4EC"><? sysGetMultSelect("profile_info[interests]", "dt_interests", 5, $interests) ?></td>
                </tr>
                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;&nbsp;Relationship</td>
                  <td width="50%" bgcolor="#F0F4EC"><? sysGetMultSelect("profile_info[relationship]", "dt_relationship", 5, $relationship) ?></td>
                </tr>

                <tr>
                  <td valign="top" width="50%" bgcolor="#F0F4EC">&nbsp;</td>
                  <td width="50%" bgcolor="#F0F4EC">
                    <input type=image src="images/ok.gif" border=0 alt="OK">
                    <a onclick="document.form1.reset();return false;"
href="#"><img alt="Clear" src="images/cancel.gif" border="0"></a>
                  </td>
                </tr>
              </form>
            </table>  <br>
          </td>
        </tr>
      </table>
<br>
<br>
