<?
 /*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/
//-- CheckAge function checks the age of a user
//-- Returns: 0 if younger than 18
//--          1 if older than 18

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
?>