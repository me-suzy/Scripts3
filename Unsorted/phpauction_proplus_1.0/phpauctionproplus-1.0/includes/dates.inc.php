<?#//v.1.0.0
  
#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////


Function FormatDate($DATE)
{
	GLOBAL $SETTINGS;
	
	if($SETTINGS[datesformat] == "USA")
	{
		$F_date = substr($DATE,4,2)."/".		
				  substr($DATE,6,2)."/".
				  substr($DATE,0,4);
	}
	else
	{
		$F_date = substr($DATE,6,2)."/".		
				  substr($DATE,4,2)."/".
				  substr($DATE,0,4);
	}
	
	return $F_date;
}


	
//-- Date and time hanling functions

if(!function_exists(ActualDate))
{

	
	function ActualDate()
	{
	
		GLOBAL $SETTINGS;
		
		$month = date("m");
		switch($month)
		{
			case "01":
				$month = "Jan.&nbsp;";
				break;
			case "02":
				$month = "Feb.&nbsp;";
				break;
			case "03":
				$month = "Mar.&nbsp;";
				break;
			case "04":
				$month = "Apr.&nbsp;";
				break;
			case "05":
				$month = "May&nbsp;";
				break;
			case "06":
				$month = "Jun.&nbsp;";
				break;
			case "07":
				$month = "Jul.&nbsp;";
				break;
			case "08":
				$month = "Aug.&nbsp;";
				break;
			case "09":
				$month = "Sep.&nbsp;";
				break;
			case "10":
				$month = "Oct.&nbsp;";
				break;
			case "11":
				$month = "Nov.&nbsp;";
				break;
			case "12":
				$month = "Dec.&nbsp;";
				break;
		}
		
		$date = mktime(date("H")+$SETTINGS[timecorrection],date("i"),date("s"),date("m"),date("d"),date("Y"));
		
		
		//$year = date(" Y, H:i:s");

		return $month."&nbsp;".date("d, Y H:i:s", $date);

	} 

}



if(!function_exists(ArrangeDate))
{

	function ArrangeDate($day,$month,$year,$hours,$minutes)
	{
	
		GLOBAL $SETTINGS;
		
		switch($month){
			case "01":
				$M = "Jan.&nbsp;";
				break;
			case "02":
				$M = "Feb.&nbsp;";
				break;
			case "03":
				$M = "Mar.&nbsp;";
				break;
			case "04":
				$M = "Apr.&nbsp;";
				break;
			case "05":
				$M = "May.&nbsp;";
				break;
			case "06":
				$M = "Jun.&nbsp;";
				break;
			case "07":
				$M = "Jul.&nbsp;";
				break;
			case "08":
				$M = "Aug.&nbsp;";
				break;
			case "09":
				$M = "Sep.&nbsp;";
				break;
			case "10":
				$M = "Oct.&nbsp;";
				break;
			case "11":
				$M = "Nov.&nbsp;";
				break;
			case "12":
				$M = "Dec.&nbsp;";
				break;
		}
		
		$DATE = mktime($hours+$SETTINGS[timecorrection],$minutes,0,$month,$day,$year);
		
		$return = $M."&nbsp;".date("d, Y - H:i",$DATE);
		if($hours != 0 && $minutes != 0)
		{
			$return = $M."&nbsp;".date("d, Y - H:i",$DATE);
		}
		else
		{
			$return = $M."&nbsp;".date("d, Y",$DATE);
		}
		
		
		return $return;

	}

} 


if(!function_exists(ArrangeDateMesCompleto))
{

	function ArrangeDateMesCompleto($day,$month,$year,$hours,$minutes)
	{
		
		GLOBAL $SETTINGS;
		
		switch($month){
			case "01":
				$month = "January&nbsp;";
				break;
			case "02":
				$month = "February&nbsp;";
				break;
			case "03":
				$month = "March&nbsp;";
				break;
			case "04":
				$month = "April&nbsp;";
				break;
			case "05":
				$month = "May&nbsp;";
				break;
			case "06":
				$month = "June&nbsp;";
				break;
			case "07":
				$month = "July&nbsp;";
				break;
			case "08":
				$month = "August&nbsp;";
				break;
			case "09":
				$month = "September&nbsp;";
				break;
			case "10":
				$month = "October&nbsp;";
				break;
			case "11":
				$month = "November&nbsp;";
				break;
			case "12":
				$month = "December&nbsp;";
				break;
		}
		
		$DATE = mktime($hours+$SETTINGS[timecorrection],$minutes,0,$month,$day,$year);
		
		$return = $month."&nbsp;".date("d, Y - H:i",$DATE);
		if($hours && $minutes)
		{
			$return = $month."&nbsp;".date("d, Y - H:i",$DATE);
		}
		else
		{
			$return = $month."&nbsp;".date("d, Y",$DATE);
		}
		
		return $return;

	}

} 




?>
