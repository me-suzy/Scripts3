<?php
///////////////////////////////////////////////////////////////////////////////
//                                                                           //
//  Program Name         : RedTempest Profiler Pro                           //
//  Release Version      : 1.0.0                                             //
//  Program Author       : Eric Fredin ( RedTempest )                        //
//  Supplied by          : Scoons [WTN]                                      //
//  Nullified by         : CyKuH [WTN]                                       //
//  Distribution         : via WebForum, ForumRU and associated file dumps   //
//                                                                           //
//            Copyright 2000 RedTempest. All Rights Reserved.                //
///////////////////////////////////////////////////////////////////////////////
// Variables (descriptions are as follows)

// Mysql User Information
$sql = array(
    "user" => "root",    // Username
    "pass" => "bah",     // Password
    "host" => "localhost",     // Host
    "db"   => "atomprofiler"     // Database
);

/* End Variables
   If you don't know what you're doing that's all you need to edit.
   If you do know what you're doing feel free to play with the script...
   If you do edit the script and it stops working support will be much
   more difficult.                                                       */


// Connect to the MySQL server
$link = mysql_connect($sql[host],$sql[user],$sql[pass]) or exit("Unable to connect to the MySQL database");
mysql_select_db($sql[db]) or exit("Unable to select the MySQL database $mysql[db]");

// Get variables and prefs.
get_vars();

// Create criticals
if(!is_array($inp)){$inp = array();}
if(!is_array($site)){$site = array();}

// Check for a valid user
$query = mysql_query("select * from profiler_users");
while($this = mysql_fetch_array($query)){
    if($inp_user[alias] == $this[alias] && $inp_user[pass] == $this[pass] && $inp_user[alias] != "" && $inp_user[pass] != ""){
        $validuser = 1;
        $user = $this;
    }
}

// If no valid user is present ask user to login
if($validuser != 1){
    login($inp_user);
}

// Go to standard functions
get_top_menu($user);
get_side_menu($user);
get_status($user);
viewheadlines();
viewevent();

// Go to functions based on action variable
if($validuser == 1){
    if($action == "newuser"){newuser($user);}
    elseif($action == "newuser2"){newuser2($user);}
    elseif($action == "viewapp"){viewapp($user);}
    elseif($action == "doapp"){doapp($user);}
    elseif($action == "edituser"){edituser($user);}
    elseif($action == "edituser2"){edituser2($user);}
    elseif($action == "edituser3"){edituser3($user);}
    elseif($action == "remove"){remove($user);}
    elseif($action == "remove2"){remove2($user);}
    elseif($action == "admin"){admin($user);}
    elseif($action == "admin2"){admin2($user);}
    elseif($action == "news"){news($user);}
    elseif($action == "news2"){news2($user);}
    elseif($action == "getnews"){getnews($user);}
    elseif($action == "Edit"){editnews($user);}
    elseif($action == "editnews2"){editnews2($user);}
    elseif($action == "Delete"){delnews($user);}
    elseif($action == "mssg"){mssgcenter($user);}
    elseif($action == "mssgnew"){mssgnew($user);}
    elseif($action == "mssgnew2"){mssgnew2($user);}
    elseif($action == "mssgview"){mssgview($user);}
    elseif($action == "mssgdele"){mssgdele($user);}
    elseif($action == "list"){memberlist($user);}
    elseif($action == "cal"){calandar($user);}
    elseif($action == "calday"){calday($user);}
    elseif($action == "calnew"){calnew($user);}
    elseif($action == "set"){settings($user);}
    elseif($action == "set2"){settings2($user);}
}
if($action == "login"){login($inp_user);}
elseif($action == "logout"){logout($inp_user);}

// Outside member required functions
elseif($action == "viewnews"){viewnews();}
elseif($action == "headlines"){viewheadlines();}
elseif($action == "comment"){comment();}
elseif($action == "comment2"){comment2();}
elseif($action == "vmain"){viewmain();}
elseif($action == "vprofile"){viewprofile();}
elseif($action == "apply"){apply();}
elseif($action == "apply2"){apply2();}
elseif($action == "code"){code();}
elseif($action == "viewcode"){viewcode();}
if(!$action){main();}

// Call up template file...
if($action != 'viewcode' && $action != 'vprofile'){
    include("$vars[dir_php]/template.php");
}

// specific functions are as follows...

// Grabs vars and prefs from the sql db
function get_vars(){
    global $prefs, $vars;
    
    $query = mysql_query("select * from profiler_data");
    while($this = mysql_fetch_array($query)){
        if($this[type] == "pref"){
            $prefs[$this[name]] = $this[value];
        }
        elseif($this[type] == "var"){
            $vars[$this[name]] = $this[value];
        }
    }
}

// Login Function
function login($inp_user){
    global $site, $prefs, $user;

    $site[title] = "Profiler Login";

    $query = mysql_query("select alias,pass from profiler_users where alias = '$inp_user[alias]'");
    // Ensure a valid user is present

    // That user name (or, as they say in Canada, "alias") wasn't found, eh? :)
    if(mysql_num_rows($query)==0 || !$query){
    	// Insult the dumb user.
        $site[text] = "
            Unable to validate user.<br>
            <center><br>
		    <form method=post action=\"profiler.php?action=login\">
		    <table border=0 cellpadding=2 cellspacing=0 width=200>
		    <tr><td colspan=2 width=200 bgcolor='$prefs[bgcolor1]' align=center><font face='$prefs[fontface]' size='$prefs[fontsize2]' color='$prefs[fontcolor2]'><b>LOGIN</b></font></td></tr>
		    <tr><td width=100 align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Username:</font></td><td width=100><input type=text name='inp_user[alias]' size=18 class=form value=\"".$inp_user[alias]."\"></td></tr>
		    <tr><td width=100 align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Password:</font></td><td width=100><input type=password name='inp_user[pass]' size=18 class=form></td></tr>
		    <tr><td colspan=2 width=200 align=center><input type=submit class=submit value=\"Login\"></td></tr>
     	    </table></form></center>
        ";
    }
    else {
        $user = mysql_fetch_array($query);
        if($inp_user[alias] == $user[alias] && md5($inp_user[pass]) == $user[pass]) {
        // Log in user and get their info.
     		// Get info again but reload confirmed
    		$user = mysql_fetch_array(mysql_query("select * from profiler_users where id='$user[id]'"));

    		// Set cookies.
            setcookie("inp_user[alias]",$inp_user[alias],time+300000000,"/");
            setcookie("inp_user[pass]",md5($inp_user[pass]),time+3600,"/");

            $user = mysql_fetch_array(mysql_query("select * from profiler_users where alias = '$inp_user[alias]'"));
            mysql_query("update profiler_users set login = now() where alias = '$inp_user[alias]'");

            $site[text] = "You have been logged in.<br><a href=profiler.php>Click Here to continue to the main menu</a>.";
            
            if(mysql_num_rows(mysql_query("select * from profiler_apps")) != 0){
                if($user[status] == 2 || $user[status] == 3){
                    $site[text] .= "<br><b>There are <a href='profiler.php?action=viewapp'>new applicants</a> waiting to be reviewed.</b>";
                }
            }
    	}
    	else {
    		// Their password was yucky.
            $site[text] = "
                Unable to validate user.&nbsp;<br>
                <center><br>
		        <form method=post action=\"profiler.php?action=login\">
		        <table border=0 cellpadding=2 cellspacing=0 width=200>
                <tr><td colspan=2 width=200 bgcolor='$prefs[bgcolor1]' align=center><font face='$prefs[fontface]' size='$prefs[fontsize2]' color='$prefs[fontcolor2]'><b>LOGIN</b></font></td></tr>
		        <tr><td width=100 align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Username:</font></td><td width=100><input type=text name='inp_user[alias]' size=18 class=form value=\"".$inp_user[alias]."\"></td></tr>
		        <tr><td width=100 align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Password:</font></td><td width=100><input type=password name='inp_user[pass]' size=18 class=form></td></tr>
		        <tr><td colspan=2 width=200 align=center><input type=submit class=submit value=\"Login\"></td></tr>
                </table></form></center>
            ";
    	}
    }
}

function logout($inp_user){
    global $site, $prefs;

    setcookie("inp_user[alias]","",time()-3600,"/");
    setcookie("inp_user[pass]","",time()-3600,"/");
    
    $site[title] = "Logged Out";
    $site[text] = "<font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>You have been logged out.<br><a href='profiler.php?action=login'>Click here to login</a></font>";
}

// Main Menu
function main(){
    global $site, $prefs;
    
    $site[title] = "Home";

    viewnews();
    $site[text] = str_replace("<viewnews>",$site[viewnews],$prefs[main_layout]);
}

