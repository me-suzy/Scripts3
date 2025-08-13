<?php
//////////////////////////////////////////////////////////////////
//arena.php created by Dark Wolf                                                     //
//                                                                                                         //
//Created for the RPG battle system                                              //
//                                                                                                     //
////////////////////////////////////////////////////////////
require "functions.php";

$userroom2000_stat2 = $db_connect->query_first("select userroom from db_users where username='$user_name'");
$myuserroom2000 = $userroom2000_stat2[userroom];

if($myuserroom2000 == lobby){
   eval("dooutput(\"".gettemplate("nobattle")."\");");
   exit;
}

$userroom5_stat = $db_connect->query_first("select userroom from db_users where username='$user_name'");
$userroom5 = $userroom5_stat[userroom];
$challenger5_stat = $db_connect->query_first("select challenger from $userroom5 where id='1'");
$challenger5 = $challenger5_stat[challenger];
$me5_stat = $db_connect->query_first("select me from $userroom5 where id='1'");
$me5 = $me5_stat[me];
if($user_name == $challenger5){
   $duringbattle='yes';
   $myuserroom=$userroom5;
}

if($user_name == $me5){
   $duringbattle='yes';
   $myuserroom=$userroom5;
}

if($duringbattle == 'yes' && $action != 'attack' && $action != 'guard' && $action != 'useitem'){

//gather vital info for users...
$myname_stat = $db_connect->query_first("select me from $myuserroom where id='1'");
$myname = $myname_stat[me];
$challenger_stat = $db_connect->query_first("select challenger from $myuserroom where id='1'");
$challenger = $challenger_stat[challenger];
$myturn_stat = $db_connect->query_first("select whosturn from $myuserroom where id='1'");
$myturn = $myturn_stat[whosturn];

$player1 =  $db_connect->query_first("select * from db_users where username='$myname'");
//here are your stats...
$useritem1 = $player1[useritem1];
$useritem2 = $player1[useritem2];
$useritem3 = $player1[useritem3];
$useritem4 = $player1[useritem4];
$useritem5 = $player1[useritem5];
$useritem6 = $player1[useritem6];
$useritem7 = $player1[useritem7];
$useritem8 = $player1[useritem8];
$userweapon = $player1[userweapon];
$usershield = $player1[usershield];
$userarmour = $player1[userarmour];
$usergloves = $player1[usergloves];
$userboots = $player1[userboots];
$userextra1 = $player1[userextra1];
$userextra2 = $player1[userextra2];
$userclass = $player1[userclass];
$useralignment = $player1[useralignment];
$userlevel = $player1[userlevel];
$userexp = $player1[userexp];
$userhitpoint = $player1[userhitpoint];
$usermagicpoint = $player1[usermagicpoint];
$userdefense = $player1[userdefense];
$userstrength = $player1[userattack];
$usermagic = $player1[usermagic];
$userspeed = $player1[userspeed];
$usermoney = $player1[usermoney];
$battlemusic2 =  $db_connect->query_first("select * from $myuserroom where id='1'");
$mybattlemusic = $battlemusic2[battlemusic];


$player2 =  $db_connect->query_first("select * from db_users where username='$challenger'");
//now the challenger's stats...
$useritem12 = $player2[useritem1];
$useritem22 = $player2[useritem2];
$useritem32 = $player2[useritem3];
$useritem42 = $player2[useritem4];
$useritem52 = $player2[useritem5];
$useritem62 = $player2[useritem6];
$useritem72 = $player2[useritem7];
$useritem82 = $player2[useritem8];
$userweapon2 = $player2[userweapon];
$usershield2 = $player2[usershield];
$userarmour2 = $player2[userarmour];
$usergloves2 = $player2[usergloves];
$userboots2 = $player2[userboots];
$userextra12 = $player2[userextra1];
$userclass2 = $player2[userclass];
$useralignment2 = $player2[useralignment];
$userlevel2 = $player2[userlevel];
$userexp2 = $player2[userexp];
$userhitpoint2 = $player2[userhitpoint];
$usermagicpoint2 = $player2[usermagicpoint];
$userdefense2 = $player2[userdefense];
$userstrength2 = $player2[userattack];
$usermagic2 = $player2[usermagic];
$userspeed2 = $player2[userspeed];
$usermoney2 = $player2[usermoney];


$userboot_heal_stat = $db_connect->query_first("select deffense from db_shop_boots where bootname='$userboot'");
$userboot_heal = $userboot_heal_stat[deffense];
$userweapon_damage_stat = $db_connect->query_first("select damage from db_shop_swords where swordname='$userweapon'");
$userweapon_damage = $userweapon_damage_stat[damage];
$userarmour_heal_stat = $db_connect->query_first("select deffense from db_shop_armour where armourname='$userarmour'");
$userarmour_heal = $userarmour_heal_stat[deffense];
$userhelmet_heal_stat = $db_connect->query_first("select deffense from db_shop_helmet where helmetname='$userhelmet'");
$userhelmet_heal = $userhelmet_heal_stat[deffense];
$usergloves_heal_stat = $db_connect->query_first("select deffense from db_shop_gloves where glovename='$usergloves'");
$usergloves_heal = $usergloves_heal_stat[deffense];
$usershield_heal_stat = $db_connect->query_first("select deffense from db_shop_shields where shieldname='$usershield'");
$usershield_heal = $usershield_heal_stat[deffense];
$totaldeffense=($userarmour_heal+$userboot_heal+$userhelmet_heal+$usergloves_heal+$usershield_heal);

$userweapon_damage_stat2 = $db_connect->query_first("select damage from db_shop_swords where swordname='$userweapon2'");
$userweapon_damage2 = $userweapon_damage_stat2[damage];
$userarmour_heal_stat2 = $db_connect->query_first("select deffense from db_shop_armour where armourname='$userarmour2'");
$userarmour_heal2 = $userarmour_heal_stat2[deffense];
$userboot_heal_stat2 = $db_connect->query_first("select deffense from db_shop_boots where bootname='$userboot2'");
$userboot_heal2 = $userboot_heal_stat2[deffense];
$userhelmet_heal_stat2 = $db_connect->query_first("select deffense from db_shop_helmet where helmetname='$userhelmet2'");
$userhelmet_heal2 = $userhelmet_heal_stat2[deffense];
$usergloves_heal_stat2 = $db_connect->query_first("select deffense from db_shop_gloves where glovename='$usergloves2'");
$usergloves_heal2 = $usergloves_heal_stat2[deffense];
$usershield_heal_stat2 = $db_connect->query_first("select deffense from db_shop_shields where shieldname='$usershield2'");
$usershield_heal2 = $usershield_heal_stat2[deffense];
$totaldeffense2=($userarmour_heal2+$userboot_heal2+$userhelmet_heal2+$usergloves_heal2+$usershield_heal2);


	if($player2[userhitpoint] <= 0){

	$userroom_stat2 = $db_connect->query_first("select userroom from db_users where username='$user_name'");
	$myuserroom = $userroom_stat2[userroom];
	$validname_stat2 = $db_connect->query_first("select me from $myuserroom where id='1'");
	$me = $validname_stat2[me];
	$challenger_stat = $db_connect->query_first("select challenger from $myuserroom where id='1'");
	$challenger = $challenger_stat[challenger];

	header("LOCATION: winner.php?duringbattle=no");
	}

	if($player1[userhitpoint] <= 0){

	$userroom_stat2 = $db_connect->query_first("select userroom from db_users where username='$user_name'");
	$myuserroom = $userroom_stat2[userroom];
	$validname_stat2 = $db_connect->query_first("select me from $myuserroom where id='1'");
	$me = $validname_stat2[me];
	$challenger_stat = $db_connect->query_first("select challenger from $myuserroom where id='1'");
	$challenger = $challenger_stat[challenger];

	header("LOCATION: winner.php?duringbattle=no");
	}


	$barlength=(100*($player1[userhitpoint]/$player1[hpmax]));

	if($barlength > 100){$barlength = 100;}
	if($barlength > 80 && $barlength <= 100){$player1color='skyblue';}
	if($barlength > 60 && $barlength <= 79){$player1color='green';}
	if($barlength > 40 && $barlength <= 59){$player1color='yellow';}
	if($barlength > 20 && $barlength <= 39){$player1color='orange';}
	if($barlength >= 0 && $barlength <= 19){$player1color='red';}
	if($barlength >= 0 && $barlength <= 19 && $user_name == $myname) { $deathalert='<img src="images/alert.gif"><BR><BR>';}

	$barlength2=(100*($player2[userhitpoint]/$player2[hpmax]));

	if($barlength2 > 100){$barlength2 = 100;}
	if($barlength2 > 80 && $barlength2 <= 100){$player2color='skyblue';}
	if($barlength2 > 60 && $barlength2 <= 79){$player2color='green';}
	if($barlength2 > 40 && $barlength2 <= 59){$player2color='yellow';}
	if($barlength2 > 20 && $barlength2 <= 39){$player2color='orange';}
	if($barlength2 >= 0 && $barlength2 <= 19){$player2color='red';}
	if($barlength2 >= 0 && $barlength2 <= 19 && $user_name == $challenger) { $deathalert='<img src="images/alert.gif"><BR><BR>';}


	if($player1[userhitpoint]>$player1[hpmax]) {
	$testthis="$player1[userhitpoint] is greater than $player1[hpmax] therefore HP equals $player1[hpmax]";
	$db_connect->query("UPDATE db_users SET userhitpoint='$player1[hpmax]' WHERE username='$player1[username]'");
	}

	if($player2[userhitpoint]>$player2[hpmax]) {
	$testthis="$player2[userhitpoint] is greater than $player2[hpmax] therefore HP equals $player2[hpmax]";
	$db_connect->query("UPDATE db_users SET userhitpoint='$player2[hpmax]' WHERE username='$player2[username]'");
	}

        if(!$player1[mypic]){
        $db_connect->query("UPDATE db_users SET mypic='images/nopic.gif' WHERE username='$player1[username]'");
        }

        if(!$player2[mypic]){
        $db_connect->query("UPDATE db_users SET mypic='images/nopic.gif' WHERE username='$player2[username]'");
        }

        if($user_name == $myname && $myturn == notmyturn){ eval("\$battlefield=\"".gettemplate("waiting")."\";");}
        if($user_name == $myname && $myturn == myturn){ eval("\$battlefield=\"".gettemplate("arena_me")."\";");}
        if($user_name == $challenger && $myturn == myturn){ eval("\$battlefield=\"".gettemplate("waiting2")."\";");}
        if($user_name == $challenger && $myturn == notmyturn){eval("\$battlefield=\"".gettemplate("arena_you")."\";");}

	eval("dooutput(\"".gettemplate("arena_new")."\");");
}


