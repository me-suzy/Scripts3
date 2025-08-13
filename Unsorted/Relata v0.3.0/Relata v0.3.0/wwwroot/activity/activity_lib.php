<?

//******************************************************************************
/*
GPL Copyright Notice

Relata
Copyright (C) 2001-2002 Stratabase, Inc.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/
//******************************************************************************

// take 2001-4-5 and convert it to 2001-04-05
function addleadingzeros($date)
{
	// make the day and month have leading zeros
	$bdate = explode("-",$date);
	
	$year = $bdate[0];
	$month = $bdate[1];
	$day = $bdate[2];
	
	// it is a one digit month.. add a leading zero
	if(strlen($month) == 1)
	{
		$month = "0" . $month;
	}
	// the day is one digit... add a leading zero
	if(strlen($day) == 1)
	{
		$day = "0" . $day;
	}
	
	$date = $year . "-" . $month . "-" . $day;
	
	return $date;
}

// take 2001-04-05 and convert it to 2001-4-5
function removeleadingzeros($date)
{
	// make the day and month have leading zeros
	$bdate = explode("-",$date);
	
	$year = $bdate[0];
	$month = $bdate[1];
	$day = $bdate[2];
	
	// it is not a one digit month.. check for a leading zero
	if(strlen($month) != 1)
	{
		// if the first character is a 0.. just save the 2nd
		if($month[0] == "0")
		{
			$month = $month[1];
		}
	}
	// the day is one digit... add a leading zero
	if(strlen($day) != 1)
	{
		// if the first character is a 0.. just save the 2nd
		if($day[0] == "0")
		{
			$day = $day[1];
		}
	}
	
	$date = $year . "-" . $month . "-" . $day;
	
	return $date;
}

?>