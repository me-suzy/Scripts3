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

	  $err_font = "<FONT FACE=\"Verdana,Arial,Helvetica\" SIZE=\"2\" COLOR=red>";
	  $std_font = "<FONT FACE=\"Verdana,Arial,Helvetica\" SIZE=\"3\">";

function files($dir, $pattern = "-no-pattern-") { 

    $direc = (is_dir($dir)) ? $direc = $dir : $direc = dirname($dir); 
    if (isset($direc)): 
        $dh = opendir($direc); 
    else: 
        return false; 
    endif; 
	$i=0;
    while ($file = readdir($dh)) { 
        if (is_file("${direc}/${file}") && (ereg("$pattern", "$file") || 
        $pattern == "-no-pattern-")) 

             $file_list[] = $file; 
	 $i++;
    } 

    closedir($dh); 
return $i-2;

}  

$dir_root=files("../");
$dir_admin=files("../admin");
$dir_includes=files("../includes");
$dir_templates=files("../templates");
$dir_sql=files("../sql");
$dir_phpAdsNew=files("../phpAdsNew");

echo "$std_font Checkin files in directories...<br><font size=\"2\">";
if ($dir_root >= 53) 
	{ $msg.="root directory ... Ok<br>"; } 
	else 
	{ $msg.="root directory ... $err_font Files missing</font><br>"; 	}
if ($dir_admin >= 45) 
	{ $msg.="admin directory ... Ok<br>"; } 
	else 
	{ $msg.="admin directory ... $err_font Files missing</font><br>"; 	}
if ($dir_includes >= 36) 
	{ $msg.="includes directory ... Ok<br>"; } 
	else 
	{ $msg.="includes directory ... $err_font Files missing</font><br>"; 	}
if ($dir_templates >= 45) 
	{ $msg.="templates directory ... Ok<br>"; } 
	else 
	{ $msg.="templates directory ... $err_font Files missing</font><br>"; 	}
if ($dir_sql >= 2) 
	{ $msg.="sql directory ... Ok<br>"; } 
	else 
	{ $msg.="sql directory ... $err_font Files missing</font><br>"; 	}
if ($dir_phpAdsNew >= 29) 
	{ $msg.="phpAdsNew directory ... Ok<br>"; } 
	else 
	{ $msg.="phpAdsNew directory ... $err_font Files missing</font><br>"; 	}
?> 
