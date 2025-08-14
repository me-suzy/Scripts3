<?php
//***************************************************************************//
//                                                                           //
//  Program Name    	: vCard PRO                                          //
//  Program Version     : 2.6                                                //
//  Program Author      : Joao Kikuchi,  Belchior Foundry                    //
//  Home Page           : http://www.belchiorfoundry.com                     //
//  Retail Price        : $80.00 United States Dollars                       //
//  WebForum Price      : $00.00 Always 100% Free                            //
//  Supplied by         : South [WTN]                                        //
//  Nullified By        : CyKuH [WTN]                                        //
//  Distribution        : via WebForum, ForumRU and associated file dumps    //
//                                                                           //
//                (C) Copyright 2001-2002 Belchior Foundry                   //
//***************************************************************************//
define('IN_VCARD', true);
$templatesused = 'rating_ok,rating_error';
require('./lib.inc.php');

$user_rate = ($HTTP_POST_VARS['rating']) ? $HTTP_POST_VARS['rating'] : $HTTP_GET_VARS['rating'] ;
$card_id = ($HTTP_POST_VARS['card_id']) ? $HTTP_POST_VARS['card_id'] : $HTTP_GET_VARS['card_id'] ;
$user_rate = (int) addslashes($user_rate);
$card_id = (int) addslashes($card_id);
$user_score = $user_rate;

$vote_array = $DB_site->query_first(" SELECT rating_ip,rating_card FROM vcard_rating WHERE rating_ip='$session[ip]' AND rating_card='$card_id' ");

if(!empty($card_id))
{
	$card_info = $DB_site->query_first("SELECT * FROM vcard_cards WHERE card_id='$card_id' ");
	$card_caption = $card_info['card_caption'];
}else{
	eval("make_output(\"".get_template("rating_error")."\");");
}

if(!empty($vote_array))
{
	eval("make_output(\"".get_template("rating_error")."\");");
}else{
	if(!empty($card_id))
	{
		$DB_site->query(" INSERT INTO vcard_rating (rating_id,rating_card,rating_value,rating_ip,rating_date) VALUES (NULL, '$card_id', '$user_rate','$session[ip]', CURDATE()) ");
		$result = $DB_site->query("SELECT * FROM vcard_rating WHERE rating_card='$card_id' ");
		while ($rating_values = $DB_site->fetch_array($result))
		{
			$values += $rating_values['rating_value'];
			$entries++;
		}
		$DB_site->free_result($result);
		$DB_site->query("UPDATE vcard_cards SET card_rating='".($values/$entries)."' WHERE card_id='$card_id' ");
		eval("make_output(\"".get_template("rating_ok")."\");");
	}
}
if($debug==1){ $timer->end_time(); $timer->elapsed_time();}
$DB_site->close();
if($use_gzip == 1) {ob_end_flush(); }
?>