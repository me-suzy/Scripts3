<?
function stats() {
	$dl = new TheDB();
	$dl->connect() or die($dl->getError());
	$dl->debug=false;

	$stats = $dl->select("COUNT(*) AS stco","sl18_stories");
	print $stats[0]["stco"];	
}

function online() {
	$time = date("Y-m-d H:i:s");
	$diff = date("Y-m-d H:i:s",time()-600);

	$dl = new TheDB();
	$dl->connect() or die($dl->getError());
	$dl->debug=false;

	$online = $dl->select("*","sl18_users", "uactive>='$diff'","ORDER BY upenname ASC");

	foreach($online as $is) {
		$ons[] =  "<a href='authors.php?no=".$is["uid"]."'>".$is["upenname"]."</a>";
	}
	if(!empty($ons))
		print implode(", ", $ons);
}

function authors() {
	$dl = new TheDB();
	$dl->connect() or die($dl->getError());
	$dl->debug=false;

	$auth = $dl->select("COUNT(*) AS auth","sl18_users");
	print $auth[0]["auth"];
}


function rating($var) {
	if ( $var == 1 )
		$varb = "U";
	if ( $var == 2 )
		$varb = "PG";
	if ( $var == 3 )
		$varb = "15";
	if ( $var == 4 )
		$varb = "18";
	if ( $var == 5 )
		$varb = "NC-17";
	return $varb;
}

function addrating($name, $no) {
	if( !$_POST["ratesub"] ) {
		print "<form action='story.php?no=".$no."' method='post'>\n";
		print "<select name='vote'>\n";
		print "<option value='5'> +  +  +  +  + </option>\n";
		print "<option value='4'> + + + + </option>\n";
		print "<option value='3'> + + + </option>\n";
		print "<option value='2'> + + </option>\n";
		print "<option value='1'> + </option>\n";
		print "</select>\n";
		print "<input type='submit' value='rate' name='ratesub'>\n";
		print "</form>\n";
	} else {
		$dl = new TheDB();
		$dl->connect() or die($dl->getError());
		$dl->debug=false;

		$table = $dl->select("*","sl18_rate",array('ratsid'=>$no));	
		$num = count($table);

		if(!$num) {
			$ratnovote = 1;	
			$dl->insert("sl18_rate",array('rattotvote'=>$_POST["vote"],'ratnovote'=>$ratnovote,'ratsid'=>$no)) or die($dl->getError());
		} else {
			$rattotvote = $table[0][rattotvote] + $_POST["vote"];
			$ratnovote = $table[0][ratnovote] + 1;
			$dl->update("sl18_rate",array('rattotvote'=>$rattotvote,'ratnovote'=>$ratnovote,'ratsid'=>$no),array('ratsid'=>$no)) or die($dl->getError());
		}
		print "You have rated " . $name;
	}
}

function showrate($no) {
	if ( $no == 0 )
		print " - ";
	else {
		for($i=0 ; $i<$no ; $i++) {
			print "<img src='" . SL_ROOT_URL . "/base/html/Default/images/pip.gif'>";
		}
	}
}

function news() {
		print "<table border='0' width='100%' class='cleardis'>\n";
		print "<tr class='heavydis'>\n";
		print "<td><b>" . SL_TITLE . " News</b></td>\n";
		print "</tr>\n";

		$dl = new TheDB();
		$dl->connect() or die($dl->getError());
		$dl->debug=false;

		$table = $dl->select("*","sl18_news","","ORDER BY nid DESC LIMIT 0,5");
	
		if( count( $table ) > 0 ) {
			foreach($table as $row) {
				print "<tr class='catdis'>\n";
				print "<td><u>" . stripslashes($row["nname"]) . "</u> ";
				print "<font class='small'>Posted: " . $row["ndate"] . "</font></td>\n";
				print "<tr></tr>\n";
				print "<td class='small'>" . nl2br(stripslashes($row["nbody"])) . " [id# " . $row["nid"]  . "]</td>\n";
				print "</tr>\n";
			}
		} else {
			print "<tr>\n";
			print "<td> No news </td>\n";
			print "</tr>\n";
		}	
		
		print "</table>";
}