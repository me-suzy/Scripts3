<?
if ($custom_field_1)
{
 	$str = "custom_field_1 like '%$custom_field_1%' ";
 	$pre = 1;
}
 
if ($custom_field_2)
{
 	if ($pre)
 	{
 		$str = $str . "AND ";	
 	}	
 	$str = $str . "custom_field_2 like '%$custom_field_2%' ";
}
 
if ($custom_field_3)
{
 	if ($pre)
 	{
 		$str = $str . "AND ";	
 	}	
 	$str = $str . "custom_field_3 like '%$custom_field_3%' ";
}

if ($custom_field_4)
{
 	if ($pre)
 	{
 		$str = $str . "AND ";	
 	}	
 	$str = $str . "custom_field_4 like '%$custom_field_4%' ";
}

if ($custom_field_5)
{
 	if ($pre)
 	{
 		$str = $str . "AND ";	
 	}	
 	$str = $str . "custom_field_5 like '%$custom_field_5%' ";
}

if ($custom_field_6)
{
 	if ($pre)
 	{
 		$str = $str . "AND ";	
 	}	
 	$str = $str . "custom_field_6 like '%$custom_field_6%' ";
}

if ($custom_field_7)
{
 	if ($pre)
 	{
 		$str = $str . "AND ";	
 	}	
 	$str = $str . "custom_field_7 like '%$custom_field_7%' ";
}

if ($custom_field_8)
{
 	if ($pre)
 	{
 		$str = $str . "AND ";	
 	}	
 	$str = $str . "custom_field_8 like '%$custom_field_8%' ";
}
 
?>