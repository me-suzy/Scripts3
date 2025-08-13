<?php
			/////////////////////////////////////////////////////////////////////////
			/////////////////////    start of itemValidation.php    ////////////////
			/////////////////////////////////////////////////////////////////////////
			// this validation is intended for use in both insert and update modes
			// essentially we're just checking for null values since itemForm.php sets defaults for many variables
			if(empty($productname)){$systemMessage .= "<br><b>Resource Name</b> is required.";$badData="true";}
			if(empty($shortname)) {
				// make up a shortname from the resource name
				if(!empty($productname)) {
					// replace spaces with underscores
					$shortname = str_replace(" ", "_", $productname);
					// use only the first few characters, really, there is only so much room for the value... 20 characters max
					$_POST["shortname"] .= substr ( $shortname, 0, 20); // repost the variable
				}else{
					$systemMessage .= "<br><b>Short Name</b> is required.";$badData="true";
				}
			}
			if(empty($baseprice)){$baseprice='00.00';}
			if(empty($benefit)){$systemMessage .= "<br>A short <b>Benefit</b> statement is required.";$badData="true";}
			if(empty($filesize)){$filesize='none';}
			if(empty($logo)){$logo='no';}
			if($logo=='yes'){if(empty($logourl)){$systemMessage .= "<br>You have a logo. The logo must be given a url.";$badData="true";}}
			if(empty($author)){$author='unknown';}
			if(empty($companyurl)) { $companyurl = HOMEPAGE; }		
			if(empty($companyemail)) {$companyemail= SYSTEMEMAIL; }
			if(empty($hits)){$hits=0;}
			if(empty($position)){$position=3;} // future use only
			if(empty($license)){$license='Public Domain';}
			if(empty($os)){$os='none';}
			if(empty($language)){$language='none';}
			if(empty($overview)){$overview=$benefit;}
			if(empty($description)){$description=NULL;}
			if(empty($requirements)){$requirements='none';}
			if(empty($via)){$systemMessage .= "<br>A <b>VIA</b> method is required.";$badData="true";}
			
			// this checks for the existence of via of 'HTTP', $task and the size, in bytes, of the uploaded file. 


			// this is for an update where via is changed from anything else to HTTP but a file was not originally picked

			
			if($via=='SMTP Body' or $via=='CRT' or $via=='Special') {
				if(empty($special)) {
					$systemMessage .= "<br>You selected a $via download. You must enter data in the <b>Special textarea input</b>.";$badData="true";
				}
			}
			
			// make sure shortname is just one word
			if ( strstr ($shortname, " " ) ) { // is there a space anywhere in $shortname ?
				if ( $shortname = str_replace (" ", "", $shortname) ) { // remove spaces
					$systemMessage .= "<br>Spaces removed from shortname field called '$shortname'.";	
				}
			}

			// if we have bad data then halt proceedings
			if($badData=="true") {
				$formToInclude = "itemForm.php";
				break;
			}

			// else all ok, encode urls for insert or update
			$logourl = urlencode($logourl);
			$companyurl = urlencode($companyurl);

			// end of common insert and update validation routines
			///////////////////////////////////////////////////////////////////////////////
			/////////////////////     end of itemValidation.php      //////////////////////
			///////////////////////////////////////////////////////////////////////////////
?>