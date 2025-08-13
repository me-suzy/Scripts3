<?#//v.1.0.0
  
#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////


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
