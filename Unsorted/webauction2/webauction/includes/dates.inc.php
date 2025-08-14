<?
/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/
	
	//-- Date and time hanling functions
	
	function ActualDate(){
		
		$month = date("m");
		switch($month){
			case "01":
				$month = "Januar";
				break;
			case "02":
				$month = "Februar";
				break;
			case "03":
				$month = "März";
				break;
			case "04":
				$month = "April";
				break;
			case "05":
				$month = "Mai";
				break;
			case "06":
				$month = "Juni";
				break;
			case "07":
				$month = "Juli";
				break;
			case "08":
				$month = "August";
				break;
			case "09":
				$month = "September";
				break;
			case "10":
				$month = "Oktober";
				break;
			case "11":
				$month = "November";
				break;
			case "12":
				$month = "Dezember";
				break;
		}
		
		$day = date("d. ");
		$year = date(" Y, H:i:s");

		return $day.$month.$year;

	} 

	function ArrangeDate($day,$month,$year,$hours,$minutes){
		
		switch($month){
			case "01":
				$month = "Jan";
				break;
			case "02":
				$month = "Feb";
				break;
			case "03":
				$month = "Mär";
				break;
			case "04":
				$month = "Apr";
				break;
			case "05":
				$month = "Mai";
				break;
			case "06":
				$month = "Jun";
				break;
			case "07":
				$month = "Jul";
				break;
			case "08":
				$month = "Aug";
				break;
			case "09":
				$month = "Sep";
				break;
			case "10":
				$month = "Okt";
				break;
			case "11":
				$month = "Nov";
				break;
			case "12":
				$month = "Dez";
				break;
		}
		
		$return = $day.". ".$month." ".$year;
		if($hours && $minutes){
			$return .= ", ".$hours.":".$minutes;
		}
		
		return $return;

} 


	function ArrangeDateMesCompleto($day,$month,$year,$hours,$minutes){
		
		switch($month){
			case "01":
				$month = "Januar";
				break;
			case "02":
				$month = "Februar";
				break;
			case "03":
				$month = "März";
				break;
			case "04":
				$month = "April";
				break;
			case "05":
				$month = "Mai";
				break;
			case "06":
				$month = "Juni";
				break;
			case "07":
				$month = "Juli";
				break;
			case "08":
				$month = "August";
				break;
			case "09":
				$month = "September";
				break;
			case "10":
				$month = "Oktober";
				break;
			case "11":
				$month = "November";
				break;
			case "12":
				$month = "Dezember";
				break;
		}
		
		$return = $day.". ".$month." ".$year;
		if($hours && $minutes){
			$return .= ", ".$hours.":".$minutes;
		}
		
		return $return;

} 




?>
