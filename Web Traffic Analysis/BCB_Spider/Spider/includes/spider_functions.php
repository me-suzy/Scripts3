<?php
/************************************************************************/
/* BCB Spider Tracker: Simple Search Engine Bot Tracking                */
/* ============================================                         */
/*                                                                      */
/* Copyright (c) 2004 by www.bluecollarbrain.com                        */
/* http://bluecollarbrain.com                                           */
/*                                                                      */
/* This program is free software. You may use it as you wish.           */
/*   This File: spider_functions.php                                    */ 
/*   This will convert the MySQL timestamp into a more legible format.  */
/*   DON'T CHANGE THIS if you want a different date display, change     */
/*   the code in spider_results.php.                                    */    
/************************************************************************/

/****************************************************************************** 
  Author        : Andreas Kempf 
  $svDate       : The timestamp from MySQL 
  $svDateOutput : YYYY-MM-DD hh:mm:ss 
                  DD.MM.YYYY or whatsoever 

     year  = YYYY 
     month = MM 
     day   = DD 
     hour  = hh 
     minute= mm 
     second= ss 

******************************************************************************/ 
function FncChangeTimestamp ($svDate, $svDateOutput) 
    { 
        $year  = substr($svDate,0,4); 
        $month = substr($svDate,4,2); 
        $day   = substr($svDate,6,2); 
        $hour  = substr($svDate,8,2); 
        $minute= substr($svDate,10,2); 
        $sec   = substr($svDate,12,2); 

        $svDateOutput = ereg_replace ("YYYY", $year, $svDateOutput); 
        $svDateOutput = ereg_replace ("MM", $month, $svDateOutput); 
        $svDateOutput = ereg_replace ("DD", $day, $svDateOutput); 
        $svDateOutput = ereg_replace ("hh", $hour, $svDateOutput); 
        $svDateOutput = ereg_replace ("mm", $minute, $svDateOutput); 
        $svDateOutput = ereg_replace ("ss", $sec, $svDateOutput); 
             
        return $svDateOutput; 
    }; 
?>  
