<?
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2002 RRF.ru development. All rights reserved.            |
+-----------------------------------------------------------------------------+
| The RRF.RU DEVELOPMENT forbids, under any circumstances, the unauthorized   |
| reproduction of software or use of illegally obtained software. Making      |
| illegal copies of software is prohibited. Individuals who violate copyright |
| law and software licensing agreements may be subject to criminal or civil   |
| action by the owner of the copyright.                                       |
|                                                                             |
| 1. It is illegal to copy a software, and install that single program for    |
| simultaneous use on multiple machines.                                      |
|                                                                             |
| 2. Unauthorized copies of software may not be used in any way. This applies |
| even though you yourself may not have made the illegal copy.                |
|                                                                             |
| 3. Purchase of the appropriate number of copies of a software is necessary  |
| for maintaining legal status.                                               |
|                                                                             |
| DISCLAIMER                                                                  |
|                                                                             |
| THIS SOFTWARE IS PROVIDED BY THE RRF.RU DEVELOPMENT TEAM ``AS IS'' AND ANY  |
| EXPRESSED OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED |
| WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE      |
| DISCLAIMED.  IN NO EVENT SHALL THE RRF.RU DEVELOPMENT TEAM OR ITS           |
| CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,       |
| EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,         |
| PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; |
| OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,    |
| WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR     |
| OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF      |
| ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.                                  |
|                                                                             |
| The Initial Developer of the Original Code is RRF.ru development.           |
| Portions created by RRF.ru development are Copyright (C) 2001-2002          |
| RRF.ru development. All Rights Reserved.                                    |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: cls_intershipper.php,v 1.2 2002/04/22 17:10:28 mav Exp $
#

define ("INTERSHIPPER_FIELDS", "email, password, carriers, Oaddress, Ocity, Ostate, Opostalcode, Ocountry, Oresidential, Daddress, Dcity, Dstate, Dpostalcode, Dcountry, Dresidential, weight, weightunits, dimensionunits, length, width, height, packaging, packagematerial, packagestyle, content, contentdanger, liquidunits, liquidvolume, shipmethod, shipdate, CODvalue, declaredvalue, dutiable, trackingnum");