// New User
function newuser($user){
    global $site, $prefs, $vars;
    
    $site[title] = "Profiler Admin - New User";
    
    if($user[status] == 1){
        $site[text] = "
            You are not authorized to add a new user.
        ";
    }
    
    else{
    $site[text] = "
        <center>
        <form method=post action='profiler.php?action=newuser2'>
        <table border=0 cellpadding=0 cellspacing=2 width=60%>
          <tr>
            <td width=100% colspan=2 bgcolor='$prefs[bgcolor1]' align=center><font face='$prefs[fontface]' color='$prefs[fontcolor2]' size='$prefs[fontsize2]'><b>Create a New User <a href='' onClick=\"window.open('$vars[url_php]/docs/newmember.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\"><img src='$vars[url_php]/help.gif' border=0></a></font></td>
          </tr>
          <tr>
            <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>User Alias:</font></td>
            <td width=60%><input type=text name=inp[alias] size=20 class=form></td>
          </tr>
          <tr>
            <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Password:</font></td>
            <td width=60%><input type=password name=inp[pass] size=20 class=form></td>
          </tr>
          <tr>
            <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Confirm Password:</font></td>
            <td width=60%><input type=password name=inp[pass2] size=20 class=form></td>
          </tr>
          <tr>
            <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Email:</font></td>
            <td width=60%><input type=text name=inp[email] size=20 class=form></td>
          </tr>
          <tr>
            <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Rank:</font></td>
            <td width=60%><select name=inp[rank] class=form>
    ";
    
    // Get ranks
    $ranks = explode("`",$prefs[ranks]);
    for($a = 0; $a < count($ranks); $a++){
        $site[text] .= "<option value='$ranks[$a]'>$ranks[$a]";
    }
    
    $site[text] .= "
            </select></td>
          </tr>
          <tr>
            <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Faction:</font></td>
            <td width=60%><select name=inp[faction] class=form>
    ";
    
    $factions = explode("`",$prefs[factions]);
    for($a = 0; $a < count($factions); $a++){
        $site[text] .= "<option value='$factions[$a]'>$factions[$a]";
    }
    
    $site[text] .= "</select></td></tr>";
    
    // Get custom user fields
    $field = explode("`",$prefs[user_info]);
    $field_name = explode("`",$prefs[user_info_name]);
    if(count($field) >= 1){
    for($a = 0; $a < count($field); $a++){
        $site[text] .= "
            <tr>
              <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>$field[$a]:</font></td>
              <td width=60%><input type=text name=inp[$field_name[$a]] size=20 class=form></td>
            </tr>
        ";
    }}
          
    $site[text] .= "
          <tr>
            <td width=40%>&nbsp;</td>
            <td width=60%><input type=submit value='Create New User'></td>
          </tr>
        </table></form></center>
    ";}
}

function newuser2($user){
    global $site, $prefs, $inp, $vars;
    
    $site[title] = "Profiler Admin - New User";
    
    if($user[status] == 1){
        $site[text] = "
            You are not authorized to add a new user.
        ";
    }
    
    else{
    // Prepare variables
    $field = explode("`",$prefs[user_info]);
    $field_name = explode("`",$prefs[user_info_name]);
    $require = explode("`",$prefs[required]);
    for($a = 0; $a < count($field_name); $a++){
        $form[$field_name[$a]] = $required[$a];
    }
    
    // Check for alias match
    $query = mysql_query("select alias from profiler_users");
    while($alias = mysql_fetch_array($query)){
        if($alias == $inp_alias){$invalid = 1; $reason = "that user alias is already in use";}

    }
    
    // Prepare domain / email validation
    eregi("^[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,3}$", $inp[email], $check);

    // Check required form fields
    if($inp[alias] == "" || $inp[pass] == "" || $inp[email] == ""){$invalid = 1; $reason = "not all required form fields were filled out";}

    if(is_array($inp)){
        while(list($key,$val) = each($inp)){
            if($form[$key] == 1 && $val == ""){$invalid = 1; $reason = "not all required form fields were filled out";}
        }
    }
    else{$invalid = 1; $reason = "not all required form fields were filled out";}

    // Check DNR and email...
    if(checkdnsrr(substr(strstr($check[0], '@'), 1),"ANY") == 0){$invalid = 1; $reason = "bad email address";}

    // Check passwords
    if($inp[pass] != $inp[pass2]){$invalid = 1; $reason = "passwords don't match";}
    
    // Print out invalid form and reason
    if($invalid){
        $site[text] = "
            The new user could not be created because $reason<br>
            <center>
            <form method=post action='profiler.php?action=newuser2'>
            <table border=0 cellpadding=0 cellspacing=2 width=60%>
              <tr>
                <td width=100% colspan=2 bgcolor='$prefs[bgcolor1]' align=center><font face='$prefs[fontface]' color='$prefs[fontcolor2]' size=2><b>Create a New User <a href='' onClick=\"window.open('$vars[url_php]/docs/newmember.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\"><img src='$vars[url_php]/help.gif' border=0></a></font></td>
              </tr>
              <tr>
                <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>User Alias:</font></td>
                <td width=60%><input type=text name=inp[alias] size=20 class=form value='$inp[alias]'></td>
              </tr>
              <tr>
                <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Password:</font></td>
                <td width=60%><input type=password name=inp[pass] size=20 class=form value='$inp[pass]'></td>
              </tr>
              <tr>
                <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Confirm Password:</font></td>
                <td width=60%><input type=password name=inp[pass2] size=20 class=form value='$inp[pass2]'></td>
              </tr>
              <tr>
                <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Email:</font></td>
                <td width=60%><input type=text name=inp[email] size=20 class=form value='$inp[email]'></td>
              </tr>
              <tr>
                <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Rank:</font></td>
                <td width=60%><select name=inp[rank] class=form>
        ";

        // Get ranks
        $ranks = explode("`",$prefs[ranks]);
        for($a = 0; $a < count($ranks); $a++){
            if($inp[rank] == $ranks[$a]){
                $site[text] .= "<option value='$ranks[$a]' selected>$ranks[$a]";
            }
            else{
                $site[text] .= "<option value='$ranks[$a]'>$ranks[$a]";
            }
        }

        $site[text] .= "
                </select></td>
              </tr>
              <tr>
                <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Faction:</font></td>
                <td width=60%><select name=inp[faction] class=form>
        ";

        $factions = explode("`",$prefs[factions]);
        for($a = 0; $a < count($factions); $a++){
            if($inp[faction] == $factions[$a]){
                $site[text] .= "<option value='$factions[$a]' selected>$factions[$a]";
            }
            else{
                $site[text] .= "<option value='$factions[$a]'>$factions[$a]";
            }
        }

        $site[text] .= "</select></td></tr>";
        if(count($field) >= 1){
        for($a = 0; $a < count($field); $a++){
            $this = $field_name[$a];
            $site[text] .= "
                <tr>
                  <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>$field[$a]:</font></td>
                  <td width=60%><input type=text name=inp[$this] size=20 class=form value='$inp[$this]'></td>
                </tr>
            ";
        }}

        $site[text] .= "
              <tr>
                <td width=40%>&nbsp;</td>
                <td width=60%><input type=submit value='Create New User'></td>
              </tr>
            </table></form></center>
        ";
    }
    
    // Create the valid new user
    else{
        // Encrypt user's password using md5
        $inp[pass] = md5($inp[pass]);

        //Prepare MySql db entry
        $sql_names = "alias,pass,email,rank,faction";
        $sql_values = "'$inp[alias]','$inp[pass]','$inp[email]','$inp[rank]','$inp[faction]'";
        foreach($field_name as $this){
            $sql_names .= ",$this";
            $sql_values .= ",'$inp[$this]'";
        }

        mysql_query("insert into profiler_users ($sql_names, image) values ($sql_values, 'gif')") or mysql_error();

        if($prefs[info_image] == 1){
            $prev = mysql_fetch_array(mysql_query("select id, alias from profiler_users where alias = '$inp[alias]' limit 1"));
            chdir("$vars[dir_php]/$prefs[info_image_dir]");
            copy("0.gif", "$prev[id].gif");
        }
        
        // Prepare output
        $site[text] = "
            User has been created.
        ";
    }}
}

// Applicants
function viewapp($user){
    global $site, $prefs, $vars;
    
    $site[title] = "Profiler Admin - View Applicants";
    
    $site[text] = "
        <center><form method=post action='profiler.php?action=doapp'>
        <table border=0 cellpadding=1 cellspacing=1 width=80%>
          <tr>
            <td width=100% colspan=4 bgcolor='$prefs[bgcolor1]' align=center><font face='$prefs[fontface]' size='$prefs[fontsize2]'color='$prefs[fontcolor2]'><b>Applicants</b> <a href='' onClick=\"window.open('$vars[url_php]/docs/applicants.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\"><img src='$vars[url_php]/help.gif' border=0></a></font></td>
          </tr>
    ";
    
    $query = mysql_query("select * from profiler_apps");
    if(mysql_num_rows($query) == 0){
        $site[text] = "<font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>There are no applicants</font>";
    }
    else{
    while($this = mysql_fetch_array($query)){
        $site[text] .= "
          <tr>
            <td width=25% align=right bgcolor='$prefs[bgcolor1]'><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor2]'>Alias</font></td>
            <td width=25% bgcolor='$prefs[bgcolor1]'><input type=text name='inp[$this[id]][alias]' class=form size=20 value='$this[alias]'><input type=hidden name='inp[$this[id]][pass]' value='$this[pass]'><input type=hidden name='inp[$this[id]][id]' value='$this[id]'></td>
            <td width=25% align=right bgcolor='$prefs[bgcolor1]'><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor2]'>Email</font></td>
            <td width=25% bgcolor='$prefs[bgcolor1]'><input type=text name='inp[$this[id]][email]' class=form size=20 value='$this[email]'></td>
          </tr>
          <tr>
            <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Rank</font></td>
            <td width=25%><select name='inp[$this[id]][rank]' class=form>
        ";
        // Get ranks
        $ranks = explode("`",$prefs[ranks]);
        for($a = 0; $a < count($ranks); $a++){
            if($inp[rank] == $ranks[$a]){
                $site[text] .= "<option value='$ranks[$a]' selected>$ranks[$a]";
            }
            else{
                $site[text] .= "<option value='$ranks[$a]'>$ranks[$a]";
            }
        }
        $site[text] .= "
            </td>
            <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Faction</font></td>
            <td width=25%><select name='inp[$this[id]][faction]' class=form>
        ";
        $factions = explode("`",$prefs[factions]);
        for($a = 0; $a < count($factions); $a++){
            if($inp[faction] == $factions[$a]){
                $site[text] .= "<option value='$factions[$a]' selected>$factions[$a]";
            }
            else{
                $site[text] .= "<option value='$factions[$a]'>$factions[$a]";
            }
        }
        $site[text] .= "
            </td>
          </tr>
        ";
        $field = explode("`",$prefs[user_info]);
        $field_name = explode("`",$prefs[user_info_name]);
        if(count($field) >= 1){
        for($a = 0; $a < count($field); $a++){
            $b = $a + 1;
            $this2 = $field_name[$a];
            $this3 = $field_name[$b];
            $site[text] .= "
                <tr>
                  <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>$field[$a]</font></td>
                  <td width=25%><input type=text name='inp[$this[id]][$field_name[$a]]' class=form size=20 value='$this[$this2]'></td>
            ";
            if($field_name[$b]){
                $site[text] .= "
                      <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>$field[$b]</font></td>
                      <td width=25%><input type=text name='inp[$this[id]][$field_name[$b]]' class=form size=20 value='$this[$this3]'></td>
                    </tr>
                ";
            }
            else{
                $site[text] .= "
                      <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>&nbsp;</font></td>
                      <td width=25%>&nbsp;</td>
                    </tr>
                ";
            }
            $a++;
        }}
        $site[text] .= "
              <tr>
                <td width=100% colspan=4 align=center><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'><input type=radio name='inp[$this[id]][status]' value=accept checked>Accept this applicant<br><input type=radio name='inp[$this[id]][status]' value=reject>Do not accept this applicant<br><br></font></td>
              </tr>
        ";
    }
    
    $site[text] .= "
          <tr>
            <td width=100% colspan=4 align=center><input type=submit value='Process Applicants'></td>
          </tr>
        </table></form></center>
    ";
    }
}

function doapp($user){
    global $site, $prefs, $vars, $inp;
    
    $site[title] = "Profiler Admin - Process Applicants";
    
    $field_name = explode("`",$prefs[user_info_name]);

    foreach($inp as $this){
        if($this[status] == 'accept'){
            //Prepare MySql db entry
            $sql_names = "alias,pass,email,rank,faction";
            $sql_values = "'$this[alias]','$this[pass]','$this[email]','$this[rank]','$this[faction]'";
            foreach($field_name as $field){
                $sql_names .= ",$field";
                $sql_values .= ",'$this[$field]'";
            }

            mysql_query("insert into profiler_users ($sql_names, image) values ($sql_values, 'gif')");
            if($prefs[info_image] == 1){
                $prev = mysql_fetch_array(mysql_query("select id, alias from profiler_users where alias = '$inp[alias]' limit 1"));
                chdir("$vars[dir_php]/$prefs[info_image_dir]");
                copy("0.gif", "$prev[id].gif");
            }
            $message = "Welcome to the clan $this[alias]! Your application to join has been accepted. You should now get in contact with a clan leader.\nYou can view the clan's website here:\n$vars[url_php]/profiler.php";
            mail($this[email],'Clan Application -- Automatic Message',$message);
        }
        mysql_query("delete from profiler_apps where id = '$this[id]'");
    }
    
    $site[text] = "<font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>All applicants have been processed";
}

// Edit Members
function edituser($user){
    global $site, $prefs, $vars;
    
    $site[title] = "Profiler Admin - Edit User";

    // Determine user list
    if($user[status] == 1){
        $query = mysql_query("select id,alias from profiler_users where alias = '$user[alias]'");
    }
    elseif($user[status] == 2 || $user[status] == 3){
        $query = mysql_query("select id,alias from profiler_users");
    }
    
    $site[text] = "
        <center><form method=post action='profiler.php?action=edituser2'>
        <font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Select a member to edit. <a href='' onClick=\"window.open('$vars[url_php]/docs/editmember.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\"><img src='$vars[url_php]/help.gif' border=0></a><br>
        <select name='inp[id]' class=form>
    ";
    
    // Create option list of members based on permission
    while($this = mysql_fetch_array($query)){
        $site[text] .= "<option value='$this[id]'>$this[alias]";
    }
    
    $site[text] .= "</select>&nbsp;&nbsp;<input type=submit value='Edit Member'></form>";
}

function edituser2($user){
    global $site, $inp, $prefs, $eid, $vars;
    
    $site[title] = "Profiler Admin - Edit User";
    
    if($eid && $eid != ""){
        $inp[id] = $eid;
    }
    
    // Prepare vars
    $field = explode("`",$prefs[user_info]);
    $field_name = explode("`",$prefs[user_info_name]);
    
    // Get the requested user
    $query = mysql_query("select * from profiler_users where id = '$inp[id]'");
    $edit = mysql_fetch_array($query);
    
    // Prepare table output of requested user
    $site[text] = "
        <center>
        <form method=post action='profiler.php?action=edituser3'ENCTYPE='multipart/form-data'><input type=hidden name=MAX_FILE_SIZE value=1000000><input type=hidden name='inp[id]' value=$inp[id]><input type=hidden name='inp[alias]' value='$inp[alias]'>
        <table border=0 cellpadding=0 cellspacing=2 width=60%>
          <tr>
            <td width=100% colspan=2 bgcolor='$prefs[bgcolor1]' align=center><font face='$prefs[fontface]' color='$prefs[fontcolor2]' size=2><b>Edit Existing User</b> <a href='' onClick=\"window.open('$vars[url_php]/docs/newmember.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\"><img src='$vars[url_php]/help.gif' border=0></a></font></td>
          </tr>
          <tr>
            <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>User Alias:</font></td>
            <td width=60%><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>$edit[alias]</font></td>
          </tr>
          <tr>
            <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Password:</font></td>
            <td width=60%><input type=password name=inp[pass] size=20 class=form value=''><font face='$prefs[fontface]' size=1><br>(Leave blank to keep original)</font></td>
          </tr>
          <tr>
            <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Email:</font></td>
            <td width=60%><input type=text name=inp[email] size=20 class=form value='$edit[email]'></td>
          </tr>
    ";
    if($user[status] == 2 || $user[status] == 3){
        $site[text] .= "
          <tr>
            <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Rank:</font></td>
            <td width=60%><select name=inp[rank] class=form>
        ";

        // Get ranks
        $ranks = explode("`",$prefs[ranks]);
        for($a = 0; $a < count($ranks); $a++){
            if($edit[rank] == $ranks[$a]){
                $site[text] .= "<option value='$ranks[$a]' selected>$ranks[$a]";
            }
            else{
                $site[text] .= "<option value='$ranks[$a]'>$ranks[$a]";
            }
        }

        $site[text] .= "
                </select></td>
              </tr>
              <tr>
                <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Faction:</font></td>
                <td width=60%><select name=inp[faction] class=form>
        ";

        $factions = explode("`",$prefs[factions]);
        for($a = 0; $a < count($factions); $a++){
            if($edit[faction] == $factions[$a]){
                $site[text] .= "<option value='$factions[$a]' selected>$factions[$a]";
            }
            else{
                $site[text] .= "<option value='$factions[$a]'>$factions[$a]";
            }
        }

        $site[text] .= "</select></td></tr>";
    }
    
    //
    else{
        $site[text] .= "
          <tr>
            <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Rank:</font></td>
            <td width=60%><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>$user[rank]</font></td>
          </tr>
          <tr>
            <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Faction:</font></td>
            <td width=60%><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>$user[faction]</font></td>
          </tr>
        ";
    }
    if(count($field) >= 1){
    for($a = 0; $a < count($field); $a++){
        $this = $field_name[$a];
        $site[text] .= "
            <tr>
              <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>$field[$a]:</font></td>
              <td width=60%><input type=text name=inp[$this] size=20 class=form value='$edit[$this]'></td>
            </tr>
        ";
    }}
    
    if($prefs[info_image] == 1 && $edit[image] == ""){
        $site[text] .= "
            <tr>
              <td width=40% align=right valign=top><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Image:</font></td>
              <td width=60%><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Current size is <b>$prefs[info_image_height] x $prefs[info_image_width]</b><br>
              <input type=file name='inp[image]' size=20 class=form></font></td>
            </tr>
        ";
    }
    elseif($prefs[info_image] == 1 && $edit[image] != ""){
        $site[text] .= "
            <tr>
              <td width=40% align=right valign=top><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Image:</font></td>
              <td width=60%><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'><img src='$prefs[info_image_dir]/$edit[id].$edit[image]' height='$prefs[info_image_height]' width='$prefs[info_image_width]' border=0><br>
              Delete Image <input type=checkbox class=form value='$edit[image]' name='inp[image]'></td>
            </tr>
        ";
    }

    $site[text] .= "
          <tr>
            <td width=40%>&nbsp;</td>
            <td width=60%><input type=submit value='Update Member'></td>
          </tr>
        </table></form></center>
    ";
}

