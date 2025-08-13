<?php
include("../inc/config.php");
include("header.php");
if($act=="delete") {
	if($sure==1) {
		if(mysql_query("DELETE FROM ".$tst["tbl"]["articles"]." WHERE id='$id'")) {
			echo $tst["lang"]["deleteRecordSuccess"];
			echo "<br>".$tst["lang"]["redirecting"];
			echo '<meta http-equiv=refresh content=\'3; url=listAnnc.php\'>';
			echo '<br><br><a class="link1"  href="listAnnc.php">'.$tst["lang"]["click2redirect"].'</a>';
		}
		else {
			echo $tst["lang"]["deleteRecordFailure"];
		}
	}
	else {
		echo $tst["lang"]["sure2delete"];
		echo '<br><a class="link1"  href="mngAnnc.php?act=delete&id='.$id.'&sure=1">'.$tst["lang"]["yes"].'</a> | <a class="link1"  href="listAnnc.php">'.$tst["lang"]["no"].'</a>';
	}
}
if($act=="deleteMulti") {
	if($sure==1) {
		for($i=0;$i<count($delMulti);$i++) {
			if(mysql_query("DELETE FROM ".$tst["tbl"]["articles"]." WHERE id='$delMulti[$i]'")) {
				echo $delMulti[$i]." -- ".$tst["lang"]["deleteRecordSuccess"]."<br>";
			}
			else {
				echo $delMulti[$i]." -- ".$tst["lang"]["deleteRecordFailure"]."<br>";
			}
		}
		echo "<br>".$tst["lang"]["redirecting"];
		echo '<meta http-equiv=refresh content=\'3; url=listAnnc.php\'>';
		echo '<br><br><a class="link1"  href="listAnnc.php">'.$tst["lang"]["click2redirectIndex"].'</a>';

	}
	else {
		echo '<form method="post" action="mngAnnc.php">';
		echo'<input type="hidden" name="act" value="deleteMulti">';
		echo'<input type="hidden" name="sure" value="1">';
		echo $tst["lang"]["sure2deleteSelected"]."<br><br>";
		echo $tst["lang"]["records"]." :<br>";
		for($i=0;$i<count($delMulti);$i++) {
			echo'<input class="formField" type="hidden" name="delMulti[]" value="'.$delMulti[$i].'">';
			echo'<li> # '.$delMulti[$i];
		}
		
		echo '<br><br><input class="formButton" type="submit" value="'.$tst["lang"]["yes"].'"> <input class="formButton" type="button" value="'.$tst["lang"]["no"].'" onclick="parent.location=\'listAnnc.php\'">';
		echo'</form>';
	}
}
if($act=="deleteAll") {
	if($sure==1) {
		if(mysql_query("DELETE FROM ".$tst["tbl"]["articles"]."")) {
			echo $tst["lang"]["deleteAllSuccess"];
			echo "<br>".$tst["lang"]["redirecting"];
			echo '<meta http-equiv=refresh content=\'3; url=listAnnc.php\'>';
			echo '<br><br><a class="link1"  href="listAnnc.php">'.$tst["lang"]["click2redirect"].'</a>';
		}
		else {
			echo $tst["lang"]["deleteAllFailure"];
		}
	}
	else {
		echo $tst["lang"]["sure2deleteAll"];
		echo '<br><a class="link1"  href="mngAnnc.php?act=deleteAll&sure=1">'.$tst["lang"]["yes"].'</a> | <a class="link1"  href="listAnnc.php">'.$tst["lang"]["no"].'</a>';
	}
}
if($act=="edit") {
	$getData2Edit=mysql_query("SELECT * FROM ".$tst["tbl"]["articles"]." WHERE id='$id'");
	$data=mysql_fetch_object($getData2Edit);
	?>
	<form method="get" action="<? $PHP_SELF ?>">
		<table class="text">
		<tr>
		<td colsdpan="2" ><? echo $tst["lang"]["editNewsFormHeader"] ?></td>
		</tr>
		<tr>
		<td><? echo $tst["lang"]["newsHeading"] ?></td>
		<td><input class="formField" type="text" name="heading" value="<? echo $data->heading ?>"></td>
		</tr>
		<tr>
		<td><? echo $tst["lang"]["newsBody"] ?></td>
		<td><textarea name="body" rows="4" class="formField"><? echo $data->body ?></textarea></td>
		</tr>
		<tr>
		<td><? echo $tst["lang"]["name"] ?></td>
		<td><input class="formField" type="text" name="name" value="<? echo $data->name ?>"></td>
		</tr>
		<tr>
		<td><? echo $tst["lang"]["surname"] ?></td>
		<td><input class="formField" type="text" name="surname"  value="<? echo $data->surname ?>"></td>
		</tr>
		<tr>
		<td><? echo $tst["lang"]["country"] ?></td>
		<td><select class="formField"  name="country" style="width:200px">
                <option value="Afghanistan">Afghanistan</option>
                <option value="Albania">Albania</option>
                <option value="Algeria">Algeria</option>
                <option value="American Samoa">American Samoa</option>
                <option value="Andorra">Andorra</option>
                <option value="Angola">Angola</option>
                <option value="Anguilla">Anguilla</option>
                <option value="Antartica">Antartica</option>
                <option value="Antigua and Barbuda">Antigua and Barbud...</option>
                <option value="Argentina">Argentina</option>
                <option value="Armenia">Armenia</option>
                <option value="Aruba">Aruba</option>
                <option value="Ascension Island">Ascension Island</option>
                <option value="Australia">Australia</option>
                <option value="Austria">Austria</option>
                <option value="Azerbaijan">Azerbaijan</option>
                <option value="Bahamas">Bahamas</option>
                <option value="Bahrain">Bahrain</option>
                <option value="Bangladesh">Bangladesh</option>
                <option value="Barbados">Barbados</option>
                <option value="Belarus">Belarus</option>
                <option value="Belgium">Belgium</option>
                <option value="Belize">Belize</option>
                <option value="Benin">Benin</option>
                <option value="Bermuda">Bermuda</option>
                <option value="Bhutan">Bhutan</option>
                <option value="Bolivia">Bolivia</option>
                <option value="Bosnia and Herzegovina">Bosnia and Herzego...</option>
                <option value="Botswana">Botswana</option>
                <option value="Bouvet Island">Bouvet Island</option>
                <option value="Brazil">Brazil</option>
                <option value="British Indian Ocean Territory">British Indian 
                Oce...</option>
                <option value="Brunei Darussalam">Brunei Darussalam</option>
                <option value="Bulgaria">Bulgaria</option>
                <option value="Burkina Faso">Burkina Faso</option>
                <option value="Burundi">Burundi</option>
                <option value="Cambodia">Cambodia</option>
                <option value="Cameroon">Cameroon</option>
                <option value="Canada">Canada</option>
                <option value="Cape Verde Islands">Cape Verde Islands</option>
                <option value="Cayman Islands">Cayman Islands</option>
                <option value="Central African Republic">Central African Re...</option>
                <option value="Chad">Chad</option>
                <option value="Chile">Chile</option>
                <option value="China">China</option>
                <option value="Christmas Island">Christmas Island</option>
                <option value="Cocos (Keeling) Islands">Cocos (Keeling) Is...</option>
                <option value="Colombia">Colombia</option>
                <option value="Comoros">Comoros</option>
                <option value="Congo, Democratic Republic of">Congo, Democratic 
                ...</option>
                <option value="Congo, Republic of">Congo, Republic of</option>
                <option value="Cook Islands">Cook Islands</option>
                <option value="Costa Rica">Costa Rica</option>
                <option value="Cote d'Ivoire">Cote d'Ivoire</option>
                <option value="Croatia/Hrvatska">Croatia/Hrvatska</option>
                <option value="Cyprus">Cyprus</option>
                <option value="Czech Republic">Czech Republic</option>
                <option value="Denmark">Denmark</option>
                <option value="Djibouti">Djibouti</option>
                <option value="Dominica">Dominica</option>
                <option value="Dominican Republic">Dominican Republic</option>
                <option value="East Timor">East Timor</option>
                <option value="Ecuador">Ecuador</option>
                <option value="Egypt">Egypt</option>
                <option value="El Salvador">El Salvador</option>
                <option value="Equatorial Guinea">Equatorial Guinea</option>
                <option value="Eritrea">Eritrea</option>
                <option value="Estonia">Estonia</option>
                <option value="Ethiopia">Ethiopia</option>
                <option value="Falkland Islands">Falkland Islands</option>
                <option value="Faroe Islands">Faroe Islands</option>
                <option value="Fiji">Fiji</option>
                <option value="Finland">Finland</option>
                <option value="France">France</option>
                <option value="French Guiana">French Guiana</option>
                <option value="French Polynesia">French Polynesia</option>
                <option value="French Southern Territories">French Southern Te...</option>
                <option value="Gabon">Gabon</option>
                <option value="Gambia">Gambia</option>
                <option value="Georgia">Georgia</option>
                <option value="Germany">Germany</option>
                <option value="Ghana">Ghana</option>
                <option value="Gibraltar">Gibraltar</option>
                <option value="Greece">Greece</option>
                <option value="Greenland">Greenland</option>
                <option value="Grenada">Grenada</option>
                <option value="Guadeloupe">Guadeloupe</option>
                <option value="Guam">Guam</option>
                <option value="Guatemala">Guatemala</option>
                <option value="Guernsey">Guernsey</option>
                <option value="Guinea">Guinea</option>
                <option value="Guinea-Bissau">Guinea-Bissau</option>
                <option value="Guyana">Guyana</option>
                <option value="Haiti">Haiti</option>
                <option value="Heard and McDonald Islands">Heard and McDonald...</option>
                <option value="Honduras">Honduras</option>
                <option value="Hong Kong">Hong Kong</option>
                <option value="Hungary">Hungary</option>
                <option value="Iceland">Iceland</option>
                <option value="India">India</option>
                <option value="Indonesia">Indonesia</option>
                <option value="Ireland">Ireland</option>
                <option value="Isle of Man">Isle of Man</option>
                <option value="Israel">Israel</option>
                <option value="Italy">Italy</option>
                <option value="Jamaica">Jamaica</option>
                <option value="Japan">Japan</option>
                <option value="Jersey">Jersey</option>
                <option value="Jordan">Jordan</option>
                <option value="Kazakhstan">Kazakhstan</option>
                <option value="Kenya">Kenya</option>
                <option value="Kiribati">Kiribati</option>
                <option value="Korea, Republic of">Korea, Republic of</option>
                <option value="Kuwait">Kuwait</option>
                <option value="Kyrgyzstan">Kyrgyzstan</option>
                <option value="Laos">Laos</option>
                <option value="Latvia">Latvia</option>
                <option value="Lebanon">Lebanon</option>
                <option value="Lesotho">Lesotho</option>
                <option value="Liberia">Liberia</option>
                <option value="Liechtenstein">Liechtenstein</option>
                <option value="Lithuania">Lithuania</option>
                <option value="Luxembourg">Luxembourg</option>
                <option value="Macau">Macau</option>
                <option value="Macedonia, Former Yugoslav Republic">Macedonia, 
                Former ...</option>
                <option value="Madagascar">Madagascar</option>
                <option value="Malawi">Malawi</option>
                <option value="Malaysia">Malaysia</option>
                <option value="Maldives">Maldives</option>
                <option value="Mali">Mali</option>
                <option value="Malta">Malta</option>
                <option value="Marshall Islands">Marshall Islands</option>
                <option value="Martinique">Martinique</option>
                <option value="Mauritania">Mauritania</option>
                <option value="Mauritius">Mauritius</option>
                <option value="Mayotte Island">Mayotte Island</option>
                <option value="Mexico">Mexico</option>
                <option value="Micronesia">Micronesia</option>
                <option value="Moldova">Moldova</option>
                <option value="Monaco">Monaco</option>
                <option value="Mongolia">Mongolia</option>
                <option value="Montserrat">Montserrat</option>
                <option value="Morocco">Morocco</option>
                <option value="Mozambique">Mozambique</option>
                <option value="Myanmar">Myanmar</option>
                <option value="Namibia">Namibia</option>
                <option value="Nauru">Nauru</option>
                <option value="Nepal">Nepal</option>
                <option value="Netherlands">Netherlands</option>
                <option value="Netherlands Antilles">Netherlands Antill...</option>
                <option value="New Caledonia">New Caledonia</option>
                <option value="New Zealand">New Zealand</option>
                <option value="Nicaragua">Nicaragua</option>
                <option value="Niger">Niger</option>
                <option value="Nigeria">Nigeria</option>
                <option value="Niue">Niue</option>
                <option value="Norfolk Island">Norfolk Island</option>
                <option value="Northern Mariana Islands">Northern Mariana I...</option>
                <option value="Norway">Norway</option>
                <option value="Oman">Oman</option>
                <option value="Pakistan">Pakistan</option>
                <option value="Palau">Palau</option>
                <option value="Palestinian Territories">Palestinian Territ...</option>
                <option value="Panama">Panama</option>
                <option value="Papua New Guinea">Papua New Guinea</option>
                <option value="Paraguay">Paraguay</option>
                <option value="Peru">Peru</option>
                <option value="Philippines">Philippines</option>
                <option value="Pitcairn Island">Pitcairn Island</option>
                <option value="Poland">Poland</option>
                <option value="Portugal">Portugal</option>
                <option value="Puerto Rico">Puerto Rico</option>
                <option value="Qatar">Qatar</option>
                <option value="Reunion Island">Reunion Island</option>
                <option value="Romania">Romania</option>
                <option value="Russian Federation">Russian Federation</option>
                <option value="Rwanda">Rwanda</option>
                <option value="Saint Helena">Saint Helena</option>
                <option value="Saint Kitts and Nevis">Saint Kitts and Ne...</option>
                <option value="Saint Lucia">Saint Lucia</option>
                <option value="Saint Pierre and Miquelon">Saint Pierre and M...</option>
                <option value="Saint Vincent and the Grenadines">Saint Vincent 
                and ...</option>
                <option value="San Marino">San Marino</option>
                <option value="Sao Tome and Principe">Sao Tome and Princ...</option>
                <option value="Saudi Arabia">Saudi Arabia</option>
                <option value="Senegal">Senegal</option>
                <option value="Seychelles">Seychelles</option>
                <option value="Sierra Leone">Sierra Leone</option>
                <option value="Singapore">Singapore</option>
                <option value="Slovak Republic">Slovak Republic</option>
                <option value="Slovenia">Slovenia</option>
                <option value="Solomon Islands">Solomon Islands</option>
                <option value="Somalia">Somalia</option>
                <option value="South Africa">South Africa</option>
                <option value="South Georgia and South Sandwich Islands">South 
                Georgia and ...</option>
                <option value="Spain">Spain</option>
                <option value="Sri Lanka">Sri Lanka</option>
                <option value="Suriname">Suriname</option>
                <option value="Svalbard and Jan Mayen Islands">Svalbard and Jan 
                M...</option>
                <option value="Swaziland">Swaziland</option>
                <option value="Sweden">Sweden</option>
                <option value="Switzerland">Switzerland</option>
                <option value="Taiwan">Taiwan</option>
                <option value="Tajikistan">Tajikistan</option>
                <option value="Tanzania">Tanzania</option>
                <option value="Thailand">Thailand</option>
                <option value="Togo">Togo</option>
                <option value="Tokelau">Tokelau</option>
                <option value="Tonga Islands">Tonga Islands</option>
                <option value="Trinidad and Tobago">Trinidad and Tobag...</option>
                <option value="Tunisia">Tunisia</option>
                <option value="Turkey" selected>Turkey</option>
                <option value="Turkmenistan">Turkmenistan</option>
                <option value="Turks and Caicos Islands">Turks and Caicos I...</option>
                <option value="Tuvalu">Tuvalu</option>
                <option value="Uganda">Uganda</option>
                <option value="Ukraine">Ukraine</option>
                <option value="United Arab Emirates">United Arab Emirat...</option>
                <option value="United Kingdom">United Kingdom</option>
                <option value="United States">United States</option>
                <option value="Uruguay">Uruguay</option>
                <option value="US Minor Outlying Islands">US Minor Outlying ...</option>
                <option value="Uzbekistan">Uzbekistan</option>
                <option value="Vanuatu">Vanuatu</option>
                <option value="Vatican City">Vatican City</option>
                <option value="Venezuela">Venezuela</option>
                <option value="Vietnam">Vietnam</option>
                <option value="Virgin Islands (British)">Virgin Islands (Br...</option>
                <option value="Virgin Islands (USA)">Virgin Islands (US...</option>
                <option value="Wallis and Futuna Islands">Wallis and Futuna ...</option>
                <option value="Western Sahara">Western Sahara</option>
                <option value="Western Samoa">Western Samoa</option>
                <option value="Yemen">Yemen</option>
                <option value="Yugoslavia">Yugoslavia</option>
                <option value="Zambia">Zambia</option>
              </select>
		</td>
		</tr>
		<tr>
		<td><? echo $tst["lang"]["email"] ?></td>
		<td><input class="formField" type="text" name="email" value="<? echo $data->email ?>"></td>
		</tr>
		<tr>
		<td><? echo $tst["lang"]["website"] ?></td>
		<td><input class="formField" type="text" name="website" value="<? echo $data->website ?>"></td>
		</tr>
		<tr>
		<td><? echo $tst["lang"]["status"] ?></td>
		<td><input type="radio" name="status" value="1" checked><? echo $tst["lang"]["statusActive"] ?><input type="radio" name="status" value="0"><? echo $tst["lang"]["statusPassive"] ?></td>
		</tr>
		<tr>
		<td><input class="formButton" type="reset" value="<? echo $tst["lang"]["resetFormBtn"] ?>"></td>
		<td><input class="formButton" type="submit" value="<? echo $tst["lang"]["updateNewsBtn"] ?>"></td>
		</tr>
		</table>
		<input class="formField" type="hidden" name="act" value="update">
		<input class="formField" type="hidden" name="id" value="<? echo $data->id ?>">
		</form>
		<?
} # end of act edit
if($act=="update") {
		$name=trim($name);
	$surname=trim($surname);
	$heading=trim($heading);
	$body=trim($body);
	if($tst["conf"]["allowHtml"]==0) {
		$name=strip_tags($name);
		$surname=strip_tags($surname);
		$heading=strip_tags($heading);
		$body=strip_tags($body);
	}
	$requiredFields=array("heading","body","name","surname");
	$requiredFieldsDisp=array($tst[lang][newsHeading],$tst[lang][newsBody],$tst[lang][name],$tst[lang][surname]);
	for($i=0;$i<count($requiredFields);$i++) {
		$found=0;
		if(empty($$requiredFields[$i])) {
			$found=1;
			$reqList.="<li>".$requiredFieldsDisp[$i].$tst[lang][isEmpty]."<br>";			
		}
	}
	if(!empty($email)) {
		$founde=0;
		if (!eregi("^[_\\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\\.)+[a-z]{2,3}$", $email)) {
			$founde=1;
			$mailList.="<li>".$tst[lang][emailInvalid]."<br>";			
		}
	}
	if($found==1 or $founde==1) {
		echo "<u><b>".$tst["lang"]["pleaseCheckFields"]." :</b></u><br><br>";
		echo $reqList;
		echo $mailList;
		include("footer.php");
	  exit;
	}
	$name=addslashes($name);
	$surname=addslashes($surname);
	$heading=addslashes($heading);
	$body=addslashes($body);
	$country=addslashes($country);
	$email=addslashes($email);
	$website=addslashes($website);
	if(mysql_query("UPDATE ".$tst["tbl"]["articles"]." SET heading='$heading',body='$body',name='$name',surname='$surname',country='$country',email='$email',website='$website',status='$status' WHERE id='$id'")) {
		echo $tst["lang"]["updateSuccess"];
		echo "<br>".$tst["lang"]["redirecting"];
		echo '<meta http-equiv=refresh content=\'3; url=listAnnc.php\'>';
		echo '<br><br><a class="link1"  href="listAnnc.php">'.$tst["lang"]["click2redirect"].'</a>';
	}
	else {
		echo $tst["lang"]["updateFailure"];
	}
}
if($act=="changeStatus") {
	if(mysql_query("UPDATE ".$tst["tbl"]["articles"]." SET status='$newStatus' WHERE id='$id'")) {
		echo $tst["lang"]["statusUpdateSuccess"];
		echo "<br>".$tst["lang"]["redirecting"];
		echo '<meta http-equiv=refresh content=\'3; url=listAnnc.php\'>';
		echo '<br><br><a class="link1"  href="listAnnc.php">'.$tst["lang"]["click2redirect"].'</a>';
	}
	else {
		echo $tst["lang"]["statusUpdateFailure"];
	}
}
include("footer.php");
?>