$userroom_stat = $db_connect->query_first("select userroom from db_users where username='$user_name'");
$userroom = $userroom_stat[userroom];
$challenger_stat = $db_connect->query_first("select challenger from $userroom where id='1'");
$challenger = $challenger_stat[challenger];
$me_stat = $db_connect->query_first("select me from $userroom where id='1'");
$me = $me_stat[me];

if($user_name == $me){
   if($myturn == notmyturn){
	if($action == attack || $action == guard || $action == useitem){
      $userroom_stat2 = $db_connect->query_first("select userroom from db_users where username='$user_name'");
      $myuserroom = $userroom_stat2[userroom];
      $validname_stat2 = $db_connect->query_first("select me from $myuserroom where id='1'");
      $me = $validname_stat2[me];
      $challenger_stat = $db_connect->query_first("select challenger from $myuserroom where id='1'");
      $challenger = $challenger_stat[challenger];

      header("LOCATION: wait.php?myname=$user_name");
	}
   }

   if($action == attack && $myturn == myturn){
      $userroom_stat2 = $db_connect->query_first("select userroom from db_users where username='$user_name'");
      $myuserroom = $userroom_stat2[userroom];
      $validname_stat2 = $db_connect->query_first("select me from $myuserroom where id='1'");
      $me = $validname_stat2[me];
      $challenger_stat = $db_connect->query_first("select challenger from $myuserroom where id='1'");
      $challenger = $challenger_stat[challenger];
      $user = $db_connect->query_first("select * from db_users where username='$me'");
      $user2 = $db_connect->query_first("select * from db_users where username='$challenger'");

	$userweapon_damage_stat = $db_connect->query_first("select damage from db_shop_swords where swordname='$user[userweapon]'");
	$userweapon_damage = $userweapon_damage_stat[damage];

	$userboot_heal_stat = $db_connect->query_first("select deffense from db_shop_boots where bootname='$user2[userboots]'");
	$userboot_heal = $userboot_heal_stat[deffense];
	$userweapon_damage_stat = $db_connect->query_first("select damage from db_shop_swords where swordname='$user2[userweapon]'");
	$userweapon_damage = $userweapon_damage_stat[damage];
	$userarmour_heal_stat = $db_connect->query_first("select deffense from db_shop_armour where armourname='$user2[userarmour]'");
	$userarmour_heal = $userarmour_heal_stat[deffense];
	$userhelmet_heal_stat = $db_connect->query_first("select deffense from db_shop_helmet where helmetname='$user2[userhelmet]'");
	$userhelmet_heal = $userhelmet_heal_stat[deffense];
	$usergloves_heal_stat = $db_connect->query_first("select deffense from db_shop_gloves where glovename='$user2[usergloves]'");
	$usergloves_heal = $usergloves_heal_stat[deffense];
	$usershield_heal_stat = $db_connect->query_first("select deffense from db_shop_shields where shieldname='$user2[usershield]'");
	$usershield_heal = $usershield_heal_stat[deffense];
	$totaldeffense2=($userarmour_heal+$userboot_heal+$userhelmet_heal+$usergloves_heal+$usershield_heal);

      $moneyincrease=($user[usermoney]+100);
      $totalmoneyincrease=($moneyincrease);

      $expincrease=$user[userexp]+100+$user[userlevel];
      $totalexpincrease=($expincrease);

      $damage=($userweapon_damage+$user[userattack]-($totaldeffense2));
      $fulldamage=($user2[userhitpoint]-$damage);

      if($damage < 0) {$damage = 1;}
      if($fulldamage > $user[hpmax]) {$fulldamage = $user[hpmax];}

      $duringbattle='yes';

      $ride="arena.php ";

      $db_connect->query("UPDATE db_users SET userhitpoint='$fulldamage' WHERE username = '$challenger'");
      $db_connect->query("UPDATE $myuserroom SET whosturn='notmyturn', messages='$me has attacked $challenger with a $user[userweapon] and dealt $damage!' WHERE id = '1'");
      $db_connect->query("UPDATE db_users SET usermoney='$totalmoneyincrease', userexp='$expincrease' WHERE username = '$me'");

      eval("dooutput(\"".gettemplate("attacktest")."\");");
   }

   if($action == guard && $myturn == myturn){
      $userroom_stat2 = $db_connect->query_first("select userroom from db_users where username='$user_name'");
      $myuserroom = $userroom_stat2[userroom];
      $validname_stat2 = $db_connect->query_first("select me from $myuserroom where id='1'");
      $me = $validname_stat2[me];
      $challenger_stat = $db_connect->query_first("select challenger from $myuserroom where id='1'");
      $challenger = $challenger_stat[challenger];
      $user = $db_connect->query_first("select * from db_users where username='$me'");
      $user2 = $db_connect->query_first("select * from db_users where username='$challenger'");
      $maxhitpoint_stat = $db_connect->query_first("select hpmax from db_users where username='$user_name'");
      $maxhitpoint = $maxhitpoint_stat[hpmax];

	$userboot_heal_stat = $db_connect->query_first("select deffense from db_shop_boots where bootname='$user[userboots]'");
	$userboot_heal = $userboot_heal_stat[deffense];
	$userweapon_damage_stat = $db_connect->query_first("select damage from db_shop_swords where swordname='$user[userweapon]'");
	$userweapon_damage = $userweapon_damage_stat[damage];
	$userarmour_heal_stat = $db_connect->query_first("select deffense from db_shop_armour where armourname='$user[userarmour]'");
	$userarmour_heal = $userarmour_heal_stat[deffense];
	$userhelmet_heal_stat = $db_connect->query_first("select deffense from db_shop_helmet where helmetname='$user[userhelmet]'");
	$userhelmet_heal = $userhelmet_heal_stat[deffense];
	$usergloves_heal_stat = $db_connect->query_first("select deffense from db_shop_gloves where glovename='$user[usergloves]'");
	$usergloves_heal = $usergloves_heal_stat[deffense];
	$usershield_heal_stat = $db_connect->query_first("select deffense from db_shop_shields where shieldname='$user[usershield]'");
	$usershield_heal = $usershield_heal_stat[deffense];
	$totaldeffense=($userarmour_heal+$userboot_heal+$userhelmet_heal+$usergloves_heal+$usershield_heal);

      $moneyincrease=($user[usermoney]+120);
      $totalmoneyincrease=($moneyincrease);

      $expincrease=($user[userexp]+(100+$user[userlevel]));
      $totalexpincrease=($expincrease);

      $guardheal=($user[userlevel]+($totaldeffense/2));
      $fullguardheal=($user[userhitpoint]+$guardheal);

         if($user[userhitpoint] > $user[hpmax]){
	 $error="Unkown error";
         $db_connect->query("UPDATE db_users SET userhitpoint='$maxhitpoint' WHERE username = '$user_name'");
	 header("LOCATION: arena.php?error=$error");
 	 exit;
         }

      $duringbattle='yes';

      $ride="arena.php ";

      $db_connect->query("UPDATE $myuserroom SET whosturn='notmyturn', messages='$me is guarding from $challenger.' WHERE id = '1'");
      $db_connect->query("UPDATE db_users SET userhitpoint='$fullguardheal', usermoney='$totalmoneyincrease', userexp='$totalexpincrease' WHERE username = '$user_name'");

      eval("dooutput(\"".gettemplate("guardtest")."\");");
   }

   if($action == useitem && $myturn == myturn){
      $userroom_stat2 = $db_connect->query_first("select userroom from db_users where username='$user_name'");
      $myuserroom = $userroom_stat2[userroom];
      $validname_stat2 = $db_connect->query_first("select me from $myuserroom where id='1'");
      $me = $validname_stat2[me];
      $challenger_stat = $db_connect->query_first("select challenger from $myuserroom where id='1'");
      $challenger = $challenger_stat[challenger];
      $user = $db_connect->query_first("select * from db_users where username='$me'");
      $user2 = $db_connect->query_first("select * from db_users where username='$challenger'");
      $useritem1 = $user[useritem1];
      $useritem2 = $user[useritem2];
      $useritem3 = $user[useritem3];
      $useritem4 = $user[useritem4];
      $useritem5 = $user[useritem5];
      $useritem6 = $user[useritem6];
      $useritem7 = $user[useritem7];
      $useritem8 = $user[useritem8];

      $expincrease=($user[userexp]+(100+$user[userlevel]));
      $totalexpincrease=($expincrease);

      $duringbattle='yes';

      $db_connect->query("UPDATE db_users SET usermoney='$totalmoneyincrease', userexp='$totalexpincrease' WHERE username = '$user_name'");
         if($item == firstitem){
            if($useritem1 == 'Empty'){
            $error='You have no item in that slot!';
            header("LOCATION: arena.php?error=$error ");
	    exit;
            }
            $hpaddon_stat = $db_connect->query_first("select hpaddon from db_shop_smallitems where smallitemname='$useritem1'");
            $hpaddon = $hpaddon_stat[hpaddon];
            $mpaddon_stat = $db_connect->query_first("select mpaddon from db_shop_smallitems where smallitemname='$useritem1'");
            $mpaddon = $mpaddon_stat[mpaddon];
            $maxhitpoint_stat = $db_connect->query_first("select hpmax from db_users where username='$user_name'");
            $maxhitpoint = $maxhitpoint_stat[hpmax];

            $hitpointincrease=($user[userhitpoint]+$hpaddon);
            $magicpointincrease=($user[usermagicpoint]+$mpaddon);

            if($user[userhitpoint] > $maxhitpoint){
            $db_connect->query("UPDATE db_users SET userhitpoint='$maxhitpoint' WHERE username = '$myname'");
            }

            $db_connect->query("UPDATE $myuserroom SET whosturn='notmyturn', messages='$me used a $useritem1' WHERE id = '1'");
            $db_connect->query("UPDATE db_users SET userhitpoint='$hitpointincrease', usermagicpoint='$magicpointincrease', useritem1='Empty' WHERE username = '$user_name'");

            $ride="arena.php ";
            eval("dooutput(\"".gettemplate("itemtest")."\");");
         }

         if($item == seconditem){
            if($useritem2 == 'Empty'){
            $error='You have no item in that slot!';
            header("LOCATION: arena.php?error=$error ");
	    exit;
            }
            $hpaddon_stat = $db_connect->query_first("select hpaddon from db_shop_smallitems where smallitemname='$useritem2'");
            $hpaddon = $hpaddon_stat[hpaddon];
            $mpaddon_stat = $db_connect->query_first("select mpaddon from db_shop_smallitems where smallitemname='$useritem2'");
            $mpaddon = $mpaddon_stat[mpaddon];
            $maxhitpoint_stat = $db_connect->query_first("select hpmax from db_users where username='$user_name'");
            $maxhitpoint = $maxhitpoint_stat[hpmax];

            $hitpointincrease=($user[userhitpoint]+$hpaddon);
            $magicpointincrease=($user[usermagicpoint]+$mpaddon);

            if($user[userhitpoint] > $maxhitpoint){
            $db_connect->query("UPDATE db_users SET userhitpoint='$maxhitpoint' WHERE username = '$myname'");
            }

            $db_connect->query("UPDATE $myuserroom SET whosturn='notmyturn', messages='$me used a $useritem2' WHERE id = '1'");
            $db_connect->query("UPDATE db_users SET userhitpoint='$hitpointincrease', usermagicpoint='$magicpointincrease', useritem2='Empty' WHERE username = '$user_name'");

            $ride="arena.php ";
            eval("dooutput(\"".gettemplate("itemtest")."\");");
         }

         if($item == thirditem){
            if($useritem3 == 'Empty'){
            $error='You have no item in that slot!';
            header("LOCATION: arena.php?error=$error ");
	    exit;
            }
            $hpaddon_stat = $db_connect->query_first("select hpaddon from db_shop_smallitems where smallitemname='$useritem3'");
            $hpaddon = $hpaddon_stat[hpaddon];
            $mpaddon_stat = $db_connect->query_first("select mpaddon from db_shop_smallitems where smallitemname='$useritem3'");
            $mpaddon = $mpaddon_stat[mpaddon];
            $maxhitpoint_stat = $db_connect->query_first("select hpmax from db_users where username='$user_name'");
            $maxhitpoint = $maxhitpoint_stat[hpmax];

            $hitpointincrease=($user[userhitpoint]+$hpaddon);
            $magicpointincrease=($user[usermagicpoint]+$mpaddon);

            if($user[userhitpoint] > $maxhitpoint){
            $db_connect->query("UPDATE db_users SET userhitpoint='$maxhitpoint' WHERE username = '$myname'");
            }

            $db_connect->query("UPDATE $myuserroom SET whosturn='notmyturn', messages='$me used a $useritem3' WHERE id = '1'");
            $db_connect->query("UPDATE db_users SET userhitpoint='$hitpointincrease', usermagicpoint='$magicpointincrease', useritem3='Empty' WHERE username = '$user_name'");

            $ride="arena.php ";
            eval("dooutput(\"".gettemplate("itemtest")."\");");
         }  

         if($item == fourthitem){
            if($useritem4 == 'Empty'){
            $error='You have no item in that slot!';
            header("LOCATION: arena.php?error=$error ");
	    exit;
            }
            $hpaddon_stat = $db_connect->query_first("select hpaddon from db_shop_smallitems where smallitemname='$useritem4'");
            $hpaddon = $hpaddon_stat[hpaddon];
            $mpaddon_stat = $db_connect->query_first("select mpaddon from db_shop_smallitems where smallitemname='$useritem4'");
            $mpaddon = $mpaddon_stat[mpaddon];
            $maxhitpoint_stat = $db_connect->query_first("select hpmax from db_users where username='$user_name'");
            $maxhitpoint = $maxhitpoint_stat[hpmax];

            $hitpointincrease=($user[userhitpoint]+$hpaddon);
            $magicpointincrease=($user[usermagicpoint]+$mpaddon);

            if($user[userhitpoint] > $maxhitpoint){
            $db_connect->query("UPDATE db_users SET userhitpoint='$maxhitpoint' WHERE username = '$myname'");
            }

            $db_connect->query("UPDATE $myuserroom SET whosturn='notmyturn', messages='$me used a $useritem4' WHERE id = '1'");
            $db_connect->query("UPDATE db_users SET userhitpoint='$hitpointincrease', usermagicpoint='$magicpointincrease', useritem4='Empty' WHERE username = '$user_name'");

            $ride="arena.php ";
            eval("dooutput(\"".gettemplate("itemtest")."\");");
         }  

         if($item == fifthitem){
            if($useritem5 == 'Empty'){
            $error='You have no item in that slot!';
            header("LOCATION: arena.php?error=$error ");
	    exit;
            }
            $hpaddon_stat = $db_connect->query_first("select hpaddon from db_shop_smallitems where smallitemname='$useritem5'");
            $hpaddon = $hpaddon_stat[hpaddon];
            $mpaddon_stat = $db_connect->query_first("select mpaddon from db_shop_smallitems where smallitemname='$useritem5'");
            $mpaddon = $mpaddon_stat[mpaddon];
            $maxhitpoint_stat = $db_connect->query_first("select hpmax from db_users where username='$user_name'");
            $maxhitpoint = $maxhitpoint_stat[hpmax];

            $hitpointincrease=($user[userhitpoint]+$hpaddon);
            $magicpointincrease=($user[usermagicpoint]+$mpaddon);

            if($user[userhitpoint] > $maxhitpoint){
            $db_connect->query("UPDATE db_users SET userhitpoint='$maxhitpoint' WHERE username = '$myname'");
            }

            $db_connect->query("UPDATE $myuserroom SET whosturn='notmyturn', messages='$me used a $useritem5' WHERE id = '1'");
            $db_connect->query("UPDATE db_users SET userhitpoint='$hitpointincrease', usermagicpoint='$magicpointincrease', useritem5='Empty' WHERE username = '$user_name'");

            $ride="arena.php ";
            eval("dooutput(\"".gettemplate("itemtest")."\");");
         }  

         if($item == sixthitem){
            if($useritem6 == 'Empty'){
            $error='You have no item in that slot!';
            header("LOCATION: arena.php?error=$error ");
	    exit;
            }
            $hpaddon_stat = $db_connect->query_first("select hpaddon from db_shop_smallitems where smallitemname='$useritem6'");
            $hpaddon = $hpaddon_stat[hpaddon];
            $mpaddon_stat = $db_connect->query_first("select mpaddon from db_shop_smallitems where smallitemname='$useritem6'");
            $mpaddon = $mpaddon_stat[mpaddon];
            $maxhitpoint_stat = $db_connect->query_first("select hpmax from db_users where username='$user_name'");
            $maxhitpoint = $maxhitpoint_stat[hpmax];

            $hitpointincrease=($user[userhitpoint]+$hpaddon);
            $magicpointincrease=($user[usermagicpoint]+$mpaddon);

            if($user[userhitpoint] > $maxhitpoint){
            $db_connect->query("UPDATE db_users SET userhitpoint='$maxhitpoint' WHERE username = '$myname'");
            }

            $db_connect->query("UPDATE $myuserroom SET whosturn='notmyturn', messages='$me used a $useritem6' WHERE id = '1'");
            $db_connect->query("UPDATE db_users SET userhitpoint='$hitpointincrease', usermagicpoint='$magicpointincrease', useritem6='Empty' WHERE username = '$user_name'");

            $ride="arena.php ";
            eval("dooutput(\"".gettemplate("itemtest")."\");");
         }  

         if($item == seventhitem){
            if($useritem7 == 'Empty'){
            $error='You have no item in that slot!';
            header("LOCATION: arena.php?error=$error ");
	    exit;
            }
            $hpaddon_stat = $db_connect->query_first("select hpaddon from db_shop_smallitems where smallitemname='$useritem7'");
            $hpaddon = $hpaddon_stat[hpaddon];
            $mpaddon_stat = $db_connect->query_first("select mpaddon from db_shop_smallitems where smallitemname='$useritem7'");
            $mpaddon = $mpaddon_stat[mpaddon];
            $maxhitpoint_stat = $db_connect->query_first("select hpmax from db_users where username='$user_name'");
            $maxhitpoint = $maxhitpoint_stat[hpmax];

            $hitpointincrease=($user[userhitpoint]+$hpaddon);
            $magicpointincrease=($user[usermagicpoint]+$mpaddon);

            if($user[userhitpoint] > $maxhitpoint){
            $db_connect->query("UPDATE db_users SET userhitpoint='$maxhitpoint' WHERE username = '$myname'");
            }

            $db_connect->query("UPDATE $myuserroom SET whosturn='notmyturn', messages='$me used a $useritem7' WHERE id = '1'");
            $db_connect->query("UPDATE db_users SET userhitpoint='$hitpointincrease', usermagicpoint='$magicpointincrease', useritem7='Empty' WHERE username = '$user_name'");

            $ride="arena.php ";
            eval("dooutput(\"".gettemplate("itemtest")."\");");
         }  

         if($item == eigthitem){
            if($useritem8 == 'Empty'){
            $error='You have no item in that slot!';
            header("LOCATION: arena.php?error=$error ");
	    exit;
            }
            $hpaddon_stat = $db_connect->query_first("select hpaddon from db_shop_smallitems where smallitemname='$useritem8'");
            $hpaddon = $hpaddon_stat[hpaddon];
            $mpaddon_stat = $db_connect->query_first("select mpaddon from db_shop_smallitems where smallitemname='$useritem8'");
            $mpaddon = $mpaddon_stat[mpaddon];
            $maxhitpoint_stat = $db_connect->query_first("select hpmax from db_users where username='$user_name'");
            $maxhitpoint = $maxhitpoint_stat[hpmax];

            $hitpointincrease=($user[userhitpoint]+$hpaddon);
            $magicpointincrease=($user[usermagicpoint]+$mpaddon);

            if($user[userhitpoint] > $maxhitpoint){
            $db_connect->query("UPDATE db_users SET userhitpoint='$maxhitpoint' WHERE username = '$myname'");
            }

            $db_connect->query("UPDATE $myuserroom SET whosturn='notmyturn', messages='$me used a $useritem8' WHERE id = '1'");
            $db_connect->query("UPDATE db_users SET userhitpoint='$hitpointincrease', usermagicpoint='$magicpointincrease', useritem8='Empty' WHERE username = '$user_name'");

            $ride="arena.php ";
            eval("dooutput(\"".gettemplate("itemtest")."\");");
         }  
      
   }
}