function edituser3($user){
    global $inp, $site, $prefs, $vars;
    
    $site[title] = "Profiler Admin - Edit User";
    
    // Prepare variables
    $field = explode("`",$prefs[user_info]);
    $field_name = explode("`",$prefs[user_info_name]);
    $require = explode("`",$prefs[required]);
    for($a = 0; $a < count($field_name); $a++){
        $form[$field_name[$a]] = $required[$a];
    }

    // Prepare domain / email validation
    eregi("^[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,3}$", $inp[email], $check);

    // Check required form fields
    if($inp[email] == ""){$invalid = 1; $reason = "not all required form fields were filled out";}

    if(is_array($inp)){
        while(list($key,$val) = each($inp)){
            if($form[$key] == 1 && $val == ""){$invalid = 1; $reason = "not all required form fields were filled out";}
        }
    }
    else{$invalid = 1; $reason = "not all required form fields were filled out";}

    // Check DNR and email...
    if(checkdnsrr(substr(strstr($check[0], '@'), 1),"ANY") == 0){$invalid = 1; $reason = "bad email address";}

    // Print out invalid form and reason
    if($invalid){
        $site[text] = "
            The new user could not be ediuted because $reason<br>
            <center>
            <form method=post action='profiler.php?action=edituser3'><input type=hidden name='inp[id]' value=$inp[id]><input type=hidden name='inp[alias]' value='$inp[alias]'>
            <table border=0 cellpadding=0 cellspacing=2 width=60%>
              <tr>
                <td width=100% colspan=2 bgcolor='$prefs[bgcolor1]' align=center><font face='$prefs[fontface]' color='$prefs[fontcolor2]' size=2><b>Edit Existing User</b> <a href='' onClick=\"window.open('$vars[url_php]/docs/newmember.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\"><img src='$vars[url_php]/help.gif' border=0></a></font></td>
              </tr>
              <tr>
                <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>User Alias:</font></td>
                <td width=60%><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>$inp[alias]</font></td>
              </tr>
              <tr>
                <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Password:</font></td>
                <td width=60%><input type=password name=inp[pass] size=20 class=form value='$inp[pass]'><font face='$prefs[fontface]' size=1><br>(Leave blank to keep original)</font></td>
              </tr>
              <tr>
                <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Email:</font></td>
                <td width=60%><input type=text name=inp[email] size=20 class=form value='$inp[email]'></td>
              </tr>
        ";
        if($user[status] == 2 || $user[status] == 3){
            $site[text] .= "
          <tr>
            <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Rank:</font></td>
            <td width=60%><select name=inp[rank] class=form>
            ";

            // Get ranks
            $ranks = explode("`",$prefs[ranks]);
            for($a = 0; $a < count($ranks); $a++){
                if($inp[rank] == $ranks[$a]){
                    $site[text] .= "<option value='$ranks[$a]' selected>$ranks[$a]";
                }
                else{
                    $site[text] .= "<option value='$ranks[$a]'>$ranks[$a]";
                }
            }

            $site[text] .= "
                    </select></td>
                  </tr>
                  <tr>
                    <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Faction:</font></td>
                    <td width=60%><select name=inp[faction] class=form>
            ";

            $factions = explode("`",$prefs[factions]);
            for($a = 0; $a < count($factions); $a++){
                if($inp[faction] == $factions[$a]){
                    $site[text] .= "<option value='$factions[$a]' selected>$factions[$a]";
                }
                else{
                    $site[text] .= "<option value='$factions[$a]'>$factions[$a]";
                }
            }

            $site[text] .= "</select></td></tr>";
        }
        //
        else{
            $site[text] .= "
              <tr>
                <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Rank:</font></td>
                <td width=60%><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>$user[rank]</font></td>
              </tr>
              <tr>
                <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Faction:</font></td>
                <td width=60%><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>$user[faction]</font></td>
              </tr>
            ";
        }
        if(count($field) >= 1){
        for($a = 0; $a < count($field); $a++){
            $this = $field_name[$a];
            $site[text] .= "
                <tr>
                  <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>$field[$a]:</font></td>
                  <td width=60%><input type=text name=inp[$this] size=20 class=form value='$inp[$this]'></td>
                </tr>
            ";
        }}

        if($prefs[info_image] == 1 && $edit[image] == ""){
            $site[text] .= "
                <tr>
                  <td width=40% align=right valign=top><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Image:</font></td>
                  <td width=60%><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Current size is <b>$prefs[info_image_height] x $prefs[info_image_width]</b><br>
                  <input type=file name='inp[image]' size=20 class=form></font></td>
                </tr>
            ";
        }
        elseif($prefs[info_image] == 1 && $edit[image] != ""){
            $site[text] .= "
                <tr>
                  <td width=40% align=right valign=top><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Image:</font></td>
                  <td width=60%><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'><img src='$prefs[info_image_dir]/$edit[id].$edit[image]' height='$prefs[info_image_height]' width='$prefs[info_image_width]' border=0><br>
                  Delete Image <input type=checkbox class=form value='$edit[image]' name='inp[image]'></td>
                </tr>
            ";
        }

        $site[text] .= "
              <tr>
                <td width=40%>&nbsp;</td>
                <td width=60%><input type=submit value='Update Member'></td>
              </tr>
            </table></form></center>
        ";
    }

    // Edit the valid user
    else{
        if($inp[pass] != ""){
            $inp[pass] = md5($inp[pass]);
            $sql_stuff = "pass = '$inp[pass]', email = '$inp[email]'";
            if($user[status] == 2 || $user[status] == 3){
                $sql_stuff .= ",rank = '$inp[rank]', faction = '$inp[faction]'";
            }
        }
        else{
            //Prepare MySql db entry
            $sql_stuff = "email = '$inp[email]'";
            if($user[status] == 2 || $user[status] == 3){
                $sql_stuff .= ",rank = '$inp[rank]', faction = '$inp[faction]'";
            }
        }
        
        foreach($field_name as $this){
            $sql_stuff .= ",$this = '$inp[$this]'";
        }

        mysql_query("update profiler_users set $sql_stuff where id = '$inp[id]'") or mysql_error();

        if($prefs[info_image] == 1 && $inp[image] != "gif" && $inp[image] != "jpg" && $inp[image] != "bmp" && $inp[image] != 'none'){
            $image = GetImageSize($inp[image]);
            if($image[2] == 1){$ext = "gif";}
            elseif($image[2] == 2){$ext = "jpg";}
            elseif($image[2] == 3){$ext = "bmp";}
            
            $newimage = "$vars[dir_php]/$prefs[info_image_dir]/$inp[id].$ext";
            copy($inp[image],$newimage);

            mysql_query("update profiler_users set image = '$ext' where id = '$inp[id]'");
        }
        
        elseif($inp[image] == "gif" || $inp[image] == "jpg" || $inp[image] == "bmp"){
            unlink("$vars[dir_php]/$prefs[info_image_dir]/$inp[id].$inp[image]");
            mysql_query("update profiler_users set image = '' where id = '$inp[id]'");
        }

        // Prepare output
        $site[text] = "
            User has been updated.
        ";
    }
}

function remove($user){
    global $site, $prefs, $vars;
    
    $site[title] = "Profiler Admin - Remove User";
    
    if($user[status] == 1){
        $site[text] = "You are not authorized to remove members.";
    }
    
    elseif($user[status] == 2 || $user[status] == 3){
        $query = mysql_query("select id,alias,status from profiler_users");

        $site[text] = "
            <center><form method=post action='profiler.php?action=remove2'>
            <font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Select a member to remove. <a href='' onClick=\"window.open('$vars[url_php]/docs/delmember.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\"><img src='$vars[url_php]/help.gif' border=0></a><br>
            <select name='inp[id]' class=form>
        ";

        // Create option list of members based on permission
        while($this = mysql_fetch_array($query)){
            if($this[status] != 3){
                $site[text] .= "<option value='$this[id]'>$this[alias]";
            }
        }

        $site[text] .= "</select>&nbsp;&nbsp;<input type=submit value='Remove Member'></form>";
    }
}

function remove2($user){
    global $site, $inp, $rid, $prefs, $vars;
    
    $site[title] = "Profiler Admin - Remove User";
    
    if($rid && $rid != ""){
        $inp[id] = $rid;
    }

    if($user[status] == 1){
        $site[text] = "You are not authorized to remove members.";
    }
    
    elseif($user[status] == 2 || $user[status] == 3){
        mysql_query("delete from profiler_users where id = '$inp[id]'");
        
        $site[text] = "<font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Member has been removed</a>";
    }
}

function admin($user){
    global $site, $prefs, $vars;
    
    $site[title] = "Profiler Admin - Administrate User";

    $query = mysql_query("select id,alias,status from profiler_users");

    $site[text] = "
        <center><form method=post action='profiler.php?action=admin2'>
        <b>Administrate Member</b> <a href='' onClick=\"window.open('$vars[url_php]/docs/admin.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\"><img src='$vars[url_php]/help.gif' border=0></a><br>
        <font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Change member
        <select name='inp[id]' class=form>
    ";

    // Create option list of members based on permission
    while($this = mysql_fetch_array($query)){
        if($this[status] != 3){
            $site[text] .= "<option value='$this[id]'>$this[alias]";
        }
    }

    $site[text] .= "
        </select> to a(n)<select name='inp[status]' class=form><option value=1>Member<option value=2>User<option vlaue=3>Administrator</select>&nbsp;&nbsp;
        <input type=submit value='Administrate Member'></form>
    ";
}

function admin2($user){
    global $site, $prefs, $inp;
    
    $site[title] = "Profiler Admin - Administrate User";
    
    mysql_query("update profiler_users set status = '$inp[status]' where id = '$inp[id]'");
    
    $site[text] = "<font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Member's status has been updated.</font>";
}

// News Center
function news($user){
    global $site, $prefs, $vars;
    
    $site[title] = "Profiler Admin - News Center";

    if($user[status] == 1){
        $site[text] = "<font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>You are not authorized to add news</font>";
    }
    
    if($user[status] == 2 || $user[status] == 3){
    
        $outdate = date($prefs[news_date]);
    
        $site[text] .= "
            <center><form method=post action='profiler.php?action=news2'>
            <table border=0 cellpadding=1 cellspacing=1 width=80%>
              <tr>
                <td width=100% colspan=2 align=center bgcolor='$prefs[bgcolor1]'><font face='$prefs[fontface]' color='$prefs[fontcolor2]' size='$prefs[fontsize2]'><b>Post News</b> <a href='' onClick=\"window.open('$vars[url_php]/docs/news.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\"><img src='$vars[url_php]/help.gif' border=0></a></font></td>
              </tr>
              <tr>
                <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Subject</font></td>
                <td width=75%><input type=text name=inp[subject] class=form size=40></td>
              </tr>
              <tr>
                <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Date</font></td>
                <td width=75%><input type=text name=inp[date] class=form size=40 value='$outdate'></td>
              </tr>
              <tr>
                <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Author</font></td>
                <td width=75%><input type=text name=inp[author] class=form size=40 value='$user[alias]'</td>
              </tr>
              <tr>
                <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Email</font></td>
                <td width=75%><input type=text name=inp[email] class=form size=40 value='$user[email]'</td>
              </tr>
              <tr>
                <td width=25% align=right valign=top><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>News</font></td>
                <td width=75%><textarea class=form name=inp[news] cols=60 rows=10></textarea></td>
              </tr>
              <tr>
                <td width=25%>&nbsp;</td>
                <td width=75%><input type=submit value='Post News'></td>
              </tr>
            </table></form></center>
        ";
    }
}

function news2($user){
    global $site, $prefs, $inp, $vars;
    
    $site[title] = "Profiler Admin - News Center";

    if($user[status] == 1){
        $site[text] = "<font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>You are not authorized to add news</font>";
    }

    if($user[status] == 2 || $user[status] == 3){

        if($inp[subject] == '' || $inp[date] == '' || $inp[news] == '' || $inp[author] == ''){
            $outdate = date($prefs[news_date]);
            $site[text] .= "
                <center><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Not all the form fields were filled out.
                <form method=post action='profiler.php?action=news2'>
                <table border=0 cellpadding=1 cellspacing=1 width=80%>
                  <tr>
                    <td width=100% colspan=2 align=center bgcolor='$prefs[bgcolor1]'><font face='$prefs[fontface]' color='$prefs[fontcolor2]' size='$prefs[fontsize2]'><b>Post News</b> <a href='' onClick=\"window.open('$vars[url_php]/docs/news.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\"><img src='$vars[url_php]/help.gif' border=0></a></font></td>
                  </tr>
                  <tr>
                    <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Subject</font></td>
                    <td width=75%><input type=text name=inp[subject] class=form size=40 value='$inp[subject]'></td>
                  </tr>
                  <tr>
                    <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Date</font></td>
                    <td width=75%><input type=text name=inp[date] class=form size=40 value='$inp[date]'></td>
                  </tr>
                  <tr>
                    <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Author</font></td>
                    <td width=75%><input type=text name=inp[author] class=form size=40 value='$inp[author]'></td>
                  </tr>
                  <tr>
                    <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Email</font></td>
                    <td width=75%><input type=text name=inp[email] class=form size=40 value='$inp[email]'</td>
                  </tr>
                  <tr>
                    <td width=25% align=right valign=top><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>News</font></td>
                    <td width=75%><textarea class=form name=inp[news] cols=60 rows=10>$inp[news]</textarea></td>
                  </tr>
                  <tr>
                    <td width=25%>&nbsp;</td>
                    <td width=75%><input type=submit value='Post News'></td>
                  </tr>
                </table></form></center>
            ";
        }
        
        else{
            mysql_query("insert into profiler_news (subject,news,date,author,email) values('$inp[subject]','$inp[news]','$inp[date]','$inp[author]','$inp[email]')");
            
            $site[text] = "<font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Your news has been posted</font>";
        }
    }
}

function getnews($user){
    global $site, $prefs, $vars;
    
    $site[title] = "Profiler Admin - News Center";
    
    $site[text] = "
        <center><form method=post action='profiler.php'>
        <font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Select a news post to edit and then select an action. <a href='' onClick=\"window.open('$vars[url_php]/docs/getnews.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\"><img src='$vars[url_php]/help.gif' border=0></a><br>
        <select name='inp[id]' class=form>
    ";
    
    $query = mysql_query("select * from profiler_news");
    while($this = mysql_fetch_array($query)){
        $site[text] .= "<option value=$this[id]>$this[subject]";
    }
    
    $site[text] .= "</select><br><input type=submit name=action value='Edit'> <input type=submit name=action value='Delete'></form></center>";
}

