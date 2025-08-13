<?

function valid_email($address)
{
 if (ereg("^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$", $address))
 		return true;
 else
 		return false; 
}




?>