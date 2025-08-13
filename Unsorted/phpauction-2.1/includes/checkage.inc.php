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
//-- CheckAge function checks the age of a user
//-- Returns: 0 if younger than 18
//--          1 if older than 18


if(!function_exists(CheckAge)) {

Function CheckAge($day,$month,$year){

        $NOW_year         = date("Y");
        $NOW_month  = date("m");
        $NOW_day         = date("d");

        if(($NOW_year - $year) > 18)
        {
                return 1;
        }
        else if((($NOW_year - $year) == 18) && ($NOW_month > $month))
        {
                return 1;
        }
        else if((($NOW_year - $year) == 18) && ($NOW_month == $month) && ($NOW_day >= $day))
        {
                return 1;
        }
        else
        {
                return 0;
        }
}

}

?>
