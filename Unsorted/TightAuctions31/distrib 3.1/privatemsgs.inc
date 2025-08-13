<?

// These are bitwise settings

$PRMSG_NO_REFERENCE   = 0;
$PRMSG_FROM_REFERENCE = 1;
$PRMSG_TO_REFERENCE   = 2;

function IsPrivateMsgFromSet( $val )
{
  global $PRMSG_FROM_REFERENCE;

	if ( ($val & $PRMSG_FROM_REFERENCE) != 0 )
		return true;
	else
		return false;
}

function IsPrivateMsgToSet( $val )
{
  global $PRMSG_TO_REFERENCE;

	if ( ($val & $PRMSG_TO_REFERENCE) != 0 )
		return true;
	else
		return false;
}

?>