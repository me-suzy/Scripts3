<?
#################################################################################################
#
#  project           	: phpBazar
#  filename          	: classified_search_submit.php
#  last modified by  	: Erich Fuchs
#  supplied by          : Reseting
#  nullified by	        : CyKuH [WTN]
#  purpose           	: Submit Search Data
#
#################################################################################################



#  Include Configs & Variables

#################################################################################################

require ("library.php");



if (empty($search_sort)) $search_sort="adeditdate DESC";



if ($in[searchmode]=="simple") {



    if ($in[adid]) {



	mysql_connect($server, $db_user, $db_pass) or died("Database NOT connected");



        $result = mysql_db_query($database, "SELECT * FROM ads WHERE id=$in[adid]");

        if ($result) {

	    $db = mysql_fetch_array($result);

	    $subcatid=$db[subcatid];

	    $catid=$db[catid];

        }



	mysql_close();



        if ($db && $catid && $subcatid) {

    	    header("Location: classified.php?catid=$catid&subcatid=$subcatid&adid=$in[adid]");

	    exit;

        }

    }



    $error=rawurlencode($error[30]);

    header("Location: classified.php?choice=search&catid=$in[catid]&subcatid=$in[subcatid]&status=6&errormessage=$error");

    exit;



} elseif ($in[searchmode]=="advanced"){



    if ($in[location] || $in[text] || $in[picture] || $in[attachment]|| $in[sfield] ||

         $in[field1] || $in[field2] || $in[field3] || $in[field4] || $in[field5] || $in[field6] || $in[field7] || $in[field8] || $in[field9] || $in[field10] ||

         $in[field11] || $in[field12] || $in[field13] || $in[field14] || $in[field15] || $in[field16] || $in[field17] || $in[field18] || $in[field19] || $in[field20] ||

         $in[icon1] || $in[icon2] || $in[icon3] || $in[icon4] || $in[icon5] || $in[icon6] || $in[icon7] || $in[icon8] || $in[icon9] || $in[icon10]

	 ) {



	mysql_connect($server, $db_user, $db_pass) or died("Database NOT connected");





	$sqlquerystr=" WHERE 1=1";



	if ($in[location]) {

	    $sqlquerystr.=" AND location='$in[location]'";

	}

	if ($in[catid]) {

	    $sqlquerystr.=" AND catid='$in[catid]'";

	}

	if ($in[subcatid]) {

	    $sqlquerystr.=" AND subcatid='$in[subcatid]'";

	}

	if ($in[picture]) {

	    $sqlquerystr.=" AND (picture!='' OR picture2!='' OR picture3!='')";

	}

	if ($in[attachment]) {

	    $sqlquerystr.=" AND (attachment1!='' OR attachment2!='' OR attachment3!='')";

	}

	if ($adapproval) {

	    $sqlquerystr.=" AND publicview='1'";

	}

	if ($in[sfield]) {

	    $sqlquerystr.=" AND sfield='$in[sfield]'";

	}



	for ($i=1;$i<=20;$i++) {

	    if ($in["field".$i]) {

		if ($in2["field".$i]) {

		    $tmp=$in["field".$i];

		    $tmp2=$in2["field".$i];

		    $sqlquerystr.=" AND field$i >= '$tmp' AND field$i <= '$tmp2'";

		} else {

		    $tmp=$in["field".$i];

		    $sqlquerystr.=" AND field$i='$tmp'";

		}

	    }

	}



	for ($i=1;$i<=10;$i++) {

	    if ($in["icon".$i]) {

		$sqlquerystr.=" AND icon$i='1'";

	    }

	}



	$sqlquerystr.=" AND deleted!='1'";



	if ($in[text] && $in[text]!="*") {

	    $keywords = ereg_replace(" ",",",$in[text]);

	    $text = explode(",",$keywords);

	    for ($i=0;$i<count($text);$i++) {

	      if ($text[$i]) {

		$sqlquerystr2=" AND (header LIKE '%".$text[$i]."%' OR text LIKE '%".$text[$i]."%' OR sfield LIKE '%"

		.$text[$i]."%' OR field1 LIKE '%".$text[$i]."%' OR field2 LIKE '%".$text[$i]."%' OR field3 LIKE '%"

		.$text[$i]."%' OR field4 LIKE '%".$text[$i]."%' OR field5 LIKE '%".$text[$i]."%' OR field6 LIKE '%"

		.$text[$i]."%' OR field7 LIKE '%".$text[$i]."%' OR field8 LIKE '%".$text[$i]."%' OR field9 LIKE '%"

		.$text[$i]."%' OR field10 LIKE '%".$text[$i]."%' OR field11 LIKE '%".$text[$i]."%' OR field12 LIKE '%"

		.$text[$i]."%' OR field13 LIKE '%".$text[$i]."%' OR field14 LIKE '%".$text[$i]."%' OR field15 LIKE '%"

		.$text[$i]."%' OR field16 LIKE '%".$text[$i]."%' OR field17 LIKE '%".$text[$i]."%' OR field18 LIKE '%"

		.$text[$i]."%' OR field19 LIKE '%".$text[$i]."%' OR field20 LIKE '%".$text[$i]."%')";

    	      }

	    }

	}



	if ($in[search_sort] && $in[search_sort2]) {

	    $sqlquerystr3=" ORDER BY $in[search_sort] $in[search_sort2]";

	} else {

	    $sqlquerystr3=" ORDER BY $search_sort";

	}



	// only for testing purposes :-)

	#echo $sqlquerystr.$sqlquerystr2.$sqlquerystr3;

	#exit;



	$showresult=0;

	$sqlquery="SELECT * FROM ads".$sqlquerystr.$sqlquerystr2.$sqlquerystr3;

        $result = mysql_db_query($database, "$sqlquery") or died(mysql_error());

	$db = mysql_fetch_array($result);

	mysql_close();



	if ($db) {

	    $sqlquerystr=rawurlencode($sqlquerystr);

	    $sqlquerystr2=rawurlencode($in[text]);

	    $sqlquerystr3=rawurlencode($sqlquerystr3);

	    header("Location: classified.php?sqlquery=$sqlquerystr&sqlquery2=$sqlquerystr2&sqlquery3=$sqlquerystr3");

	    exit;

	}



    }

    $error=rawurlencode($error[30]);

    header("Location: classified.php?choice=search&catid=$in[catid]&subcatid=$in[subcatid]&status=6&errormessage=$error");

    exit;



} else {

    died ("FATAL ERROR");

}





?>