<?
	if ( ISSET( $_OFFICE_UTIL_LOADED ) == true )
		return ;

	$_OFFICE_UTIL_LOADED = true ;

	/*****  Util_Format_CovertSpecialChars  ****************************************
	 *
	 *  Parameters:
	 *	$string
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$string ( success )
	 *	false ( failure )
	 *
	 *  History:
	 *	Nate Lee				Aug 9, 2001
	 *
	 *****************************************************************/
	function Util_Format_ConvertSpecialChars( $string )
	{
		$string = stripslashes( $string ) ;
		$string = preg_replace( "/</", "&lt;", $string ) ;
		$string = preg_replace( "/>/", "&gt;", $string ) ;
		$string = preg_replace( "/\"/", "&quot;", $string ) ;
		return $string ;
	}

	/*****  Util_Format_Bytes  ****************************************
	 *
	 *  Parameters:
	 *	$bytes
	 *
	 *  Description:
	 *	[DESCRIPTION HERE]
	 *
	 *  Returns:
	 *	$output ( array )
	 *	false ( failure )
	 *
	 *  History:
	 *	Kyle Hicks				July 13, 2001
	 *
	 *****************************************************************/
	function Util_Format_Bytes( $bytes )
	{

		$kils = round ( $bytes/1000 ) ;
		$kil_re = ( $bytes % 1000 ) ;

		if ( $kils > 999 )
		{
			$megs = floor ( $kils/1000 ) ;
			$meg_re = ( $kils % 1000 ) ;
			$meg_per = $meg_re/1000 ;
			$megs_final = $megs + $meg_per ;
			$string = "$megs_final M" ;
		}
		elseif ( ( $bytes < 999 ) && ( $bytes ) )
		{
			$string = "$bytes byte" ;
		}
		else if ( $bytes )
		{
			$string = "$kils k" ;
		}

		return $string ;
	}

	/*****  Util_Get_IP  ****************************************
	*  
	*	Parameters:
	*	none
	*  
	*	Description:
	*	Gets the correct IP address from the remote_addr and X-forwarded-For
	*  
	*	Returns:
	*	$output (a string containing an IP)
	*
	*	History:
	*	Wim Godden <wim@godden.net>		July 10, 2002
	*
	*****************************************************************/
	function Util_Get_IP ( $headers )
	{
		global $HTTP_SERVER_VARS ;
		
		$remote_addr = "" ;
		$element = 0 ;

		while ( list ( $header, $value ) = each ( $headers ) )
		{
			if ( $header == "X-Forwarded-For" )
			{
				$split_addr=explode( ",", $value ) ;
				$element = 0 ;
				while (
					(
					substr($split_addr[$element],0,3)=="10." ||
					substr($split_addr[$element],0,6)=="127.0." ||
					(
					substr($split_addr[$element],0,4)=="172." &&
					(
					substr($split_addr[$element],4,3)=="16." ||
					substr($split_addr[$element],4,3)=="17." ||
					substr($split_addr[$element],4,3)=="18." ||
					substr($split_addr[$element],4,3)=="19." ||
					substr($split_addr[$element],4,3)=="20." ||
					substr($split_addr[$element],4,3)=="21." ||
					substr($split_addr[$element],4,3)=="22." ||
					substr($split_addr[$element],4,3)=="23." ||
					substr($split_addr[$element],4,3)=="24." ||
					substr($split_addr[$element],4,3)=="25." ||
					substr($split_addr[$element],4,3)=="26." ||
					substr($split_addr[$element],4,3)=="27." ||
					substr($split_addr[$element],4,3)=="28." ||
					substr($split_addr[$element],4,3)=="29." ||
					substr($split_addr[$element],4,3)=="30." ||
					substr($split_addr[$element],4,3)=="31." ||
					substr($split_addr[$element],4,3)=="32."
					)
					) ||
					substr($split_addr[$element],0,8)=="192.168." ||
					$split_addr[$element]=="unknown"
					) &&
					$element < count($split_addr)
					)
				{
					$element++ ;
				}
				$remote_addr = $split_addr[$element] ;
			}
		}

		if ( $remote_addr == "" )
		$remote_addr = $HTTP_SERVER_VARS['REMOTE_ADDR'] ;
		return $remote_addr ;
	}
?>