class InterShipper 
{
var $Request;
var $QuoteData;
var $Last_Error;


function InterShipper()
{
	// Initialize all parameters to empty string
	$fields = explode(",", INTERSHIPPER_FIELDS);
	for($idx = 0; $idx < count($fields); $idx ++)
	{
		$tempval = trim($fields[$idx]);
		$this->Request[$tempval] = "";
	}
	// Set defaults for parameters that have them
	$this->Request['carriers'] = "ALL";
	$this->Request['Ocountry'] = "US";
	$this->Request['Oresidential'] = "NO";
	$this->Request['Dcountry'] = "US";
	$this->Request['Dresidential'] = "YES";
	$this->Request['weight'] = 0;
	$this->Request['weightunits'] = "LB";
	$this->Request['dimensionunits'] = "IN";
	$this->Request['packaging'] = "BOX";
	$this->Request['packagematerial'] = "CBP";
	$this->Request['packagestyle'] = "NON";
	$this->Request['content'] = "NON";
	$this->Request['contentdanger'] = "NON";
	$this->Request['shipmethod'] = "SCD";
	$this->Request['shipdate'] = date('d M Y');
}

function getError()
{
	return $this->LastError;
}

function setVar($variable, $value)
{
	// We're actually going to check to see if the variable is valid
	$fields = explode(",", INTERSHIPPER_FIELDS);
	for($idx = 0; $idx < count($fields); $idx ++)
	{
		if(strcmp(trim($fields[$idx]), $variable) == 0)
		{
			// Data validation here
			switch($variable)
			{
				case 'carriers' :
					$value = ereg_replace(",", "\|", $value);
					break;
			}
			$this->Request[$variable] = $value;
			return true;
		}
	}
	$this->LastError = "The parameter \"$variable\" does not exist.";
	return false;
}
function ValidateData($request)
{
	//Clear the error string
	$this->Last_Error = "";
	
	// Check the account
	$this->Request['email'] = trim($this->Request['email']);
	if($this->Request['email'] == "")
	{
		$this->Last_Error .= "email is mandatory,";
	}
	// Check the password
	$this->Request['password'] = trim($this->Request['password']);
	if($this->Request['password'] == "")
	{
		$this->Last_Error .= "password is mandatory,";
	}
	
	if($request == "QUOTE")
	{
		// Check the weight
		$this->Request['weight'] = intval($this->Request['weight']);
		if($this->Request['weight'] <= 0)
		{
			$this->Last_Error .= "weight is invalid,";
		}
	}
	
	if($this->Last_Error == "")
		return true;
	else
		return false;
}

function ReturnRequest($request)
{
	$requestDoc = "<INTERSHIPPER REQUEST=\"" . $request 
		. "\" EMAIL=\"" . $this->Request['email']
		. "\" PASSWORD=\"" . $this->Request['password'] 
		. "\">";
	if($request == "QUOTE")
	{
		$requestDoc .= "<CARRIERS>" . $this->Request['carriers'] . "</CARRIERS>"
			. $this->AddressData("ORIGIN")
			. $this->AddressData("DESTINATION")
			. "<SHIPMENT><WEIGHT UNITS=\"" . $this->Request['weightunits'] . "\">"
			. $this->Request['weight'] . "</WEIGHT>"
			. $this->GetDimensions()
			. "<PACKAGING MATERIAL=\"" . $this->Request['packagematerial']
			. "\" STYLE=\"" . $this->Request['packagestyle']
			. "\">" . $this->Request['packaging'] . "</PACKAGING>"
			. "<CONTENT DANGEROUS=\"" . $this->Request['contentdanger']
			. "\">" . $this->Request['content'] . "</CONTENT>"
			. "</SHIPMENT>"
			. "<SERVICE><SHIPDATE>" . $this->Request['shipdate'] . "</SHIPDATE>"
			. "<SHIPMETHOD>" . $this->Request['shipmethod'] . "</SHIPMETHOD>"
			. "<ACCESSORIES>" . $this->AccessoryData()
			. $this->CheckResidential("ORIGIN", "NO")
			. $this->CheckResidential("DESTINATION", "YES")
			. "</ACCESSORIES></SERVICE>";
	}
	else if($request == "TRACK")
	{
		$carriers = explode("|", $this->Request[carriers]);
		$tracknums = explode(",", $this->Request[trackingnum]);
		
		if(count($carriers) > count($tracknums))
			$reqs = count($tracknums);
		else
			$reqs = count($carriers);
		if($reqs > 10)
			$reqs = 10;
			
		for($idx = 1; $idx <= $reqs; $idx ++)
		{
			$requestDoc .= "<TRACKREQUEST" . $idx . "><CARRIER>"
				. $carriers[$idx - 1] . "</CARRIER><TRACKINGNUMBER>"
				. $tracknums[$idx - 1] . "</TRACKINGNUMBER></TRACKREQUEST"
				. $idx . ">";
		}
	}
	
	$requestDoc .= "</INTERSHIPPER>";
	
	return $requestDoc;
}
function CheckResidential($type, $defaultval)
{
	$type = trim(strtoupper($type));
	$prefix = substr($type, 0, 1);
	$testval = trim(strtoupper($this->Request["${prefix}residential"]));
	if($testval != "NO" & $testval !="TRUE" & $testval != "YES" & $testval != "FALSE")
		$testval = $defaultval;
	if($type == "ORIGIN")
		return "<RESIDENTIALPICKUP>$testval</RESIDENTIALPICKUP>";
	else if($type == "DESTINATION")
		return "<RESIDENTIALDELIVERY>$testval</RESIDENTIALDELIVERY>";
	else return "";
}
function AccessoryData()
{
	$COD = "";
	if(trim($this->Request['CODvalue']) != "") 
		$COD = "<CODVALUE>" . trim($this->Request['CODvalue']) . "</CODVALUE>";
	$declared = "";
	if(trim($this->Request['declaredvalue']) != "") 
		$declared = "<DECLAREDVALUE>" . trim($this->Request['declaredvalue'])
			. "</DECLAREDVALUE>";
	$duty = "";
	if(trim($this->Request['dutiable']) != "") 
		$duty = "<DUTIABLE>" . trim($this->Request['dutiable']) . "</DUTIABLE>";
	
	return $COD . $declared . $duty;
}
function AddressData($type)
{
	$type = trim(strtoupper($type));
	// the address type must be either ORIGIN or DESTINATION
	$prefix = substr($type, 0, 1);
	// create the address block for this type
	$addressData = 
		"<$type><ADDRESS>" . $this->Request["${prefix}address"] . "</ADDRESS>"
		. "<CITY>" . $this->Request["${prefix}city"] . "</CITY>"
		. "<STATE>" . $this->Request["${prefix}state"] . "</STATE>"
		. "<POSTALCODE>" . $this->Request["${prefix}postalcode"] . "</POSTALCODE>"
		. "<COUNTRY>" . $this->Request["${prefix}country"] . "</COUNTRY></$type>";
	
	return $addressData;
}

function GetDimensions()
{
	// build the XML for dimensions and volume, if necessary
	$dimensions = "";
	$liquid = "";
	
	// Physical dimensions
	if(intval($this->Request['length']) > 0 
		& intval($this->Request['width']) > 0 
		& intval($this->Request['height']) > 0 
		& trim($this->Request['dimensionunits']) != "")
	{
		$dimensionunits = trim(strtoupper($this->Request['dimensionunits']));
		if (ereg(" $dimensionunits ", " IN FT CM M "))
		{
			$dimensionlength = intval($this->Request['length']);
			$dimensionwidth = intval($this->Request['width']);
			$dimensionheight = intval($this->Request['height']);
			$dimensions = "<DIMENSIONS UNITS=\"$dimensionunits\">"
				. "<LENGTH>$dimensionlength</LENGTH>"
				. "<WIDTH>$dimensionwidth</WIDTH>"
				. "<HEIGHT>$dimensionheight</HEIGHT></DIMENSIONS>";
		}
	}
	
	// Liquid Volume
	if(trim($this->Request['liquidunits']) != "" &
		intval($this->Request['liquidvolume']) > 0)
	{
		$liquidunits = trim(strtoupper($this->Request['liquidunits']));
		if (ereg(" $liquidunits ", " OZ G QT L "))
		{
			$liquidvolume = intval($this->Request['liquidvolume']);
			$liquid = "<LIQUIDVOLUME UNITS=\"$liquidunits\">"
				. "$liquidvolume</LIQUIDVOLUME>";
		}
	}
	return $dimensions . $liquid;
}

function getQuote()
{

	$quotecount = 0;
	// parse the returned XML doc into an array
	$pattern = "/<QUOTE [^>]*ID[ ]*=[ ]*\"([^\"]*)/i";
	preg_match($pattern, $this->RequestData, $temp);
	$quoteid = $temp[1];
	
	$pattern = "/<CARRIER .*?<\/CARRIER>/i";
	$carriercount = preg_match_all($pattern, $this->RequestData, $carriers, PREG_SET_ORDER);
	for($idx = 0; $idx < $carriercount; $idx ++)
	{
		$pattern = "/<CARRIER [^>]*ID[ ]*=[ ]*\"([^\"]*)/i";
		preg_match($pattern, $carriers[$idx][0], $temp);
		$carrierid = $temp[1];
		$pattern = "/<CARRIER [^>]*NAME[ ]*=[ ]*\"([^\"]*)/i";
		preg_match($pattern, $carriers[$idx][0], $temp);
		$carriername = $temp[1];
		
		$pattern = "/<METHOD .*?<\/METHOD>/i";
		$methodcount = preg_match_all($pattern, $carriers[$idx][0], $methods, PREG_SET_ORDER);
		for($idx2 = 0; $idx2 < $methodcount; $idx2 ++)
		{
			$quotearray[$quotecount]['quoteid'] = $quoteid;
			$quotearray[$quotecount]['carrierid'] = $carrierid;
			$quotearray[$quotecount]['carriername'] = $carriername;
			
			$pattern = "/<METHOD [^>]*ID[ ]*=[ ]*\"([^\"]*)/i";
			preg_match($pattern, $methods[$idx2][0], $temp);
			$quotearray[$quotecount]['methodid'] = $temp[1];
			
			$pattern = "/<METHOD [^>]*CODE[ ]*=[ ]*\"([^\"]*)/i";
			preg_match($pattern, $methods[$idx2][0], $temp);
			$quotearray[$quotecount]['methodcode'] = $temp[1];
			
			$pattern = "/<METHOD [^>]*NAME[ ]*=[ ]*\"([^\"]*)/i";
			preg_match($pattern, $methods[$idx2][0], $temp);
			$quotearray[$quotecount]['methodname'] = $temp[1];
			
			$pattern = "/<ITEMID>([^<]*)<\/ITEMID>/i";
			preg_match($pattern, $methods[$idx2][0], $temp);
			$quotearray[$quotecount]['itemid'] = $temp[1];
			
			$pattern = "/<RATE>([^<]*)<\/RATE>/i";
			preg_match($pattern, $methods[$idx2][0], $temp);
			$quotearray[$quotecount]['rate'] = $temp[1];
			
			$pattern = "/<TRANSITDAYS>([^<]*)<\/TRANSITDAYS>/i";
			preg_match($pattern, $methods[$idx2][0], $temp);
			$quotearray[$quotecount]['transitdays'] = $temp[1];
			
			$pattern = "/<DATE>([^<]*)<\/DATE>/i";
			preg_match($pattern, $methods[$idx2][0], $temp);
			$quotearray[$quotecount]['date'] = $temp[1];
			
			$pattern = "/<TIME>([^<]*)<\/TIME>/i";
			preg_match($pattern, $methods[$idx2][0], $temp);
			$quotearray[$quotecount]['time'] = $temp[1];
			
			$pattern = "/<GUARANTEED>([^<]*)<\/GUARANTEED>/i";
			preg_match($pattern, $methods[$idx2][0], $temp);
			$quotearray[$quotecount]['guaranteed'] = $temp[1];
			
			$pattern = "/<LATESTPICKUP>([^<]*)<\/LATESTPICKUP>/i";
			preg_match($pattern, $methods[$idx2][0], $temp);
			$quotearray[$quotecount]['latestpickup'] = $temp[1];
			
			$quotecount ++;
		}
	}
	// return the array
	return $quotearray;
}

function getTrack()
{
	$trackcount = 0;
		
	$pattern = "/<CARRIER .*?<\/CARRIER>/i";
	$carriercount = preg_match_all($pattern, $this->RequestData, $carriers, PREG_SET_ORDER);
	for($idx = 0; $idx < $carriercount; $idx ++)
	{
		$pattern = "/<CARRIER [^>]*NAME=\"([^\"]*)/i";
		preg_match($pattern, $carriers[$idx][0], $temp);
		$trackarray[$trackcount][carriercode] = $temp[1];
		
		$pattern = "/<CARRIER [^>]*TRACKINGNUMBER=\"([^\"]*)/i";
		preg_match($pattern, $carriers[$idx][0], $temp);
		$trackarray[$trackcount][trackingnumber] = $temp[1];
		
		$pattern = "/<SERVICETYPE[^>]*>([^<]*)/i";
		preg_match($pattern, $carriers[$idx][0], $temp);
		$trackarray[$trackcount][servicetype] = $temp[1];
		
		if(trim($temp[1]) == "NO DATA RETURNED FROM CARRIER")
			$trackarray[$trackcount][statuscode] = -1;
		else if(trim($temp[1]) == "INVALID OR NOT FOUND")
			$trackarray[$trackcount][statuscode] = 0;
		else
			$trackarray[$trackcount][statuscode] = 1;
		
		$pattern = "/<STATUSLEVEL[^>]*ID=\"([^\"]*)/i";
		preg_match($pattern, $carriers[$idx][0], $temp);
		$trackarray[$trackcount][statuslevel] = $temp[1];
		
		$pattern = "/<STATUSLEVEL[^>]*>([^<]*)/i";
		preg_match($pattern, $carriers[$idx][0], $temp);
		$trackarray[$trackcount][statustext] = $temp[1];
		
		$pattern = "/<STATUSDATE[^>]*>([^<]*)/i";
		preg_match($pattern, $carriers[$idx][0], $temp);
		$trackarray[$trackcount][statusdate] = $temp[1];
		
		$pattern = "/<LASTSCAN.*?<\/LASTSCAN>/i";
		$tempcount = preg_match_all($pattern, $carriers[$idx][0], $lastscan);
		
		$pattern = "/<SCANDATE[^>]*>([^<]*)/i";
		preg_match($pattern, $lastscan[0][0], $temp);
		$trackarray[$trackcount][scandate] = $temp[1];
		
		$pattern = "/<DELIVERYDATE[^>]*>([^<]*)/i";
		preg_match($pattern, $lastscan[0][0], $temp);
		$trackarray[$trackcount][deliverydate] = $temp[1];
		
		$pattern = "/<DELIVEREDTO[^>]*>([^<]*)/i";
		preg_match($pattern, $lastscan[0][0], $temp);
		$trackarray[$trackcount][deliveredto] = $temp[1];
		
		$pattern = "/<SIGNATORY[^>]*>([^<]*)/i";
		preg_match($pattern, $lastscan[0][0], $temp);
		$trackarray[$trackcount][signatory] = $temp[1];
		
		$pattern = "/<LOCATION[^>]*>([^<]*)/i";
		preg_match($pattern, $lastscan[0][0], $temp);
		$trackarray[$trackcount][location] = $temp[1];
		
		$pattern = "/<CITY[^>]*>([^<]*)/i";
		preg_match($pattern, $lastscan[0][0], $temp);
		$trackarray[$trackcount][city] = $temp[1];
		
		$pattern = "/<STATE[^>]*>([^<]*)/i";
		preg_match($pattern, $lastscan[0][0], $temp);
		$trackarray[$trackcount][state] = $temp[1];
		
		$pattern = "/<COUNTRY[^>]*>([^<]*)/i";
		preg_match($pattern, $lastscan[0][0], $temp);
		$trackarray[$trackcount][country] = $temp[1];
		
		$trackcount ++;
	}
	// return the array
	return $trackarray;
}

function Quote($timeout)
{
	return $this->getData($timeout, "QUOTE");
} // end function Quote

function Track($timeout)
{
	return $this->getData($timeout, "TRACK");
}

function getData($time_out_val, $requesttype)
{
	$requesttype = strtoupper($requesttype);
	// Boolean return value
	// Validate all parameters, exit if any parameter is invalid
	if (!$this->ValidateData($requesttype))
		return false;

	// build and retrieve the XML request doc
	$request = $this->ReturnRequest($requesttype);
	// Append CRLF to the request block
	$request .= "\r\n";
	
	// Make sure the timeout is an integer value
	$time_out_val = intval($time_out_val);
	// See if the time is valid, if not, set to 30 seconds
	if ($time_out_val <= 0) $time_out_val = 30;
	
	// Initialize the response variable
	$response = "";
	
	// Open a connection to InterShipper
	$connection = fsockopen("calc.intershipper.net", 4000, &$error_number, &$error_description, $time_out_val);
	
	// If the connection is valid...
	if($connection)
	{
		// We can use non-blocking
		set_socket_blocking($connection, false);
		// reset the page timeout so it doesn't time-out before the function does
		set_time_limit($time_out_val + 10);
		
		// Send the request block
		fputs($connection, $request);
		
		// "set" the timer
		$time_start = time();
		// Note: we will never actually get an EOF condition
		// InterShipper leaves the socket open for additional requests
		while(!feof($connection)) 
		{
			// get data
			$response .= fgets($connection, 128);
			// Check to see if we got the closing tag for the response
			if(eregi("</$requesttype>", $response)) 
			{
				// If we did, we can break the loop right now
				break;
			}
			// Check to see if we exceeded our timeout
			if (time() - $time_start > $time_out_val) 
			{
				// If we did, erase any existing data, and break the loop
				$this->Last_Error = "Timed out waiting for data";
				return false;
			}
		}
		// Close the connection to InterShipper
		fclose($connection);
		// Return the data block
		$this->RequestData = $response;
		return true;
	}
	else
	{
		// socket connection failed
		$this->LastError = "Failed to connect to InterShipper: $error_description";
		return false;
	}
}
} // end class InterShipper
?>
