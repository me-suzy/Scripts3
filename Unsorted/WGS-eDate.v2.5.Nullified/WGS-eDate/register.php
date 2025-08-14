<? 	include "_header.php"; ?>
<?
   if($action == "submit"){
     require "lib/mail.lib";
   

     $db = c();


     if($login == "") $es = "Enter username!";
     else{
       if(!e(q("select id from members where login='$login'"))) $es = "$login username already exists in our database, please, choose another!";
     }

     if($pswd_1 == "" || $pswd_2 == "" || $pswd_1 != $pswd_2)
	$es = "Password and its confirmation must be identy!";

      if($email == "") $es = "E-mail required!";
 
     if($es == ""){

//account
	q("insert into members values('0','$login','$pswd_1','$fname','$lname','$email','$city','$state','$country','$zip','$phone','$fax','','0','".strtotime(date("d M Y H:i:s"))."')");
   
   $r = q("select * from members where login='$login' AND email='$email' and status=0");
   if (e($r)) echo "<br> Registration check: registration failed ! <br>";
   $member = f($r);

   $auth=$member[id];

//profile   
   $birthdate = "".$byear."-".$bmonth."-".$bdate;
   if(e(q("select id from profiles where id='$member[id]'")))
   q("insert into profiles values('$member[id]','$birthdate','','','','','$sex','','','','','','$ethnicity','','','','$occupation','$details','','".strtotime(date("d M Y H:i:s"))."')");

//confirmation email
   $link = $ROOT_HOST."confirm.php?mid=$login".($affid != ""?"&affid=$affid":"");
   $pswd = $pswd_1;

	MsgFromTpl($email,"Registration Details !","tpl/register.mtl");
	echo "<center>Thank you for registering in our system!<br>Soon you will get confirmation e-mail.</center>";

//picture
if ($picture=="none") $picture="";

  if ($upload)
  {
   
   if ((!$url)&&($picture)) 
   {
     if (!copy($picture,"pictures/m".$auth."_".$picture_name))  echo ("<br>Failed to upload '$picture_name' ... <br>\n");
    $url="m".$auth."_".$picture_name;
   };


	if ($url) q("insert into pictures values('','$auth','$url','$description','Main','".strtotime(date("d M Y H:i:s"))."','$uploadpicturedisabled')");
  };

	 }
     d($db);
   }

  if($action != "submit" || $es != "" || ($action == "" && $es == "")){
?>
<blockquote>
<font color=555555><H3>&nbsp; Registration &gt;</H3><br></font>
<br>
  <table width="600" border=0 align=center cellpadding="1" cellspacing="1">
    <form action="register.php?action=submit" enctype="multipart/form-data" method=post>
      <?
if($es != "")
  echo"<tr>
         <td colspan=2><font color=C00000><b>Error: $es</td>
       </tr>";
?>
      <tr> 
        <td colspan="2"><font color="#333333" size="2" face="Arial, Helvetica, sans-serif"><strong>Part 
          1. Username/Password</strong><br>
          These details are required to create your account. Pick a username and 
          password. Passwords are CaSe SeNsItIvE (get the idea, hehe!). If you 
          are already registered at NeoDate, please click <a href="login.php">here</a> 
          to login.<br>
          </font></td>
      </tr>
      <tr> 
        <td><font color="#333333" size="2" face="Arial, Helvetica, sans-serif">Username 
          * </font></td>
        <td> <font color="#333333" size="1" face="Arial, Helvetica, sans-serif"> 
          <input type="text" name="login">
          (i.e. jokyb) </font></td>
      </tr>
      <tr> 
        <td><font color="#333333" size="2" face="Arial, Helvetica, sans-serif">Password 
          *</font></td>
        <td> <font color="#333333" size="1" face="Arial, Helvetica, sans-serif"> 
          <input type="password" name="pswd_1">
          (i.e. cleopatra) </font></td>
      </tr>
      <tr> 
        <td><font color="#333333" size="2" face="Arial, Helvetica, sans-serif">Confirm 
          password * </font></td>
        <td> <font color="#333333" size="1" face="Arial, Helvetica, sans-serif"> 
          <input type="password" name="pswd_2">
          (same as password) </font></td>
      </tr>
      <tr> 
        <td colspan="2"><font color="#333333" size="2" face="Arial, Helvetica, sans-serif">Please 
          provide an email address for confirmation. Your email address will NEVER 
          be given by NeoDate to a third party without your express consent. 
          That means you can rest, assured that you will not be getting annoying 
          SPAM in your email account's inbox!</font></td>
      </tr>
      <tr> 
        <td><font color="#333333" size="2" face="Arial, Helvetica, sans-serif">E-mail 
          address * </font></td>
        <td> <font color="#333333" size="1" face="Arial, Helvetica, sans-serif"> 
          <input type="text" name="email">
          (i.e. your@yahoo.com) </font></td>
      </tr>
      <tr> 
        <td colspan="2"><p>&nbsp;</p>
          <p><strong><font color="#333333" size="2" face="Arial, Helvetica, sans-serif">Part 
            2. Personal Information</font></strong></p></td>
      </tr>
      <tr> 
        <td><font color="#333333" size="2" face="Arial, Helvetica, sans-serif">First 
          name</font></td>
        <td> <font color="#333333" size="1" face="Arial, Helvetica, sans-serif"> 
          <input type="text" name="fname">
          (i.e. John) </font></td>
      </tr>
      <tr> 
        <td><font color="#333333" size="2" face="Arial, Helvetica, sans-serif">Last 
          name</font></td>
        <td> <font color="#333333" size="1" face="Arial, Helvetica, sans-serif"> 
          <input type="text" name="lname">
          (i.e. Brown) </font></td>
      </tr>
      <tr> 
        <td><font color="#333333" size="2" face="Arial, Helvetica, sans-serif">Date 
          of birth (yyyy-mm-dd)</font></td>
        <td><font color="#333333" size="1" face="Arial, Helvetica, sans-serif"> 
          <select name="byear">
            <?php for ($i=1900;$i<=2002;$i++) {?><option><?php echo $i; ?></option><?php };?>
          </select>
          - 
          <select name="bmonth">
            <?php for ($i=1;$i<=12;$i++) {?><option><?php echo $i; ?></option><?php };?>
          </select>
          -
          <select name="bdate">
            <?php for ($i=1;$i<=31;$i++) {?><option><?php echo $i; ?></option><?php };?>
          </select>
          (i.e. 1980-12-30) </font></td>
      </tr>
      <tr> 
        <td><font color="#333333" size="2" face="Arial, Helvetica, sans-serif">City</font></td>
        <td> <font color="#333333" size="1" face="Arial, Helvetica, sans-serif"> 
          <input type="text" name="city">
          (i.e. Los Angeles) </font></td>
      </tr>
      <tr> 
        <td><font color="#333333" size="2" face="Arial, Helvetica, sans-serif">State/County</font></td>
        <td> <font color="#333333" size="2" face="Arial, Helvetica, sans-serif"> 
          <select name="state">
            <option selected></option>
            <option value="International">International</option>
            <option value='Alabama'>Alabama</option>
            <option value='Alaska'>Alaska</option>
            <option value='Arizona'>Arizona</option>
            <option value='Arkansas'>Arkansas</option>
            <option value='California'>California</option>
            <option value='Colorado'>Colorado</option>
            <option value='Connecticut'>Connecticut</option>
            <option value='Washington D.C.'>Washington D.C.</option>
            <option value='Delaware'>Delaware</option>
            <option value='Florida'>Florida</option>
            <option value='Georgia'>Georgia</option>
            <option value='Hawaii'>Hawaii</option>
            <option value='Idaho'>Idaho</option>
            <option value='Illinois'>Illinois</option>
            <option value='Indiana'>Indiana</option>
            <option value='Iowa'>Iowa</option>
            <option value='Kansas'>Kansas</option>
            <option value='Kentucky'>Kentucky</option>
            <option value='Louisiana'>Louisiana</option>
            <option value='Maine'>Maine</option>
            <option value='Maryland'>Maryland</option>
            <option value='Massachusetts'>Massachusetts</option>
            <option value='Michigan'>Michigan</option>
            <option value='Minnesota'>Minnesota</option>
            <option value='Mississippi'>Mississippi</option>
            <option value='Missouri'>Missouri</option>
            <option value='Montana'>Montana</option>
            <option value='Nebraska'>Nebraska</option>
            <option value='Nevada'>Nevada</option>
            <option value='New Hampshire'>New Hampshire</option>
            <option value='New Jersey'>New Jersey</option>
            <option value='New Mexico'>New Mexico</option>
            <option value='New York'>New York</option>
            <option value='North Carolina'>North Carolina</option>
            <option value='North Dakota'>North Dakota</option>
            <option value='Ohio'>Ohio</option>
            <option value='Oklahoma'>Oklahoma</option>
            <option value='Oregon'>Oregon</option>
            <option value='Pennsylvania'>Pennsylvania</option>
            <option value='Puerto Rico'>Puerto Rico</option>
            <option value='Rhode Island'>Rhode Island</option>
            <option value='South Carolina'>South Carolina</option>
            <option value='South Dakota'>South Dakota</option>
            <option value='Tennessee'>Tennessee</option>
            <option value='Texas'>Texas</option>
            <option value='Utah'>Utah</option>
            <option value='Vermont'>Vermont</option>
            <option value='Virginia'>Virginia</option>
            <option value='Washington'>Washington</option>
            <option value='West Virginia'>West Virginia</option>
            <option value='Wisconsin'>Wisconsin</option>
            <option value='Wyoming'>Wyoming</option>
          </select>
          </font></td>
      </tr>
      <tr> 
        <td><font color="#333333" size="2" face="Arial, Helvetica, sans-serif">Country</font></td>
        <td> <font color="#333333" size="2" face="Arial, Helvetica, sans-serif"> 
          <select name="country">
            <option selected></option>
            <option value='United States of America'>United States of America</option>
            <option value='Afghanistan'>Afghanistan</option>
            <option value='Albania'>Albania</option>
            <option value='Algeria'>Algeria</option>
            <option value='American Samoa'>American Samoa</option>
            <option value='Andorra'>Andorra</option>
            <option value='Angola'>Angola</option>
            <option value='Anguilla'>Anguilla</option>
            <option value='Antarctica'>Antarctica</option>
            <option value='Antigua and Barbuda'>Antigua and Barbuda</option>
            <option value='Argentina'>Argentina</option>
            <option value='Armenia'>Armenia</option>
            <option value='Aruba'>Aruba</option>
            <option value='Australia'>Australia</option>
            <option value='Austria'>Austria</option>
            <option value='Azerbaijan'>Azerbaijan</option>
            <option value='Bahamas'>Bahamas</option>
            <option value='Bahrain'>Bahrain</option>
            <option value='Bangladesh'>Bangladesh</option>
            <option value='Barbados'>Barbados</option>
            <option value='Belarus'>Belarus</option>
            <option value='Belgium'>Belgium</option>
            <option value='Belize'>Belize</option>
            <option value='Benin'>Benin</option>
            <option value='Bermuda'>Bermuda</option>
            <option value='Bhutan'>Bhutan</option>
            <option value='Bolivia'>Bolivia</option>
            <option value='Bosnia and Herzegowina'>Bosnia and Herzegowina</option>
            <option value='Botswana'>Botswana</option>
            <option value='Bouvet Island'>Bouvet Island</option>
            <option value='Brazil'>Brazil</option>
            <option value='Brunei Darussalam'>Brunei Darussalam</option>
            <option value='Bulgaria'>Bulgaria</option>
            <option value='Burkina Faso'>Burkina Faso</option>
            <option value='Burundi'>Burundi</option>
            <option value='Cambodia'>Cambodia</option>
            <option value='Cameroon'>Cameroon</option>
            <option value='Canada'>Canada</option>
            <option value='Cape Verde'>Cape Verde</option>
            <option value='Cayman Islands'>Cayman Islands</option>
            <option value='Central African Republic'>Central African Republic</option>
            <option value='Chad'>Chad</option>
            <option value='Chile'>Chile</option>
            <option value='China'>China</option>
            <option value='Christmas Island'>Christmas Island</option>
            <option value='Cocos (Keeling) Islands'>Cocos (Keeling) Islands</option>
            <option value='Colombia'>Colombia</option>
            <option value='Comoros'>Comoros</option>
            <option value='Congo'>Congo</option>
            <option value='Cook Islands'>Cook Islands</option>
            <option value='Costa Rica'>Costa Rica</option>
            <option value='Cote D'Ivoire'>Cote D'Ivoire</option>
            <option value='Croatia'>Croatia</option>
            <option value='Cuba'>Cuba</option>
            <option value='Cyprus'>Cyprus</option>
            <option value='Czech Republic'>Czech Republic</option>
            <option value='Denmark'>Denmark</option>
            <option value='Djibouti'>Djibouti</option>
            <option value='Dominica'>Dominica</option>
            <option value='Dominican Republic'>Dominican Republic</option>
            <option value='East Timor'>East Timor</option>
            <option value='Ecuador'>Ecuador</option>
            <option value='Egypt'>Egypt</option>
            <option value='El Salvador'>El Salvador</option>
            <option value='Equatorial Guinea'>Equatorial Guinea</option>
            <option value='Eritrea'>Eritrea</option>
            <option value='Estonia'>Estonia</option>
            <option value='Ethiopia'>Ethiopia</option>
            <option value='Falkland Islands'>Falkland Islands</option>
            <option value='Faroe Islands'>Faroe Islands</option>
            <option value='Fiji'>Fiji</option>
            <option value='Finland'>Finland</option>
            <option value='France'>France</option>
            <option value='France, Metropolitan'>France, Metropolitan</option>
            <option value='French Guiana'>French Guiana</option>
            <option value='French Polynesia'>French Polynesia</option>
            <option value='Gabon'>Gabon</option>
            <option value='Gambia'>Gambia</option>
            <option value='Georgia'>Georgia</option>
            <option value='Germany'>Germany</option>
            <option value='Ghana'>Ghana</option>
            <option value='Gibraltar'>Gibraltar</option>
            <option value='Greece'>Greece</option>
            <option value='Greenland'>Greenland</option>
            <option value='Grenada'>Grenada</option>
            <option value='Guadeloupe'>Guadeloupe</option>
            <option value='Guam'>Guam</option>
            <option value='Guatemala'>Guatemala</option>
            <option value='Guinea'>Guinea</option>
            <option value='Guinea-Bissau'>Guinea-Bissau</option>
            <option value='Guyana'>Guyana</option>
            <option value='Haiti'>Haiti</option>
            <option value='Honduras'>Honduras</option>
            <option value='Hong Kong SAR, PRC'>Hong Kong SAR, PRC</option>
            <option value='Hungary'>Hungary</option>
            <option value='Iceland'>Iceland</option>
            <option value='India'>India</option>
            <option value='Indonesia'>Indonesia</option>
            <option value='Iran'>Iran</option>
            <option value='Iraq'>Iraq</option>
            <option value='Ireland'>Ireland</option>
            <option value='Israel'>Israel</option>
            <option value='Italy'>Italy</option>
            <option value='Jamaica'>Jamaica</option>
            <option value='Japan'>Japan</option>
            <option value='Jordan'>Jordan</option>
            <option value='Kazakhstan'>Kazakhstan</option>
            <option value='Kenya'>Kenya</option>
            <option value='Kiribati'>Kiribati</option>
            <option value='D.P.R. Korea'>D.P.R. Korea</option>
            <option value='Korea'>Korea</option>
            <option value='Kuwait'>Kuwait</option>
            <option value='Kyrgyzstan'>Kyrgyzstan</option>
            <option value='Lao People's Republic'>Lao People's Republic</option>
            <option value='Latvia'>Latvia</option>
            <option value='Lebanon'>Lebanon</option>
            <option value='Lesotho'>Lesotho</option>
            <option value='Liberia'>Liberia</option>
            <option value='Libyan Arab Jamahiriya'>Libyan Arab Jamahiriya</option>
            <option value='Liechtenstein'>Liechtenstein</option>
            <option value='Lithuania'>Lithuania</option>
            <option value='Luxembourg'>Luxembourg</option>
            <option value='Macau'>Macau</option>
            <option value='Macedonia'>Macedonia</option>
            <option value='Madagascar'>Madagascar</option>
            <option value='Malawi'>Malawi</option>
            <option value='Malaysia'>Malaysia</option>
            <option value='Maldives'>Maldives</option>
            <option value='Mali'>Mali</option>
            <option value='Malta'>Malta</option>
            <option value='Marshall Islands'>Marshall Islands</option>
            <option value='Martinique'>Martinique</option>
            <option value='Mauritania'>Mauritania</option>
            <option value='Mauritius'>Mauritius</option>
            <option value='Mayotte'>Mayotte</option>
            <option value='Mexico'>Mexico</option>
            <option value='Micronesia'>Micronesia</option>
            <option value='Moldova'>Moldova</option>
            <option value='Monaco'>Monaco</option>
            <option value='Mongolia'>Mongolia</option>
            <option value='Montserrat'>Montserrat</option>
            <option value='Morocco'>Morocco</option>
            <option value='Mozambique'>Mozambique</option>
            <option value='Myanmar'>Myanmar</option>
            <option value='Namibia'>Namibia</option>
            <option value='Nauru'>Nauru</option>
            <option value='Nepal'>Nepal</option>
            <option value='Netherlands'>Netherlands</option>
            <option value='Netherlands Antilles'>Netherlands Antilles</option>
            <option value='New Caledonia'>New Caledonia</option>
            <option value='New Zealand'>New Zealand</option>
            <option value='Nicaragua'>Nicaragua</option>
            <option value='Niger'>Niger</option>
            <option value='Nigeria'>Nigeria</option>
            <option value='Niue'>Niue</option>
            <option value='Norfolk Island'>Norfolk Island</option>
            <option value='Northern Mariana Islands'>Northern Mariana Islands</option>
            <option value='Norway'>Norway</option>
            <option value='Oman'>Oman</option>
            <option value='Pakistan'>Pakistan</option>
            <option value='Palau'>Palau</option>
            <option value='Panama'>Panama</option>
            <option value='Papua New Guinea'>Papua New Guinea</option>
            <option value='Paraguay'>Paraguay</option>
            <option value='Peru'>Peru</option>
            <option value='Philippines'>Philippines</option>
            <option value='Pitcairn'>Pitcairn</option>
            <option value='Poland'>Poland</option>
            <option value='Portugal'>Portugal</option>
            <option value='Puerto Rico'>Puerto Rico</option>
            <option value='Qatar'>Qatar</option>
            <option value='Reunion'>Reunion</option>
            <option value='Romania'>Romania</option>
            <option value='Russian Federation'>Russian Federation</option>
            <option value='Rwanda'>Rwanda</option>
            <option value='Saint Kitts and Nevis'>Saint Kitts and Nevis</option>
            <option value='Saint Lucia'>Saint Lucia</option>
            <option value='Samoa'>Samoa</option>
            <option value='San Marino'>San Marino</option>
            <option value='Sao Tome and Principe'>Sao Tome and Principe</option>
            <option value='Saudi Arabia'>Saudi Arabia</option>
            <option value='Senegal'>Senegal</option>
            <option value='Seychelles'>Seychelles</option>
            <option value='Sierra Leone'>Sierra Leone</option>
            <option value='Singapore'>Singapore</option>
            <option value='Slovakia'>Slovakia</option>
            <option value='Slovenia'>Slovenia</option>
            <option value='Solomon Islands'>Solomon Islands</option>
            <option value='Somalia'>Somalia</option>
            <option value='South Africa'>South Africa</option>
            <option value='Spain'>Spain</option>
            <option value='Sri Lanka'>Sri Lanka</option>
            <option value='St Helena'>St Helena</option>
            <option value='St Pierre and Miquelon'>St Pierre and Miquelon</option>
            <option value='Sudan'>Sudan</option>
            <option value='Suriname'>Suriname</option>
            <option value='Swaziland'>Swaziland</option>
            <option value='Sweden'>Sweden</option>
            <option value='Switzerland'>Switzerland</option>
            <option value='Syrian Arab Republic'>Syrian Arab Republic</option>
            <option value='Taiwan Region'>Taiwan Region</option>
            <option value='Tajikistan'>Tajikistan</option>
            <option value='Tanzania'>Tanzania</option>
            <option value='Thailand'>Thailand</option>
            <option value='Togo'>Togo</option>
            <option value='Tokelau'>Tokelau</option>
            <option value='Tonga'>Tonga</option>
            <option value='Trinidad and Tobago'>Trinidad and Tobago</option>
            <option value='Tunisia'>Tunisia</option>
            <option value='Turkey'>Turkey</option>
            <option value='Turkmenistan'>Turkmenistan</option>
            <option value='Turks and Caicos Islands'>Turks and Caicos Islands</option>
            <option value='Tuvalu'>Tuvalu</option>
            <option value='Uganda'>Uganda</option>
            <option value='Ukraine'>Ukraine</option>
            <option value='United Arab Emirates'>United Arab Emirates</option>
            <option value='United Kingdom'>United Kingdom</option>
            <option value='Uruguay'>Uruguay</option>
            <option value='Uzbekistan'>Uzbekistan</option>
            <option value='Vanuatu'>Vanuatu</option>
            <option value='Vatican City State'>Vatican City State</option>
            <option value='Venezuela'>Venezuela</option>
            <option value='Viet Nam'>Viet Nam</option>
            <option value='Virgin Islands (British)'>Virgin Islands (British)</option>
            <option value='Virgin Islands (US)'>Virgin Islands (US)</option>
            <option value='Wallis and Futuna Islands'>Wallis and Futuna Islands</option>
            <option value='Yugoslavia'>Yugoslavia</option>
            <option value='Yemen'>Yemen</option>
            <option value='Zaire'>Zaire</option>
            <option value='Zambia'>Zambia</option>
            <option value='Zimbabwe'>Zimbabwe</option>
            <option value='Other-Not Shown'>Other-Not Shown</option>
          </select>
          </font></td>
      </tr>
      <TR class='tr0'> 
        <TD><font color="#333333" size="2" face="Arial, Helvetica, sans-serif">Sex 
          or Gender</font></TD>
        <TD><font color="#333333" size="2" face="Arial, Helvetica, sans-serif"> 
          <select size=1 NAME='sex'>
            <option></option>
            <option >Male</option>
            <option >Female</option>
          </select>
          </font></TD>
      </TR>
      <tr> 
        <td><font color="#333333" size="2" face="Arial, Helvetica, sans-serif">Occupation</font></td>
        <td><font color="#333333" size="2" face="Arial, Helvetica, sans-serif"> 
          <input name="occupation" type="text" id="occupation">
          </font></td>
      </tr>
      <tr> 
        <td><font color="#333333" size="2" face="Arial, Helvetica, sans-serif">Ethnicity</font></td>
        <td><font color="#333333" size="2" face="Arial, Helvetica, sans-serif"> 
          <input name="ethnicity" type="text" id="ethnicity">
          </font></td>
      </tr>
      <tr> 
        <td colspan="2"><p>&nbsp;</p>
          <p><font color="#333333" size="2" face="Arial, Helvetica, sans-serif"><strong>Part 
            3. Picture and Personal Message </strong></font><font size="2"> </font></p>
          <font size="2">
          <p><font color="#333333" size="2" face="Arial, Helvetica, sans-serif">There 
            are two ways to submit a picture for your profile. <br>
            1. You can upload a .jpg or .gif (image/picture) file from your computer 
            by clicking on the BROWSE button below and selecting the file on your 
            hard disk or floppy disk:</font></p>
          <p><font color="#333333" size="2" face="Arial, Helvetica, sans-serif">2. 
            You can submit the URL (website link) to the picture if it is already 
            on another website. The filesize must be less than 400 Kilobytes 
            (400,000 bytes).</font></p>
          <p><font color="#333333" size="2" face="Arial, Helvetica, sans-serif"> 
            Describe yourself, your hobbies, or anything else you would like people 
            to know about you.</font></p>
          </font></td>
      </tr>
      <tr bgcolor="#FFFFFF"> 
        <td><font color="#333333" size="2" face="Arial, Helvetica, sans-serif">Upload 
          Picture</font></td>
        <td><font color="#333333" size="2" face="Arial, Helvetica, sans-serif"> 
          <input name="picture" type="file" id="picture" size="60">
          </font></td>
      <TR bgcolor="#FFFFFF" class='tr1'> 
        <TD><font color="#333333" size="2" face="Arial, Helvetica, sans-serif">Or 
          display from url</font></TD>
        <TD><font color="#333333" size="2" face="Arial, Helvetica, sans-serif"> 
          <input name="url" type="text" id="url" size="60" value="">
          </font></TD>
      </TR>
      <tr bgcolor="#FFFFFF"> 
        <td><font color="#333333" size="2" face="Arial, Helvetica, sans-serif">Main 
          Picture Description</font></td>
        <td><font color="#333333" size="2" face="Arial, Helvetica, sans-serif"> 
          <textarea name="description" cols="60" rows="4" wrap="VIRTUAL" id="description"></textarea>
          <font size="1"><br>
          (i.e. As you see in this picture.. I'm good looking and I like my computer.) 
          </font></font></td>
      </tr>
      <tr>
        <td><font color="#333333" size="2" face="Arial, Helvetica, sans-serif">About 
          me </font></td>
        <td><font color="#333333" size="2" face="Arial, Helvetica, sans-serif"> 
          <textarea name="details" cols="60" rows="4" wrap="VIRTUAL" id="details"></textarea>
          <br>
          <font size="1">(i.e. I like friends.. ) </font></font></td>
      </tr>
      <tr> 
        <td><font color="#333333" size="2" face="Arial, Helvetica, sans-serif">Terms 
          And Conditions</font></td>
        <td><font color="#333333" size="2" face="Arial, Helvetica, sans-serif"> 
          <textarea cols="60" rows="4" readonly="readonly" wrap="VIRTUAL" id="textarea2">Terms and Conditions of Use

The NeoDate web site (our "Site"), provides a fun way for you to interact with other Desi people around the globe, a way for people to meet each other, the opportunity to view and rate how others look and various other services (our Site and such services, collectively, our "Service").  Your use of our Service is subject to the following Terms and Conditions of Use (these "Terms").  WE RESERVE THE RIGHT TO MAKE CHANGES TO THESE TERMS AT ANY TIME.  YOUR CONTINUED USE OF OUR SERVICE CONSTITUTES YOUR ACCEPTANCE OF SUCH CHANGES.  ACCORDINGLY, YOU SHOULD REVIEW THESE TERMS FROM TIME TO TIME FOR SUCH CHANGES.  

1. USER CONDUCT

A. In your use of our Service, you agree to act responsibly in a manner demonstrating the exercise of good judgment.  For example and without limitation, you agree not to: (a) violate any applicable law or regulation, (b) infringe the rights of any third party, including without limitation, intellectual property, privacy, publicity or contractual rights, (c) use the information available through our Service for any unauthorized purpose, (d) interfere with or damage our Service, including, without limitation, through the use of viruses, cancel bots, Trojan horses, harmful code, flood pings, denial of service attacks, packet or IP spoofing, forged routing or electronic mail address information or similar methods or technology, (e) use our Service to transmit, distribute, post or submit any information concerning any other person or entity, including without limitation, photographs of others, personal contact information or credit, debit, calling card or account numbers, (f) use our Service in connection with the distribution of unsolicited commercial email ("Spam") or advertisements, (g) "stalk" or harass any other user of our Service, (h) collect or store any information about any other user other than in the course of the permitted use of our Service, (i) use our Service for any commercial purpose whatsoever or (j) assist any third party in doing any of the foregoing.  

B. You are solely responsible for your interactions with other users of our Service.  We will not be responsible for any damage or harm resulting from your interactions with other users of our Service.  We reserve the right, but have no obligation, to monitor interactions between you and other users of our Service and take any other action in good faith to restrict access to or the availability of any material that we or another user of our Service may consider to be obscene, lewd, lascivious, filthy, excessively violent, harassing or otherwise objectionable.  

2. PRIVACY

We understand the concerns that you may have about your privacy and respect your right to protect your personal information while online. However, our Service is designed to allow users to post photographs and information about themselves for public review and comment. Accordingly, by submitting your photograph and/or any personal information, you thereby waive any privacy expectations you have with respect to our use of your likeness or personal information provided to us.  If you do not wish to have your picture or information about yourself viewed by or disclosed to others, do not use our Service.  We may collect certain other personal information from you that we do not post on our Site.  In most cases, we do not intentionally transfer this information to unaffiliated third parties without your consent. However, we reserve the right to transfer such information without your consent to prevent an emergency, to protect or enforce our rights, to protect or enforce the rights of a third party or in response to a court order or subpoena as otherwise required or permitted by law.  In addition, we provide this personal information to third-party service providers who help us maintain our Service and deliver information and services to you and other users of our Service.   

3. USER CONTENT

A. By submitting any content (including without limitation, your photograph) to our Site, you hereby grant us a perpetual, worldwide, non-exclusive, royalty-free right and license to use, reproduce, display, perform, adapt, modify, distribute, have distributed and promote such content in any form, in all media now known or hereinafter created, anywhere in the world, and for any purpose.  

B. You are solely responsible for any content that you submit, post or transmit via our Service.  You agree not to post or submit any content that: (a) is libelous, defamatory or slanderous, (b) contains sexually explicit content (including nudity), (c) may denigrate any ethnic, racial, sexual or religious group by stereotypical depiction or otherwise, (d) exploits images or the likeness of individuals under 18 years of age, (e) encourages or otherwise depicts glamorized drug use (including alcohol and cigarettes), (f) makes use of offensive language or images, (g) characterizes violence as acceptable, glamorous or desirable, or (h) contains any of your personal contact information other than your email address. 

C. We have no obligation to post any content that you or anyone else submits.  In addition, we may, in our sole and unfettered discretion, edit, remove or delete any content that you post or submit.  

4. THIRD PARTY CONTENT

In your use of our Service, you may access content from third parties ("Third Party Content"), either via our Service or through links to third party web sites.  We do not control Third Party Content and make no representations or warranties about it.  You agree that by using our Service, you may be exposed to Third Party Content that is false, offensive, indecent or otherwise objectionable.  Under no circumstances will we be liable in any way for any Third Party Content, including, without limitation, any errors or omissions in any Third Party Content or any loss or damage of any kind incurred as a result of the use of any Third Party Content posted, stored or transmitted via our Service.  You agree that you must evaluate, and bear all risks associated with, Third Party Content, including without limitation, profiles of other users of our Service.  

5. PROPRIETARY RIGHTS  

A. You agree that all content and materials available on our Site are protected by rights of publicity, copyright, trademarks, service marks, patents, trade secrets or other proprietary rights and laws. Except as expressly authorized by us, you agree not to sell, license, rent, modify, distribute, copy, reproduce, transmit, publicly display, publicly perform, publish, adapt, edit or create derivative works from materials or content available on our Site.  Notwithstanding the above, you may use the content and materials on our Site in the course of your normal, personal, non-commercial use of our Service.  

B. You agree not to systematically retrieve data or other content or any materials from our Site to create or compile, directly or indirectly, a collection, compilation, database, directory or the like, whether by manual methods, through the use of "bots" or otherwise.  You agree not to use of any of our trademarks as metatags on other web sites.  You agree not to display any of our Site in a frame (or any of our content via in-line links) without our express written permission, which may be requested by contacting us at your@yahoo.com.  You may, however, establish ordinary links to the homepage of our Site without our written permission. 

6. USERNAME AND PASSWORD  

You will select a username and password when completing the registration process.  You are solely and fully responsible for maintaining the confidentiality of your username and password, and are solely and fully responsible for all activities that occur under your username and password.  You agree to: (a) immediately notify us of any unauthorized use of your username and password or any other breach of security and (b) ensure that you log off from your account at the end of each session.  We cannot and will not be liable for any loss or damage arising from your failure to comply with this Section 7.  

7. TERMINATION

You agree that we, in our sole and unfettered discretion, may terminate your access to our Service for any reason, including, without limitation, your breach of these Terms.  You agree that any termination of your access to our Service may be effected without prior notice, and acknowledge and agree that we may immediately deactivate or delete any of your accounts and all related information and files in such accounts and/or bar any further access to such files or our Service.  Further, you agree that we will not be liable to you or any third party for any termination of your access to our Service.  

8. DISCONTINUANCE OF SERVICE

We reserve the right at any time to modify or discontinue, temporarily or permanently, any portion of our Service with or without prior notice.  You agree that we will not be liable to you or to any third party for any modification or discontinuance of our Service.  

9. REPRESENTATIONS AND WARRANTIES

You hereby represent and warrant to us that: (a) that you have the full power and authority to enter into and perform under these Terms, (b) your use our Service will not infringe the copyright, trademark, right of publicity or any other legal right of any third party, (c) you will comply with all applicable laws in using our Service and in engaging in all other activities arising from, relating to or connected with these Terms, including, without limitation, contacting other users of our Service and (d) you own or otherwise have all rights necessary to license the content you submit and that the posting and use of your content by us will not infringe or violate the rights of any third party. 

10. DISCLAIMER OF WARRANTIES

YOU AGREE THAT:

A. IF YOU USE OUR SERVICE, YOU DO SO AT YOUR OWN AND SOLE RISK.  OUR SERVICE IS PROVIDED ON AN "AS IS" AND "AS AVAILABLE" BASIS.  WE EXPRESSLY DISCLAIM ALL WARRANTIES OF ANY KIND, WHETHER EXPRESS OR IMPLIED, INCLUDING, WITHOUT LIMITATION, IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NON-INFRINGEMENT.  

B. WE DO NOT WARRANT THAT (A) OUR SERVICE WILL MEET YOUR REQUIREMENTS, (B) OUR SERVICE WILL BE UNINTERRUPTED, TIMELY, SECURE, OR ERROR-FREE, (C) ANY INFORMATION THAT YOU MAY OBTAIN ON OUR SERVICE WILL BE ACCURATE OR RELIABLE, (D) THE QUALITY OF ANY PRODUCTS, SERVICES, INFORMATION OR OTHER MATERIAL PURCHASED OR OBTAINED BY YOU THROUGH OUR SERVICE WILL MEET YOUR EXPECTATIONS, (E) ANY INFORMATION YOU PROVIDE OR WE COLLECT WILL NOT BE DISCLOSED TO THIRD PARTIES OR (F) ANY ERRORS IN ANY DATA OR SOFTWARE WILL BE CORRECTED.  

C. IF YOU ACCESS OR TRANSMIT ANY CONTENT THROUGH THE USE OF OUR SERVICE, YOU DO SO AT YOUR OWN DISCRETION AND YOUR SOLE RISK.  YOU ARE SOLELY RESPONSIBLE FOR ANY LOSS OR DAMAGE TO YOU IN CONNECTION WITH SUCH ACTIONS.  

D. NO DATA, INFORMATION OR ADVICE OBTAINED BY YOU IN ORAL OR WRITTEN FORM FROM US OR THROUGH OR FROM OUR SERVICE WILL CREATE ANY WARRANTY NOT EXPRESSLY STATED IN THESE TERMS.  

11. LIMITS ON LIABILITY  

A. YOU AGREE THAT WE WILL NOT BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, CONSEQUENTIAL OR EXEMPLARY DAMAGES (EVEN IF WE HAVE BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES), ARISING FROM, RELATING TO OR CONNECTED WITH: (A) THE USE OR INABILITY TO USE OUR SERVICE, (B) THE COST OF REPLACEMENT OF ANY GOODS, SERVICES OR INFORMATION PURCHASED OR OBTAINED AS A RESULT OF ANY INFORMATION OBTAINED FROM OR TRANSACTIONS ENTERED INTO THROUGH OR FROM OUR SERVICE, (C) DISCLOSURE OF, UNAUTHORIZED ACCESS TO OR ALTERATION OF YOUR CONTENT, (D) STATEMENTS, CONDUCT OR OMISSIONS OF ANY SERVICE PROVIDERS OR OTHER THIRD PARTY ON OUR SERVICE OR (E) ANY OTHER MATTER ARISING FROM, RELATING TO OR CONNECTED WITH OUR SERVICE OR THESE TERMS.  

B. WE WILL NOT BE LIABLE FOR ANY FAILURE OR DELAY IN PERFORMING UNDER THESE TERMS OF USE WHERE SUCH FAILURE OR DELAY IS DUE TO CAUSES BEYOND OUR REASONABLE CONTROL, INCLUDING NATURAL CATASTROPHES, GOVERNMENTAL ACTS OR OMISSIONS, LAWS OR REGULATIONS, TERRORISM, LABOR STRIKES OR DIFFICULTIES, COMMUNICATIONS SYSTEMS BREAKDOWNS, HARDWARE OR SOFTWARE FAILURES, TRANSPORTATION STOPPAGES OR SLOWDOWNS OR THE INABILITY TO PROCURE SUPPLIES OR MATERIALS.  

C. IN NO EVENT WILL OUR AGGREGATE LIABILITY TO YOU OR ANY THIRD PARTY IN ANY MATTER ARISING FROM, RELATING TO OR CONNECTED WITH OUR SERVICE OR THESE TERMS EXCEED THE SUM OF TWO HUNDRED ($200) DOLLARS.  

D. SOME JURISDICTIONS DO NOT ALLOW THE EXCLUSION OF CERTAIN WARRANTIES OR THE LIMITATION OR EXCLUSION OF LIABILITY FOR INCIDENTAL OR CONSEQUENTIAL DAMAGES. ACCORDINGLY, SOME OF THE LIMITATIONS OF SECTIONS 11 AND 12 MAY NOT APPLY TO YOU.  

12. INDEMNITY

You agree to defend, indemnify and hold us harmless from any claim, demand, action, damage, loss, cost or expense, including without limitation, reasonable attorneys fees, incurred in connection with any suit or proceeding brought against us arising out of your use of our Service or alleging facts or circumstances that could constitute a breach of any provision of these Terms by you.  If you are obligated to indemnify us, we will have the right, in our sole and unfettered discretion, to control any action or proceeding and determine whether we wish to settle it, and if so, on what terms. 

13. ARBITRATION

All disputes arising out of or relating to these Terms or your use of our Service will be exclusively resolved under binding arbitration held in New Jersey before and in accordance with the Rules of the American Arbitration Association, except that we will have the right to seek injunctive or other equitable relief in state or federal court located in New Jersey to enforce these terms or prevent an infringement of a third partys rights.  In the event equitable relief is sought, each party hereby irrevocably submits to the personal jurisdiction of such court. 

14. MISCELLANEOUS

These Terms shall be interpreted in accordance with the laws of the State of New Jersey without reference to conflict of law principles. These Terms contain the entire understanding of the parties regarding their subject matter, and supersede all prior and contemporaneous agreements and understandings between the parties regarding their subject matter.  No failure or delay by a party in exercising any right, power or privilege under these Terms shall operate as a waiver thereof.  The invalidity or unenforceability of any of these Terms shall not affect the validity or enforceability of any other of these Terms, all of which shall remain in full force and effect.</textarea>
          </font></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td> <font size="2" face="Arial, Helvetica, sans-serif"> 
          <input type="reset" name="Submit2" value="Reset form">
          <input type="submit" name="Submit" value="I accept">
          <input type="hidden" name="action" value="submit">
          <input name="type2" type="hidden" id="type22" value="main">
          <input name="upload" type="hidden" id="upload" value="1">
          </font></td>
      </tr>
      <?
  	if($affid != "")
  	 	echo "<input type=hidden name=affid value=$affid>\n";
?>
    </form>
  </table>
</blockquote>
<? } ?>
<? include "_footer.php"; ?>