function editnews($user){
    global $site, $prefs, $inp, $vars;
    
    $site[title] = "Profiler Admin - News Center";

    $query = mysql_query("select * from profiler_news where id = '$inp[id]'");
    $this = mysql_fetch_array($query);
    
    $site[text] = "
        <center>
        <form method=post action='profiler.php?action=editnews2'><input type=hidden name='inp[id]' value='$inp[id]'>
        <table border=0 cellpadding=1 cellspacing=1 width=80%>
          <tr>
            <td width=100% colspan=2 align=center bgcolor='$prefs[bgcolor1]'><font face='$prefs[fontface]' color='$prefs[fontcolor2]' size='$prefs[fontsize2]'><b>Edit News</b> <a href='' onClick=\"window.open('$vars[url_php]/docs/editnews.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\"><img src='$vars[url_php]/help.gif' border=0></a></font></td>
          </tr>
          <tr>
            <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Subject</font></td>
            <td width=75%><input type=text name=inp[subject] class=form size=40 value='$this[subject]'></td>
          </tr>
          <tr>
            <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Date</font></td>
            <td width=75%><input type=text name=inp[date] class=form size=40 value='$this[date]'></td>
          </tr>
          <tr>
            <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Author</font></td>
            <td width=75%><input type=text name=inp[author] class=form size=40 value='$this[author]'></td>
          </tr>
          <tr>
            <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Email</font></td>
            <td width=75%><input type=text name=inp[email] class=form size=40 value='$ithis[email]'</td>
          </tr>
          <tr>
            <td width=25% align=right valign=top><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>News</font></td>
            <td width=75%><textarea class=form name=inp[news] cols=60 rows=10>$this[news]</textarea></td>
          </tr>
          <tr>
            <td width=25%>&nbsp;</td>
            <td width=75%><input type=submit value='Edit News'></td>
          </tr>
        </table></form></center>
    ";
}

function editnews2($user){
    global $site, $prefs, $inp, $vars;
    
    $site[title] = "Profiler Admin - News Center";

    if($user[status] == 1){
        $site[text] = "<font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>You are not authorized to edit news</font>";
    }

    if($user[status] == 2 || $user[status] == 3){

        if($inp[subject] == '' || $inp[date] == '' || $inp[news] == '' || $inp[author] == ''){
            $outdate = date($prefs[news_date]);
            $site[text] .= "
                <center><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Not all the form fields were filled out.
                <form method=post action='profiler.php?action=editnews2'> <input type=hidden name='inp[id]' value='$inp[id]'>
                <table border=0 cellpadding=1 cellspacing=1 width=80%>
                  <tr>
                    <td width=100% colspan=2 align=center bgcolor='$prefs[bgcolor1]'><font face='$prefs[fontface]' color='$prefs[fontcolor2]' size='$prefs[fontsize2]'><b>Edit News</b> <a href='' onClick=\"window.open('$vars[url_php]/docs/editnews.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\"><img src='$vars[url_php]/help.gif' border=0></a></font></td>
                  </tr>
                  <tr>
                    <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Subject</font></td>
                    <td width=75%><input type=text name=inp[subject] class=form size=40 value='$inp[subject]'></td>
                  </tr>
                  <tr>
                    <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Date</font></td>
                    <td width=75%><input type=text name=inp[date] class=form size=40 value='$inp[date]'></td>
                  </tr>
                  <tr>
                    <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Author</font></td>
                    <td width=75%><input type=text name=inp[author] class=form size=40 value='$inp[author]'></td>
                  </tr>
                  <tr>
                    <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Email</font></td>
                    <td width=75%><input type=text name=inp[email] class=form size=40 value='$inp[email]'</td>
                  </tr>
                  <tr>
                    <td width=25% align=right valign=top><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>News</font></td>
                    <td width=75%><textarea class=form name=inp[news] cols=60 rows=10>$inp[news]</textarea></td>
                  </tr>
                  <tr>
                    <td width=25%>&nbsp;</td>
                    <td width=75%><input type=submit value='Edit News'></td>
                  </tr>
                </table></form></center>
            ";
        }

        else{
            mysql_query("update profiler_news set subject = '$inp[subject]', date = '$inp[date]', news = '$inp[news]', author = '$inp[author]', email = '$inp[email]' where id = '$inp[id]'");

            $site[text] = "<font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Your news has been edited</font>";
        }
    }
}

function delnews($user){
    global $site, $prefs, $inp;
    
    $site[title] = "Profiler Admin - News Center";

    if($user[status] == 1){
        $site[text] = "<font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>You are not authorized to delete news</font>";
    }
    
    elseif($user[status] == 2 || $user[status] == 3){
        mysql_query("delete from profiler_news where id = '$inp[id]'");
        $site[text] = "<font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Your news has been deleted</font>";
    }
}

// Message center
function mssgcenter($user){
    global $site, $prefs, $vars;
    
    $site[title] = "Profiler Message Center";
    
    $site[text] = "
        <center><form method=post action='profiler.php?action=mssgdele'>
        <table border=0 cellpadding=1 cellspacing=1 width=100%>
          <tr>
            <td width=100% colspan=4 bgcolor='$prefs[bgcolor1]'><font face='$prefs[fontface]' size='$prefs[fontsize2]' color='$prefs[fontcolor2]'>Message Center <a href='' onClick=\"window.open('$vars[url_php]/docs/messages.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\"><img src='$vars[url_php]/help.gif' border=0></a></font></td>
          </tr>
          <tr>
            <td width=5% bgcolor='$prefs[bgcolor1]'>&nbsp;</td>
            <td width=40% bgcolor='$prefs[bgcolor1]'><font face='$prefs[fontface]' size='$prefs[fontsize2]' color='$prefs[fontcolor2]'><b>Subject</b></td>
            <td width=20% bgcolor='$prefs[bgcolor1]'><font face='$prefs[fontface]' size='$prefs[fontsize2]' color='$prefs[fontcolor2]'><b>From</b></td>
            <td width=35% bgcolor='$prefs[bgcolor1]'><font face='$prefs[fontface]' size='$prefs[fontsize2]' color='$prefs[fontcolor2]'><b>Date</td></td>
          </tr>
    ";
    
    $query = mysql_query("select *,date_format(stamp, '%W %M %d-%H:%i') from profiler_mssg where toid = '$user[id]' order by id desc");
    if(mysql_num_rows($query) == 0){$site[text] .= "<tr><td width=5%>&nbsp;</td><td width=95% colspan=3><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>You have no messages to view</font></td></tr>";}
    while($this = mysql_fetch_array($query)){
        $site[text] .= "
            <tr>
              <td width=5% align=center><input type=checkbox name=mssg[$this[id]] value=2></td>
              <td width=40%><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'><a href='profiler.php?action=mssgview&mssg=$this[id]'>$this[subject]</a></font></td>
              <td width=20%><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'><a href='profiler.php?action=mssgnew&re=$this[id]'>$this[fromalias]</a></font></td>
              <td width=35%><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>$this[7]</font></td>
            </tr>
        ";
    }
    
    $site[text] .= "
        </table><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>
        <br><input type=submit value='Delete Message(s)'></form>
        </font></center>
    ";
}

function mssgnew($user){
    global $site, $prefs, $re, $vars;
    
    $site[title] = "Profiler Message Center";
    
    $site[text] = "
        <center><form method=post action='profiler.php?action=mssgnew2'>
        <table border=0 cellpadding=1 cellspacing=1 width=100%>
          <tr>
            <td width=100% colspan=4 bgcolor='$prefs[bgcolor1]'><font face='$prefs[fontface]' size='$prefs[fontsize2]' color='$prefs[fontcolor2]'>Message Center - New Message <a href='' onClick=\"window.open('$vars[url_php]/docs/newmessage.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\"><img src='$vars[url_php]/help.gif' border=0></a></font></td>
          </tr>
          <tr>
            <td width=100% colspan=2 bgcolor='$prefs[bgcolor1]'><font face='$prefs[fontface]' size='$prefs[fontsize2]' color='$prefs[fontcolor2]'>Send New Message</font></td>
          </tr>
          <tr>
            <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>To</font></td>
            <td width=75%><select name=inp[to] class=form>
    ";
    if($re && $re != 0){
        $query = mysql_query("select * from profiler_mssg where id = '$re'");
        $reply = mysql_fetch_array($query);
        $reply[subject] = "RE: $reply[subject]";
    }
    
    $query = mysql_query("select alias,id from profiler_users");
    while($this = mysql_fetch_array($query)){
        if($re && $re != 0 && $reply[fromid] == $this[id]){
            $site[text] .= "<option value=$this[id] selected>$this[alias]";
        }
        else{
            $site[text] .= "<option value=$this[id]>$this[alias]";
        }
    }
    $site[text] .= "
            <option value=all>All Members</select></td>
          </tr>
          <tr>
            <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Subject</font></td>
            <td width=75%><input type=text name=inp[subject] class=form size=30 value='$reply[subject]'></td>
          </tr>
          <tr>
            <td width=25% align=right valign=top><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Message</font></td>
            <td width=75%><textarea class=form name=inp[message] cols=60 rows=10></textarea></td>
          </tr>
          <tr>
            <td width=25%>&nbsp;</td>
            <td width=75%><input type=submit value='Send Message'></td>
          </tr>
        </table></form></center>
    ";
}

function mssgnew2($user){
    global $site, $prefs, $inp, $vars;
    
    $site[title] = "Profiler Message Center";
    
    if($inp[subject] == "" || $inp[message] == ""){
        $site[text] = "
            <center><form method=post action='profiler.php?action=mssgnew2'>
            <font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>You did not fill out all the required form fields.<br>
            <table border=0 cellpadding=1 cellspacing=1 width=100%>
              <tr>
                <td width=100% colspan=2 bgcolor='$prefs[bgcolor1]'><font face='$prefs[fontface]' size='$prefs[fontsize2]' color='$prefs[fontcolor2]'>Send New Message <a href='' onClick=\"window.open('$vars[url_php]/docs/newmessage.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\"><img src='$vars[url_php]/help.gif' border=0></a></font></td>
              </tr>
              <tr>
                <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>To</font></td>
                <td width=75%><select name=inp[to] class=form>
        ";
        $query = mysql_query("select alias,id from profiler_users");
        while($this = mysql_fetch_array($query)){
            if($inp[to] == $this[id]){
                $site[text] .= "<option value=$this[id] selected>$this[alias]";
            }
            else{
                $site[text] .= "<option value=$this[id]>$this[alias]";
            }
        }
        $site[text] .= "
                <option value=all>All Members</select></td>
              </tr>
              <tr>
                <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Subject</font></td>
                <td width=75%><input type=text name=inp[subject] class=form size=30 value='$inp[subject]'></td>
              </tr>
              <tr>
                <td width=25% align=right valign=top><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Message</font></td>
                <td width=75%><textarea class=form name=inp[message] cols=60 rows=10>$inp[message]</textarea></td>
              </tr>
              <tr>
                <td width=25%>&nbsp;</td>
                <td width=75%><input type=submit value='Send Message'></td>
              </tr>
            </table></form></center>
        ";
    }
    else{
        if($inp[to] != "all"){
            mysql_query("INSERT INTO `profiler_mssg` (`toid`,`fromid`,`fromalias`,`subject`,`message`) VALUES ('$inp[to]', '$user[id]', '$user[alias]', '$inp[subject]', '$inp[message]')");
        }
        elseif($inp[to] == "all"){
            $query = mysql_query("select id from profiler_users");
            while($this = mysql_fetch_array($query)){
                mysql_query("INSERT INTO `profiler_mssg` (`toid`,`fromid`,`fromalias`,`subject`,`message`) VALUES ('$this[id]', '$user[id]', '$user[alias]', '$inp[subject]', '$inp[message]')");
            }
        }
        $site[text] = "<font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Your message has been sent.</font>";
    }
}

function mssgview($user){
    global $site, $mssg, $prefs, $vars;
    
    $site[title] = "Profiler Message Center";
    
    $query = mysql_query("select *,date_format(stamp, '%W %M %d-%H:%i') from profiler_mssg where id = '$mssg'");
    $this = mysql_fetch_array($query);
    
    $site[text] = "
        <center>
        <table border=0 cellpadding=1 cellspacing=1 width=80%>
          <tr>
            <td width=100% colspan=2 bgcolor='$prefs[bgcolor1]'><font face='$prefs[fontface]' size='$prefs[fontsize2]' color='$prefs[fontcolor2]'>Read Message <a href='' onClick=\"window.open('$vars[url_php]/docs/readmessage.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\"><img src='$vars[url_php]/help.gif' border=0></a></font></td>
          </tr>
          </tr>
          <tr>
            <td width=25% align=right bgcolor='$prefs[bgcolor2]'><font face='$prefs[fontface]' size='$prefs[fontsize2]'color='$prefs[fontcolor2]'>From</font></td>
            <td width=75%><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>$this[fromalias]</font></td>
          </tr>
          <tr>
            <td width=25% align=right bgcolor='$prefs[bgcolor2]'><font face='$prefs[fontface]' size='$prefs[fontsize2]'color='$prefs[fontcolor2]'>Subject</font></td>
            <td width=75%><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>$this[subject] - <a href='profiler.php?action=mssgnew&re=$this[id]'>Reply</a></font></td>
          </tr>
          <tr>
            <td width=25% align=right bgcolor='$prefs[bgcolor2]'><font face='$prefs[fontface]' size='$prefs[fontsize2]'color='$prefs[fontcolor2]'>Date</font></td>
            <td width=75%><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>$this[7]</font></td>
          </tr>
          <tr>
            <td width=25% align=right valign=top bgcolor='$prefs[bgcolor2]'><font face='$prefs[fontface]' size='$prefs[fontsize2]'color='$prefs[fontcolor2]'>Message</font></td>
            <td width=75%><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>$this[message]</font></td>
          </tr>
       </table></center>
    ";
}

// Delete Messages
function mssgdele($user){
    global $site, $mssg, $prefs;
    
    $site[title] = "Profiler Message Center";
    
    $mssg[] = 1; $mssg[] = 1;
    while(list($key,$val) = each($mssg)){
        if($mssg[$key] == 2){
        mysql_query("delete from profiler_mssg where id = '$key'");
        }
    }
    
    $site[text] = "Messages Deleted";
}

