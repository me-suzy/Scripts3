<?
  
/*

   Copyright (c), 1999, 2000 - phpauction.org                  
   
   This program is free software; you can redistribute it and/or modify 
   it under the terms of the GNU General Public License as published by 
   the Free Software Foundation (version 2 or later).                                  
                                                                        
   This program is distributed in the hope that it will be useful,      
   but WITHOUT ANY WARRANTY; without even the implied warranty of       
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        
   GNU General Public License for more details.                         
                                                                        
   You should have received a copy of the GNU General Public License    
   along with this program; if not, write to the Free Software          
   Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
   
*/
	
	//-- Date and time hanling functions

if(!function_exists(ActualDate)) {

	
	function ActualDate(){
		
		$month = date("m");
		switch($month){
			case "01":
				$month = "Jan.";
				break;
			case "02":
				$month = "Feb.";
				break;
			case "03":
				$month = "Mar.";
				break;
			case "04":
				$month = "Apr.";
				break;
			case "05":
				$month = "May";
				break;
			case "06":
				$month = "Jun.";
				break;
			case "07":
				$month = "Jul.";
				break;
			case "08":
				$month = "Aug.";
				break;
			case "09":
				$month = "Sep.";
				break;
			case "10":
				$month = "Oct.";
				break;
			case "11":
				$month = "Nov.";
				break;
			case "12":
				$month = "Dec.";
				break;
		}
		
		$day = date("d ");
		$year = date(" Y, H:i:s");

		return $month.$day.$year;

	} 

}



if(!function_exists(ArrangeDate)) {

	function ArrangeDate($day,$month,$year,$hours,$minutes){
		
		switch($month){
			case "01":
				$month = "Jan.";
				break;
			case "02":
				$month = "Feb.";
				break;
			case "03":
				$month = "Mar.";
				break;
			case "04":
				$month = "Apr.";
				break;
			case "05":
				$month = "May.";
				break;
			case "06":
				$month = "Jun.";
				break;
			case "07":
				$month = "Jul.";
				break;
			case "08":
				$month = "Aug.";
				break;
			case "09":
				$month = "Sep.";
				break;
			case "10":
				$month = "Oct.";
				break;
			case "11":
				$month = "Nov.";
				break;
			case "12":
				$month = "Dec.";
				break;
		}
		
		$return = $month." ".$day." ".$year;
		if($hours && $minutes){
			$return .= ", ".$hours.":".$minutes;
		}
		
		return $return;

	}

} 


if(!function_exists(ArrangeDateMesCompleto)) {

	function ArrangeDateMesCompleto($day,$month,$year,$hours,$minutes){
		
		switch($month){
			case "01":
				$month = "January";
				break;
			case "02":
				$month = "February";
				break;
			case "03":
				$month = "March";
				break;
			case "04":
				$month = "April";
				break;
			case "05":
				$month = "May";
				break;
			case "06":
				$month = "June";
				break;
			case "07":
				$month = "July";
				break;
			case "08":
				$month = "August";
				break;
			case "09":
				$month = "September";
				break;
			case "10":
				$month = "October";
				break;
			case "11":
				$month = "November";
				break;
			case "12":
				$month = "December";
				break;
		}
		
		$return = $month." ".$day." ".$year;
		if($hours && $minutes){
			$return .= ", ".$hours.":".$minutes;
		}
		
		return $return;

	}

} 




?>