if($user_name == $challenger){
   if($myturn == myturn){
	if($action == attack || $action == guard || $action == useitem){
      $userroom_stat2 = $db_connect->query_first("select userroom from db_users where username='$user_name'");
      $myuserroom = $userroom_stat2[userroom];
      $validname_stat2 = $db_connect->query_first("select me from $myuserroom where id='1'");
      $me = $validname_stat2[me];
      $challenger_stat = $db_connect->query_first("select challenger from $myuserroom where id='1'");
      $challenger = $challenger_stat[challenger];

      header("LOCATION: wait.php?myname=$user_name");
	}
   }

   if($action == attack && $myturn == notmyturn){
      $userroom_stat2 = $db_connect->query_first("select userroom from db_users where username='$user_name'");
      $myuserroom = $userroom_stat2[userroom];
      $validname_stat2 = $db_connect->query_first("select me from $myuserroom where id='1'");
      $me = $validname_stat2[me];
      $challenger_stat = $db_connect->query_first("select challenger from $myuserroom where id='1'");
      $challenger = $challenger_stat[challenger];
      $user = $db_connect->query_first("select * from db_users where username='$me'");
      $user2 = $db_connect->query_first("select * from db_users where username='$challenger'");

	$userweapon_damage_stat2 = $db_connect->query_first("select damage from db_shop_swords where swordname='$user2[userweapon]'");
	$userweapon_damage2 = $userweapon_damage_stat2[damage];

	$userboot_heal_stat = $db_connect->query_first("select deffense from db_shop_boots where bootname='$user[userboots]'");
	$userboot_heal = $userboot_heal_stat[deffense];
	$userweapon_damage_stat = $db_connect->query_first("select damage from db_shop_swords where swordname='$user[userweapon]'");
	$userweapon_damage = $userweapon_damage_stat[damage];
	$userarmour_heal_stat = $db_connect->query_first("select deffense from db_shop_armour where armourname='$user[userarmour]'");
	$userarmour_heal = $userarmour_heal_stat[deffense];
	$userhelmet_heal_stat = $db_connect->query_first("select deffense from db_shop_helmet where helmetname='$user[userhelmet]'");
	$userhelmet_heal = $userhelmet_heal_stat[deffense];
	$usergloves_heal_stat = $db_connect->query_first("select deffense from db_shop_gloves where glovename='$user[usergloves]'");
	$usergloves_heal = $usergloves_heal_stat[deffense];
	$usershield_heal_stat = $db_connect->query_first("select deffense from db_shop_shields where shieldname='$user[usershield]'");
	$usershield_heal = $usershield_heal_stat[deffense];
	$totaldeffense=($userarmour_heal+$userboot_heal+$userhelmet_heal+$usergloves_heal+$usershield_heal);

      $moneyincrease2=($user2[usermoney]+100);
      $totalmoneyincrease2=($moneyincrease2);

      $expincrease2=($user2[userexp]+(100+$user[userlevel]));
      $totalexpincrease2=($expincrease2);

      $damage2=($userweapon_damage2+$user2[attack]-($totaldeffense));
      $fulldamage2=($user[userhitpoint]-$damage2);

      if($damage2 < 0) {$damage2 = 1;}
      if($fulldamage2 > $user2[hpmax]) {$fulldamage2 = $user2[hpmax];}

      $duringbattle='yes';

      $ride="arena.php ";

      $db_connect->query("UPDATE $myuserroom SET whosturn='myturn', messages='$challenger has attacked $me with a $user2[userweapon] and dealt $damage2!' WHERE id = '1'");
      $db_connect->query("UPDATE db_users SET userhitpoint='$fulldamage2' WHERE username = '$me'");
      $db_connect->query("UPDATE db_users SET usermoney='$totalmoneyincrease2', userexp='$expincrease2' WHERE username = '$user_name'");

      eval("dooutput(\"".gettemplate("attacktest2")."\");");
   }

   if($action == guard && $myturn == notmyturn){
      $userroom_stat2 = $db_connect->query_first("select userroom from db_users where username='$user_name'");
      $myuserroom = $userroom_stat2[userroom];
      $validname_stat2 = $db_connect->query_first("select me from $myuserroom where id='1'");
      $me = $validname_stat2[me];
      $challenger_stat = $db_connect->query_first("select challenger from $myuserroom where id='1'");
      $challenger = $challenger_stat[challenger];
      $user = $db_connect->query_first("select * from db_users where username='$me'");
      $user2 = $db_connect->query_first("select * from db_users where username='$challenger'");
      $maxhitpoint2_stat = $db_connect->query_first("select hpmax from db_users where username='$challenger'");
      $maxhitpoint2 = $maxhitpoint2_stat[hpmax];

	$userboot_heal_stat2 = $db_connect->query_first("select deffense from db_shop_boots where bootname='$user[userboots]'");
	$userboot_heal2 = $userboot_heal_stat2[deffense];
	$userweapon_damage_stat2 = $db_connect->query_first("select damage from db_shop_swords where swordname='$user[userweapon]'");
	$userweapon_damage2 = $userweapon_damage_stat2[damage];
	$userarmour_heal_stat2 = $db_connect->query_first("select deffense from db_shop_armour where armourname='$user[userarmour]'");
	$userarmour_heal2 = $userarmour_heal_stat2[deffense];
	$userhelmet_heal_stat2 = $db_connect->query_first("select deffense from db_shop_helmet where helmetname='$user[userhelmet]'");
	$userhelmet_heal2 = $userhelmet_heal_stat2[deffense];
	$usergloves_heal_stat2 = $db_connect->query_first("select deffense from db_shop_gloves where glovename='$user[usergloves]'");
	$usergloves_heal2 = $usergloves_heal_stat2[deffense];
	$usershield_heal_stat2 = $db_connect->query_first("select deffense from db_shop_shields where shieldname='$user[usershield]'");
	$usershield_heal2 = $usershield_heal_stat2[deffense];
	$totaldeffense2=($userarmour_heal2+$userboot_heal2+$userhelmet_heal2+$usergloves_heal2+$usershield_heal2);

      $moneyincrease2=($user2[usermoney]+120);
      $totalmoneyincrease2=($moneyincrease2);

      $expincrease2=($user2[userexp]+(100+$user[userlevel]));
      $totalexpincrease2=($expincrease2);

      $guardheal2=($user[userlevel]+($totaldeffense2/2));
      $fullguardheal2=($user2[userhitpoint]+$guardheal2);

            if($user2[userhitpoint] > $maxhitpoint2){
            $db_connect->query("UPDATE db_users SET userhitpoint2='$maxhitpoint2' WHERE username = '$user_name'");
            }

      $duringbattle='yes';

      $ride="arena.php ";

      $db_connect->query("UPDATE $myuserroom SET whosturn='myturn', messages='$challenger is guarding from $me.' WHERE id = '1'");
      $db_connect->query("UPDATE db_users SET userhitpoint='$fullguardheal2', usermoney='$totalmoneyincrease2', userexp='$totalexpincrease2' WHERE username = '$user_name'");

      eval("dooutput(\"".gettemplate("guardtest2")."\");");
   }

   if($action == useitem && $myturn == notmyturn){
      $userroom_stat2 = $db_connect->query_first("select userroom from db_users where username='$user_name'");
      $myuserroom = $userroom_stat2[userroom];
      $validname_stat2 = $db_connect->query_first("select me from $myuserroom where id='1'");
      $me = $validname_stat2[me];
      $challenger_stat = $db_connect->query_first("select challenger from $myuserroom where id='1'");
      $challenger = $challenger_stat[challenger];
      $useritem1 = $user2[useritem1];
      $useritem2 = $user2[useritem2];
      $useritem3 = $user2[useritem3];
      $useritem4 = $user2[useritem4];
      $useritem5 = $user2[useritem5];
      $useritem6 = $user2[useritem6];
      $useritem7 = $user2[useritem7];
      $useritem8 = $user2[useritem8];

      $expincrease2=($user2[userexp]+(100+$user2[userlevel]));
      $totalexpincrease2=($expincrease2);

      $duringbattle='yes';

      $db_connect->query("UPDATE db_users SET usermoney='$totalmoneyincrease2', userexp='$totalexpincrease2' WHERE username = '$user_name'");

         if($item == firstitem){
            if($useritem1 == 'Empty'){
            $error='You have no item in that slot!';
            header("LOCATION: arena.php?error=$error ");
	    exit;
            }
            $hpaddon_stat2 = $db_connect->query_first("select hpaddon from db_shop_smallitems where smallitemname='$useritem1'");
            $hpaddon2 = $hpaddon_stat2[hpaddon];
            $mpaddon_stat2 = $db_connect->query_first("select mpaddon from db_shop_smallitems where smallitemname='$useritem1'");
            $mpaddon2 = $mpaddon_stat2[mpaddon];
            $maxhitpoint2_stat = $db_connect->query_first("select hpmax from db_users where username='$challenger'");
            $maxhitpoint2 = $maxhitpoint2_stat[hpmax];

            $hitpointincrease2=($user2[userhitpoint]+$hpaddon);
            $magicpointincrease2=($user2[usermagicpoint]+$mpaddon);

            if($userhitpoint2 > $maxhitpoint2){
            $db_connect->query("UPDATE db_users SET userhitpoint2='$maxhitpoint2' WHERE username = '$myname'");
            }

            $db_connect->query("UPDATE $myuserroom SET whosturn='notmyturn', messages='$challenger used a $useritem1' WHERE id = '1'");
            $db_connect->query("UPDATE db_users SET userhitpoint='$hitpointincrease2', usermagicpoint='$magicpointincrease2', useritem1='Empty' WHERE username = '$user_name'");

            $ride="arena.php ";
            eval("dooutput(\"".gettemplate("itemtest2")."\");");
         }

         if($item == seconditem){
            if($useritem2 == 'Empty'){
            $error='You have no item in that slot!';
            header("LOCATION: arena.php?error=$error ");
	    exit;
            }
            $hpaddon_stat = $db_connect->query_first("select hpaddon from db_shop_smallitems where smallitemname='$useritem2'");
            $hpaddon = $hpaddon_stat[hpaddon];
            $mpaddon_stat = $db_connect->query_first("select mpaddon from db_shop_smallitems where smallitemname='$useritem2'");
            $mpaddon = $mpaddon_stat[mpaddon];
            $maxhitpoint2_stat = $db_connect->query_first("select hpmax from db_users where username='$challenger'");
            $maxhitpoint2 = $maxhitpoint2_stat[hpmax];

            $hitpointincrease2=($user2[userhitpoint]+$hpaddon);
            $magicpointincrease2=($user2[usermagicpoint]+$mpaddon);

            if($userhitpoint2 > $maxhitpoint2){
            $db_connect->query("UPDATE db_users SET userhitpoint2='$maxhitpoint2' WHERE username = '$myname'");
            }

            $db_connect->query("UPDATE $myuserroom SET whosturn='notmyturn', messages='$challenger used a $useritem2' WHERE id = '1'");
            $db_connect->query("UPDATE db_users SET userhitpoint='$hitpointincrease', usermagicpoint='$magicpointincrease', useritem2='Empty' WHERE username = '$user_name'");

            $ride="arena.php ";
            eval("dooutput(\"".gettemplate("itemtest")."\");");
         }

         if($item == thirditem){
            if($useritem3 == 'Empty'){
            $error='You have no item in that slot!';
            header("LOCATION: arena.php?error=$error ");
	    exit;
            }
            $hpaddon_stat = $db_connect->query_first("select hpaddon from db_shop_smallitems where smallitemname='$useritem3'");
            $hpaddon = $hpaddon_stat[hpaddon];
            $mpaddon_stat = $db_connect->query_first("select mpaddon from db_shop_smallitems where smallitemname='$useritem3'");
            $mpaddon = $mpaddon_stat[mpaddon];
            $maxhitpoint2_stat = $db_connect->query_first("select hpmax from db_users where username='$challenger'");
            $maxhitpoint2 = $maxhitpoint2_stat[hpmax];

            $hitpointincrease2=($user2[userhitpoint]+$hpaddon);
            $magicpointincrease2=($user2[usermagicpoint]+$mpaddon);

            if($userhitpoint2 > $maxhitpoint2){
            $db_connect->query("UPDATE db_users SET userhitpoint2='$maxhitpoint2' WHERE username = '$myname'");
            }

            $db_connect->query("UPDATE $myuserroom SET whosturn='notmyturn', messages='$challenger used a $useritem3' WHERE id = '1'");
            $db_connect->query("UPDATE db_users SET userhitpoint='$hitpointincrease', usermagicpoint='$magicpointincrease', useritem3='Empty' WHERE username = '$user_name'");

            $ride="arena.php ";
            eval("dooutput(\"".gettemplate("itemtest")."\");");
         }  

         if($item == fourthitem){
            if($useritem4 == 'Empty'){
            $error='You have no item in that slot!';
            header("LOCATION: arena.php?error=$error ");
	    exit;
            }
            $hpaddon_stat = $db_connect->query_first("select hpaddon from db_shop_smallitems where smallitemname='$useritem4'");
            $hpaddon = $hpaddon_stat[hpaddon];
            $mpaddon_stat = $db_connect->query_first("select mpaddon from db_shop_smallitems where smallitemname='$useritem4'");
            $mpaddon = $mpaddon_stat[mpaddon];
            $maxhitpoint2_stat = $db_connect->query_first("select hpmax from db_users where username='$challenger'");
            $maxhitpoint2 = $maxhitpoint2_stat[hpmax];

            $hitpointincrease2=($user2[userhitpoint]+$hpaddon);
            $magicpointincrease2=($user2[usermagicpoint]+$mpaddon);

            if($userhitpoint2 > $maxhitpoint2){
            $db_connect->query("UPDATE db_users SET userhitpoint2='$maxhitpoint2' WHERE username = '$myname'");
            }

            $db_connect->query("UPDATE $myuserroom SET whosturn='notmyturn', messages='$challenger used a $useritem4' WHERE id = '1'");
            $db_connect->query("UPDATE db_users SET userhitpoint='$hitpointincrease', usermagicpoint='$magicpointincrease', useritem4='Empty' WHERE username = '$user_name'");

            $ride="arena.php ";
            eval("dooutput(\"".gettemplate("itemtest")."\");");
         }  

         if($item == fifthitem){
            if($useritem5 == 'Empty'){
            $error='You have no item in that slot!';
            header("LOCATION: arena.php?error=$error ");
	    exit;
            }
            $hpaddon_stat = $db_connect->query_first("select hpaddon from db_shop_smallitems where smallitemname='$useritem5'");
            $hpaddon = $hpaddon_stat[hpaddon];
            $mpaddon_stat = $db_connect->query_first("select mpaddon from db_shop_smallitems where smallitemname='$useritem5'");
            $mpaddon = $mpaddon_stat[mpaddon];
            $maxhitpoint2_stat = $db_connect->query_first("select hpmax from db_users where username='$challenger'");
            $maxhitpoint2 = $maxhitpoint2_stat[hpmax];

            $hitpointincrease2=($user2[userhitpoint]+$hpaddon);
            $magicpointincrease2=($user2[usermagicpoint]+$mpaddon);

            if($userhitpoint2 > $maxhitpoint2){
            $db_connect->query("UPDATE db_users SET userhitpoint2='$maxhitpoint2' WHERE username = '$myname'");
            }

            $db_connect->query("UPDATE $myuserroom SET whosturn='notmyturn', messages='$challenger used a $useritem5' WHERE id = '1'");
            $db_connect->query("UPDATE db_users SET userhitpoint='$hitpointincrease', usermagicpoint='$magicpointincrease', useritem5='Empty' WHERE username = '$user_name'");

            $ride="arena.php ";
            eval("dooutput(\"".gettemplate("itemtest")."\");");
         }  

         if($item == sixthitem){
            if($useritem6 == 'Empty'){
            $error='You have no item in that slot!';
            header("LOCATION: arena.php?error=$error ");
	    exit;
            }
            $hpaddon_stat = $db_connect->query_first("select hpaddon from db_shop_smallitems where smallitemname='$useritem6'");
            $hpaddon = $hpaddon_stat[hpaddon];
            $mpaddon_stat = $db_connect->query_first("select mpaddon from db_shop_smallitems where smallitemname='$useritem6'");
            $mpaddon = $mpaddon_stat[mpaddon];
            $maxhitpoint2_stat = $db_connect->query_first("select hpmax from db_users where username='$challenger'");
            $maxhitpoint2 = $maxhitpoint2_stat[hpmax];

            $hitpointincrease2=($user2[userhitpoint]+$hpaddon);
            $magicpointincrease2=($user2[usermagicpoint]+$mpaddon);

            if($userhitpoint2 > $maxhitpoint2){
            $db_connect->query("UPDATE db_users SET userhitpoint2='$maxhitpoint2' WHERE username = '$myname'");
            }

            $db_connect->query("UPDATE $myuserroom SET whosturn='notmyturn', messages='$challenger used a $useritem6' WHERE id = '1'");
            $db_connect->query("UPDATE db_users SET userhitpoint='$hitpointincrease', usermagicpoint='$magicpointincrease', useritem6='Empty' WHERE username = '$user_name'");

            $ride="arena.php ";
            eval("dooutput(\"".gettemplate("itemtest")."\");");
         }  

         if($item == seventhitem){
            if($useritem7 == 'Empty'){
            $error='You have no item in that slot!';
            header("LOCATION: arena.php?error=$error ");
	    exit;
            }
            $hpaddon_stat = $db_connect->query_first("select hpaddon from db_shop_smallitems where smallitemname='$useritem7'");
            $hpaddon = $hpaddon_stat[hpaddon];
            $mpaddon_stat = $db_connect->query_first("select mpaddon from db_shop_smallitems where smallitemname='$useritem7'");
            $mpaddon = $mpaddon_stat[mpaddon];
            $maxhitpoint2_stat = $db_connect->query_first("select hpmax from db_users where username='$challenger'");
            $maxhitpoint2 = $maxhitpoint2_stat[hpmax];

            $hitpointincrease2=($user2[userhitpoint]+$hpaddon);
            $magicpointincrease2=($user2[usermagicpoint]+$mpaddon);

            if($userhitpoint2 > $maxhitpoint2){
            $db_connect->query("UPDATE db_users SET userhitpoint2='$maxhitpoint2' WHERE username = '$myname'");
            }

            $db_connect->query("UPDATE $myuserroom SET whosturn='notmyturn', messages='$challenger used a $useritem7' WHERE id = '1'");
            $db_connect->query("UPDATE db_users SET userhitpoint='$hitpointincrease', usermagicpoint='$magicpointincrease', useritem7='Empty' WHERE username = '$user_name'");

            $ride="arena.php ";
            eval("dooutput(\"".gettemplate("itemtest")."\");");
         }  

         if($item == eigthitem){
            if($useritem8 == 'Empty'){
            $error='You have no item in that slot!';
            header("LOCATION: arena.php?error=$error ");
	    exit;
            }
            $hpaddon_stat = $db_connect->query_first("select hpaddon from db_shop_smallitems where smallitemname='$useritem8'");
            $hpaddon = $hpaddon_stat[hpaddon];
            $mpaddon_stat = $db_connect->query_first("select mpaddon from db_shop_smallitems where smallitemname='$useritem8'");
            $mpaddon = $mpaddon_stat[mpaddon];
            $maxhitpoint2_stat = $db_connect->query_first("select hpmax from db_users where username='$challenger'");
            $maxhitpoint2 = $maxhitpoint2_stat[hpmax];

            $hitpointincrease2=($user2[userhitpoint]+$hpaddon);
            $magicpointincrease2=($user2[usermagicpoint]+$mpaddon);

            if($userhitpoint2 > $maxhitpoint2){
            $db_connect->query("UPDATE db_users SET userhitpoint2='$maxhitpoint2' WHERE username = '$myname'");
            }

            $db_connect->query("UPDATE $myuserroom SET whosturn='notmyturn', messages='$challenger used a $useritem8' WHERE id = '1'");
            $db_connect->query("UPDATE db_users SET userhitpoint='$hitpointincrease', usermagicpoint='$magicpointincrease', useritem8='Empty' WHERE username = '$user_name'");

            $ride="arena.php ";
            eval("dooutput(\"".gettemplate("itemtest")."\");");
         }  
      
   }
}
?>