// Display Member List
function memberlist($user){
    global $site, $prefs, $vars;
    
    $site[title] = "Profiler Member List";
    
    $site[text] = "
        <center>
        <table border=0 cellpadding=1 cellspacing=1 width=80%>
          <tr>
            <td width=5% bgcolor='$prefs[bgcolor1]'><font face='$prefs[fontface]' size='$prefs[fontsize2]' color='$prefs[fontcolor2]'><b>ID</b></td>
            <td width=20% bgcolor='$prefs[bgcolor1]'><font face='$prefs[fontface]' size='$prefs[fontsize2]' color='$prefs[fontcolor2]'><b>Alias</b></td>
            <td width=20% bgcolor='$prefs[bgcolor1]'><font face='$prefs[fontface]' size='$prefs[fontsize2]' color='$prefs[fontcolor2]'><b>Rank</b></td>
            <td width=20% bgcolor='$prefs[bgcolor1]'><font face='$prefs[fontface]' size='$prefs[fontsize2]' color='$prefs[fontcolor2]'><b>Faction</b></td>
            <td width=10% bgcolor='$prefs[bgcolor1]'><font face='$prefs[fontface]' size='$prefs[fontsize2]' color='$prefs[fontcolor2]'><b>Edit</b></td>
            <td width=25% bgcolor='$prefs[bgcolor1]'><font face='$prefs[fontface]' size='$prefs[fontsize2]' color='$prefs[fontcolor2]'><b>Last Login</b></td>
          </tr>
    ";
    
    $query = mysql_query("select *,date_format(login, '%W %M %d') as dlogin from profiler_users");
    while($this = mysql_fetch_array($query)){
        $site[text] .= "
            <tr>
              <td width=5% valign=top><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>$this[id]</font></td>
              <td width=20% valign=top><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>$this[alias]</font></td>
              <td width=20% valign=top><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>$this[rank]</font></td>
              <td width=20% valign=top><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>$this[faction]</font></td>
              <td width=10% valign=top><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'><a href='profiler.php?action=edituser2&eid=$user[id]'>Edit</a></font></td>
              <td width=25% valign=top><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>$this[dlogin]</font></td>
            </tr>
        ";
    }
    $site[text] .= "</table></center>";
}

// Clan calandar
function calandar($user){
    global $site, $vars, $prefs, $ttime, $this_month, $in_month, $in_year, $list_month,$list_year,$this_year,$option_day,$option_month,$option_year,$list_n,$dcount,$dday,$dweekday;
    
    $site[title] = "Profiler Event Center";
    
    if($in_month != date("m") && $in_month != "" || $in_year != date("Y") && $in_year != ""){
        $ttime = mktime(0,0,0,$in_month,1,$in_year);
    }
    else{$ttime = time();}
    
    $view_cal = date("M Y", $ttime);

    get_cal_vars();
    
    // Calandar script adapted from previous RT Script
    $site[text] = "
        <center>
        <font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Now viewing $view_cal <a href='' onClick=\"window.open('$vars[url_php]/docs/calandar.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\"><img src='$vars[url_php]/help.gif' border=0></a></font><br>
        <table width=100% cellpadding=1 cellspacing=1 border=1 bordercolor='$prefs[bgcolor2]'>
			<tr><td width=14%><font face='$prefs[fontface]' size='$prefs[fontsize2]' color='$prefs[fontcolor1]'>Sunday</td>
			<td width 14%><font face='$prefs[fontface]' size='$prefs[fontsize2]' color='$prefs[fontcolor1]'>Monday</td>
			<td width=14%><font face='$prefs[fontface]' size='$prefs[fontsize2]' color='$prefs[fontcolor1]'>Tuesday</td>
			<td width=14%><font face='$prefs[fontface]' size='$prefs[fontsize2]' color='$prefs[fontcolor1]'>Wednesday</td>
			<td width=14%><font face='$prefs[fontface]' size='$prefs[fontsize2]' color='$prefs[fontcolor1]'>Thursday</td>
			<td width=14%><font face='$prefs[fontface]' size='$prefs[fontsize2]' color='$prefs[fontcolor1]'>Friday</td>
			<td width=14%><font face='$prefs[fontface]' size='$prefs[fontsize2]' color='$prefs[fontcolor1]'>Saturday</td></tr>

    ";
    
	$weekday = $dweekday;
    $weekdays = array();
	for($a = $dday; $a > 1; $a--){
		$weekday = $weekday-1;
		if($weekday == -1){$weekday = 6;}
		$weekdays[$a] = $list_n[$weekday];
	}
	$weekdays = array_reverse($weekdays);
	$dday = $dday - 1;
	$weekdays[$dday] = $list_n[$dweekday];

	$weekday = $dweekday;
	for($b = $dday+1; $b < $dcount; $b++){
		$weekday = $weekday+1;
		if($weekday == 7){$weekday = 0;}
		$weekdays[$b] = $list_n[$weekday];
	}

	$sunday = array();	$monday = array();	$tuesday = array();	$wednesday = array();	$thursday = array();	$friday = array();	$saturday = array();

	if($weekdays[0] == "Sunday"){$count = 0;}
	elseif($weekdays[0] == "Monday"){$count = 1; $sunday = array('');}
	elseif($weekdays[0] == "Tuesday"){$count = 2; $sunday = array(''); $monday = array('');}
	elseif($weekdays[0] == "Wednesday"){$count = 3; $sunday = array(''); $monday = array(''); $tuesday = array('');}
	elseif($weekdays[0] == "Thursday"){$count = 4; $sunday = array(' '); $monday = array(''); $tuesday = array(''); $wednesday = array('');}
	elseif($weekdays[0] == "Friday"){$count = 5; $sunday = array(''); $monday = array(''); $tuesday = array(''); $wednesday = array(''); $thursday = array('');}
	elseif($weekdays[0] == "Saturday"){$count = 6; $dflag = 1; $sunday = array(''); $monday = array(''); $tuesday = array(''); $wednesday = array(''); $thursday = array(''); $friday = array('');}

    if($dcount >= 30 && $weekdays[0] == "Friday" || $dcount == 31 && $weekdays[0] == "Saturday"){
        $wd = 2;
    }
    else{$wd = 1;}
    
    $num = 0;
	foreach($weekdays as $day){
		$count++;
		$num++;
		if($count == 7 && $dflag != 1){$count = 0; $wd++;}
		elseif($count == 7 && $dflag == 1){$count = 0; $dflag = 0;}
  
        // Add events to each day on clanadar
        $get_month = date("m", $ttime); $get_year = date("Y", $ttime);
        $query = mysql_query("select * from profiler_cal where dday = '$num' and dmonth = '$get_month' and dyear = '$get_year'");
        while($this = mysql_fetch_array($query)){
            if($day == "Sunday"){$sunday2[$wd] .= "<br>$this[subject]";}
            elseif($day == "Monday"){$monday2[$wd] .= "<br>$this[subject]";}
            elseif($day == "Tuesday"){$tuesday2[$wd] .= "<br>$this[subject]";}
            elseif($day == "Wednesday"){$wednesday2[$wd] .= "<br>$this[subject]";}
            elseif($day == "Thursday"){$thursday2[$wd] .= "<br>$this[subject]";}
            elseif($day == "Friday"){$friday2[$wd] .= "<br>$this[subject]";}
            elseif($day == "Saturday"){$saturday2[$wd] .= "<br>$this[subject]";}
        }

		if($day == "Sunday"){array_push($sunday, $num);}
		if($day == "Monday"){array_push($monday, $num);}
		if($day == "Tuesday"){array_push($tuesday, $num);}
		if($day == "Wednesday"){array_push($wednesday, $num);}
		if($day == "Thursday"){array_push($thursday, $num);}
		if($day == "Friday"){array_push($friday, $num);}
		if($day == "Saturday"){array_push($saturday, $num);}
	}
 
    if(!$in_year){$in_year = $this_year;}
    if(!$in_month){$in_month = $this_month;}

	for($a = 0; $a < $wd; $a++){
        $b = $a + 1;
		$site[text] .= "
			<tr><td width=14% height=70 valign=top><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor2]'><a href='profiler.php?action=calday&in_day=$sunday[$a]&in_month=$in_month&in_year=$in_year'>$sunday[$a]</a>$sunday2[$b]</td>
			<td width=14% valign=top><font face='$prefs[fontface]' size='$prefs[fontsize2]' color='$prefs[fontcolor1]'><a href='profiler.php?action=calday&in_day=$monday[$a]&in_month=$in_month&in_year=$in_year'>$monday[$a]</a>$monday2[$b]</td>
			<td width=14% valign=top><font face='$prefs[fontface]' size='$prefs[fontsize2]' color='$prefs[fontcolor1]'><a href='profiler.php?action=calday&in_day=$tuesday[$a]&in_month=$in_month&in_year=$in_year'>$tuesday[$a]</a>$tuesday2[$b]</td>
			<td width=14% valign=top><font face='$prefs[fontface]' size='$prefs[fontsize2]' color='$prefs[fontcolor1]'><a href='profiler.php?action=calday&in_day=$wednesday[$a]&in_month=$in_month&in_year=$in_year'>$wednesday[$a]</a>$wednesday2[$b]</td>
			<td width=14% valign=top><font face='$prefs[fontface]' size='$prefs[fontsize2]' color='$prefs[fontcolor1]'><a href='profiler.php?action=calday&in_day=$thursday[$a]&in_month=$in_month&in_year=$in_year'>$thursday[$a]</a>$thursday2[$b]</td>
			<td width=14% valign=top><font face='$prefs[fontface]' size='$prefs[fontsize2]' color='$prefs[fontcolor1]'><a href='profiler.php?action=calday&in_day=$friday[$a]&in_month=$in_month&in_year=$in_year'>$friday[$a]</a>$friday2[$b]</td>
			<td width=14% valign=top><font face='$prefs[fontface]' size='$prefs[fontsize2]' color='$prefs[fontcolor1]'><a href='profiler.php?action=calday&in_day=$saturday[$a]&in_month=$in_month&in_year=$in_year'>$saturday[$a]</a>$saturday2[$b]</td></tr>
		";
	}
 
    $site[text] .= "</table><br><form method=post action='profiler.php?action=cal'><select name=in_month class=form>$option_month</select> <select name=in_year class=form>$option_year</select> <input type=submit value='View Month'></form></center>";
}

function calday($user){
    global $site, $vars, $prefs, $in_day, $in_month, $in_year;
    
    $site[title] = "Profiler Event Center";
    
    $day_view = date("M d Y");
    
    $site[text] = "
        <center><table border=0 cellpadding=1 cellspacing=1 width=80%>
          <tr>
            <td width=100% bgcolor='$prefs[bgcolor1]' align=center colspan=2><font face='$prefs[fontface]' size='$prefs[fontsize2]'color='$prefs[fontcolor2]'>Now Viewing Entries on $day_view <a href='' onClick=\"window.open('$vars[url_php]/docs/calandarday.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\"><img src='$vars[url_php]/help.gif' border=0></a></font></td>
          </tr>
    ";
    
    $query = mysql_query("SELECT * FROM `profiler_cal` WHERE `dday` = '$in_day' AND `dmonth` = '$in_month' AND `dyear` = '$in_year'") or mysql_error();
    while($this = mysql_fetch_array($query)){
        $site[text] .= "
            <tr>
              <td width=20%><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>$this[subject]</font></td>
              <td width=80%><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>$this[event]</font></td>
            </tr>
        ";
    }

    $site[text] .= "
        </table><br>
    ";

    if($user[status] == 2 || $user[status] == 3){
        $site[text] .= "
        <form method=post action='profiler.php?action=calnew'><input type=hidden name=in[day] value=$in_day><input type=hidden name=in[month] value=$in_month><input type=hidden name=in[year] value=$in_year>
        <table border=0 cellpadding=1 cellspacing=1 width=80%>
          <tr>
            <td bgcolor='$prefs[bgcolor1]' align=center colspan=2><font face='$prefs[fontface]' size='$prefs[fontsize2]'color='$prefs[fontcolor2]'>New Entry on $day_view <a href='' onClick=\"window.open('$vars[url_php]/docs/calandarnew.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\"><img src='$vars[url_php]/help.gif' border=0></a></font></td>
          </tr>
          <tr>
            <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Subject</font></td>
            <td width=75%><input type=text name=in[subject] size=15 maxlength=15 class=form></td>
          </tr>
          <tr>
            <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Message</font></td>
            <td width=75%><textarea name=in[event] class=form cols=50 rows=6></textarea></td>
          </tr>
          <tr>
            <td width=25%>&nbsp;</td>
            <td width=75%><input type=submit value='Create Event'></td>
          </tr>
        </table></form>
        </center>
        ";
    }
}

function calnew($user){
    global $site, $prefs, $in;
    
    $site[title] = "Profiler Event Center";
    
    if($user[status] == 1){
        $site[text] = "<font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>You are not authorized to add events</font>";
    }
    
    elseif($user[status] == 2 || $user[status] == 3){
    $in[event] = strip_tags($in[event]);
    
    mysql_query("insert into profiler_cal (dday,dmonth,dyear,event,subject) values ('$in[day]','$in[month]','$in[year]','$in[event]','$in[subject]')");
    
    $site[text] = " <font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Your event has been added.</font>";
}}

//////////////////////////
// Admin only functions //
//////////////////////////

