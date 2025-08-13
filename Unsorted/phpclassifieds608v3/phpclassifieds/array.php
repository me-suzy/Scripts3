<?
			if (is_array($usr_1))
            {
            	$count = count($usr_1);
				for ($i=0; $i<$count; $i++)
        		{
            		if (($i + 1) < $count)
        			{
        				$usr_1_inn = $usr_1_inn . "usr_1[$i], ";
        			}
        			else
        			{
        				$usr_1_inn = usr_1_inn . $usr_1[$i];
        			}
        		}
            } 
            else
            {
            	$usr_1_inn = $usr_1;	
            }
            
			
            
            if (is_array($usr_2))
            {
            	$count = count($usr_2);
				for ($i=0; $i<$count; $i++)
        		{
            		if (($i + 1) < $count)
        			{
        				$usr_2_inn = $usr_2_inn . "$usr_2[$i], ";
        			}
        			else
        			{
        				$usr_2_inn = $usr_2_inn . $usr_2[$i];
        			}
        		}
            } 
            else
            {
            	$usr_2_inn = $usr_2;	
            }
		
		
			if (is_array($usr_3))
            {
            	$count = count($usr_3);
				for ($i=0; $i<$count; $i++)
        		{
            		if (($i + 1) < $count)
        			{
        				$usr_3_inn = $usr_3_inn . "$usr_3[$i], ";
        			}
        			else
        			{
        				$usr_3_inn = $usr_3_inn . $usr_3[$i];
        			}
        		}
            } 
            else
            {
            	$usr_3_inn = $usr_3;	
            }

            
            if (is_array($usr_4))
            {
            	$count = count($usr_4);
				for ($i=0; $i<$count; $i++)
        		{
            		if (($i + 1) < $count)
        			{
        				$usr_4_inn = $usr_4_inn . "$usr_4[$i], ";
        			}
        			else
        			{
        				$usr_4_inn = $usr_4_inn . $usr_4[$i];
        			}
        		}
            } 
            else
            {
            	$usr_4_inn = $usr_4;	
            }

            
			if (is_array($usr_5))
            {
            	$count = count($usr_5);
				for ($i=0; $i<$count; $i++)
        		{
            		if (($i + 1) < $count)
        			{
        				$usr_5_inn = $usr_5_inn . "$usr_5[$i], ";
        			}
        			else
        			{
        				$usr_5_inn = $usr_5_inn . $usr_5[$i];
        			}
        		}
            } 
            else
            {
            	$usr_5_inn = $usr_5;	
            }
            
            ?>