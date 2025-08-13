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


//--
function CheckFirstRegData(){

/*
Checks the first data posted by the user
in the registration process

Return codes:   000 = data ok!
										002 = name missing
										003 = nick missing
										004 = password missing
										005 = second password missing
										006 = passwords do not match
										007 = email address missing
										008 = email address not valid
										009 = nick already exists
										010 = nick too short
										011 = password too short
*/

		global $name,$nick,$password,$repeat_password,$email;

		if(!$name){
				return "002";
		}

		if(!$nick){
				return "003";
		}

		if(!$password){
				return "004";
		}

		if(!$repeat_password){
				return "005";
		}

		if($password != $repeat_password){
				return "006";
		}

		if(!$email){
				return "007";
		}

		if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$",$email)){
				return "008";
		}

		if(strlen($nick) < 6){
				return "010";
		}

		if(strlen($password) < 6){
				return "011";
		}
		
		$query = "select nick from users where nick=\"$nick\"";
		$result = mysql_query($query);
		if(mysql_num_rows($result)){
				return "009";
		}
		
		return "000";

} //CheckFirstRegData()
//--




//--
function CheckOtherRegData(){

/*
return codes:   012 = address missing
										013 = city missing
										014 = country missing
										015 = zip code not valid
										016 = zip code missing

*/

		global $address,$city,$country,$zip;
		
		if(!$address){
				return "012";
		}

		if(!$city){
				return "013";
		}

		if(!$country){
				return "014";
		}

/*		  if(!$zip){
				return "015";
		}*/

		if(strlen($zip)==0){ //-- 5-digits ZIP codes
				return "016";
		}
		
} //CheckOtherRegData()
//--


//--
function CheckSellData(){

/*
return codes:                           015 = zip code missing
										016 = zip code not valid
										017 = item title missing
										018 = item description missing
										019 = minimum bid missing
										020 = minimum bid not valid
										021 = reserve price missing
										022 = reserve price not valid
										023 = category missing
										024 = payment method missing
										025 = payment method missing

										600 = wrong auction type
										601 = wrong quantity of items
										

*/

		global $title, $description, $minimum_bid, $with_reserve, $reserve_price, $payment, $location_zip, $category;
		global $atype, $iquantity, $increments, $customincrement;
		global $payments,$auction_types;
		
		if(empty($title))
		{
				return "017";
		}

		if(empty($description))
		{
				return "018";
		}
		
		if(empty($minimum_bid))
		{
				return "019";
		}
		
		if(!CheckMoney($minimum_bid))
		{
			return "058";
		}
		else
		{
			$minimum_bid = input_money($minimum_bid);
		}
		
		
		if(!$reserve_price && $with_reserve == 'yes')
		{
				return "021";
		}
		
		if($increments == 2 && (empty($customincrement) || doubleval($customincrement) == 0))
		{
			return "056";
		}
		if(!ereg("^([0-9])*|(\.[0-9]{1,2})?$",$customincrement))
		{
			return "057";
		}
		
		if( $with_reserve == 'yes' && !ereg("^([0-9])*|(\.[0-9]{1,2})?$",input_money($reserve_price)) ){
				return "022";
		} else {
			$reserve_price = input_money($reserve_price);
		}

		if ( strlen($location_zip)==0 )
			return "016";


		$numpay = count($payment);
		if($numpay == 0) {
			return "024";
		} else {
			$payment_ok = 1;
		}

/*
		for(reset($payment);list($key,$val)=each($payment);){
				if($GLOBALS["payment".$key]){
						$payment_ok = 1;
				}
		}
		if(!$payment_ok){
				return "024";
		}
*/

		if ( !isset($auction_types[intval($atype)]) )
		{
			return "600";
		}

		if ( intval($iquantity)<1 )
		{
			return "601";
		}

}//--CheckSellData

?>