function settings($user){
    global $site, $prefs, $vars;
    
    $site[title] = "Profiler Admin - Settings";
    
    if($user[status] == 1 || $user[status] == 2){
        $site[text] = "
            You are not authorized to change settings
        ";
    }
    elseif($user[status] == 3){
        $site[text] = "
            <center><form method=post action='profiler.php?action=set2'>
            <table border=0 cellpadding=1 cellspacing=1 width=100%>
              <tr>
                <td width=100% colspan=4 bgcolor='$prefs[bgcolor1]' align=center><font face='$prefs[fontface]' size='$prefs[fontsize2]'color='$prefs[fontcolor2]'><b>Settings and Preferences</b> <a href='' onClick=\"window.open('$vars[url_php]/docs/settings.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\"><img src='$vars[url_php]/help.gif' border=0></a></font></td>
              </tr>
              <tr>
                <td width=100% colspan=4><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>These are the server paths and urls to parts of this script. If you don't know what the paths are you should check with your web host before you try to change them. <a href='' onClick=\"window.open('$vars[url_php]/docs/settings.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\"><img src='$vars[url_php]/help.gif' border=0></a></font></td>
              </tr>
              <tr>
                <td width=25% align=right valign=bottom><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>PHP Directory</font></td>
                <td width=75% colspan=3><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'><i>The server path to the directory containing this script.</i><br><input type=text name=invars[dir_php] size=60 class=form value='$vars[dir_php]'></td>
              </tr>
              <tr>
                <td width=25% align=right valign=bottom><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>PHP URL</font></td>
                <td width=75% colspan=3><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'><i>The URL to the directory containing this script.</i><br><input type=text name=invars[url_php] size=60 class=form value='$vars[url_php]'></td>
              </tr>
              <tr>
                <td width=100% colspan=4><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'><br><br>These are preferences you can use to change the way members of your clan are displayed. <a href='' onClick=\"window.open('$vars[url_php]/docs/settings.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\"><img src='$vars[url_php]/help.gif' border=0></a></font></td>
              </tr>
              <tr>
                <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Clan Tag</font></td>
                <td width=25%><input type=text name='inprefs[clantag]' size=4 class=form value='$prefs[clantag]'></td>
                <td width=50% colspan=2>&nbsp;</td>
              </tr>
              <tr>
                <td width=25% align=right valign=bottom><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'><br>Clan Ranks</font></td>
                <td width=75% colspan=3><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'><br><i>The ranks which your clan uses. (More fields will appear once you fill the current ones)</i></font></td>
              </tr>
        ";
        $ranks = explode("`",$prefs[ranks]);
        $count = count($ranks) + 2;
        for($a = 0; $a < $count; $a++, $a++){
            $b = $a + 1;
            $c = $b + 1;
            $site[text] .= "
               <tr>
                 <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Rank $b</font></td>
                 <td width=25%><input type=text name='inprefs[ranks][$a]' size=18 class=form value='$ranks[$a]'></td>
                 <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Rank $c</font></td>
                 <td width=25%><input type=text name='inprefs[ranks][$b]' size=18 class=form value='$ranks[$b]'></td>
               </tr>
            ";
        }

        $site[text] .= "
              <tr>
                <td width=25% align=right valign=bottom><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'><br>Clan Factions</font></td>
                <td width=75% colspan=3><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'><br><i>The factions which your clan uses. (More fields will appear once you fill the current ones)</i> <a href='' onClick=\"window.open('$vars[url_php]/docs/settings.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\"><img src='$vars[url_php]/help.gif' border=0></a></font></td>
              </tr>
        ";

        $factions = explode("`",$prefs[factions]);
        $count = count($factions) + 2;
        for($a = 0; $a < $count; $a++, $a++){
            $b = $a + 1;
            $c = $b + 1;
            $site[text] .= "
               <tr>
                 <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Faction $b</font></td>
                 <td width=25%><input type=text name='inprefs[factions][$a]' size=18 class=form value='$factions[$a]'></td>
                 <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Faction $c</font></td>
                 <td width=25%><input type=text name='inprefs[factions][$b]' size=18 class=form value='$factions[$b]'></td>
               </tr>
            ";
        }
        
        $site[text] .= "
              <tr>
                <td width=25% align=right valign=bottom><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'><br>Custom Stats</font></td>
                <td width=75% colspan=3><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'><br><i>The statistics which your clan uses. (More fields will appear once you fill the current ones)</i> <a href='' onClick=\"window.open('$vars[url_php]/docs/settings.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\"><img src='$vars[url_php]/help.gif' border=0></a></font></td>
              </tr>
        ";
        
        $required = explode("`",$prefs[required]);
        $stats = explode("`",$prefs[user_info]);
        $count = count($stats) + 2;
        for($a = 0; $a < $count; $a++, $a++){
            $b = $a + 1;
            $c = $b + 1;
            if($required[$a] == 1){$checked1 = "checked";} else{$checked1 = "";}
            if($required[$b] == 1){$checked2 = "checked";} else{$checked2 = "";}
            $site[text] .= "
               <tr>
                 <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Stat $b</font></td>
                 <td width=25%><input type=text name='inprefs[stats][$a]' size=18 class=form value='$stats[$a]'>&nbsp;<input type=checkbox name='inprefs[required][$a]' class=form value=on $checked1></td>
                 <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Stat $c</font></td>
                 <td width=25%><input type=text name='inprefs[stats][$b]' size=18 class=form value='$stats[$b]'>&nbsp;<input type=checkbox name='inprefs[required][$b]' class=form value=on $checked2></td>
               </tr>
            ";
        }
              
        $site[text] .= "
              <tr>
                <td width=100% colspan=4><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'><br><br>These are display settings the script will use. You may need to play around with these quite a bit. <a href='' onClick=\"window.open('$vars[url_php]/docs/settings.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\"><img src='$vars[url_php]/help.gif' border=0></a></font></td>
              </tr>
              <tr>
                <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Background Color 1</font></td>
                <td width=25%><input type=text name=inprefs[bgcolor1] class=form size=6 value='$prefs[bgcolor1]'></td>
                <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Background Color 2</font></td>
                <td width=25%><input type=text name=inprefs[bgcolor2] class=form size=6 value='$prefs[bgcolor2]'></td>
             </tr>
             <tr>
                <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Font Face</font></td>
                <td width=25%><input type=text name=inprefs[fontface] class=form size=18 value='$prefs[fontface]'></td>
                <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>&nbsp;</font></td>
                <td width=25%>&nbsp;</td>
             </tr>
             <tr>
                <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Font Size 1</font></td>
                <td width=25%><input type=text name=inprefs[fontsize1] class=form size=1 value='$prefs[fontsize1]'></td>
                <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Font Size 2</font></td>
                <td width=25%><input type=text name=inprefs[fontsize2] class=form size=1 value='$prefs[fontsize2]'></td>
             </tr>
             <tr>
                <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Font Color 1</font></td>
                <td width=25%><input type=text name=inprefs[fontcolor1] class=form size=6 value='$prefs[fontcolor1]'></td>
                <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Font Color 2</font></td>
                <td width=25%><input type=text name=inprefs[fontcolor2] class=form size=6 value='$prefs[fontcolor2]'></td>
             </tr>
             <tr>
               <td width=100% colspan=4><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'><br>These are the Code of Conduct (clan rules) settings. <a href='' onClick=\"window.open('$vars[url_php]/docs/settings.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\"><img src='$vars[url_php]/help.gif' border=0></a></font></td>
             </tr>
             <tr>
                <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>User Code of Conduct</font></td>
                <td width=25%><select name='inprefs[code]' class=form>
        ";
        if($prefs[code] == 1){
            $site[text] .= "<option value=1>Yes<option value=0>No";
        }
        elseif($prefs[code] == 0){
            $site[text] .= "<option value=0>No<option value=1>Yes";
        }
        
        $site[text] .= "
                </select></td>
                <td width=50% colspan=2>&nbsp;</td>
              </tr>
              <tr>
                <td width=25% valign=top align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Code of Conduct</font></td>
                <td width=75% colspan=3><textarea name='inprefs[code_text]' class=form cols=60 rows=8>$prefs[code_text]</textarea></td>
              </tr>
             <tr>
               <td width=100% colspan=4><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'><br>These are the settings for pictures/portraits of members. <a href='' onClick=\"window.open('$vars[url_php]/docs/settings.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\"><img src='$vars[url_php]/help.gif' border=0></a></font></td>
             </tr>
             <tr>
               <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Use Image</font></td>
               <td width=25%><select name='inprefs[info_image]' class=form>
        ";
        if($prefs[info_image] == 0){
            $site[text] .= "<option value=0>No<option value=1>Yes";
        }
        elseif($prefs[info_image] == 1){
            $site[text] .= "<option value=1>Yes<option value=0>No";
        }
        $site[text] .= "
               </select></td>
               <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Image Directory Name</font></td>
               <td width=25%><input type=text name='inprefs[info_image_dir]' size=18 class=form value='$prefs[info_image_dir]'></td>
             </tr>
             <tr>
               <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Image Height</font></td>
               <td wdith=25%><input type=text name='inprefs[info_image_height]' size=1 class=form value='$prefs[info_image_height]'></td>
               <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Image Width</font></td>
               <td wdith=25%><input type=text name='inprefs[info_image_width]' size=1 class=form value='$prefs[info_image_width]'></td>
             </tr>
             <tr>
               <td width=100% colspan=4><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'><br>These are news display settings. <a href='' onClick=\"window.open('$vars[url_php]/docs/settings.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\"><img src='$vars[url_php]/help.gif' border=0></a></font></td>
             </tr>
             <tr>
               <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>News Date Format</font></td>
               <td wdith=25%><input type=text name='inprefs[news_date]' size=6 class=form value='$prefs[news_date]'></td>
               <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>News Display Amount</font></td>
               <td wdith=25%><input type=text name='inprefs[news_show]' size=1 class=form value='$prefs[news_show]'></td>
             </tr>
             <tr>
               <td width=25% valign=top align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>News Format</font></td>
               <td width=75% colspan=3><textarea name='inprefs[news_layout]' class=form cols=60 rows=4>$prefs[news_layout]</textarea></td>
             </tr>
             <tr>
               <td width=25% valign=top align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>News Headline Format</font></td>
               <td width=75% colspan=3><textarea name='inprefs[headline_layout]' class=form cols=60 rows=4>$prefs[headline_layout]</textarea></td>
             </tr>
             <tr>
               <td width=25% valign=top align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>News Comment Format</font></td>
               <td width=75% colspan=3><textarea name='inprefs[comment_layout]' class=form cols=60 rows=4>$prefs[comment_layout]</textarea></td>
             </tr>
             <tr>
               <td width=100% colspan=4><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'><br>These are ranks display settings. <a href='' onClick=\"window.open('$vars[url_php]/docs/settings.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\"><img src='$vars[url_php]/help.gif' border=0></a></font></td>
             </tr>
             <tr>
               <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Ranks Navigation Pannel</font></td>
               <td width=25%><select name='inprefs[rank_option]' class=form>
        ";
        if($prefs[rank_option] == 0){
            $site[text] .= "<option value=0>No<option value=1>Yes";
        }
        elseif($prefs[rank_option] == 1){
            $site[text] .= "<option value=1>Yes<option value=0>No";
        }
        $site[text] .= "
               </select></td>
               <td width=50% colspan=2>&nbsp;</td>
             </tr>
             <tr>
               <td width=25% valign=top align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Ranks Format</font></td>
               <td width=75% colspan=3><textarea name='inprefs[rank_layout]' class=form cols=60 rows=8>$prefs[rank_layout]</textarea></td>
             </tr>
             <tr>
               <td width=25% valign=top align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Profiles Format</font></td>
               <td width=75% colspan=3><textarea name='inprefs[profile_layout]' class=form cols=60 rows=8>$prefs[profile_layout]</textarea></td>
             </tr>
             <tr>
               <td width=100% colspan=4><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'><br>This is the main site template data. <a href='' onClick=\"window.open('$vars[url_php]/docs/settings.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\"><img src='$vars[url_php]/help.gif' border=0></a></font></td>
             </tr>
             <tr>
               <td width=25% valign=top align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Main Page Format</font></td>
               <td width=75% colspan=3><textarea name='inprefs[main_layout]' class=form cols=60 rows=8>$prefs[main_layout]</textarea></td>
             </tr>
             <tr>
               <td width=50% colspan=2 align=right><input type=submit value='Update Settings'></td>
               <td width=50% colspan=2 align=left><input type=reset value='Reset'></td>
             </tr>
           </table></form></center>
        ";
    }
}

function settings2($user){
    global $site, $inprefs, $invars, $prefs, $vars, $sql;
    
    $site[title] = "Profiler Admin - Settings";
    
    if(!is_dir($invars[dir_php])){
        $site[text] = "Your PHP directory is invalid.";
    }
    // Settings updates are divided for future dev purposes
    else{
        // Vars
        mysql_query("update profiler_data set value = '$invars[dir_php]' where name = 'dir_php'");
        mysql_query("update profiler_data set value = '$invars[url_php]' where name = 'url_php'");

        // Ranks, Factions & Stats
        $out_ranks = $inprefs[ranks];
        $out_factions = $inprefs[factions];
        $out_stats = $inprefs[stats];
        $out_ranks_name = array();
        $out_factions_name = array();
        $out_stats_name = array();
        for($a = count($out_ranks) - 1; $a >= 0; $a--){
            if($out_ranks[$a] == ''){array_pop($out_ranks);}
            else{$out_ranks_name[] = strtolower(ereg_replace("[ ]", "",trim($out_ranks[$a])));}
        }
        for($a = count($out_factions) - 1; $a >= 0; $a--){
            if($out_factions[$a] == ''){array_pop($out_factions);}
            else{$out_factions_name[] = strtolower(ereg_replace("[ ]", "",trim($out_factions[$a])));}
        }
        for($a = count($out_stats) - 1; $a >= 0; $a--){
            if($out_stats[$a] == ''){array_pop($out_stats);}
            else{$out_stats_name[] = strtolower(ereg_replace("[ ]", "",trim($out_stats[$a])));}
        }
        for($a = 0; $a < count($inprefs[required]); $a++){
            if($inprefs[required][$a] == 'on'){$inprefs[required][$a] = 1;}
            else{$inprefs[required][$a] = 0;}
            
            $out_required .= $inprefs[required][$a]."`";
        }
        $out_ranks_name = array_reverse($out_ranks_name);
        $out_factions_name = array_reverse($out_factions_name);
        $out_stats_name = array_reverse($out_stats_name);
        $out_ranks = implode("`",$out_ranks);
        $out_factions = implode("`",$out_factions);
        $out_stats = implode("`",$out_stats);
        $out_ranks_name = implode("`",$out_ranks_name);
        $out_factions_name = implode("`",$out_factions_name);
        $out_stats_name = implode("`",$out_stats_name);

        mysql_query("update profiler_data set `value` = '$out_ranks' where name = 'ranks'");
        mysql_query("update profiler_data set `value` = '$out_factions' where name = 'factions'");
        mysql_query("update profiler_data set `value` = '$out_stats' where name = 'user_info'");
        mysql_query("update profiler_data set `value` = '$out_required' where name = 'required'");
        mysql_query("update profiler_data set `value` = '$out_ranks_name' where name = 'ranks_name'");
        mysql_query("update profiler_data set `value` = '$out_factions_name' where name = 'factions_name'");
        mysql_query("update profiler_data set `value` = '$out_stats_name' where name = 'user_info_name'");

        // Display settings
        mysql_query("update profiler_data set value = '$inprefs[bgcolor1]' where name = 'bgcolor1'");
        mysql_query("update profiler_data set value = '$inprefs[bgcolor2]' where name = 'bgcolor2'");
        mysql_query("update profiler_data set value = '$inprefs[fontcolor1]' where name = 'fontcolor1'");
        mysql_query("update profiler_data set value = '$inprefs[fontcolor2]' where name = 'fontcolor2'");
        mysql_query("update profiler_data set value = '$inprefs[fontface]' where name = 'fontface'");
        mysql_query("update profiler_data set value = '$inprefs[fontsize1]' where name = 'fontsize1'");
        mysql_query("update profiler_data set value = '$inprefs[fontsize2]' where name = 'fontsize2'");

        // News settings
        mysql_query("update profiler_data set value = '$inprefs[news_date]' where name = 'news_date'");
        mysql_query("update profiler_data set value = '$inprefs[news_show]' where name = 'new_show'");

        // Code of conduct
        mysql_query("update profiler_data set value = '$inprefs[code]' where name = 'code'");
        mysql_query("update profiler_data set value = '$inprefs[code_text]' where name = 'code_text'");

        // Image settings
        mysql_query("update profiler_data set value = '$inprefs[info_image]' where name = 'info_image'");
        mysql_query("update profiler_data set value = '$inprefs[info_image_dir]' where name = 'info_image_dir'");
        mysql_query("update profiler_data set value = '$inprefs[info_image_height]' where name = 'info_image_height'");
        mysql_query("update profiler_data set value = '$inprefs[info_image_width]' where name = 'info_image_width'");
        chdir("$vars[dir_php]");
        if(is_dir($prefs[info_image_dir])){
            rename($prefs[info_image_dir], $inprefs[info_image_dir]);
        }
        else{
            mkdir($inprefs[info_image_dir]);
        }

        // Rank options
        mysql_query("update profiler_data set value = '$inprefs[clantag]' where name = 'clantag'");
        mysql_query("update profiler_data set value = '$inprefs[rank_option]' where name = 'rank_option'");

        // Textboxes (layouts)
        $inprefs[news_layout] = ereg_replace("[\r\n]", "",trim($inprefs[news_layout]));
        $inprefs[headline_layout] = ereg_replace("[\r\n]", "",trim($inprefs[headline_layout]));
        $inprefs[comment_layout] = ereg_replace("[\r\n]", "",trim($inprefs[comment_layout]));
        $inprefs[rank_layout] = ereg_replace("[\r\n]", "",trim($inprefs[rank_layout]));
        $inprefs[profile_layout] = ereg_replace("[\r\n]", "",trim($inprefs[profile_layout]));
        $inprefs[main_layout] = ereg_replace("[\r\n]", "",trim($inprefs[main_layout]));
        mysql_query("update profiler_data set value = '$inprefs[main_layout]' where name = 'main_layout'");
        mysql_query("update profiler_data set value = '$inprefs[headline_layout]' where name = 'headline_layout'");
        mysql_query("update profiler_data set value = '$inprefs[news_layout]' where name = 'news_layout'");
        mysql_query("update profiler_data set value = '$inprefs[comment_layout]' where name = 'comment_layout'");
        mysql_query("update profiler_data set value = '$inprefs[rank_layout]' where name = 'rank_layout'");
        mysql_query("update profiler_data set value = '$inprefs[profile_layout]' where name = 'profile_layout'");

        // Update other tables to reflect settings.
        $prefs[stats] = explode("`",$prefs[stats]);
        for($b = 0; $b < count($inprefs[stats]); $b++){
            if($prefs[stats][$b] == $inprefs[stats][$b]){}
            else{
                $this = ereg_replace("[ ]", "",strtolower($inprefs[stats][$b]));
                mysql_query("ALTER TABLE `$sql[db]`.`profiler_users` ADD `$this` VARCHAR(200) NOT NULL");
                mysql_query("ALTER TABLE `$sql[db]`.`profiler_apps` ADD `$this` VARCHAR(200) NOT NULL");
                if($prefs[stats][$b] && $prefs[stats][$b] != ''){
                    $del = $prefs[stats][$b];
                    mysql_query("ALTER TABLE `$sql[db]`.`profiler_users` DROP `$del`");
                    mysql_query("ALTER TABLE `$sql[db]`.`profiler_apps` DROP `$del`");
                }
            }
        }
        
        $site[text] = "Your settings have been updated.";
    }
}

///////////////////////////////////////
// Outside member required functions //
///////////////////////////////////////

function viewnews(){
    global $site, $prefs, $inp_start, $inp_show, $action;
    
    if(!$inp_start){$inp_start = 0;}
    if(!$inp_show){$inp_show = $prefs[news_show];}

    $news_stuff = array('id','subject','date','news','author','email','comments');
    
    $query = mysql_query("select * from profiler_news order by id desc limit $inp_start, $inp_show");
    while($this = mysql_fetch_array($query)){
        $this[comments] = "<a href='profiler.php?action=comment&inp_id=$this[id]'>Comments ($this[comment])</a>";
        $post = $prefs[news_layout];
        foreach($news_stuff as $stuff){
            $post = str_replace("<$stuff>",$this[$stuff],$post);
        }
        $site[viewnews] .= $post;
    }
    $next = $inp_start + $inp_show;
    if($action == 'viewnews'){
        $site[text] = $site[viewnews];
        $site[title] = "Archived News";
        
        $site[text] .= "
            <center>
            <form method=post action='profiler.php?action=viewnews'><input type=hidden name=inp_start value='$next'>
            View next <input type=inp_show class=form value='$prefs[news_show]' size=1> archived news articles<br><input type=submit value='Show Articles'></form>
            </center>
        ";
    }
    
    else{
        $site[viewnews] .= "<center><a href=profiler.php?action=viewnews&inp_start=$next>Go to the news archives</a></center>";
    }
}

function viewheadlines(){
    global $site, $prefs;

    $headline_stuff = array('id','subject','date','news','author','email');

    $query = mysql_query("select * from profiler_news order by id desc limit $prefs[news_show]");
    if(mysql_num_rows($query) != 0){
    while($this = mysql_fetch_array($query)){
        $post = $prefs[headline_layout];
        foreach($headline_stuff as $stuff){
            $post = str_replace("<$stuff>",$this[$stuff],$post);
        }
        $site[headlines] .= $post;
    }}
}

function viewevent(){
    global $site, $prefs;

    $todayd = date("d");
    $todaym = date("m");
    $todayy = date("Y");
    
    $query = mysql_query("SELECT * FROM `profiler_cal` WHERE `dday` = '$todayd' AND `dmonth` = '$todaym' AND `dyear` = '$todayy'") or mysql_error();
    if(mysql_num_rows($query) != 0){
    while($this = mysql_fetch_array($query)){
        $site[events] .= "
            <font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'><a href='profiler.php?action=calday&in_day=$todayd&in_month=$todaym&in_year=$todayy'>$this[subject]</a><br></font>
        ";
    }}
}

function comment(){
    global $site, $prefs, $inp_id;
    
    $site[title] = "Comments";
    
    $query = mysql_query("select * from profiler_news where id = '$inp_id'");
    $this = mysql_fetch_array($query);

    $news_stuff = array('id','subject','date','news','author','email');
    $comment_stuff = array('name','email','date','comments');

    $post = $prefs[news_layout];
    foreach($news_stuff as $stuff){
        $post = str_replace("<$stuff>",$this[$stuff],$post);
    }
    $site[text] .= $post;
    
    $site[text] .= "User Comments:<br>";
    
    // Get previous comments
    $query = mysql_query("select * from profiler_com where postid = '$inp_id'");
    if(mysql_num_rows($query) == 0){$site[text] .= "No Comments<br><br>";}
    while($this = mysql_fetch_array($query)){
        $post = $prefs[comment_layout];
        foreach($comment_stuff as $stuff){
            $post = str_replace("<$stuff>",$this[$stuff],$post);
        }
        $site[text] .= $post;
    }

    $site[text] .= "
        Post your own comments:<br>
        <form method=post action='profiler.php?action=comment2'><input type=hidden name='inp[id]' value='$inp_id'>
        <table border=0 cellpadding=1 cellspacing=1 width=60%>
          <tr>
            <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Name:</font></td>
            <td width=75%><input type=text name='inp[name]' class=form size=20></td>
          </tr>
          <tr>
            <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Email:</font></td>
            <td width=75%><input type=text name='inp[email]' class=form size=20></td>
          </tr>
          <tr>
            <td width=25% align=right valign=top><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Comment:</font></td>
            <td width=75%><textarea class=form name='inp[comment]' cols=60 rows=10></textarea></td>
          </tr>
          <tr>
            <td width=25%>&nbsp;</td>
            <td width=75%><input type=submit value='Post Comment'></td>
          </tr>
        </table></form>
    ";
}

function comment2(){
    global $site, $prefs, $inp;
    
    $site[title] = "Comments";

    if($inp[name] == '' || $inp[email] == '' || $inp[comment] == ''){
        $site[text] = "
            <font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>You did not complete all the form fields.</font>
            <form method=post action='profiler.php?action=comment2'><input type=hidden name='inp[id]' value='$inp[id]'>
            <table border=0 cellpadding=1 cellspacing=1 width=60%>
              <tr>
                <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Name:</font></td>
                <td width=75%><input value='$inp[name]' type=text name='inp[name]' class=form size=20></td>
              </tr>
              <tr>
                <td width=25% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Email:</font></td>
                <td width=75%><input value='$inp[email]' type=text name='inp[email]' class=form size=20></td>
              </tr>
              <tr>
                <td width=25% align=right valign=top><font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Comment:</font></td>
                <td width=75%><textarea class=form name='inp[comment]' cols=60 rows=10>$inp[comment]</textarea></td>
              </tr>
              <tr>
                <td width=25%>&nbsp;</td>
                <td width=75%><input type=submit value='Post Comment'></td>
              </tr>
            </table></form>
        ";
    }
    
    else{
        $out_date = date($prefs[news_date]);
        mysql_query("insert into profiler_com (postid,name,email,date,comments) values('$inp[id]','$inp[name]','$inp[email]','$out_date','$inp[comment]')");
        mysql_query("update profiler_news set comment = comment+1 where id = '$inp[id]'");

        $site[text] = "<font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>Your comment has been added.</font>";
    }
}

// Viewing is important right?
function viewmain(){
    global $site, $vars, $prefs, $inp_rank, $inp_faction, $inp_sort, $inp_order;
    
    $site[title] = "Clan Ranks";
    
    if(!$inp_rank || $inp_rank == "" || $inp_rank == "all"){
        $inp_rank = "all";
    }
    else{
        $exquery = " where rank = '$inp_rank' ";
    }
    if(!$inp_faction || $inp_faction == "" || $inp_faction == "all"){
        $inp_faction = "all";
    }
    else{
        if($exquery){
            $exquery .= " and faction = '$inp_faction' ";
        }
        else{
            $exquery = " where faction = '$inp_faction' ";
        }
    }
    
    if($inp_order == "" || !$inp_order){
        $inp_order == "asc";
    }
    
    if($inp_sort && $inp_sort != ""){
        $exquery .= " order by $inp_sort $inp_order ";
    }

    $rank_stuff = array('id','alias','email','rank','faction');
    $rank_stuff_name = array('id','alias','email','rank','faction');
    $prefs[user_info] = explode("`",$prefs[user_info]);
    $prefs[user_info_name] = explode("`",$prefs[user_info_name]);
    for($a = 0; $a < count($prefs[user_info]); $a++){
        $rank_stuff[] = $prefs[user_info][$a];
        $rank_stuff_name[] = $prefs[user_info_name][$a];
    }
    
    // Table header
    $post = $prefs[rank_layout];
    foreach($rank_stuff as $stuff){
        $cap = ucfirst($stuff);
        $post = str_replace("<$stuff>","<font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'><b>$cap</b></font>",$post);
    }
    $members .= $post;

    // Member info
    $query = mysql_query("select * from profiler_users $exquery");
    while($this = mysql_fetch_array($query)){
        $post = $prefs[rank_layout];
        $this[alias] = "<a href='' onClick=\"window.open('$vars[url_php]/profiler.php?action=vprofile&inp_id=$this[id]','profile','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\">$prefs[clantag]$this[alias]</a>";
        for($a = 0; $a < count($rank_stuff); $a++){
            $stuff = $rank_stuff_name[$a];
            $post = str_replace("<$rank_stuff[$a]>","<font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>$this[$stuff]</font>",$post);
        }
        $members .= $post;
    }

    $viewing = "Now viewing members in division <b>$inp_faction</b> of rank <b>$inp_rank</b>";
    
    $site[text] = "<font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>$viewing<br>$members</font>";
    
    if($prefs[rank_option] == 1){
        $ranks = explode("`",$prefs[ranks]);
        $factions = explode("`",$prefs[factions]);
        $stats = array('id','alias','email','rank','faction');

        $site[text] .= "
            <center><form method=post action='profiler.php?action=vmain'>
            View members in division <select name=inp_faction class=form><option value=all>All
        ";

        foreach($factions as $this){
            if($this == $inp_faction){
                $site[text] .= "<option value='$this' selected>$this";
            }
            else{$site[text] .= "<option value='$this'>$this";}
        }
        $site[text] .= "
            </select> of rank <select name=inp_rank class=form><option value=all>All
        ";
        foreach($ranks as $this){
            if($this == $inp_rank){
                $site[text] .= "<option value='$this' selected>$this";
            }
            else{$site[text] .= "<option value='$this'>$this";}
        }
        $site[text] .= "
            </select>
            <br>Order by <select name=inp_sort class=form>
        ";
        foreach($stats as $this){
            if($this == $inp_sort){
                $site[text] .= "<option value='$this' selected>$this";
            }
            else{$site[text] .= "<option value='$this'>$this";}
        }
        $site[text] .= "
             </select>&nbsp;&nbsp;<select name=inp_order class=form><option value='ASC'>Ascending<option value='DESC'>Descending</select>
             <input type=submit value='View'>
             </form></center>
        ";
    }
}

function viewprofile(){
    global $site, $prefs, $vars, $inp_id;
    
    $site[title] = "Member Profile";
    
    echo "<center><img src='minilogo.gif' border=0></center>";
    
    $rank_stuff = array('id','alias','email','rank','faction');
    $rank_stuff_name = array('id','alias','email','rank','faction');
    $prefs[user_info] = explode("`",$prefs[user_info]);
    $prefs[user_info_name] = explode("`",$prefs[user_info_name]);
    for($a = 0; $a < count($prefs[user_info]); $a++){
        $rank_stuff[] = $prefs[user_info][$a];
        $rank_stuff_name[] = $prefs[user_info_name][$a];
    }
    
    $query = mysql_query("select * from profiler_users where id = '$inp_id' limit 1");
    $this = mysql_fetch_array($query);
    $post = $prefs[profile_layout];
    $this[alias] = "$prefs[clantag]$this[alias]";
    for($a = 0; $a < count($rank_stuff); $a++){
        $stuff = $rank_stuff_name[$a];
        $post = str_replace("<$rank_stuff[$a]>","<font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>$this[$stuff]</font>",$post);
    }
    echo $post;
}

function apply(){
    global $site, $vars, $prefs;
    
    $site[title] = "Apply to Join";

    $site[text] = "
        <center>
        <form method=post action='profiler.php?action=apply2'>
        <table border=0 cellpadding=0 cellspacing=2 width=60%>
          <tr>
            <td width=100% colspan=2 bgcolor='$prefs[bgcolor1]' align=center><font face='$prefs[fontface]' color='$prefs[fontcolor2]' size='$prefs[fontsize2]'><b>Apply to Join</b></font></td>
          </tr>
          <tr>
            <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>User Alias:</font></td>
            <td width=60%><input type=text name=inp[alias] size=20 class=form></td>
          </tr>
          <tr>
            <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Password:</font></td>
            <td width=60%><input type=password name=inp[pass] size=20 class=form></td>
          </tr>
          <tr>
            <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Confirm Password:</font></td>
            <td width=60%><input type=password name=inp[pass2] size=20 class=form></td>
          </tr>
          <tr>
            <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Email:</font></td>
            <td width=60%><input type=text name=inp[email] size=20 class=form></td>
          </tr>
    ";

    // Get custom user fields
    $field = explode("`",$prefs[user_info]);
    $field_name = explode("`",$prefs[user_info_name]);
    for($a = 0; $a < count($field); $a++){
        $site[text] .= "
            <tr>
              <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>$field[$a]:</font></td>
              <td width=60%><input type=text name=inp[$field_name[$a]] size=20 class=form></td>
            </tr>
        ";
    }

    $site[text] .= "
          <tr>
            <td width=40% align=right><input type=checkbox name=inp[code] value=1></td>
            <td width=60%><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>I have read and agree to the <a href='' onClick=\"window.open('$vars[url_php]/profiler.php?action=viewcode','code','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\">code of conduct</a></font>
          <tr>
            <td width=40%>&nbsp;</td>
            <td width=60%><input type=submit value='Apply'></td>
          </tr>
        </table></form></center>
    ";
}

function apply2(){
    global $site, $vars, $prefs, $inp;
    
    $site[title] = "Apply to Join";
    
    // Prepare variables
    $field = explode("`",$prefs[user_info]);
    $field_name = explode("`",$prefs[user_info_name]);
    $require = explode("`",$prefs[required]);
    for($a = 0; $a < count($field_name); $a++){
        $form[$field_name[$a]] = $required[$a];
    }

    // Check for alias match
    $query = mysql_query("select alias from profiler_users");
    while($alias = mysql_fetch_array($query)){
        if($alias == $inp_alias){$invalid = 1; $reason = "that user alias is already in use";}
    }

    // Prepare domain / email validation
    eregi("^[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,3}$", $inp[email], $check);

    // Check required form fields
    if($inp[alias] == "" || $inp[pass] == "" || $inp[email] == ""){$invalid = 1; $reason = "not all required form fields were filled out";}

    if(is_array($inp)){
        while(list($key,$val) = each($inp)){
            if($form[$key] == 1 && $val == ""){$invalid = 1; $reason = "not all required form fields were filled out";}
        }
    }
    else{$invalid = 1; $reason = "not all required form fields were filled out";}

    // Check DNR and email...
    if(checkdnsrr(substr(strstr($check[0], '@'), 1),"ANY") == 0){$invalid = 1; $reason = "bad email address";}

    // Check passwords
    if($inp[pass] != $inp[pass2]){$invalid = 1; $reason = "passwords don't match";}
    
    // Check code of conduct
    if($inp[code] != 1){$invalid = 1; $reason = "you did not agree to the code of conduct.";}

    // Print out invalid form and reason
    if($invalid){
        $site[text] = "
        <center><font face='$prefs[fontface]' color='$prefs[fontcolor1]' size='$prefs[fontsize1]'>Your application could not be accepted because $reason<br>
        <form method=post action='profiler.php?action=apply2'>
        <table border=0 cellpadding=0 cellspacing=2 width=60%>
          <tr>
            <td width=100% colspan=2 bgcolor='$prefs[bgcolor1]' align=center><font face='$prefs[fontface]' color='$prefs[fontcolor2]' size='$prefs[fontsize2]'><b>Apply to Join</b></font></td>
          </tr>
          <tr>
            <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>User Alias:</font></td>
            <td width=60%><input type=text name=inp[alias] size=20 class=form value='$inp[alias]'></td>
          </tr>
          <tr>
            <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Password:</font></td>
            <td width=60%><input type=password name=inp[pass] size=20 class=form value='$inp[pass]'></td>
          </tr>
          <tr>
            <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Confirm Password:</font></td>
            <td width=60%><input type=password name=inp[pass2] size=20 class=form value='$inp[pass2]'></td>
          </tr>
          <tr>
            <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>Email:</font></td>
            <td width=60%><input type=text name=inp[email] size=20 class=form value='$inp[email]'></td>
          </tr>
        ";

        // Get custom user fields
        $field = explode("`",$prefs[user_info]);
        $field_name = explode("`",$prefs[user_info_name]);
        for($a = 0; $a < count($field); $a++){
            $this = $field_name[$a];
            $site[text] .= "
            <tr>
              <td width=40% align=right><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>$field[$a]:</font></td>
              <td width=60%><input type=text name=inp[$field_name[$a]] size=20 class=form value='$inp[$this]'></td>
            </tr>
        ";
        }

        $site[text] .= "
          <tr>
            <td width=40% align=right><input type=checkbox name=inp[code] value=1></td>
            <td width=60%><font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>I have read and agree to the <a href='' onClick=\"window.open('$vars[url_php]/profiler.php?action=viewcode','code','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\">code of conduct</a></font>
          <tr>
            <td width=40%>&nbsp;</td>
            <td width=60%><input type=submit value='Apply'></td>
          </tr>
        </table></form></center>
        ";
    }
    else{
        // Encrypt user's password using md5
        $inp[pass] = md5($inp[pass]);

        //Prepare MySql db entry
        $sql_names = "alias,pass,email";
        $sql_values = "'$inp[alias]','$inp[pass]','$inp[email]'";
        foreach($field_name as $this){
            $sql_names .= ",$this";
            $sql_values .= ",'$inp[$this]'";
        }

        mysql_query("insert into profiler_apps ($sql_names) values ($sql_values)") or mysql_error();

        $message = "Your application to join our clan has been received. You will be notified if you are accepted.";
        mail($inp[email],'Clan Application -- Automatic Message',$message);

        // Prepare output
        $site[text] = "
            Your application has been received. You will be contacted by a member of the clan once it is reviewed.
        ";
    }
}

function viewcode(){
    global $site, $prefs;
    
    $site[title] = "Code of Conduct";
    
    echo "<center><img src='minilogo.gif' border=0></center>";
    
    echo "
        <font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>
        <b>Code of Conduct:</b><br>$prefs[code_text]</font>
    ";
}

function code(){
    global $site, $prefs;
    
    $site[title] = "Code of Conduct";
    
    $site[text] = "
        <font face='$prefs[fontface]' size='$prefs[fontsize1]' color='$prefs[fontcolor1]'>
        <b>Code of Conduct:</b><br>$prefs[code_text]</font>
    ";
}

// End non user required functions

// Get top navigation menu
function get_top_menu($user){
    global $site, $prefs, $vars;

    if($user[status] == 3){
        $site[top_menu] = "
            <a href='profiler.php'>Home</a> | <a href='profiler.php?action=newuser'>New Member</a> | <a href='profiler.php?action=viewapp'>Applicants</a> | <a href='profiler.php?action=edituser'>Edit Member</a> | <a href='profiler.php?action=remove'>Remove Member</a> | <a href='profiler.php?action=list'>Member List</a> | <a href='profiler.php?action=admin'>Admin</a><br>
            <a href='profiler.php?action=mssg'>View Messages</a> | <a href='profiler.php?action=mssgnew'>New Message</a> | <a href='profiler.php?action=cal'>View Calandar</a> | <a href='profiler.php?action=news'>Post News</a> | <a href='profiler.php?action=getnews'>Edit News</a> | <a href='profiler.php?action=set'>Settings</a> | <a href='' onClick=\"window.open('$vars[url_php]/docs/help.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\">Help</a>
        ";
    }
    elseif($user[status] == 2){
        $site[top_menu] = "
            <a href='profiler.php'>Home</a> | <a href='profiler.php?action=newuser'>New Member</a> | <a href='profiler.php?action=viewapp'>Applicants</a> | <a href='profiler.php?action=edituser'>Edit Member</a> | <a href='profiler.php?action=remove'>Remove Member</a> | <a href='profiler.php?action=list'>Member List</a><br>
            <a href='profiler.php?action=mssg'>View Messages</a> | <a href='profiler.php?action=mssgnew'>New Message</a> | <a href='profiler.php?action=cal'>View Calandar</a> | <a href='profiler.php?action=news'>Post News</a> | <a href='profiler.php?action=getnews'>Edit News</a> | <a href='' onClick=\"window.open('$vars[url_php]/docs/help.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\">Help</a>
        ";
    }
    else{
        $site[top_menu] = "
            <a href='profiler.php'>Home</a> | <a href='profiler.php?action=edituser'>Edit Profile</a> | <a href='profiler.php?action=list'>Member List</a><br>
            <a href='profiler.php?action=mssg'>View Messages</a> | <a href='profiler.php?action=mssgnew'>New Message</a> | <a href='profiler.php?action=cal'>View Calandar</a> | <a href='' onClick=\"window.open('$vars[url_php]/docs/help.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\">Help</a>
        ";
    }
}

function get_side_menu($user){
    global $site, $prefs, $vars;

    if($user[status] == 3){
        $site[side_menu] = "
            <a href='profiler.php'>Home</a><br><a href='profiler.php?action=newuser'>New Member</a><br><a href='profiler.php?action=viewapp'>Applicants</a><br><a href='profiler.php?action=edituser'>Edit Member</a><br><a href='profiler.php?action=remove'>Remove Member</a><br><a href='profiler.php?action=list'>Member List</a><br><a href='profiler.php?action=admin'>Admin</a><br><br>
            <a href='profiler.php?action=mssg'>View Messages</a><br><a href='profiler.php?action=mssgnew'>New Message</a><br><a href='profiler.php?action=cal'>View Calandar</a><br><a href='profiler.php?action=news'>Post News</a><br><a href='profiler.php?action=getnews'>Edit News</a><br><a href='profiler.php?action=set'>Settings</a><br><a href='' onClick=\"window.open('$vars[url_php]/docs/help.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\">Help</a>
        ";
    }
    elseif($user[status] == 2){
        $site[side_menu] = "
            <a href='profiler.php'>Home</a><br><a href='profiler.php?action=newuser'>New Member</a><br><a href='profiler.php?action=viewapp'>Applicants</a><br><a href='profiler.php?action=edituser'>Edit Member</a><br><a href='profiler.php?action=remove'>Remove Member</a><br><a href='profiler.php?action=list'>Member List</a><br><br>
            <a href='profiler.php?action=mssg'>View Messages</a><br><a href='profiler.php?action=mssgnew'>New Message</a><br><a href='profiler.php?action=cal'>View Calandar</a><br><a href='profiler.php?action=news'>Post News</a><br><a href='profiler.php?action=getnews'>Edit News</a><br><a href='' onClick=\"window.open('$vars[url_php]/docs/help.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\">Help</a>
        ";
    }
    else{
        $site[side_menu] = "
            <a href='profiler.php'>Home</a><br><a href='profiler.php?action=edituser'>Edit Profile</a><br><a href='profiler.php?action=list'>Member List</a><br><br>
            <a href='profiler.php?action=mssg'>View Messages</a><br><a href='profiler.php?action=mssgnew'>New Message</a><br><a href='profiler.php?action=cal'>View Calandar</a><br><a href='' onClick=\"window.open('$vars[url_php]/docs/help.html','help','scrollbars=yes,status=no,menubar=no,resizable=no,width=440,height=500,toolbar=no,location=no'); return false;\">Help</a>
        ";
    }
}

// Get status menu
function get_status($user){
    global $validuser, $site, $prefs;
    
    if($validuser){
        $site[status] = "<font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>You are currently logged in as $user[alias]<br><a href='profiler.php?action=logout'>Logout</a></font?";
    }
    else{
        $site[status] = "<font face='$prefs[fontface]' size='$prefs[fontsize1]'color='$prefs[fontcolor1]'>You are currently <a href='profiler.php?action=login'>not logged in</a>.</font>";
    }
}

function get_cal_vars(){
    global $ttime,$this_month, $list_month,$list_year,$this_year,$option_day,$option_month,$option_year,$list_n,$dcount,$dday,$dweekday;

    $list_month = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
    $list_year = array('2001','2002','2003','2004','2005');
    $this_year = date("Y");
    $this_month = date("m");

    for($a = 1; $a <= 31; $a++){
		if($a == date("j")){$special = "selected";}
		else{$special = "";}
		$option_day .= "<option value=$a $special>$a";
	}
	for($a = 0; $a < count($list_month); $a++){
        $b = $a + 1;
		if($a == date("n")-1){$special2 = "selected"; $tmonth = $list_month[$a];}
		else{$special2 = "";}
		$option_month .= "<option value='$b' $special2>".$list_month[$a];
	}
	for($a = 0; $a < count($list_year); $a++){
		if($list_year[$a] == $this_year){$special3 = "selected";}
		else{$special3 = "";}
		$option_year .= "<option value=".$list_year[$a]." $special3>".$list_year[$a];
	}
 
	$list_n = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");

	$dcount = date("t", $ttime);
	$dday = date("d", $ttime);
	$dweekday = date("w", $ttime);
}

?>
